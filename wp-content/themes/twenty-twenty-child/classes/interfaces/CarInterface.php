<?php

namespace Visionmate\interfaces;

interface CarInterface {
  
  public function getName() : string;
    
  public function setName(string $value) : bool;
  
  public function getPrice() : float;

  public function setPrice(float $value) : bool;

  public function getDetails() : array;
  
} 
