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
        $argc = 2;
        $this->testClass = new GetOrders($argv, $argc);

        $prop = $this->getPrivateProperty($this->testClass::class, 'stock');
        $this->assertIsObject($prop->getValue($this->testClass));
    }

    /**
     * @test
     */
    public function sikeresFileBetoltes() : void
    {
        $argv = '{"1":2,"2":3,"3":1}';
        $argc = 2;
        $this->testClass = new GetOrders($argv, $argc);    
        $this->testClass->loadOrdersFromFile('./data/orders.csv');
        
        $prop = $this->getPrivateProperty($this->testClass::class, 'ordersH');
        $this->assertEquals(4, count($prop->getValue($this->testClass)));
        $prop = $this->getPrivateProperty($this->testClass::class, 'orders');
        $this->assertEquals(10, count($prop->getValue($this->testClass)));
    }


    /**
     * A private/protected property-k tesztelésének biztosítása
     * 
     * @param string $className
     * @param string $propertyName
     * @return mixed
     */
    private function getPrivateProperty(string $className, string $propertyName) : mixed
    {
        $ref = new ReflectionClass($className);
        $prop = $ref->getProperty($propertyName);
        $prop->setAccessible(true);

        return $prop;
    }
}