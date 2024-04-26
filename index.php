<?php

use Controllers\Megoldas;

/**
 * Struktúra felfedezése
 */
require_once './autoload.php';

/**
 * Útválasztás
 */
$request = $_SERVER['REQUEST_URI'];
// Ha a kérés vége "/" akkor levágjuk mivel "/feladat_1" és "/feladat_1/" között nem teszünk különbséget
if (substr($request, -1) === '/') {
  $request = substr($request, 0, -1);
}

// Útvonalak deffiniálása
switch ($request) {
  case '':
    echo '
        <strong>Főoldal</strong>
        <br>
        <br>- <a href="/feladat_1">Feladat_1 megoldása</a>
        <br>- <a href="/feladat_2">Feladat_2 megoldása</a>
    ';
    break;

  case '/feladat_1':
    echo '
        <strong>Feladat_1 megoldása</strong>
        <br>
        <br>- <a href="/">Vissza a főoldalra</a>
        <br><br>
    ';
    (new Megoldas())->feladat_1();
    break;

  case '/feladat_2':
    echo '
        <strong>Feladat_1 megoldása</strong>
        <br>
        <br><a href="/">Vissza a főoldalra</a>
        <br><br>
    ';
    (new Megoldas())->feladat_2();
    break;

  default:
    echo '
        <strong>Hiba: A keresett oldal nem található! (404)</strong>
        <br>
        <br>- <a href="/">Vissza a főoldalra</a>
        <br>
    ';
}
