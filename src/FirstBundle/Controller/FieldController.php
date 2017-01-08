<?php

namespace FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FirstBundle\Repository\FieldRepository;
use FirstBundle\Entity\Field;
use FirstBundle\Form\FieldType;

use \Symfony\Component\Translation\Exception\NotFoundResourceException;

use \Doctrine\ORM\ORMException;

class FieldController extends AbstractController
{
    public function indexAction()
    {

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);
        
        $em = $this->getDoctrine()->getManager();
        
        $fieldDAO = $em->getRepository('FirstBundle:Field');
        
//        $fields = $fieldDAO->findAll();
        $fields = $fieldDAO->fetchAllWithItems();
        
//       dump($fields);die;
        
        // replace this example code with whatever you need
        return $this->render('FirstBundle:Field:index.html.twig', array(
            'fields'  => $fields,
        ));        

    }
    
    
    public function viewAction($id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);
        
        $em = $this->getDoctrine()->getManager();
        
        $fieldDAO = $em->getRepository('FirstBundle:Field');
        
        $field = $fieldDAO->find($id);
        
        return $this->render('FirstBundle:Field:view.html.twig', array(
            'field'  => $field,
        ));        
                
    }    
    
    public function createAction()
    {

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);
        
        //on créer un Workset et on lui donne des valeurs en dur pour l'instant
        $field = new Field();

        $form = $this->createForm(FieldType::class, $field);
        
        $request = Request::createFromGlobals();


        //si le formulaire a été soumis
        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);

            if($form->isValid()){

                //on récupère le EntityManager
                $em = $this->getDoctrine()->getManager();

                //on persiste le field
                $em->persist($field);

//        dump("ok");die;

                try{

                    //on valide les transactions
                    $em->flush();
                }
                catch(\Exception $e){
                    dump($e); die;
                }
                catch(ORMException $e){
                    dump($e); die;
                }

                //onrenvoie vers la liste
                $url = $this->generateUrl('list_field');
                return $this->redirect($url);                
            }
        }
        
        return $this->render('FirstBundle:Field:create-edit.html.twig',array(
            'action'    => 'create',
            'form'      => $form->createView(),
        ));
 
    }
    
    public function editAction($id){

        $connected = $this->checkConnected();
        if(!($connected === true)) return $this->redirect($connected);
        
        $em = $this->getDoctrine()->getManager();
        
        $fieldDAO = $em->getRepository('FirstBundle:Field');
        
        $field = $fieldDAO->find($id);
        
        $form = $this->createForm(FieldType::class, $field);
        
        $request = Request::createFromGlobals();   
        
        //si le form a été soumis
        if($request->getMethod() == 'POST'){
            
            $form->handleRequest($request);
            
            //si il est valide
            if($form->isValid()){
                
                //on récupère le EntityManager
                $em = $this->getDoctrine()->getManager();   
                
                //on persiste le field
                $em->persist($field);    
                
                //on valide les transactions
                $em->flush();  
                
                //onrenvoie vers la liste
                $url = $this->generateUrl('list_field');
                return $this->redirect($url);                
            }
        }
        
        return $this->render('FirstBundle:Field:create-edit.html.twig',array(
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
        //si le form a été soumis
        if($request->getMethod() == 'POST'){
            
            $id = $request->request->get('delete_id');

            $em = $this->getDoctrine()->getManager();

            $fieldDAO = $em->getRepository('FirstBundle:Field');

            $field = $fieldDAO->find($id);

            $em->remove($field);

            $em->flush();

            $url = $this->generateUrl('list_field');

            return $this->redirect($url);                 
        }
        
        return $this->render('FirstBundle:Field:delete.html.twig', array(
            'id'    => $id,
        ));        
        
    }
}
