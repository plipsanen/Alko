<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alkon hinnasto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
          crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
require("model.php");
require_once("controller.php");

updateAlkoDataFromWeb();

$alkoData = initModel();
$filters = handleRequest();
$alkoProductTable = generateView($alkoData, $filters, 'products');

echo "<div id=\"tbl-header\" class=\"alert alert-success\">Alkon tuotekatalogi $priceListDate</div>";

echo "<div class='card mb-3'><div class='card-body'>";
echo "<form method='GET' action='index.php' class='row g-3'>";

// 1. Tyyppi
$types = getUniqueValues('Tyyppi');
echo "<div class='col-md-2'><label class='form-label'>Tyyppi:</label><select name='type' class='form-control'>";
echo "<option value=''>Valitse tyyppi</option>";
foreach ($types as $type) {
    $selected = ($filters['TYPE'] == $type) ? 'selected' : '';
    echo "<option value='$type' $selected>$type</option>";
}
echo "</select></div>";

// 2. Valmistusmaa
$countries = getUniqueCountries();
echo "<div class='col-md-2'><label class='form-label'>Maa:</label><select name='country' class='form-control'>";
echo "<option value=''>Valitse maa</option>";
foreach ($countries as $country) {
    $selected = ($filters['COUNTRY'] == $country) ? 'selected' : '';
    echo "<option value='$country' $selected>$country</option>";
}
echo "</select></div>";

// 3. Pullokoko
$sizes = getUniqueValues('Pullokoko');
echo "<div class='col-md-2'><label class='form-label'>Koko:</label><select name='size' class='form-control'>";
echo "<option value=''>Valitse koko</option>";
foreach ($sizes as $size) {
    $selected = ($filters['SIZE'] == $size) ? 'selected' : '';
    echo "<option value='$size' $selected>$size</option>";
}
echo "</select></div>";

// 4. Hinta
echo "<div class='col-md-2'><label class='form-label'>Hinta (€):</label>";
echo "<div class='d-flex align-items-center gap-1'>";
echo "<input type='number' step='0.01' name='priceLow' class='form-control form-control-sm' placeholder='Min' value='{$filters['PRICELOW']}'>";
echo "<span style='font-size:0.8rem;'>-</span>";
echo "<input type='number' step='0.01' name='priceHigh' class='form-control form-control-sm' placeholder='Max' value='{$filters['PRICEHIGH']}'>";
echo "</div></div>";

// 5. Energia
echo "<div class='col-md-2'><label class='form-label'>Energia:</label>";
echo "<div class='d-flex align-items-center gap-1'>";
echo "<input type='number' name='energyLow' class='form-control form-control-sm' placeholder='Min' value='{$filters['ENERGYLOW']}'>";
echo "<span style='font-size:0.8rem;'>-</span>";
echo "<input type='number' name='energyHigh' class='form-control form-control-sm' placeholder='Max' value='{$filters['ENERGYHIGH']}'>";
echo "</div></div>";

// Nappulat
echo "<div class='col-md-2 d-flex align-items-end'>";
echo "<button type='submit' class='btn btn-primary btn-sm'>Suodata</button>";
echo "<a href='index.php' class='btn btn-secondary btn-sm'>Tyhjennä</a>";
echo "</div>";

echo "<input type='hidden' name='page' value='{$filters['PAGE']}'>";

echo "</form></div></div>";

// --- SIVUTUS ---
$currpage = $filters['PAGE'] ?? 0;
$prevpage = ($currpage > 0 ? $currpage - 1 : 0);
$nextpage = $currpage + 1;

$totalRows = count($alkoData);
$rowsPerPage = $filters['LIMIT'];
$totalPages = ceil($totalRows / $rowsPerPage);
$currentPage = $currpage + 1;

$queryParams = [];
if ($filters['TYPE']) $queryParams[] = "type=" . urlencode($filters['TYPE']);
if ($filters['COUNTRY']) $queryParams[] = "country=" . urlencode($filters['COUNTRY']);
if ($filters['SIZE']) $queryParams[] = "size=" . urlencode($filters['SIZE']);
if ($filters['PRICELOW']) $queryParams[] = "priceLow=" . $filters['PRICELOW'];
if ($filters['PRICEHIGH']) $queryParams[] = "priceHigh=" . $filters['PRICEHIGH'];
if ($filters['ENERGYLOW']) $queryParams[] = "energyLow=" . $filters['ENERGYLOW'];
if ($filters['ENERGYHIGH']) $queryParams[] = "energyHigh=" . $filters['ENERGYHIGH'];
$queryString = !empty($queryParams) ? '&' . implode('&', $queryParams) : '';

echo "<div class='mb-3'>";
echo "<input type='button' onClick=\"location.href='./index.php?page=$prevpage$queryString'\" value='Edellinen' class='btn btn-secondary'>";
echo "<input type='button' onClick=\"location.href='./index.php?page=$nextpage$queryString'\" value='Seuraava' class='btn btn-secondary ms-2'>";
echo "</div>";

echo $alkoProductTable;

echo "<div class='mt-3'><strong>Sivu $currentPage / $totalPages</strong></div>";
?>
</body>
</html>
