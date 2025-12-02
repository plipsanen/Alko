<?php

function createColumnHeaders($columns2Include) {
    $t = "<thead><tr>";
    foreach ($columns2Include as $val) {
        $t .= '<th scope="col">'.$val."</th>";
    }
    $t .= "</tr></thead>";    
    return $t;
}

function createTableRow($product, $columns2Include, $columnNamesMap) {
    $t = "<tr>";
    for ($i = 0; $i < count($columns2Include); $i++) {
        $columnName = $columns2Include[$i];
        $item = $product[$columnNamesMap[$columnName]] ?? '';

        $safeItem = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');

        if ($i == 0) {
            $t .= '<th scope="row">' . $safeItem . "</th>";
        } else {
            $t .= "<td>" . $safeItem . "</td>";
        }
    }
    $t .= "</tr>";
    return $t;
}

function createAlkoProductsTable($products, $columns2Include, $columnNamesMap, $filters, $tblId) {
    $limitCounter = 0;
    $limitCounterLow = $filters['LIMIT']*$filters['PAGE'];
    $limitCounterHigh = $limitCounterLow + $filters['LIMIT'];
    
    if($tblId != null) {
        $t = "<table id=\"$tblId\" class=\"table\">";    
    } else {
        $t = '<table class="table">';    
    }
    $t .= createColumnHeaders($columns2Include); 
    $t .= '<tbody>';
    
    for($i = 0; $i < count($products); $i++) {
        $product = $products[$i];
        
        // ===== FILTTERIT =====
        
        // 1. Tyyppi
        if($filters['TYPE'] != null){
            if($product[$columnNamesMap['Tyyppi']] !== $filters['TYPE']) {
                continue;
            }
        }
        
        // 2. Valmistusmaa
        if($filters['COUNTRY'] != null){
            if($product[$columnNamesMap['Valmistusmaa']] !== $filters['COUNTRY']) {
                continue;
            }
        }
        
        // 3. Pullokoko
        if($filters['SIZE'] != null){
            if($product[$columnNamesMap['Pullokoko']] !== $filters['SIZE']) {
                continue;
            }
        }
        
        // 4. Hinta
        if($filters['PRICELOW'] != null){
            $price = str_replace(',', '.', $product[$columnNamesMap['Hinta']]);
            if($price < $filters['PRICELOW']) {
                continue;
            }
        }
        if($filters['PRICEHIGH'] != null){
            $price = str_replace(',', '.', $product[$columnNamesMap['Hinta']]);
            if($price > $filters['PRICEHIGH']) {
                continue;
            }
        }
        
        // 5. Energia
        if($filters['ENERGYLOW'] != null){
            $energy = str_replace(',', '.', $product[$columnNamesMap['Energia kcal/100 ml']]);
            if($energy < $filters['ENERGYLOW']) {
                continue;
            }
        }
        if($filters['ENERGYHIGH'] != null){
            $energy = str_replace(',', '.', $product[$columnNamesMap['Energia kcal/100 ml']]);
            if($energy > $filters['ENERGYHIGH']) {
                continue;
            }
        }
        
        $limitCounter++;
        if($limitCounter > $limitCounterLow) {
            $t .= createTableRow($product,$columns2Include,$columnNamesMap);
            if($limitCounter >= $limitCounterHigh) {
                break;
            }
        }
    }
    $t .= '</tbody>';
    $t .= "</table>";
    return $t;
}

function generateView($alkoData, $filters, $tblId=null) {
    global $columns2Include, $columnNamesMap;
    
    $alkoProductTable = createAlkoProductsTable(
        $alkoData, $columns2Include, $columnNamesMap, $filters, $tblId
    );
    return $alkoProductTable;
}
