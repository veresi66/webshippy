<?php
namespace App\classes;

use Exception;
use InvalidArgumentException;

class GetOrders
{
    public function __construct(string $argv, int $argc)
    {
        if ($argc != 2) {
            throw new InvalidArgumentException('Ambiguous number of parameters!');
        }
    }
}