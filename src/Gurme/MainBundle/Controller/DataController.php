<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gurme\MainBundle\Entity\Unit;
use Gurme\MainBundle\Form\UnitType;

/**
 * Unit controller.
 *
 * @Route("/data")
 */
class DataController extends Controller
{
    /**
     * Lists all Unit entities.
     *
     * @Route("/", name="data")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $name = "swswsws";
        return $this->render('GurmeMainBundle:Data:index.html.twig', array('name' => $name));
    }
}
