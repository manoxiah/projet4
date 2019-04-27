<?php

namespace App\Validator\Constraints;

class ValidateDateFormat
{
    public function ValidateDateFormat( $date ) 
    {
        if ( ! strpos( $date, '/' ) ) 
        {
            return false;
        }
    
        if ( substr_count( $date, '/' ) !== 2 ) 
        {
            return false;
        }

        $split = explode( '/', $date );
    
        return checkdate( $split[1], $split[0], $split[2] );
    }
}
