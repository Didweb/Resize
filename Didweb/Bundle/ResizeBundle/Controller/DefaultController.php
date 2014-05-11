<?php

namespace Didweb\Bundle\ResizeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DidwebResizeBundle:Default:index.html.twig', array('name' => $name));
    }
}
