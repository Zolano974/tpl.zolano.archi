<?php

namespace FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FirstBundle\Entity\Workset;
use FirstBundle\Repository\WorksetRepository;
use FirstBundle\Form\WorksetType;

use \Symfony\Component\Translation\Exception\NotFoundResourceException;

class AbstractController extends Controller
{


    public function authAction(){



        $request = Request::createFromGlobals();

        $session = $this->get('session');

        if($request->getMethod() == 'POST'){

            $login = $request->request->get('login');
            $pwd = $request->request->get('pwd');

            if($login == "Sophie" && $pwd == "kafrine2sb0!s"){
//                dump($login, $pwd, "success");

                $session->set('connected', true);

                $url = $this->generateUrl('list_workset');

                return $this->redirect($url);
            }
            else{
                $session->set('connected', false);
            }

        }

        return $this->render('FirstBundle:Auth:auth.html.twig', array());
    }

    public function logoutAction(){
        $session = $this->get('session');
        $session->set('connected', false);
        return $this->render('FirstBundle:Auth:auth.html.twig', array());

    }

    protected function checkConnected(){

//        dump($_SERVER["SCRIPT_FILENAME"]);die;

        $session = $this->get('session');

        $connected = $session->get('connected');

        if($connected){
            return true;
        }
        else{
            $url = $this->generateUrl('auth');

            return $url;
        }
    }


}
