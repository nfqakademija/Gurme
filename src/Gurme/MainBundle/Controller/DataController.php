<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManager;
use Gurme\MainBundle\Entity\Unit;
use Gurme\MainBundle\Form\UnitType;
use Gurme\MainBundle\Entity\Ingredient;
use Gurme\MainBundle\Entity\Recipe;
use Gurme\MainBundle\Entity\RecipeRepository;
use Gurme\MainBundle\Entity\RecipePhoto;
use Gurme\MainBundle\Entity\RecipeIngredient;
use Gurme\MainBundle\Entity\RecipeCategorie;

/**
 * Unit controller.
 *
 * @Route("/")
 */
class DataController extends Controller
{
    /**
     * Test function.
     *
     * @Route("/test", name="test")
     * @Method("GET")
     */
    public function testAction()
    {
//        $calories = $request->request->get('calories','0');
        $recipes = null;

//        $ingredients = array(141,50,21);
//        $calories=500;
//        /**
//         * @var $em \Doctrine\ORM\EntityManager
//         */
//        $em = $this->getDoctrine()->getManager();
//
//        if ($ingredients==array()) {
//            $dql = "SELECT r.id,r.name,r.calories,p.url
//                FROM 'GurmeMainBundle:Recipe' r
//                JOIN r.coverPhoto p
//                WHERE r.calories < $calories
//                ORDER BY r.calories";
//            $recipes = $em->createQuery($dql)->getResult();
//        } else {
//            $dql = "SELECT DISTINCT r.id,r.name,r.calories,p.url
//                FROM GurmeMainBundle:RecipeIngredient ri JOIN ri.recipe r
//                JOIN r.coverPhoto p
//                WHERE r.calories < $calories AND ri.ingredient IN (:ingredients)
//                ORDER BY r.calories";
//            $recipes = $em->createQuery($dql)->setParameter('ingredients',$ingredients)->getResult();
//        }

        $result = 'DB loaded';

        return new JsonResponse(array('status' => $result, 'recipes' => $recipes));
    }

    /**
     * AJAX query for recipes by id and ingredients.
     *
     * @Route("/list", name="list_result_json")
     * @Method("POST")
     */
    public function listAction(Request $request)
    {
        $calories = $request->request->get('calories','1000');
        $ingredients = $request->request->get('ingredients');
        if (!isset($ingredients)||is_null($ingredients)||$ingredients=='') $ingredients = array();

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        if ($ingredients==array()) {

            /** @var RecipeRepository $repo */
            $repo = $em->getRepository('GurmeMainBundle:Recipe');
            $recipes = $repo->searchByCalories($calories);

        } else {
            $dql = "SELECT DISTINCT r.id,r.name,r.calories,p.url
                FROM GurmeMainBundle:RecipeIngredient ri JOIN ri.recipe r
                JOIN r.coverPhoto p
                WHERE r.calories < $calories AND ri.ingredient IN (:ingredients)
                ORDER BY r.calories";
            $recipes = $em->createQuery($dql)->setParameter('ingredients',$ingredients)->getResult();
        }

        $result = 'DB loaded';

        return new JsonResponse(array('status' => $result, 'recipes' => $recipes));
    }

    /**
     * Show recipe by id.
     *
     * @Route("/recipe/{id}", name="recipe_get")
     * @Method("GET")
     * @Template()
     */
    public function getRecipeAction($id)
    {
        $recipe = $this->get('gurme_main.recipe')->getFullDescription($id,$this->getUser());
        $suggestions = $this->get('gurme_main.recipe')->getRandomRecipes(2);

        return $this->render('GurmeMainBundle:FrontEnd:recipe.html.twig',
            array('recipe' => $recipe, 'suggestions' => $suggestions));
    }

}
