<?php

namespace App\Helpers;

Class AppHelper {

    protected static $_instance = null;

    public static function instance() 
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function validateEmail($email) 
    {
        $regex = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";
        if (preg_match($regex, $email[0])) {
            return true;
        } else return false;
    }
}