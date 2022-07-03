<?php 
namespace TwentyTwenty;

class Email {

    protected $email;
    
    public function __construct(string $email) {
	    $this->setEmail($email);
    }

    public function getEmail() {
	    return $this->email;
    }
    
    public function setEmail(string $email) {
	    $this->email = $email;
    }
}