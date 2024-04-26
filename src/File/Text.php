<?php

namespace File;

class Text
{
    /**
     * String
     */
    private string $content = '';

    /**
     * Fájl készítése
     * $this->content lesz a tartalma
     */
    public function write (string $path): void
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if ($file = fopen($path, 'w')) {
            fwrite($file, $this->content);
            fclose($file);
        } else {
            die('Hiba történt a fájl létrehozása vagy írása közben!');
        }
    }

    /**
     * $this->content felülírása
     */
    public function set (string $content): Text
    {
        $this->content = $content;
        return $this;
    }

    /**
     * $this->content visszaadása
     */
    public function get (): string
    {
        return $this->content ?? '';
    }
}
