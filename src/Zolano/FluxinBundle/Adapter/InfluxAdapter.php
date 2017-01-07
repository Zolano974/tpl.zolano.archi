<?php

### COMPOSER ###

//  composer require "corley/influxdb-sdk": "0.9.*"

//cette classe Adapter utilise les vendors suivant, qui doivent être gités car ils ne font pas partie d'OFT2 par défaut
//  corley
//  guzzlehttp
//  psr

namespace Zolano\FluxinBundle\Adapter;

//requirements for corley InfluxDB-php-SDK
use \InfluxDB\Client as InfluxClient;
use \InfluxDB\Adapter\Http\Options as HttpOptions;
use \InfluxDB\Adapter\Http\Reader as HttpReader;
use \InfluxDB\Adapter\Http\Writer as HttpWriter;

// requirements for GuzzleHttp Client
use \GuzzleHttp\Client;

class InfluxAdapter
{
    // @var String
    protected $database;

    // @var String
    protected $host;

    protected $user;

    protected $pwd;

    protected $options;

    // @var InfluxClient
    protected $IDBClient;

    public function __construct($config){

//        dump($config); die;

        //on récupère la config InfluxDB
        $this->user     = $config['idb_user'];
        $this->pwd      = $config['idb_pwd'];
        $this->host     = $config['idb_host'];
        $this->database = $config['idb_dbname'];

        //on instancie le client HTTP
        $http = new \GuzzleHttp\Client();

        //on crée les options HTTP pour communiquer avec le webservice InfluxDB
        $this->options = new HttpOptions();
        $this->options->setHost($this->host);
        $this->options->setDatabase($this->database);
        $this->options->setUsername($this->user);
        $this->options->setPassword($this->pwd);

        $this->IDBClient = new InfluxClient(new HttpReader($http,$this->options),new HttpWriter($http,$this->options));

    }

    public function query($query){

        return $this->IDBClient->query($query);
    }

    public function mark($name, array $values = []){
        return $this->IDBClient->mark($name, $values);
    }

    public function setDataBase($database){

        //on instancie le client HTTP
        $http = new Client();

        $this->options->setDatabase($database);

        $this->IDBClient = new InfluxClient(new HttpReader($http,$this->options),new HttpWriter($http,$this->options));
    }


}


