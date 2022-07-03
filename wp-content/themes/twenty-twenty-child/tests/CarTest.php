<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Visionmate\Car;

class CarTest extends TestCase {
    
    public function testArgumentCount() {
        $this->expectException(\ArgumentCountError::class);
        new Car();
    }
    
    public function testCanCreate() {
        $this->assertInstanceOf(
            Car::class,
            new Car('First Car', 1000)
        );
    }

    public function testGetters() {
        $car = new Car('First Car', 1000);
        $this->assertEquals($car->getName(), 'First Car');
        $this->assertEquals($car->getPrice(), 1000);
    }

    public function testSetters() {
        $car = new Car('First Car', 1000);
        $this->assertTrue($car->setName('Second Car'));
        $this->assertTrue($car->setPrice(2000));
        $this->assertEquals($car->getName(), 'Second Car');
        $this->assertEquals($car->getPrice(), 2000);
        $this->assertFalse($car->setPrice(0));
        $this->assertFalse($car->setName(""));
    }
    
    public function testDetails() {
        $car = new Car('First Car', 1000);
        $this->assertEquals($car->getDetails(), [
            'name' => 'First Car',
            'price' => 1000
        ]);
    }
}