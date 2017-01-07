<?php

namespace Zolano\FluxinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('InfluxBundle:Default:index.html.twig');
    }
}
