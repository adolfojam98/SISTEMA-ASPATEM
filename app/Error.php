<?php

namespace App;

class Error
{
    function __construct($code, $description, $type, $httpcode)  
    {   
        $this->code = $code;
        $this->description = $description;
        $this->type = $type;
        $this->httpCode = $httpcode;
    }

    public $code; 
    public $description; 
    public $type; 
    public $httpCode; 
}
