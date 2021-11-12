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

    /**
     * @test
     */
    public function letrehozasiHibaInvalidJson() : void
    {
        $argv = '{1:2,2:3,3:1}';
        $argc = 2;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid json!');
        $this->testClass = new GetOrders($argv, $argc);        
    }

    /**
     * @test
     */
    public function sikeresJsonKonvertalas() : void
    {
        $argv = '{"1":2,"2":3,"3":1}';
        $argc = 3;
        $this->testClass = new GetOrders($argv, $argc);

        $this->assertIsObject($this->testClass->stock);
    }
}