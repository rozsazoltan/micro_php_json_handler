# JSON feldolgozása PHP használatával

## Követelmények

Használtam egy új `json_validate()` függvényt, melyet csak a `PHP 8.3` verziótól vezettek be. Ha ezt elhagyjuk, úgy `PHP 8.x`, egyébként `PHP ^8.3`.

## Futtatás

A feladat gyökérmappájára mutató portot hozzunk létre:

```
php -S localhost:8000
```

Ezt követően a böngészőből `localhost:8000` címen elérhető a megoldás.

## Működési elv

Alapvetően minden kérést a `.htaccess` fájlban foglalt szabályozás alapján az `index.php`-ra irányítunk. Ott betöltjük a szükséges osztályokat az `autoload.php` segítségével. Ezt követően rögtön az `index.php`-ban egy nagyon kezdetleges útválasztót helyeztem el, mely lekérdezi a `REQUEST` részét az URL címnek és ez alapján határozza meg a megjeleníteni kívánt tartalmat. Az útválasztó figyelmen kívül hagyja az URL végén szereplő (egyébként is elhagyható) `/` (per) jelet, tehát nem tesz különbséget `localhost:8000/oldal` és `localhost:8000/oldal/` között.

Az útválasztásban 3 címet hoztam létre:
1. főoldal (`/`)
2. első feladat megoldása (`/feladat_1`)
3. második feladat megoldása (`/feladat_2`)
4. extra: az előbb nem deklarált útvonalak esetén 404 hibaüzenet jelenik meg, illetve egy a főoldalra navigálást segítő hivatkozás

A feladat megoldásokat egy közös class-ba a `Controllers\Megoldas.php` osztály-ba helyeztem el, mivel mindkét feladat ugyanazt a JSON fájl értelmezését kéri, csak más szűrési feltételekkel.

Az egyes feladatok futtatásakor a képernyőre írom ki az eredményt. A dizájnt nem vittem túlzásba. Itt lehetne javítani a megoldáson, ha külön frontend kódot futtatnánk ami egy háttér lekérdezésben lekérdezi a kiírandó adatokat, melyet JSON-el API lekérdezésből tudtam volna továbbítani és szépen megjeleníteni. Elismerem a PHP echo függvénye tényleg csak a hasonló teszt jellegű feladatok elvégzésére opcionális döntés.

Emellett a már kiszámolt eredményeket azok struktúrája szerint fájlba is kiírom a projekt `/dist` mappájába a feladatnak megfelelő elnevezéssel: `feladat_1_megoldas.txt` és `feladat_2_megoldas.json` (utóbbinál a PHP tömbből megfelelően (pretty/szépen) formázott JSON kerül kiírásra).

## Felépítés

Alapvetően a fő fájlokat a gyökérben helyeztem el. Egy éles projekt esetében azonban javasolt lenne a `.htaccess` és az `index.php` elkülönítése pld. egy `/public` mappába. Ezzel egy extra biztonságot lehet a kód köré húzni, hiszen a domain-ból nem lehet mappába visszalépő utasítást adni (pld.: `localhost:8000/../src/Controllers/Megoldas.php`) és ezáltal valamilyen belső fájlt meghívni, függetlenül a jól konfigurált `.htaccess` fájltól, ez minden ilyen jellegű támadás ellen védelmet nyújtana a támadókkal szemben.

Minden szükséges osztályt a `/src` mappában találhatnak meg, funkciójuknak megfelelő mappa és fájlnevekkel. Minden bennük írt függvényhez fűztem egy-egy apró kommentet a működés gyors leírásához.

A projekt sikeres működéséhez egyelőre a gyökérmappában kell elhelyezni a `orders.json` fájlt.

## Megjegyzés

A feladat megoldásához használhattam volna adatbázist, ahova betöltöm a kapott JSON fájl tartalmát. Ehhez 2 táblát kellett volna létesítenem: `customers` és `orders`. A kettő között egy egyirányú kapcsolatot kellett volna létrehoznom `ONE-TO-MANY` elvet követve, 1 rendeléshez csak 1 megrendelő kapcsolódhatott volna a `orders.customer_id` oszlop segítségével. Ebből következtetve 1 megrendelőhöz végtelen megrendelés kapcsolódhatott volna.

Az előbb felvázolt szerkezetből való lekérdezéshez felhasználható SQL kódokat azonban mellékeltem a `Controllers\Megoldas.php` megfelelő függvényeiben kommentelve. Amennyiben szükséges, úgy a most felvázolt adatbázist automatikusan generáló és adatokkal feltöltő verziót is eltudom készíteni a kérésetekre. Jelenleg csak az OOP szemléletnek megfelelő struktúrát alakítottam ki, hogy láthassátok milyen kódot írok viszonylag rövid időn belül.

Elkészítésre szánt idő: kb. 30 perc
