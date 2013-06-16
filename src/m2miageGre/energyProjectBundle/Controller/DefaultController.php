<?php

namespace m2miageGre\energyProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('m2miageGreenergyProjectBundle:Default:index.html.twig', array('name' => $name));
    }
}
