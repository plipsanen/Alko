<?php
require "vendor/autoload.php";
use Shuchkin\SimpleXLSX;
// excel to csv converter from uri by shucking
function excel_to_csv($uri, $output_csv) {
    // download the excel file
    $excel_content = file_get_contents($uri);
    file_put_contents('temp.xlsx', $excel_content);
    if ( $xlsx = SimpleXLSX::parse( 'temp.xlsx' ) ) {
        $f = fopen($output_csv, 'wb');
        // fwrite($f, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
        foreach ( $xlsx->readRows() as $r ) {
            fputcsv($f, $r); // fputcsv($f, $r, ';', '"', "\\", "\r\n");
        }
        fclose($f);
    } else {
        echo SimpleXLSX::parseError();
    }
}

// Example usage
$excel_uri = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx';
$output_csv = 'alko.csv';
// timestamp for starting
$start_time = microtime(true);
excel_to_csv($excel_uri, $output_csv);
$end_time = microtime(true);
echo "Conversion took " . ($end_time - $start_time) . " seconds.\n";

?>
