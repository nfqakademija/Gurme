<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;

use Gurme\MainBundle\Entity\Ingredient;

/**
 * Unit controller.
 *
 * @Route("/")
 */

class FrontEndController extends Controller
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
        $name = "Gurme";
        return array('name' => $name);
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
    public function recipesAction(Request $request)
    {
//        exit(var_dump($request));
        //if ($request->request->get('calories')) {


        if (isset($_POST['calories'])) {
            $calories = $_POST['calories'];
            $recipes = $product = $this->getDoctrine()
                ->getRepository('GurmeMainBundle:Recipe')->retrieveRecipesWithPhotos($calories);
        } else {
            $recipes = $product = $this->getDoctrine()
                ->getRepository('GurmeMainBundle:Recipe')->retrieveRecipesWithPhotos();
        }
        return $this->render('GurmeMainBundle:Default:recipes.html.twig', array('recipes' => $recipes));
    }

    /**
     * Gets ingredient list for Chosen Ajax Call.
     *
     * @Route("/queryIngredient/{query}", name="query_ingredient")
     */
    public function queryIngredientAction($query,Request $request)
    {
        $ingredients = array();

        $query = ($query=='ajaxChosen') ? $request->request->get('data')['q'] : $query;
        /**
         * @var $em \Doctrine\ORM\EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("SELECT i.id,i.name FROM Gurme\MainBundle\Entity\Ingredient i WHERE i.name LIKE '%$query%' OR i.alias LIKE '%$query%'");
        $result = $q->getResult();

        foreach($result as $ingredient) {
            $ingredients[] = array(
                'id' => $ingredient['id'] ,
                'text' => str_replace(array("\r\n", "\n", "\r"), '', $ingredient['name'])
            );
        }

        return new JsonResponse(
            array(
                'q' => $query,
                'results' => $ingredients
            )
        );
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
        if(isset($_GET['id']))
        $recipe = $product = $this->getDoctrine()
            ->getRepository('GurmeMainBundle:Recipe')->find($_GET['id']);
        else return false;

        //var_dump($recipe->getIngredient());
      //  die;
        return $this->render('GurmeMainBundle:Default:recipe.html.twig', array('recipe' => $recipe));
    }


    public function addRecipeAction($name)
    {

        return $this->render('GurmeMainBundle:Default:index.html.twig', array('name' => $name));
    }

}
