<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Visionmate\ElectricCar;

class ElectricCarTest extends TestCase {
    
    public function testArgumentCount() {
        $this->expectException(\ArgumentCountError::class);
        new ElectricCar();
    }
    
    public function testCanCreateElectric() {
        $this->assertInstanceOf(
            ElectricCar::class,
            new ElectricCar('First Car', 1000, 'electric', 400, 300)
        );
    }

    public function testCanCreateHybrid() {
        $this->assertInstanceOf(
            ElectricCar::class,
            new ElectricCar('First Car', 1000, 'hybrid', 400, 300)
        );
    }

    public function test() {
        $this->expectException(\Exception::class);
        new ElectricCar('First Car', 1000, 'unknown', 400, 300);
    }

    public function testGetters() {
        $car = new ElectricCar('First Car', 1000, 'electric', 400, 300);
        $this->assertEquals($car->getType(), 'electric');
        $this->assertEquals($car->getRangeOnCharge(), 400);
        $this->assertEquals($car->getChargeTime(), 300);
        $this->assertEquals($car->approximate(), 20);
    }

    public function testSetters() {
        $car = new ElectricCar('First Car', 1000, 'electric', 400, 300);
        $this->assertTrue($car->setRangeOnCharge(800));
        $this->assertTrue($car->setChargeTime(600));
        $this->assertEquals($car->getRangeOnCharge(), 800);
        $this->assertEquals($car->getChargeTime(), 600);
        $this->assertEquals($car->approximate(), 20);
        $this->assertFalse($car->setRangeOnCharge(0));
        $this->assertFalse($car->setChargeTime(0));
    }

    public function testApproximate() {
        $this->assertEquals((new ElectricCar('First Car', 1000, 'electric', 100, 60))->approximate(), 25);
        $this->assertEquals((new ElectricCar('First Car', 1000, 'electric', 400, 300))->approximate(), 20);
        $this->assertEquals((new ElectricCar('First Car', 1000, 'electric', 800, 300))->approximate(), 40);
        $this->assertEquals((new ElectricCar('First Car', 1000, 'electric', 400, 600))->approximate(), 10);
    }
    
    public function testDetails() {
        $car = new ElectricCar('First Car', 1000, 'electric', 100, 60);
        $this->assertEquals($car->getDetails(), [
            'name' => 'First Car',
            'price' => 1000,
            'type' => 'electric',
            'rangeOnCharge' => 100.0,
            'chargeTime' => 60,
            'approximate' => 25.0
        ]);
    }

}