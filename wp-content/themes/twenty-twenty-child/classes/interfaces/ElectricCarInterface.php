<?php 

namespace Visionmate\interfaces;

interface ElectricCarInterface extends CarInterface {

  public function getRangeOnCharge() : float;

  public function setRangeOnCharge(float $value) : bool;
  
  public function getChargeTime() : int;
    
  public function setChargeTime(int $value) : bool;
    
  public function approximate() : float;

}
