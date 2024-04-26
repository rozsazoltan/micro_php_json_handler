# JSON feldolgozása PHP használatával

## Követelmények

Használtam egy új `json_validate()` függvényt, melyet csak a `PHP 8.3` verziótól vezettek be. Ha ezt elhagyjuk, úgy `PHP 8.x`, egyébként `PHP ^8.3`.

## Futtatás

### Fejlesztői környezetben

A feladat `/public` mappára mutató portot hozunk létre:

```
php dev
```

Ezt követően a böngészőből `localhost:8000` címen elérhető a megoldás.

### Éles üzembe helyezés

A domain címet irányítsa a projekt `/public` mappájába. Amennyiben ez rajtunk kívül álló okok miatt nem lehetséges, például a domain címünk fixen egy `public_html` (vagy más) mappára mutatnak, úgy a projekt `/public` mappa tartalmát másolja oda, és szerkessze az ott található `index.php`-ban az `autoload.php` relatív elérését az új hely szerint, úgy hogy az a jelenlegi projekt gyökérmappában keresse a fájlt.

## Működési elv

Alapvetően minden kérést a `/public/.htaccess` fájlban foglalt szabályozás alapján az `/public/index.php`-ra irányítunk. Ott betöltjük a szükséges osztályokat az `autoload.php` segítségével. Ezt követően rögtön az `/public/index.php`-ban egy nagyon kezdetleges útválasztót helyeztem el, mely lekérdezi a `REQUEST` részét az URL címnek és ez alapján határozza meg a megjeleníteni kívánt tartalmat. Az útválasztó figyelmen kívül hagyja az URL végén szereplő (egyébként is elhagyható) `/` (per) jelet, tehát nem tesz különbséget `localhost:8000/oldal` és `localhost:8000/oldal/` között.

Az útválasztásban 3 címet hoztam létre:
1. főoldal (`/`)
2. első feladat megoldása (`/feladat_1`)
3. második feladat megoldása (`/feladat_2`)
4. extra: az előbb nem deklarált útvonalak esetén 404 hibaüzenet jelenik meg, illetve egy a főoldalra navigálást segítő hivatkozás

A feladat megoldásokat egy közös class-ba a `Controllers\Megoldas.php` osztály-ba helyeztem el, mivel mindkét feladat ugyanazt a JSON fájl értelmezését kéri, csak más szűrési feltételekkel.

Az egyes feladatok futtatásakor a képernyőre írom ki az eredményt. A dizájnt nem vittem túlzásba. Itt lehetne javítani a megoldáson, ha külön frontend kódot futtatnánk ami egy háttér lekérdezésben lekérdezi a kiírandó adatokat, melyet JSON-el API lekérdezésből tudtam volna továbbítani és szépen megjeleníteni. Elismerem a PHP echo függvénye tényleg csak a hasonló teszt jellegű feladatok elvégzésére opcionális döntés.

Emellett a már kiszámolt eredményeket azok struktúrája szerint fájlba is kiírom a projekt `/public/dist` mappájába a feladatnak megfelelő elnevezéssel: `feladat_1_megoldas.txt` és `feladat_2_megoldas.json` (utóbbinál a PHP tömbből megfelelően (pretty/szépen) formázott JSON kerül kiírásra).

## Felépítés

A kliens szempontjából az alkalmazás futtatásához szükséges fájlokat a `/public` mappában helyeztem el. Ez egy biztonsági megoldás. A támadók ugyanis így nem képesek a `/public` mappán kívüli fájlok közvetlen elérésére és futtatására sem, még akkor sem ha bármilyen más figyelmetlenség vagy hiba miatt erre lehetőségük lenne. Indoklás: Hiszen a domain-ból nem lehet mappába visszalépő utasítást adni (pld.: `localhost:8000/../src/Controllers/Megoldas.php`) és ezáltal valamilyen belső fájlt meghívni.

Minden szükséges osztályt a `/src` mappában találhatnak meg, funkciójuknak megfelelő mappa és fájlnevekkel. Minden bennük írt függvényhez fűztem egy-egy apró kommentet a működés gyors leírásához.

A projekt sikeres működéséhez egyelőre a `/public` mappában kell elhelyezni a `orders.json` fájlt. (Mivel a portot ide irányítottuk, így ez tekinthető a weboldal szempontjából root-nak.)

A projekt gyökérmappájában található egy `dev` nevű fájl. Ez a futtatáshoz tartalmazza a megfelelő parancsot, azaz PHP-val 8000-es portot a `/public` mappába irányítja:

```
php -S localhost:8000 -t public/
```

A fájl segítségével az útmutató lényegesen rövidebb `php dev` parancsot írhatja elő a fejlesztők számára.

## Megjegyzés

A feladat megoldásához használhattam volna adatbázist, ahova betöltöm a kapott JSON fájl tartalmát. Ehhez 2 táblát kellett volna létesítenem: `customers` és `orders`. A kettő között egy egyirányú kapcsolatot kellett volna létrehoznom `ONE-TO-MANY` elvet követve, 1 rendeléshez csak 1 megrendelő kapcsolódhatott volna a `orders.customer_id` oszlop segítségével. Ebből következtetve 1 megrendelőhöz végtelen megrendelés kapcsolódhatott volna.

Az előbb felvázolt szerkezetből való lekérdezéshez felhasználható SQL kódokat azonban mellékeltem a `Controllers\Megoldas.php` megfelelő függvényeiben kommentelve. Amennyiben szükséges, úgy a most felvázolt adatbázist automatikusan generáló és adatokkal feltöltő verziót is eltudom készíteni a kérésetekre. Jelenleg csak az OOP szemléletnek megfelelő struktúrát alakítottam ki, hogy láthassátok milyen kódot írok viszonylag rövid időn belül.

Elkészítésre szánt idő: kb. 30 perc
