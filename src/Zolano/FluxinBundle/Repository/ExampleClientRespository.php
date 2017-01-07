<?php

class Example{
    
    

//renvoie toutes les courbes des matières du workset, ainsi que la courbe agrégée
    public function loadWorksetData($user_id, $workset, $begin = null, $end = null, $mikbook = false, $aggreg = 'day'){
        
        $fields = $workset->getFields();
        $fields[] = null;
        
        return $this->loadFieldsData($user_id, $workset->getId(), $workset->getFields(), $begin, $end, $mikbook, $aggreg);
    }
    
    

    /**     fonction dédiée à récupérer les données et les paramètres AMCHARTS pour un workset                   FONCTION DU PLUS HAUT NIVEAU D'ORCHESTRATION !!!
     * @param $graph            GraphEntity correspondant au workset (ou autre), muni d'une liste de Fields, et d'une fonction getFieldList()
     * @param $field_id         GraphEntity correspondant au workset (ou autre), muni d'une liste de Fields, et d'une fonction getFieldList()
     * @param null $begin       date de début de la récupération au format "Y-m-d",;
     * @param null $end         date de fin de la récupération au format "Y-m-d"
     * @param bool $mikbook     items DONE ou MIKBOOKED
     * @return array, composé de 4 champs :
     *                                      'graph'         =>  L'entité Graph passé en entrée
     *                                      'kpi_data'      =>  Les séries collectées, remaniées mais pas formatées pour AmCharts                                   (construction d'un tableau de données dans la vue)
     *                                      'chart_params'  =>  Les paramètres du graphe (en JSON), directement pluggable dans ungraphe amCharts :                  chart = AmCharts.makeChart("html_id", chart_params);
     *                                      'chart_data'    =>  Les données formatées pour Amcharts (en JSON), directement pluggable dans un graphe amChart :       chart.dataProvider = generateChartData(chart_data);
     */
    public function loadFieldsData($user_id, $workset_id, $fields = array(), $begin = null, $end = null, $mikbook = false, $aggreg = 'day'){
        
        $influx = $this->getInfluxRepository();
//
        //on récupère les données des KPI du graphe
        $series = $this->fetchFieldsData($user_id, $workset_id, $fields, $begin, $end, $mikbook, $aggreg);

        //on formatte le résultat dans un format facilement exploitable par la suite
        $series =  $influx->formatSeries($series);        

        //on formate les données + convert JSON pour les rendre exploitables par les graphes AmCharts
        $chart_data = $influx->formatData4AmCharts($series);

        //on obtient un objet JSON représentant les paramètres du graphe AmCharts, appliquables directement
        $chart_params = $influx->getAmChartsJsonParams($series);

        //on ordonne le résultat dans un structure à 4 champs
        $output = array(
            'series'        => $series,
            'chart_data'    => $chart_data,
            'chart_params'  => $chart_params,
        );

        return $output;

    }    
    

    /**         Renvoie les données pour l'utilisateur et tous lesFields passés en oaram
     * 
     * @param type $user_id
     * @param type $workset_id
     * @param type $fields                  //un tableau avec les fields a requêter. Un élément NULL signifie l'agrégation de tous ceux du workset
     * @param type $begin
     * @param type $end
     * @param type $mikbooked
     * @param type $aggreg
     * @return type
     */
    public function fetchFieldsData($user_id, $workset_id, $fields = array(), $begin = null, $end = null , $mikbooked = false, $aggreg = 'day'){

        $series = array();

        //on assure les valeurs de BEGIN et END
        $date = date("Y-m-d");
        //$last_week = date("Y-m-d",mktime(0,0,0,date("m"), date("d")-7, date("Y")));
        $last_month = date("Y-m-d",mktime(0,0,0,date("m")-1, date("d"), date("Y")));

        //si les dates ne sont pas renseignées, on met les bornes par défaut (de ya un mois à AJD)
        if($begin === null){ $begin = $last_month; }
        if($end === null){ $end = $date; }

        //pour chaque indicateur du graphe
        foreach($fields as $field){
            //si lefield est null alors on requête leworkset agrégé
            $field_id = ($field !== null) ? $field->getId() : null;
            $field_name = ($field !== null) ? $field->getName() : "Total";
            
            $data =  $this->getItemsAggregate($begin, $end, $user_id, $workset_id, $field_id, $aggreg, $mikbooked);

            if(count($data) > 0) $series[$field_name] = $data;
        }
        
//
//        //on formatte le résultat dans un format facilement exploitable par la suite
//        return $this->formatSeries($series);
        return $series;

    }        
    
    
    
    //
    /**
     * Renvoie l'agrégation des items miknookés, granularité en paramètre
     *
     * @param UserId            $user_id        The ID of the user to be queried
     * @param Worksetd          $workset_id     The ID of the workset wanted.
     * @param FieldId           $field_id       The ID of the field wanted. NULL means all this workset's fields agregated
     * @param Begin             $begin          The beginning of the period wanted. Expected format : YYYY-MM-DD
     * @param End               $end            The end of the period wanted. Expected format : YYYY-MM-DD
     * @param Aggreg            $aggreg         The granularity wanted : ENUM ( (hour, day, week, month)
     * @param Mkb               $mkb            Either we fetch DONE or MIKBOOKED items
     */        
    public function getItemsAggregate($begin, $end, $user_id, $workset_id, $field_id = null, $aggreg = 'day', $mkb = false){
        
        $agregation = "day";
        
        switch($aggreg){
            case 'hour' :
    
                $agregation = '1h';
                break;
            case 'day' :
         
                $agregation = '1d';
                break;
            case 'week' :
  
                $agregation = '1w';
                break;
            case 'month' :
  
                $agregation = '4w';
                break;

        }
        
        $collection = ($mkb) ? "item_mkb" : "items_done" ;
        
        //on crée la condition sur matiere uniquement si différent de -1
        $where_condition = " workset_id = '$workset_id'";
        
        //on crée la condition sur matiere uniquement si différent de -1
        $where_condition .= ($field_id == null) ? "" : " AND field_id = '$field_id'";
        
        $groupby =  " GROUP BY time($agregation)";
        
        $influx = $this->getInfluxRepository();
        
        $brute_data = $influx->selectMetrics("count(done)", $collection, $begin, $end, $where_condition, $groupby, null);
        
        $data = $influx->Influx2Array($brute_data);
        
        return $data;
        
    }    
    

    
    //
    /**
     * Ecrit un point dans influxDB 
     *
     * @param ItemId            $item_id        The ID of the item to be written
     * @param UserId            $user_id        The bID of the user to be written
     * @param WorksetId         $workset_id     The bID of the user to be written
     * @param FieldId           $field_id       The bID of the field to be written
     * @param Mkb               $mkb            Wether the item has benn mikbooked TRUE or done FALSE
     */      
    public function markInfluxDBItem($item_id, $user_id, $workset_id, $field_id, $mkb = false){

        $mark_array = [
            "tags" => [
                "item_id"       => "$item_id",
                "field_id"      => "$field_id",
                "workset_id"    => "$workset_id",
                "user_id"       => "$user_id",
            ],
            "points" => [
                [
                    "measurement" => ($mkb) ? "items_mkb" : "items_done",
                    "fields"    => [
                        "done" => 1,
                    ]
                ],
            ],
        ];    
        
   
        $influx = $this->getInfluxRepository();    

        return $influx->mark($mark_array);
    }        
        
    
    /** Instancie un Influxrepository
     * 
     * @return InfluxRepository
     */
    private function getInfluxRepository(){
        
       return  new InfluxRepository($this->getEntityManager());
        
         
    }
}