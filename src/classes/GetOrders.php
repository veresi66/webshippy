<?php
namespace App\classes;

use Exception;
use InvalidArgumentException;

class GetOrders
{
    private ?object $stock;

    public function __construct(string $argv, int $argc)
    {
        if ($argc != 2) {
            throw new InvalidArgumentException('Ambiguous number of parameters!');
        }
        if (($this->stock = json_decode($argv)) == null) {
            throw new InvalidArgumentException('Invalid json!');
        }
    }
}