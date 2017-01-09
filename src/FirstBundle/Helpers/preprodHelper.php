<?php

namespace FirstBundle\Helpers;

class preprodHelper
{

    public static function areWeOnPreprod(){
//        dump($_SERVER);
        if(strpos($_SERVER['HTTP_REFERER'], 'preprod')){
            return true; //we are on preprod !
        }
        else return false; // we are on prod
    }
}