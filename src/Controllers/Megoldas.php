<?php

namespace Controllers;

use File\Json;
use File\Text;

class Megoldas
{
    private array $data = [];

    /**
     * Első feladat megoldása
     * - kiírás a képernyőre
     * - fájl elkészítése az eredményről
     */
    public function feladat_1 (): void
    {
        // Alapadatok betöltése
        $json = new Json();
        $this->data = $json->read('./orders.json')->get();

        // Feladat megoldása
        [$maxTotalCustomerName, $maxTotal] = $this->getMaxTotalCustomer();

        // Eredmények kiírása
        $result = $maxTotalCustomerName . ' költötte a legtöbbet: $' . number_format($maxTotal, 2, '.', ',');
        echo $result;

        // Fájl elkészítése
        $txt = new Text();
        $txt->set($result)->write('dist/feladat_1_megoldas.txt');
    }

    /**
     * Második feladat megoldása
     * - kiírás a képernyőre
     * - fájl elkészítése az eredményről
     */
    public function feladat_2 (): void
    {
        // Alapadatok betöltése
        $json = new Json();
        $this->data = $json->read('./orders.json')->get();

        // Feladat megoldása
        $grouped_total_count = $this->getOrderCountGroupByCity();

        // Eredmények kiírása + JSON fájlhoz tömb összekészítése (így elegendő 1 ciklus)
        $result = '<table><tr><th>Város</th><th>Rendelés darabszám</th></tr>';
        $result_json = [];
        foreach ($grouped_total_count as $city => $count) {
            $result .= "<tr><td>$city</td><td>$count</td></tr>";
            $result_json[] = [
                "city" => $city,
                "count" => $count,
            ];
        }
        $result .= '</table>';

        echo $result;

        // Fájl elkészítése
        $txt = new Text();
        $txt->set(json_encode($result_json, JSON_PRETTY_PRINT))->write('dist/feladat_2_megoldas.json');
    }

    /**
     * A legtöbb pénzt elköltő rendelő neve és összesen elköltött pénze
     *
     * @return array [rendelő_neve, rendelések_össszesített_értéke]
     */
    private function getMaxTotalCustomer(): array
    {
        $totals = $this->getTotalGroupByCustomer();
        $maxTotal = max($totals);
        $name = array_search($maxTotal, $totals);

        return [
            $name,
            $maxTotal,
        ];
    }

    /**
     * Lekérdezés: az alapadatokból customer.name alapján csoportosított sum(total) értékek lekérdezése
     * Adatbázis esetén SQL lekérdezés így nézne ki:
     *  SELECT `customers`.`name`, SUM(`orders`.`total`)
     *  FROM `orders`
     *  LEFT JOIN `customers` ON `customers`.`id` = `orders`.`customer_id`
     *  GROUP BY `customers`.`name`
     *
     * @return array key: rendelő_neve, value: összesen_elköltött_pénz
     */
    private function getTotalGroupByCustomer(): array
    {
        $grouped_totals = [];

        foreach ($this->data as $item) {
            $customer_name = $item->customer->name;
            $total = floatval(str_replace(['$', ','], '', $item->total));

            if (! isset($grouped_totals[$customer_name])) {
                $grouped_totals[$customer_name] = 0.0;
            }

            $grouped_totals[$customer_name] += $total;
        }

        return $grouped_totals;
    }

    /**
     * Lekérdezés: az alapadatokból customer.address.city alapján csoportosított count(*) értékek lekérdezése
     * Adatbázis esetén SQL lekérdezés így nézne ki:
     *  SELECT `customers`.`city`, COUNT(`orders`.`id`)
     *  FROM `orders`
     *  LEFT JOIN `customers` ON `customers`.`id` = `orders`.`customer_id`
     *  GROUP BY `customers`.`city`
     *
     * @return array key: város_neve, value: darabszám
     */
    private function getOrderCountGroupByCity(): array
    {
        $grouped_order_count = [];

        foreach ($this->data as $item) {
            $city = $item->customer->address->city;

            if (! isset($grouped_order_count[$city])) {
                $grouped_order_count[$city] = 0;
            }

            $grouped_order_count[$city]++;
        }

        return $grouped_order_count;
    }
}
