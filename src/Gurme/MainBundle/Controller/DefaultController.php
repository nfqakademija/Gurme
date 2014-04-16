<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\BrowserKit\Request;

/**
 * Unit controller.
 *
 * @Route("/")
 */

class DefaultController extends Controller
{
    /**
     * Lists all Unit entities.
     *
     * @Route("/", name="index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('GurmeMainBundle:Default:index.html.twig', array());
    }

    /**
     * Lists all Unit entities.
     *
     * @Route("/calories", name="calories")
     * @Method("GET")
     * @Template()
     */
    public function caloriesAction()
    {
        return $this->render('GurmeMainBundle:Default:calories.html.twig', array());
    }

    /**
     * Lists all Unit entities.
     *
     * @Route("/recipes", name="recipes")
     * @Method("POST")
     * @Template()
     */
    public function recipesAction()
    {
        if(isset($_POST['calories']))
            $calories = $_POST['calories'];
        $recipes = $product = $this->getDoctrine()
            ->getRepository('GurmeMainBundle:Recipe')->retrieveRecipesWithPhotos();
        var_dump($recipes);
        die;
        return $this->render('GurmeMainBundle:Default:recipes.html.twig', array('recipes'=>$recipes));
    }

    /**
     * Lists all Unit entities.
     *
     * @Route("/recipe", name="recipe")
     * @Method("GET")
     * @Template()
     */
    public function singleRecipeAction()
    {
        return $this->render('GurmeMainBundle:Default:recipe.html.twig', array());
    }


    public function addRecipeAction($name)
    {

        return $this->render('GurmeMainBundle:Default:index.html.twig', array('name' => $name));
    }

}
