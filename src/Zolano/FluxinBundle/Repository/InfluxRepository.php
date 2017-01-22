<?php

namespace Zolano\FluxinBundle\Repository;


use Symfony\Component\Config\Definition\Exception\Exception;
use Zolano\FluxinBundle\Adapter\InfluxAdapter;
use FirstBundle\Helpers\remindMe;
use Doctrine\ORM\EntityManager;

class InfluxRepository{


//zobbylamouche
    /**
     * @var InfluxAdapter       Adapter qui encapsule le client influxDB proposé par corley.
     */
    protected $influxAdapter;
    

    /**
     * @var EntityManager
     */
    protected $_em;    

    /**
     * @var Config : array of configuration
     */
    protected $config;

//    /**
//     * Connexion à la base de données
//     *
//     * @var Connection
//     */
//    protected $db;

    /**
     * InfluxRepository constructor.
     * @param string $host
     * @param string $database
        $this->user     = $config['idb_user'];
        $this->pwd      = $config['idb_pwd'];
        $this->host     = $config['idb_host'];
        $this->database = $config['idb_dbname'];* 
     */
    public function __construct(EntityManager $em, Array $config = array()){
  
//
//        $this->config = (count($config)) ? $config : array(
//            'idb_user'      => 'zolano',
//            'idb_pwd'       => 'zolano',
//            'idb_host'      => 'localhost',
//            'idb_dbname'    => 'items',
//        );

        $dbname = (remindMe::areWeOnPreprod()) ? 'items_preprod' : 'items';

//        dump($dbname);die;

        $this->config = (count($config)) ? $config : array(
            'idb_user'      => 'admInflux',
            'idb_pwd'       => '12d1c5e1c1c39c557cb57819338efb80',
            'idb_host'      => '137.74.197.5',
            'idb_dbname'    => $dbname,
        );


//        $this->db = $app->db;
        
        $this->_em = $em;

//        dump($this->config);die;

        try{

            $this->influxAdapter = new InfluxAdapter($this->config);
        }
        catch(Exception $e){
//            dump("zob");
            throw $e;
        }
    }

    /**
     *  ############################################### Fonctions publiques Génériques ###############################################
     *  ############################################### Fonctions publiques Génériques ###############################################
     *  ############################################### Fonctions publiques Génériques ###############################################
     *  ############################################### Fonctions publiques Génériques ###############################################
     *  ############################################### Fonctions publiques Génériques ###############################################
     */

    /**
     * Fonction dédiée à effectuer nptequelle requête custom sur une base InfluxDB
     * @param $database
     * @param $query
     * @return mixed
     */
    public function selectQueryFromDatabase($database, $query){

//        dump($query);

        try{

            $this->influxAdapter->setDataBase($database);

            return $this->query($query);
        }
        catch(Exception $e){
            throw $e;
        }
    }


    /**
     * Fonction dédiée à requêter une collection influxDb dans une DB influx (pas beaucoup de subtilité)
     * @param $collection
     * @return mixed
     */
    public function selectAllFrom($database, $collection, $condition = ""){

        $where = ($condition !== "") ? " WHERE $condition " : "" ;

        $query = "SELECT * FROM $collection $where";



        return $this->selectQueryFromDatabase($database, $query);
    }


    /**
     * Fonction dédiée à la récupération de KPI ( un ou tous ) entre les bornes temporelles données en entrées
     * @param $database
     * @param $collection
     * @param $begin
     * @param $end
     * @param string $kpi_id
     * @return mixed
     */
    //select * from stats_Cecosane where time < '2016-05-01T00:00:00Z' AND time  > '2016-02-03T00:00:00Z' AND kpi_id = '133'
    public function selectMetrics($fields, $collection,  $begin, $end, $where = "", $groupby = "", $database = null){
        
        if($database === null) $database = $this->config['idb_dbname'];    
        
         //on retire le décalage avec GMT aux bornes temporelles pour bien avoir les données de minuit à minuit
//        $begin = $this->addGMToffset($begin . " 00:00:00", true);
//        $end = $this->addGMToffset($end . " 00:00:00", true);

        $where_condition = ($where !== "") ? " AND $where" : "" ;

        $query = "SELECT $fields FROM $collection WHERE time > '$begin' AND time <= '$end' $where_condition $groupby";

//        dump($database);
//        dump($query);
        
        return $this->selectQueryFromDatabase($database, $query);
    }
    
