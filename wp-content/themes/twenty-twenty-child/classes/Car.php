<?php

namespace Visionmate;

class Car implements interfaces\CarInterface {
  protected $name;
  protected $price;
    
  public function __construct(string $name, float $price) {
    $this->setName($name);
    $this->setPrice($price);
  }
  
  public function getName() : string {
    return $this->name;
  }
  
  public function setName(string $value) : bool {
    if (empty($value)) {
      return false;
    }
    $this->name = $value;
    return true;
  }
  
  public function getPrice() : float {
    return (float) $this->price;
  }
  
  public function setPrice(float $value) : bool {
    if (empty($value)) {
      return false;
    }
    $this->price = $value;
    return true;
  }
  
  public function getDetails() : array {
    $vars = get_object_vars($this);
    return $vars;
  }

}
