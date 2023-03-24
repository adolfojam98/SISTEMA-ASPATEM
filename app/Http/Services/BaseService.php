<?php

namespace App\Http\Services;

use App\Error; 
use App\Helpers\AppHelper;

class BaseService 
{
    public $errorDefinitions = array(); 
    public $lastError;     

    function __construct()
    {                 
        $this->clearErrors();        
    }

    public function getError($code)
    {
        foreach($this->errorDefinitions as $e) {
            if ($e->code == $code) {
                return($e);
            }
        }
    }

    public function clearErrors()
    {
        $this->lastError = null;
    }

    public function setError($code)
    {
        $this->lastError = $this->getError($code); 
    }
    
    public function setErrorData($code, $description, $type, $httpcode)
    {
        $this->lastError = new Error(); 
        $this->lastError->code = $code; 
        $this->lastError->description = $description; 
        $this->lastError->type = $type; 
        $this->lastError->httpCode = $httpcode; 
    }

    public function hasErrors()
    {
        return($this->lastError != null); 
    }

    public function getLastError()
    {
        return($this->lastError); 
    }
    
}
