<?php

namespace bancopruebas\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('bancopruebasBackendBundle:Default:index.html.twig', array('name' => $name));
    }
}
