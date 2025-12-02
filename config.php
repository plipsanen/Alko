<?php

$filename = __DIR__ . "/data/alkon-hinnasto-ascii.csv";
$priceListDate = "14.09.2020";

$columns2Include = [
    "Numero",
    "Nimi",
    "Valmistaja",
    "Pullokoko",
    "Hinta",
    "Litrahinta",
    "Tyyppi",
    "Valmistusmaa",
    "Vuosikerta",              // LISÄTTY
    "Alkoholi-%",              // LISÄTTY
    "Energia kcal/100 ml"      // LISÄTTY
];

// Sivutusasetus (25 riviä per sivu)
$rows_per_page = 25;

/* all columns listed below
 * Numero;
 * Nimi;
 * Valmistaja;
 * Pullokoko;
 * Hinta;
 * Litrahinta;
 * Uutuus;
 * Hinnastojärjestyskoodi;
 * Tyyppi;
 * Alatyyppi;
 * Erityisryhmä;
 * Oluttyyppi;
 * Valmistusmaa;
 * Alue;
 * Vuosikerta;
 * Etikettimerkintöjä;
 * Huomautus;
 * Rypäleet;
 * Luonnehdinta;
 * Pakkaustyyppi;
 * Suljentatyyppi;
 * Alkoholi-%;
 * Hapot g/l;
 * Sokeri g/l;
 * Kantavierrep-%;
 * Väri EBC;
 * Katkerot EBU;
 * Energia kcal/100 ml;
 * Valikoima;
 * EAN
 */