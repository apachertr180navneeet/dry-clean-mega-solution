<?php

namespace App\Services;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class PrintService
{
    protected $printer;

    public function __construct()
    {
        $connector = new FilePrintConnector("php://stdout"); // Adjust to your printer connection
        $this->printer = new Printer($connector);
    }

    public function printLabel($content)
    {
        $this->printer->setTextSize(2, 2);
        $this->printer->text($content);
        $this->printer->cut();
    }

    public function __destruct()
    {
        $this->printer->close();
    }
}
