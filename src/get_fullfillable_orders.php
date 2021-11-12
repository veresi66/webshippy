<?php
namespace App;

use \App\classes\GetOrders;
use InvalidArgumentException;

require './vendor/autoload.php';
try {
    $orders = new GetOrders($argv[1], $argc);
    $orders->loadOrdersFromFile('./data/orders.csv');
    $orders->printOrders();
} catch (InvalidArgumentException $e) {
    echo $e->getMessage() , "\n";
}