    /** On délègue l'écriture de points à l'objet encapsulé : InfluxAdapter
     * @param $name
     * @param $values
     * @return mixed
     */
    public function mark($name, array $values = []){
        return $this->influxAdapter->mark($name, $values);
    }    
    
    
/**
 *  ############################################### Fonctions  Orientées TDB          ###############################################
 *  ############################################### ################################# ###############################################
 *  ############################################### ################################# ###############################################
 *  ############################################### ################################# ###############################################


    /** Convertit le format des données renvoyées par InfluxDB à un format array propice aux usages futurs de ces données
     * @param $brute_data       : données issues d'un requêtage direct de la base influxDB (avec selectQueryFromDatabase par exemple)
     * @return array            : données au format "Séries" utilisé en input pour le formatage ultérieur des données
     */
    public function Influx2Array($brute_data){

        $results = $brute_data['results'][0];

        $output = array();

        if(isset($results['series'])){
            $values = $brute_data['results'][0]['series'][0]['values'];

            foreach($values as $val){
                // 0 ==> time, 1 ==> value
                $date = $val[0];

                //                                      true : on applique l'offset GMT aux données extraites (pour gérer le fuseau horaire)
                $time = $this->formatRFC3339Date($date, true);

                $output[] = array(
                    'value'     => "$val[1]",
                    'date'     => $time,
                );
            }

        }
        return $output;

    }

    /** Transforme le format des séries  pour qu'il soit exploitable par la suite
     * @param $series       données renvoyees par fetchGraphData ou fetchKpiData
     * @return array        données dans un format exploitable pour AmCharts
     */
    public function formatSeries($series){

        $kpi_headers = array();
        $kpi_values = array();
     
        foreach($series as $kpi => $data){
            $kpi_headers[] = $kpi;
            foreach($data as $row){
                $kpi_values[$row['date']][$kpi] = $row['value'];
            }
        }

        ksort($kpi_values);
        ksort($kpi_headers);

        $kpi_data = array(
            'headers'   => $kpi_headers,
            'values'    => $kpi_values,
        );

        return $kpi_data;
    }

    /** Transforme les données d'input pour les faire correspondre au format d'entrée pour AmCharts
     * @param $series         Output de formatSeries()
     * @return JSON             Données en JSON au format attendu par les graphes AmCharts
     */
    public function formatData4AmCharts($series){

        $result = array();
        foreach($series['values'] as $date => $values){

            $row = array();
            $row['date'] = strtotime($date)*1000;
            foreach($values as $kpi => $val){
                $row[$kpi] = $val;
            }
            $result[] = $row;
        }

//        return json_encode($result);
        return $result;
    }

    /** Restitue a partir des données en input un objet JSON de configuration apte à paramétrer le graphe AmCharts qui devra les tracer
     * @param $series       Données des KPI du graphe, servant de base pour la constitution du paramétrage (OUTPUT de formatSeries() )
     * @return JSON         Objet JSON de configuration du graphe AmChart dédié à tracer ces données
     */
    public function getAmChartsJsonParams($series){

        $params = $this->formatParams4amCharts($series);

//        $json_params = $this->generateJsonLineParams4amcharts($params, $series);
        $json_params = $this->generateJsonStackedParams4amcharts($params, $series);

        return $json_params;

    }

    /** Transforme les données renvoyées par formatSerie() pour créer les params de configuration des courbes pour chaque série
     * @param $series       OUTPUT de formatSeries() : les données à tracer dans le graphe sont utilisées pour générer le paramétrage (nb de headers, nb de courbes, etc..)
     * @return array        Objet params, muni de la config AXES + courbes pour chacun des KPIs dans la collectio de données en INPUT
     */
    private function formatParams4amCharts($series){

        $colors = $series['colors'];

        $params = array();

        $axis_offset = 5;
        $color_index = 0;
        foreach($series['headers'] as $head){

            $axis = new \stdClass();
            $axis->offset = $axis_offset;
            $axis->gridAlpha = 0;
            $axis->axisColor = $colors[$head];
            $axis->axisThickness = 1;

            $chart = new \stdClass();
            $chart->title = $head;
            $chart->valueField = $head;
            $chart->bullet = "round";
            $chart->hideBulletsCount = 30;
            $chart->bulletBorderThickness = 1;
//            $chart->bulletColorR = "#333";

            $params[] = array(
                'axis'      => $axis,
                'chart'     => $chart,
            );

            $axis_offset += 30;

        }
        //        dump($params);
        return $params;

    }

