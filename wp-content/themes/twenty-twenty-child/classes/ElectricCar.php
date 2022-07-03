<?php

namespace Visionmate;

class ElectricCar extends Car implements interfaces\ElectricCarInterface {
  
  const minChargeTime = 15;

  protected $type;
  protected $rangeOnCharge;
  protected $chargeTime;

  public function __construct(string $name, float $price, string $type, float $rangeOnCharge, int $chargeTime) {
    parent::__construct($name, $price); 
    $this->setType($type);
    $this->setRangeOnCharge($rangeOnCharge);
    $this->setChargeTime($chargeTime);
  }

  public function getType() {
    return $this->type;
  }

  public function setType($value) { 
    if (!in_array($value,['electric', 'hybrid'])) {
      throw new \Exception('Wrong type value');
    }
    $this->type = $value;
  }

  public function getRangeOnCharge() : float {
    return (float) $this->rangeOnCharge;
  }

  public function setRangeOnCharge(float $value) : bool { 
    if (empty($value)) {
      return false;
    }
    $this->rangeOnCharge = $value;
    return true;
  }

  public function getChargeTime() : int {
    return $this->chargeTime;
  }

  public function setChargeTime(int $value) : bool { 
    if (empty($value)) {
      return false;
    }
    $this->chargeTime = $value;
    return true;
  }

  public function getDetails(bool $approximate = false) : array {
    $vars = get_object_vars($this);
    $vars['approximate'] = $this->approximate();
    return $vars;
  }

  public function approximate() : float {
    $calculated = ($this->getRangeOnCharge() / $this->getChargeTime()) * self::minChargeTime;
    return (float) $calculated;
  }

}
