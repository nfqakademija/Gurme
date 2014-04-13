<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GurmeMainBundle:Default:index.html.twig', array('name' => $name));
    }

    public function addRecipeAction($name)
    {

        return $this->render('GurmeMainBundle:Default:index.html.twig', array('name' => $name));
    }
}
