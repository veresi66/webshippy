<?php
namespace App\classes;

use InvalidArgumentException;

class GetOrders
{
    private ?object $stock;
    private ?array $ordersH;
    private ?array $orders;

    public function __construct(string $argv, int $argc)
    {
        if ($argc != 2) {
            throw new InvalidArgumentException('Ambiguous number of parameters!');
        }
        if (($this->stock = json_decode($argv)) == null) {
            throw new InvalidArgumentException('Invalid json!');
        }
    }

    public function loadOrdersFromFile(string $filename) : void
    {
        $row = 1;
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if ($row == 1) {
                    $this->ordersH = $data;
                } else {
                    $o = [];
                    for ($i = 0; $i < count($this->ordersH); $i++) {
                        $o[$this->ordersH[$i]] = $data[$i];
                    }
                    $this->orders[] = $o;
                }
                $row++;
            }
            fclose($handle);
        }

        usort($this->orders, function ($a, $b) {
            $pc = -1 * ($a['priority'] <=> $b['priority']);
            return $pc == 0 ? $a['created_at'] <=> $b['created_at'] : $pc;
        }); 
    }

    public function printHeader() : void
    {
        foreach ($this->ordersH as $h) {
            echo str_pad($h, 20);
        }

        echo "\n" , str_repeat('=', 20 * count($this->ordersH)) , "\n";
    }

    
    public function printOrders() : void
    {
        $this->printHeader();
        $this->printItems();
    }

    public function printItems() : void
    {
        foreach ($this->orders as $item) {
            if ($this->stock->{$item['product_id']} >= $item['quantity']) {
                foreach ($this->ordersH as $h) {
                    if ($h == 'priority') {
                        echo str_pad($this->getPriority($item['priority']), 20);
                    } else {
                        echo str_pad($item[$h], 20);
                    }
                }
                echo "\n";
            }
        }        
    }


    private function getPriority(int $priority) : string
    {
        if ($priority == 1) {
            return 'low';
        } 
        return ($priority == 2) ? 'medium' : 'high';
    }
}