    /** Utilise les params renvoyés par formatParams4amCharts(), et les utilise pour composer l'objet JSON qui va paramétrer ENTIEREMENT le graph amcharts
     * @param $params   OUTPUT de formatParams4amCharts(), objet contenant les paramètres pour tous les KPI à grapher
     * @return Json     Objet de conf prêt à l'emploi, appliquable directement sur un objet graph AmCharts lors de la fonction makeChart(html_id, json_params)
     */
    private function generateJsonLineParams4amcharts($params){

        //        dump($params); exit;

        $json_params = new \stdClass();

        $json_params->type = "serial";
        $json_params->theme = "light";
        $json_params->legend = array(
            'marginLeft'        => 110,
            'useGraphSettings'  => true,
        );
        $json_params->chartScrollbar = array(
            'enabled'           => true,
            'scrollbarHeight'   => 20,
        );
        $json_params->valueScrollbar = array(
            'enabled'           => false,
            'scrollbarHeight'   => 10,
        );
        $json_params->chartCursor = array(
            'cursorPosition'            => 'mouse',
            'cursorAlpha'               => 0.1,
            'cursorColor'               => "#333",
            'color'                     => "#FFF",
            'fullWidth'                 => true,
            'valueLineBalloonEnabled'   => true,
            "categoryBalloonDateFormat" => "DD MMM  HHh00",
        );
        $json_params->categoryField = "date";
        $json_params->categoryAxis = array(
            'parseDates'        => true,
            'axisColor'         => "#DADADA",
            'minPeriod'         => "hh",
            'twoLineMode'       => true,
            'minorGridEnabled'  => true,
        );
        $json_params->valueAxes = array(array(
            'id'                => 'v1',
            'axisColor'         => (isset($params[0]['axis'])) ? $params[0]['axis']->axisColor : "#333",
            'axisThickness'     => 2,
            'gridAlpha'         => 0,
            'axisAlpha'         => 1,
            'position'          => 'left',
            'precision'         => 3,
        ));
        $json_params->graphs = array();
        foreach($params as $param){

            $axis = $param['axis'];
            $chart = $param['chart'];

            $graph = array(
                "valueAxis"             => "v1",
                "bullet"                => "round",
                "title"                 => $chart->title,
                "type"                  => "smoothedLine",
                "bulletBorderThickness" => 1,
                "lineColor"             => $axis->axisColor,
                "legendColor"           => $axis->axisColor,
                "hideBulletsCount"      => 30,
                "valueField"            => $chart->valueField,
                "fillAlphas"            => 0,
            );

            $json_params->graphs[] = $graph;
        }

        $json_params->export = array(
            "enabled"   => true,
            "position"  => "bottom-right",
        );

//        dump($params);
//        die;
//        return json_encode($json_params);
        return $json_params;
    }


