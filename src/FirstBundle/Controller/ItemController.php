<?php

namespace FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FirstBundle\Repository\ItemRepository;
use FirstBundle\Entity\Item;
use FirstBundle\Form\ItemType;
//use FirstBundle\Helpers\InfluxDB\InfluxRepository;
use \Symfony\Component\Translation\Exception\NotFoundResourceException;

use Zolano\FluxinBundle\Repository\InfluxRepository;

class ItemController extends Controller {

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $itemDAO = $em->getRepository('FirstBundle:Item');

        $items = $itemDAO->findAll();

//        var_dump($items);

        return $this->render('FirstBundle:Item:index.html.twig', array(
                    'items' => $items,
        ));
    }

    public function viewAction($id) {

        $em = $this->getDoctrine()->getManager();

        $itemDAO = $em->getRepository('FirstBundle:Item');

        $item = $itemDAO->find($id);

        return $this->render('FirstBundle:Item:view.html.twig', array(
                    'item' => $item,
        ));
    }

    public function createAction() {

        //on créer un Workset et on lui donne des valeurs en dur pour l'instant
        $item = new Item();

        $form = $this->createForm(ItemType::class, $item);

        $request = Request::createFromGlobals();

        //si le formulaire a été soumis
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                //on récupère le EntityManager
                $em = $this->getDoctrine()->getManager();

                //on persiste le item
                $em->persist($item);

                //on valide les transactions
                $em->flush();

                //onrenvoie vers la liste
                $url = $this->generateUrl('list_item');
                return $this->redirect($url);
            }
        }

        return $this->render('FirstBundle:Item:create-edit.html.twig', array(
                    'action' => 'create',
                    'form' => $form->createView(),
        ));
    }

    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();

        $itemDAO = $em->getRepository('FirstBundle:Item');

        $item = $itemDAO->find($id);

        $form = $this->createForm(ItemType::class, $item);

        $request = Request::createFromGlobals();

        //si le form a été soumis
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            //si il est valide
            if ($form->isValid()) {

                //on récupère le EntityManager
                $em = $this->getDoctrine()->getManager();

                //on persiste le item
                $em->persist($item);

                //on valide les transactions
                $em->flush();

                //onrenvoie vers la liste
                $url = $this->generateUrl('list_item');
                return $this->redirect($url);
            }
        }

        return $this->render('FirstBundle:Item:create-edit.html.twig', array(
                    'action' => 'edit',
                    'form' => $form->createView(),
        ));
    }

    public function deleteAction($id) {

        if ($id === null) {
            throw new NotFoundResourceException();
        }

        $request = Request::createFromGlobals();

        //si le form a été soumis
        if ($request->getMethod() == 'POST') {

            $id = $request->request->get('delete_id');

            $em = $this->getDoctrine()->getManager();

            $itemDAO = $em->getRepository('FirstBundle:Item');

            $item = $itemDAO->find($id);

            $em->remove($item);

            $em->flush();

            $url = $this->generateUrl('list_item');

            return $this->redirect($url);
        }

        return $this->render('FirstBundle:Item:delete.html.twig', array(
                    'id' => $id,
        ));
    }

    //fonction dédiée Ajax, pour le mikbookage des items
    public function mikbookAction() {

        $request = Request::createFromGlobals();

        if ($request->isXmlHttpRequest()) {
            
            $user_id = 1;

            $item_id = $request->request->get('item_id', null);
            
            $workset_id = $request->request->get('workset_id', null);
            
            $field_id = $request->request->get('field_id', null);

            $itemDAO = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('FirstBundle:Item');

            $itemDAO->mikbook($item_id, $user_id);

//            dump("ok");die;

            //trigger une insertion influxDB
            //  ici mikbook = true
            $influx_output = $itemDAO->markInfluxDBItem($item_id, $user_id, $workset_id, $field_id, true);

            $json_data = json_encode(array(
                'mikbooked'
            ));

            $response = new Response($json_data);

            $response->headers->set('Content-Type', 'application/json');

            return $response; //on utilise pas de template généralement en ajax
        }
    }

    //fonction dédiée Ajax, pour le mikbookage des items
    public function doneAction() {

        $request = Request::createFromGlobals();

//        if ($request->isXmlHttpRequest()) {

            $user_id = 1;

            $item_id = $request->request->get('item_id', null);

            $iteration = $request->request->get('iteration', null);
            
            $workset_id = $request->request->get('workset_id', null);
            
            $field_id = $request->request->get('field_id', null);

            $itemDAO = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('FirstBundle:Item');

            $field_complete = $itemDAO->done($item_id, $iteration, $user_id);

            //trigger une insertion influxDB
            $influx_output = $itemDAO->markInfluxDBItem($item_id, $user_id, $workset_id, $field_id, false);

            $json_data = json_encode($field_complete);

            $response = new Response($json_data);

            $response->headers->set('Content-Type', 'application/json');

            return $response; //on utilise pas de template généralement en ajax
//        }
    }

    public function fetchItemsDoneInfluxData($begin, $end, $user_id, $field_id = null){
        
    }



    public function testAction() {
        
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
