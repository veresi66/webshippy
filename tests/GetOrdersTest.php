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
     * @test
     */
    public function fejlecKiiratas() : void
    {
        $argv = '{"1":2,"2":3,"3":1}';
        $argc = 2;
        $this->testClass = new GetOrders($argv, $argc);    
        $this->testClass->loadOrdersFromFile('./data/orders.csv');
        
        $expected  = "product_id          quantity            priority            created_at          \n";
        $expected .= "================================================================================\n";

        $this->expectOutputString($expected);

        $this->testClass->printHeader();

    }

    /**
     * @test
     */
    public function torzsKiiratas() : void
    {
        $argv = '{"1":2,"2":3,"3":1}';
        $argc = 2;
        $this->testClass = new GetOrders($argv, $argc);    
        $this->testClass->loadOrdersFromFile('./data/orders.csv');
        
        $expected  = "1                   2                   high                2021-03-25 14:51:47 \n";
        $expected .= "2                   1                   medium              2021-03-21 14:00:26 \n";
        $expected .= "3                   1                   medium              2021-03-22 12:31:54 \n";
        $expected .= "2                   2                   low                 2021-03-24 11:02:06 \n";
        $expected .= "1                   1                   low                 2021-03-25 19:08:22 \n";
        
        $this->expectOutputString($expected);

        $this->testClass->printItems();    
    }

    /**
     * @test
     */
    public function kiiratasExaple1() : void
    {
        $argv = '{"1":8,"2":4,"3":5}';
        $argc = 2;
        $this->testClass = new App\classes\GetOrders($argv, $argc);
        $this->testClass->loadOrdersFromFile('./data/orders.csv');

        $expected  = "product_id          quantity            priority            created_at          \n";
        $expected .= "================================================================================\n";
        $expected .= "3                   5                   high                2021-03-23 05:01:29 \n";
        $expected .= "1                   2                   high                2021-03-25 14:51:47 \n";
        $expected .= "2                   1                   medium              2021-03-21 14:00:26 \n";
        $expected .= "1                   8                   medium              2021-03-22 09:58:09 \n";
        $expected .= "3                   1                   medium              2021-03-22 12:31:54 \n";
        $expected .= "1                   6                   low                 2021-03-21 06:17:20 \n";
        $expected .= "2                   4                   low                 2021-03-22 17:41:32 \n";
        $expected .= "2                   2                   low                 2021-03-24 11:02:06 \n";
        $expected .= "3                   2                   low                 2021-03-24 12:39:58 \n";
        $expected .= "1                   1                   low                 2021-03-25 19:08:22 \n";

        $this->expectOutputString($expected);

        $this->testClass->printOrders();
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