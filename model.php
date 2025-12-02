<?php

require_once("config.php");
require_once("view.php");
require_once("controller.php");

$cs = ini_get("default_charset");

$columnNames = [];
$columnNamesMap = [];
$alkoData = [];

function readPriceList($filename) {
    global $priceListDate, $columnNames; 
    
    $row = 0;
    $alkoDataIndex = 0;
    $alkoData = [];
    
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";", "\"", "\\")) !== FALSE) {
            if( $row === 0 ) {
                $key = "Alkon hinnasto ";
                
                if( isset($data[0]) && $data[0] !== null && $data[0] !== "" ) {
                    if( str_starts_with($data[0], $key) ) {
                        $priceListDate = substr($data[0], strlen($key));
                    }
                }
            } else if( $row === 1 ) {
                ;
            } else if( $row === 2 ) {
                ;
            } else if ( $row === 3 ) {
                $columnNames = $data;
            } else {
                $alkoData[$alkoDataIndex] = $data;
                $alkoDataIndex++;
            }
            $row++;
        }
        fclose($handle);
    }    
    return $alkoData;
}

function createColumnNamesMap($cn) {
    $cnMap = [];
    for($i = 0; $i < count($cn); $i++) {
        $cnMap[$cn[$i]] = $i;
    }
    return $cnMap;
}

function initModel() {
    global $alkoData, $columnNames, $columnNamesMap, $filename;
    
    $alkoData = readPriceList($filename);
    $columnNamesMap = createColumnNamesMap($columnNames);
        
    return $alkoData;
}

function getUniqueCountries() {
    global $alkoData, $columnNamesMap;
    
    $countries = [];
    
    foreach ($alkoData as $product) {
        $country = $product[$columnNamesMap['Valmistusmaa']];
        if (!empty($country) && !in_array($country, $countries)) {
            $countries[] = $country;
        }
    }
    
    sort($countries);
    
    return $countries;
}

function getUniqueValues($columnName) {
    global $alkoData, $columnNamesMap;
    
    $values = [];
    
    if (!isset($columnNamesMap[$columnName])) {
        return $values;
    }
    
    foreach ($alkoData as $product) {
        $value = $product[$columnNamesMap[$columnName]];
        if (!empty($value) && !in_array($value, $values)) {
            $values[] = $value;
        }
    }
    
    sort($values);
    
    return $values;
}

require_once(__DIR__.'/vendor/shuchkin/simplexlsx/src/SimpleXLSX.php');

$remote_filename_xlsx = "https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx";
$local_filename_xlsx = "alko.xlsx";

function updateAlkoDataFromWeb() {
    global $remote_filename_xlsx, $filename;

    if (!function_exists('curl_init')) {
        return ['success' => false, 'message' => 'cURL ei ole käytössä palvelimella. Ota yhteyttä järjestelmänvalvojaan.'];
    }

    $ch = curl_init($remote_filename_xlsx);
    if ($ch === false) {
        return ['success' => false, 'message' => 'cURL:n alustus epäonnistui'];
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $excel_content = curl_exec($ch);
    $error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($excel_content === false || !empty($error)) {
        return ['success' => false, 'message' => 'Excel-tiedoston lataus epäonnistui: ' . $error . ' (HTTP: ' . $http_code . ')'];
    }

    if (empty($excel_content)) {
        return ['success' => false, 'message' => 'Ladattu tiedosto on tyhjä'];
    }

    $temp_xlsx = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'temp_alko_' . uniqid() . '.xlsx';

    if (file_put_contents($temp_xlsx, $excel_content) === false) {
        return ['success' => false, 'message' => 'Väliaikaisen tiedoston tallennus epäonnistui'];
    }

    if (!file_exists($temp_xlsx)) {
        return ['success' => false, 'message' => 'Väliaikaista tiedostoa ei löydy: ' . $temp_xlsx];
    }

    if ($xlsx = SimpleXLSX::parse($temp_xlsx)) {
        $f = fopen($filename, 'wb');
        if ($f === false) {
            unlink($temp_xlsx);
            return ['success' => false, 'message' => 'CSV-tiedoston avaaminen epäonnistui: ' . $filename];
        }

        fwrite($f, chr(0xEF) . chr(0xBB) . chr(0xBF));

        foreach ($xlsx->rows() as $r) {
            fputcsv($f, $r, ';');
        }
        fclose($f);

        unlink($temp_xlsx);

        return [
            'success' => true,
            'message' => 'Hinnasto päivitetty onnistuneesti Alkon sivuilta!'
        ];
    } else {
        unlink($temp_xlsx);
        return ['success' => false, 'message' => 'Excel-tiedoston parsiminen epäonnistui: ' . SimpleXLSX::parseError()];
    }
}
