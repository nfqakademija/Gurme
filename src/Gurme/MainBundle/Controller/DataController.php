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

        $ingredients = array(141,50,21);
        $calories=500;
        /**
         * @var $em \Doctrine\ORM\EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        if ($ingredients==array()) {
            $dql = "SELECT r.id,r.name,r.calories,p.url
                FROM 'GurmeMainBundle:Recipe' r
                JOIN r.coverPhoto p
                WHERE r.calories < $calories
                ORDER BY r.calories";
            $recipes = $em->createQuery($dql)->getResult();
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

//    /**
//     * Lists all Unit entities.
//     *
//     * @Route("/list", name="list_result")
//     * @Method("GET")
//     * @Template()
//     */
//    public function listAction(Request $request)
//    {
////        $calories = $request->request->get('calories','0');
//        $inputCal = 250;
//        $repository = $this->getDoctrine()->getRepository('GurmeMainBundle:Recipe');
//        $query = $repository->createQueryBuilder('r')
//            ->leftJoin('r.coverPhoto','p')
//            ->select('r.id','r.name','r.calories','p.url')
//            ->where('r.calories <= :inputCal')
//            ->orderBy('r.calories', 'DESC')
//            ->setParameter('inputCal',$inputCal)
//            ->getQuery();
//
//        $recipes = $query->getResult();
////        $em = $this->getDoctrine()->getManager();
////        $qb = $em->createQueryBuilder();
////        $qb->select('*')
////            ->from('Recipes', 'u')
////            ->where('u.id = ?1')
////            ->orderBy('u.name ASC');
////
////        $em->createQueryBuilder()
////            ->from('Project\Entities\Item', 'i')
////            ->select("*");
//exit (var_dump($recipes));
//
//        return $this->render('GurmeMainBundle:Data:list.html.twig', array('recipe' => $recipes));
//    }

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
            $dql = "SELECT r.id,r.name,r.calories,p.url
                FROM 'GurmeMainBundle:Recipe' r
                JOIN r.coverPhoto p
                WHERE r.calories < $calories
                ORDER BY r.calories";
            $recipes = $em->createQuery($dql)->getResult();
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
        $favorite = false;
        $em = $this->getDoctrine()->getManager();

        $recipe = $em->getRepository('GurmeMainBundle:Recipe')->find($id);
        $recipes = $em->getRepository('GurmeMainBundle:Recipe')->findAll();
        if (!is_null($this->getUser())) {
            $favorite = $em->getRepository('GurmeMainBundle:UserFavorite')->findOneBy(array('user' => $this->getUser()->getId(), 'recipe' => $id));
            $favorite = ($favorite) ? true : false ;
        }

        $suggestions = array();
        $i = 0;
        /** @var $s \Gurme\MainBundle\Entity\Recipe */
        foreach ($recipes as $s) {
            $suggestions[$i]['id']=$s->getId();
            $suggestions[$i]['name']=$s->getName();
            $suggestions[$i]['calories']=$s->getCalories();
            $photo = $em->getRepository('GurmeMainBundle:RecipePhoto')->find($s->getCoverPhoto()->getId());
            if (!$photo) { throw $this->createNotFoundException('Unable to find Recipe photo.'); }
            $suggestions[$i]['url']=$photo->getUrl();

            $i++;
            if ($i>3) break;
        }
//        exit (var_dump($suggestions));


        if (!$recipe) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }

        $photo = $em->getRepository('GurmeMainBundle:RecipePhoto')->find($recipe->getCoverPhoto()->getId());

        if (!$photo) {
            throw $this->createNotFoundException('Unable to find Recipe photo.');
        }

        $photo = $photo->getUrl();

        /** @var $em \Doctrine\ORM\EntityManager */
        $query = $em->getRepository('GurmeMainBundle:RecipeIngredient')
            ->createQueryBuilder('il')
            ->leftJoin('il.recipe','r')
            ->leftJoin('il.ingredient','i')
            ->leftJoin('il.unit','u')
            ->select('r.id rid','il.amount','u.main','i.name','il.note')
            ->where('r.id = :recipeId')
            ->setParameter('recipeId',$id)
            ->getQuery();
        $ingredients = $query->getResult();
        $x=0;
        foreach ($ingredients as $i) {
            if (($i['amount'] != '')&&($i['amount'] > 1)) {
                $ingredients[$x]['main'] .= 's';
            }
            $x++;
        }

        $directions = '';
        $searchFor = '';
        // escape special characters in the query
        $pattern = preg_quote($searchFor, '/');
        $pattern = "/^.*$pattern.*\$/m";
        if(preg_match_all($pattern, $recipe->getDirections(), $matches)){
            $directions = $matches[0];
        }

//        exit (var_dump($photo));

//        $recipe = 'asdasdasd';
        return $this->render('GurmeMainBundle:FrontEnd:recipe.html.twig',
            array('recipe' => $recipe,
                'ingredients' => $ingredients,
                'directions' => $directions,
                'photoUrl' => $photo,
                'favorite' => $favorite,
                'suggestions' => $suggestions));
    }

}
