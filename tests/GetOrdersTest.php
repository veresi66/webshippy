<?php
use \App\classes\GetOrders;

class GetOrdersTest extends \PHPUnit\Framework\TestCase
{
    private $testClass;

    /**
     * @test
     */
    public function letrehozasiHibaTobbParameter() : void
    {
        $argv = '{"1":2,"2":3,"3":1}';
        $argc = 3;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Ambiguous number of parameters!');
        $this->testClass = new GetOrders($argv, $argc);
    }
}