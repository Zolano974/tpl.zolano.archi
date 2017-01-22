<?php

namespace FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FirstBundle\Entity\Workset;
use FirstBundle\Repository\WorksetRepository;
use FirstBundle\Form\WorksetType;

use \Symfony\Component\Translation\Exception\NotFoundResourceException;

class WorksetController extends AbstractController
{
    public function indexAction()
    {

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $worksetDAO = $this ->getDoctrine()
                            ->getManager()
                            ->getRepository('FirstBundle:Workset');
                            
                
                
        $worksets = $worksetDAO->findAll();
//        $worksets = $worksetDAO->fetchAllWithFields();

//        dump($worksets);die;

        return $this->render('FirstBundle:Workset:index.html.twig', array(
            'worksets'  => $worksets,
        ));        

    }
    
    public function viewAction($id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $workset = $this ->getDoctrine()
                    ->getManager()
                    ->getRepository('FirstBundle:Workset')
                    ->fetchOneWithFields($id);
//                    ->find($id);


        
        return $this->render('FirstBundle:Workset:view.html.twig', array(
            'workset'  => $workset,
        ));        
                
    }
    
    public function workAction($id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);
        
        $user_id = 1;
        
        $worksetDAO = $this ->getDoctrine()
                            ->getManager()
                            ->getRepository('FirstBundle:Workset');
        
        $tourDAO = $this ->getDoctrine()
                            ->getManager()
                            ->getRepository('FirstBundle:Tour');
        
        $items = $worksetDAO->getAllItemsDataByWorksetId($id, $user_id);
        
        $tours = $tourDAO->getAllByNumber($id , $user_id);

//        dump($tours);die;
        
        $item_status = $worksetDAO->getItemStatus($id, $user_id);
        
//        dump("tours");
//        dump($tours);
//        dump("items");
//        dump($items);
//        dump("status");
//        dump($item_status);
//        die;

        
        return $this->render('FirstBundle:Workset:work.html.twig', array(
            'workset_id'=> $id,
            'data'      => $items,
            'tours'     => $tours,
            'status'    => $item_status,
        ));               
    }
    
    public function testAction($id){
        
        dump("zob");die;
        
//        $user_id = 1;
//        
//        $item_id = 4;
//        
//        $iteration = 1;
//        
//        $itemDAO = $this ->getDoctrine()
//                            ->getManager()
//                            ->getRepository('FirstBundle:Item');   
//        
//        $itemDAO->allFieldItemsDone($item_id, $user_id, $iteration);
        
        
        
    }

    public function createAction()
    {

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        //on créer un Workset et on lui donne des valeurs en dur pour l'instant
        $workset = new Workset();

        $form = $this->createForm(WorksetType::class, $workset);

        $request = Request::createFromGlobals();

        //si le formulaire a été soumis
        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);

            if($form->isValid()){

                //on récupère le EntityManager
                $em = $this->getDoctrine()->getManager();

                //on persiste le workset
                $em->persist($workset);

                //on valide les transactions
                $em->flush();

                //onrenvoie vers la liste
                $url = $this->generateUrl('list_workset');
                return $this->redirect($url);
            }
        }

        return $this->render('FirstBundle:Workset:create-edit.html.twig',array(
            'action'    => 'create',
            'form'      => $form->createView(),
        ));

    }



    public function editAction($id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);
        
        $em = $this->getDoctrine()->getManager();
        
        $worksetDAO = $em->getRepository('FirstBundle:Workset');
        
        $workset = $worksetDAO->find($id);
        
        $form = $this->createForm(WorksetType::class, $workset);
        
        $request = Request::createFromGlobals();
        
        if($request->getMethod() == 'POST'){
            
            $form->handleRequest($request);
            
            if($form->isValid()){
                
                //on récupère le EntityManager
                $em = $this->getDoctrine()->getManager();   
                
                //on persiste le workset
                $em->persist($workset);    
                
                //on valide les transactions
                $em->flush();  
                
                //onrenvoie vers la liste
                $url = $this->generateUrl('list_workset');
                return $this->redirect($url);                
            }
        }
        
        return $this->render('FirstBundle:Workset:create-edit.html.twig',array(
            'action'    => 'edit',
            'form'      => $form->createView(),
        ));
               
        
    }
    
    public function deleteAction($id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);
        
        if($id === null){
            throw new NotFoundResourceException();
        }        
        
        $request = Request::createFromGlobals();
        
        if($request->getMethod() == 'POST'){
            
            $id = $request->request->get('delete_id');
            
            $em = $this->getDoctrine()->getManager();

            $worksetDAO = $em->getRepository('FirstBundle:Workset');

            $workset = $worksetDAO->find($id);

            $em->remove($workset);

            $em->flush();

            $url = $this->generateUrl('list_workset');

            return $this->redirect($url);                 
        }
        
        return $this->render('FirstBundle:Workset:delete.html.twig', array(
            'id'    => $id,
        ));        
        
    }

    public function createNewTourAction(){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $request = Request::createFromGlobals();

        $worksetDAO = $this ->getDoctrine()
            ->getManager()
            ->getRepository('FirstBundle:Workset');

        $tourDAO = $this ->getDoctrine()
            ->getManager()
            ->getRepository('FirstBundle:Tour');

//        $iteration = $request->query->get('iteration');

        $user_id = 1;

        $workset_id = 1;

        $iteration = $tourDAO->getLastTour($workset_id, $user_id);

        $iteration++;

//        dump("user id : $user_id , $workset_id , iteration = $iteration");die;

        $tourDAO->createTour($iteration, $workset_id, $user_id);

        $url = $this->generateUrl('work_workset', array( //omde la route tel que défini dans routing.yml
            'id' =>  $workset_id,
        ));

        return $this->redirect($url);
    }

    public function deleteAllToursAction(){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);

        $request = Request::createFromGlobals();

        $tourDAO = $this ->getDoctrine()
            ->getManager()
            ->getRepository('FirstBundle:Tour');

//        $iteration = $request->query->get('iteration');

        $user_id = 1;

        $workset_id = 1;

        //si la confirmation a été envoyée on supprime tous les tours
        if($request->getMethod() == 'POST'){


            $tourDAO->deleteAllTours($workset_id, $user_id);

            $url = $this->generateUrl('work_workset', array( //omde la route tel que défini dans routing.yml
                'id' =>  $workset_id,
            ));

            return $this->redirect($url);
        }


        return $this->render('FirstBundle:Workset:delete-tours.html.twig', array());
    }
}
