<?php

function handleRequest() {
    $filters = [];

    $filters['MODE'] = $_GET['mode'] ?? 'view';

    // Filtterit
    $filters['TYPE']       = $_GET['type'] ?? null;
    $filters['COUNTRY']    = $_GET['country'] ?? null;
    $filters['SIZE']       = $_GET['size'] ?? null;
    $filters['PRICELOW']   = isset($_GET['priceLow']) ? (float)$_GET['priceLow'] : null;
    $filters['PRICEHIGH']  = isset($_GET['priceHigh']) ? (float)$_GET['priceHigh'] : null;
    $filters['ENERGYLOW']  = isset($_GET['energyLow']) ? (float)$_GET['energyLow'] : null;
    $filters['ENERGYHIGH'] = isset($_GET['energyHigh']) ? (float)$_GET['energyHigh'] : null;

    // Sivutus
    $filters['LIMIT'] = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
    $filters['PAGE']  = isset($_GET['page']) ? (int)$_GET['page'] : 0;

    return $filters;
}

