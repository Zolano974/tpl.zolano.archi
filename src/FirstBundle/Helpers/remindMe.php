<?php

namespace FirstBundle\Helpers;

class remindMe
{

    public static function areWeOnPreprod(){
//        dump($_SERVER);die;
        if(strpos($_SERVER['REQUEST_URI'], 'preprod')){
//            dump('preprod');die;
            return true; //we are on préprod !
        }

        else {
//            dump('prod');die;
            return false; // we are on prod
        }
    }

    public static function amIConnected(){

        return (isset($_SESSION['connected']) && $_SESSION['connected'] === true);

    }

}