<?php

namespace FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {

        #générationd'url en fonction d'une route définie
        $url = $this->generateUrl('first_say', array( //omde la route tel que défini dans routing.yml
            'thing' =>  'redirection'
        ));
        
        #rendering d'une vue
        // return $this->render('FirstBundle:Default:index.html.twig');
        
        #redirection
        return $this->redirect($url);
    }
    
    public function sayAction($thing)
    {
        
        #utilisation del'objet request
        
            $request = Request::createFromGlobals();
        
        #recupération d'un param GET non défini dans les routes
            
            $plus = $request->query->get('plus');
        
        #condition d'une réception d'appel Ajax
            
            if($request->isXmlHttpRequest()){

                //traitement del'appel ajax

                #renvoyer du JSON

                $json_data= json_encode(array(
                    'key'   => 'value',
                ));

                $response = new Response($json_data);

                $response->headers->set('Content-Type','application/json');

                return $response; //on utilise pas de template généralement en ajax
            }

        #condition de soumission d'un formulaire
            
            if($request->getMethod() == 'POST'){

                #recupération d'un param POST
                $postparam = $request->request->get('postparam');   

                //traitement du formulaire...
            }
        
        #Access aux variables de session

            $session = $this->get('session');

            $var1 = $session->get('var1');

            $session->set('var1', $var1+1 );


        #Rendering d'une vue

            return $this->render('FirstBundle:Default:say.html.twig', array(
                'thing' =>  $thing, //leparam $thing provient de la définition de la route
                'plus'  => $plus,
            ));
    }
}
