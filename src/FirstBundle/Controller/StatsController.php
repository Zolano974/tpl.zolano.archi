<?php

namespace FirstBundle\Controller;

use FirstBundle\Form\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FirstBundle\Repository\StatsRepository;
use FirstBundle\Entity\Item;
use FirstBundle\Form\ItemType;
//use FirstBundle\Helpers\InfluxDB\InfluxRepository;
use \Symfony\Component\Translation\Exception\NotFoundResourceException;

use Zolano\FluxinBundle\Repository\InfluxRepository;

class StatsController extends AbstractController {


    public function curveAction($workset_id, $mikbook){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

//        dump("zob");die;

        $user_id = 1;

        $request = Request::createFromGlobals();

//        $begin_date = $request->request->get('begin_date', '2017-01-01');
        $begin_date = $request->request->get('begin_date', null);
        $end_date = $request->request->get('end_date', null);

        $workset = $this->getDoctrine()
            ->getManager()
            ->getRepository('FirstBundle:Workset')
            ->fetchOneWithFields($workset_id);


        $itemDAO = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('FirstBundle:Item');

        try{


            $data_done = $itemDAO->loadWorksetData($user_id, $workset, '2017-01-08', $end_date, false);

//            $data_mkb = $itemDAO->loadFieldsData($user_id, $workset->getId(), array(null), $begin_date, $end_date, true, 'hour');
            $data_mkb = $itemDAO->loadWorksetData($user_id, $workset, '2017-01-01', '2017-06-01', false, 'month');

            $statsDao = new StatsRepository($this->getDoctrine()->getManager());

            $data_notes = $statsDao->getNotesStatsData($user_id, $workset_id, '2017-01-01', $end_date,'day');
        }
        catch(Exception $e){
            dump($e->getMessage());
        }


//        dump($data_notes);die;

        return $this->render('FirstBundle:Stats:curve.html.twig', array(
            'workset'               => $workset,
            //items done
            'series_done'           => $data_done['series'],
            'chart_data_done'       => $data_done['chart_data'],
            'chart_params_done'     => $data_done['chart_params'],
            //items_mikbooked
            'series_mkb'           => $data_mkb['series'],
            'chart_data_mkb'       => $data_mkb['chart_data'],
            'chart_params_mkb'     => $data_mkb['chart_params'],
            //stats
            'chart_data_note'      =>$data_notes['chart_data'],
            'chart_params_note'    =>$data_notes['chart_params'],
        ));

    }

    //      pour chaque matière

    //  - le nombre d'items total
    //  - le nombre d'items terminés
    //  - on en déduit le %

    //  le nombre de matières total

    //  le nombre de matières terminées

    //  nb d'items terminés / nb items total => % global du tour

    public function tourAction($workset_id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $request = Request::createFromGlobals();

        $tourDAO = $this->getDoctrine()
            ->getManager()
            ->getRepository('FirstBundle:Tour');

        $user_id = 1;

        $workset = $this->getDoctrine()
            ->getManager()
            ->getRepository('FirstBundle:Workset')
            ->fetchOneWithFields($workset_id);


        $statsDAO = new StatsRepository($this->getDoctrine()->getManager());

        $last_iteration = $tourDAO->getLastTour($workset_id, $user_id);

        $iteration = $request->query->get('iteration', $last_iteration);

        $it_numbers = array();
        for($i = $last_iteration; $i > 0; $i--){
            $it_numbers[] = "$i";
        }
        sort($it_numbers);

        $stats = $statsDAO->getWorksetGlobal($workset, $iteration, $user_id);

//        dump($stats);die;

        //si c'est un appel Ajax
        if($request->isXmlHttpRequest()){

            //traitement del'appel ajax

            #renvoyer du JSON

            $json_data= json_encode(array(
                'stats' => $stats,
                'iteration' => $iteration,
            ));

            $response = new Response($json_data);

            $response->headers->set('Content-Type','application/json');

            return $response;
        }
        else{
            return $this->render('FirstBundle:Stats:tour.html.twig', array(
                'workset_id'    => $workset_id,
                'iteration'     => $iteration,
                'it_numbers'    => $it_numbers,
                'stats'         => $stats,
            ));

        }


    }

    public function notestatsAction($workset_id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $user_id = 1;

        $request = Request::createFromGlobals();

        $begin_date = $request->request->get('begin_date', null);
        $end_date = $request->request->get('end_date', null);

    }


    public function noteAction($workset_id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $user_id = 1;

        $request = Request::createFromGlobals();

        $em = $this->getDoctrine()->getManager();

        $fieldDAO = $em->getRepository('FirstBundle:Field');

        $fields = $fieldDAO->fetchAllByWorksetId($workset_id);

        return $this->render('FirstBundle:Stats:note.html.twig', array(
            'fields'        => $fields,
            'workset_id'    => $workset_id,
        ));
    }

    //fonction dédiée Ajax, pour le mikbookage des items
    public function marknoteAction($workset_id) {

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $user_id = 1;

        $request = Request::createFromGlobals();

        if ($request->isXmlHttpRequest()) {

            $type = $request->request->get('type', 'cas');

//            print_r($request->request);die;

            $note = $request->request->get('note', null);

//            $workset_id = $request->request->get('workset_id', null);

            $field_id = $request->request->get('field_id', null);

            $itemDAO = $this->getDoctrine()
                ->getRepository('FirstBundle:Item');

            //trigger une insertion influxDB
            $influx_output = $itemDAO->markInfluxDBNote($type, $user_id, $workset_id, $field_id, $note);

            $json_data = json_encode($influx_output);

            $response = new Response($json_data);

            $response->headers->set('Content-Type', 'application/json');

            return $response; //on utilise pas de template généralement en ajax
        }
    }

    public function testAction() {

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $workset_id = 1;

        $user_id = 1;

        $begin  = "2016-10-21";
        $end    = "2016-10-30";
        $mikbook = false;

        $workset = $this->getDoctrine()
            ->getManager()
            ->getRepository('FirstBundle:Workset')
            ->fetchOneWithFields($workset_id);


        $itemDAO = $this->getDoctrine()
            ->getManager()
            ->getRepository('FirstBundle:Item');


        $data = $itemDAO->loadWorksetData($user_id, $workset, $begin, $end, $mikbook, 'hour');


//        dump($data);die;

        return $this->render('FirstBundle:Item:test.html.twig', array(
            'workset'           => $workset,
            'series'            => $data['series'],
            'chart_data'        => $data['chart_data'],
            'chart_params'      => $data['chart_params'],
        ));


//        $influxDAO = new InfluxRepository($this->getDoctrine()->getManager());
//
//        $brute_data_done    = $influxDAO->getItemsDoneAggregate( '2016-10-07T00:00:00Z', 'now()', -1, 'day');
//        $brute_data_mkb     = $influxDAO->getItemsMkbAggregate( '2016-10-07T00:00:00Z', 'now()', -1, 'day');
//
//        dump($brute_data_done);
//        dump($brute_data_mkb); die;

    }

}
