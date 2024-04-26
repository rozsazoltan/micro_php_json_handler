<?php

namespace File;

class Json
{
    /**
     * Valid JSON-t tartalmazó string
     */
    private string $content = '[]';

    /**
     * Fájl tartalmának betöltése
     * $this->content felülírása, ha a fájl Valid JSON-t tartalmaz
     */
    public function read (string $path): Json
    {
        $content = file_get_contents($path);
        return $this->set($content);
    }

    /**
     * $this->content felülírása, ha a $content Valid JSON-t tartalmaz
     */
    public function set (string $content): Json
    {
        if ($this->isValid($this->content)) {
            $this->content = $content;
            return $this;
        }

        die('Az átadott string nem valid JSON-t tartalamz!');
    }

    /**
     * $this->content visszaadása
     */
    public function get (): array
    {
        return json_decode($this->content);
    }


    /**
     * $content Valid JSON-t tartalmaz-e
     */
    private function isValid (string $content): bool
    {
        return json_validate($content); // Warning: PHP 8.3 funkció, kisebb PHP-n is futtatható, amennyiben ezt elhagyjuk!
    }
}