    /** Utilise les params renvoyés par formatParams4amCharts(), et les utilise pour composer l'objet JSON qui va paramétrer ENTIEREMENT le graph amcharts
     * @param $params   OUTPUT de formatParams4amCharts(), objet contenant les paramètres pour tous les KPI à grapher
     * @return Json     Objet de conf prêt à l'emploi, appliquable directement sur un objet graph AmCharts lors de la fonction makeChart(html_id, json_params)
     */
    private function generateJsonStackedParams4amcharts($params){

        //        dump($params); exit;

        $json_params = new \stdClass();

        $json_params->type = "serial";
        $json_params->theme = "light";
//        $json_params->legend = array(
//            'marginLeft'        => 110,
//            'useGraphSettings'  => true,
//            "equalWidths"       => false,
//            "periodValueText"   => "total: [[value.sum]]",
//            "valueAlign"        => "left",
//            "valueText"         =>  "[[value]] ([[percents]]%)",
//            "valueWidth"        => 100
//        );
        $json_params->chartScrollbar = array(
            'enabled'           => true,
            'scrollbarHeight'   => 20,
        );
        $json_params->valueScrollbar = array(
            'enabled'           => false,
            'scrollbarHeight'   => 10,
        );
        $json_params->chartCursor = array(
            'cursorPosition'            => 'mouse',
            'cursorAlpha'               => 0.1,
            'cursorColor'               => "#333",
            'color'                     => "#FFF",
            'fullWidth'                 => true,
            'valueLineBalloonEnabled'   => true,
            "categoryBalloonDateFormat" => "DD MMM HHh00",
//            "categoryBalloonDateFormat" => "HH heures",
        );
        $json_params->categoryField = "date";
        $json_params->categoryAxis = array(
            'parseDates'        => true,
            "startOnAxis"       => true,
            'axisColor'         => "#DADADA",
            'minPeriod'         => "hh",
            'twoLineMode'       => true,
            'minorGridEnabled'  => true,
            "gridAlpha"         => 0.07,
        );
        $json_params->valueAxes = array(array(
            'id'                => 'v1',
            'stackType'         => "regular",
//            'axisColor'         => (isset($params[0]['axis'])) ? $params[0]['axis']->axisColor : "#333",
            'axisThickness'     => 1,
            'gridAlpha'         => 0.07,
            'axisAlpha'         => 1,
            'position'          => 'left',
//            'precision'         => 3,
        ));
        $json_params->graphs = array();
        foreach($params as $param){

            $axis = $param['axis'];
            $chart = $param['chart'];

            $graph = array(
                "valueAxis"             => "v1",
                "bullet"                => "round",
                "balloonText"           => $chart->valueField . " : [[value]]",
                "title"                 => $chart->title,
//                "type"                  => "smoothedLine",
                "bulletBorderThickness" => 0.5,
                "lineColor"             => $axis->axisColor,
                "legendColor"           => $axis->axisColor,
                "hideBulletsCount"      => 30,
                "valueField"            => $chart->valueField,
                "fillAlphas"            => 0.5,
                "lineAlpha"             => 0.5,
            );
//            "listeners": [{
//                            "event": "init",
//                "method": function(event) {
//                                var chart = event.chart;
//                                for(var x = 0; x < chart.graphs.length; x++) {
//                                    var graph = chart.graphs[x];
//                                    var graphSum = 0;
//                                    for(var i = 0; i < chart.dataProvider.length; i++) {
//                                        graphSum += chart.dataProvider[i][graph.valueField];
//                                    }
//                    if (graphSum == 0)
//                        graph.visibleInLegend = false;
//                  }
//                }
            $json_params->graphs[] = $graph;
        }

        $json_params->export = array(
            "enabled"   => true,
            "position"  => "bottom-right",
        );

//        dump($params);
//        die;
//        return json_encode($json_params);
        return $json_params;
    }


/**
 * ######################################## PRIVATE FUNCTIONS ########################################
 * ######################################## ################# ########################################
 * ######################################## ################# ########################################
 */

    /** transforme une date au format RFC 3339 en format YYYY-MM-DD hh:mm:ss, en tenant compte du décalage horaire (grâce à addGMToffset)
     * @param $date                 sous la forme : yyyy-mm-jjThh:ii:ssZ        Format RFC3339   ( https://www.ietf.org/rfc/rfc3339.txt )
     * @param bool $GMToffset       TRUE si on souhaite ajouter le GMT offset à la date transformée (i.e tenir compte du décalage horaire)
     * @return string               YYYY-MM-DD hh:mm:ss
     */
    private function formatRFC3339Date($date, $GMToffset = true){

        //on split par T central
        $date_row = explode('T' , $date);

        //on recupère yyyy-mm-dd
        $ymd = $date_row[0];

        $hms = $date_row[1];

        $date_origin = $ymd . " " . substr($hms,0,-1);

        $date_output = ($GMToffset) ? $this->addGMToffset($date_origin) : $date_origin ;

        return $date_output;
    }

    /** Ajoute les heures d'écart avec le fuseau GMT
     * @param $date                 date d'input, sur laquelle on veut appliquer le décalage avec GMT
     * @param bool $remove          définit si l'écart doit etre soustrait ou ajouté (pour les bornes temporelles il faut soustraire, pour l'extraction des données i faut rajouter)
     * @return date format          YYYY-MM-DD hh:mm:ss (avec l'offset appliqué)
     */
    private function addGMToffset($date, $remove = false){

        //on récupère le décalage GMT
        $decalage = date('P');                      //dump($decalage);
        $dec_hm = explode('+',$decalage)[1];        //dump($dec_hm);
        $dec_h =  substr($dec_hm, 0, -3);           //dump($dec_h);
        $int_dec_h = intval($dec_h);                //dump($int_dec_h);
        //on ,passe en timestamp
        $timestamp = strtotime($date);              //dump($timestamp);
        //on ajoute (ou retranche) l'offset au timestamp
        $timestamp = (!$remove) ? $timestamp + $int_dec_h * 60 * 60 : $timestamp - $int_dec_h * 60 * 60 ;
                                                    //dump($timestamp);
        //on formatte la date au format d'output
        $date_offset = date('Y-m-d H:i:s', $timestamp);
                                                    //dump($date_offset);

        return $date_offset;
    }


    /** On délègue le requêtage à l'objet encapsulé : InfluxAdapter
     * @param $query
     * @return mixed
     */
    private function query($query){
        return $this->influxAdapter->query($query);
    }
    



    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->_em;
    }    
    
}


