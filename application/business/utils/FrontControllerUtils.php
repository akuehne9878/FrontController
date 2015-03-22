<?php

namespace FrontController\application\business\utils;

class FrontControllerUtils {
 
    
    public static function isDevelopmentEnvironment() {
        $localhost = array(
            '127.0.0.1',
            '::1'
        );
        
        if (in_array($_SERVER['REMOTE_ADDR'], $localhost)) {
            return true;
        }
        return false;
    }
    
    
}
