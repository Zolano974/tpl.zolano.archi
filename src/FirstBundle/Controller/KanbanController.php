<?php

namespace FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FirstBundle\Repository\KanbanRepository;

//use FirstBundle\Helpers\InfluxDB\InfluxRepository;
use Zolano\FluxinBundle\Repository\InfluxRepository;
use \Symfony\Component\Translation\Exception\NotFoundResourceException;

class KanbanController extends AbstractController
{
    
    //action qui affiche le kanban d'unematiere pour un tour donné
    public function kanbanAction($field_id, $iteration){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);
        
        $user_id = 1;
        
        if($field_id === null || $iteration === null){
            throw new NotFoundResourceException();
        }       
        
        
        $em = $this->getDoctrine()->getManager();
        
        $itemDAO = $em->getRepository('FirstBundle:Item');
        $fieldDAO = $em->getRepository('FirstBundle:Field');
        $kanbanDAO = $em->getRepository('FirstBundle:Kanban');
        
//        dump($kanbanDAO);
        
        $field = $fieldDAO->find($field_id);
        
        $items = $kanbanDAO->fetchAllItemsByFieldAndIteration($field_id, $iteration, $user_id);
        
        $items_step = $this->groupItemsBySteps($items);
        
        return $this->render('FirstBundle:Kanban:kanban.html.twig', array(
            'items_notdone'     => $items_step[0],
            'items_todo'        => $items_step[1],
            'items_ongoing'     => $items_step[2],
            'items_done'        => $items_step[3],
            'field'             => $field,
            'iteration'         => $iteration,
            'workset_id'        => $field->getWorkset()->getId(),
        ));             
        
    }
    
    //fonction dédiée ajax pour faire avancer un item d'une étape dans le kanban pour un tour donné
    public function stepupAction($item_id, $iteration, $field_id, $workset_id){
        
        $user_id = 1;
        
        if($item_id === null || $iteration === null || $field_id === null || $workset_id === null){
            throw new NotFoundResourceException();
        }       
        
        $em = $this->getDoctrine()->getManager();
        
        $itemDAO = $em->getRepository('FirstBundle:Item');
        $kanbanDAO = $em->getRepository('FirstBundle:Kanban');
        
        //on récupère le worksetId pour INfluxDB
//        $field = $em->getRepository('FirstBundle:Field')->find($field_id);
//        $workset_id = $field->getWorkset()->getId();
        
        //on incrément l'étape de l'item dans le kanban
        $item_kanban = $kanbanDAO->stepUp($item_id, $iteration, $user_id);
        
        //si l'item est au step final
        if($item_kanban->getStep() == 2){
            //on check l'item à done
            $itemDAO->done($item_id, $iteration, $user_id);
            
            //on écrit un point influx dans la collection item_mkb
            $itemDAO->markInfluxDBItem($item_id, $user_id, $workset_id, $field_id, false);
        }
        
//        dump($item_kanban); die;
        
        try{
           $em->persist($item_kanban);
           
           $em->flush();
           
        } catch (Exception $ex) {
            dump($ex); die;
        }

        
        $url = $this->generateUrl('kanban_kanban', array( 
            'field_id' =>  $field_id,
            'iteration' =>  $iteration,
        ));        
        
        return $this->redirect($url);
        
        
                
    }
    
    private function groupItemsBySteps($items){
        $output = array(
            0 => array(),
            1 => array(),
            2 => array(),
            3 => array(),
        );
        
        foreach($items as $row){
            
            $item = new \stdClass();
            $item->id = $row['item_id'];
            $item->name = $row['item_name'];
            $item->step = $row['step'];
            
            $output[$item->step][] = $item;
            
        }
        
        return $output;
    }
                   
}
