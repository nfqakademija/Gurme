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
     * @Template()
     */
    public function testAction(Request $request)
    {
//        $calories = $request->request->get('calories','0');

        $query = array(141,50,21);
        $calories=500;
        /**
         * @var $em \Doctrine\ORM\EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        $where = '';
        foreach($query as $q) {
            $where .= ($where=='') ? " AND ( ri.ingredient = '$q'" : " OR ri.ingredient = '$q'";
        }
        $where .= ($where!='') ? ' )' : '';
//        'r.id','r.name','r.calories','p.url'
        $dql = "SELECT r.id,r.name,r.calories,p.url
                FROM GurmeMainBundle:RecipeIngredient ri
                JOIN ri.recipe r
                JOIN r.coverPhoto p
                WHERE r.calories < $calories".$where."
                ORDER BY r.calories";
        $recipes = $em->createQuery($dql)->getResult(); //i.name LIKE '%$query%' OR i.alias LIKE '%$query%'");
//        exit (var_dump($recipes));

//        foreach($result as $ingredient) {
//            $ingredients[] = array(
//                'id' => $ingredient['id'] ,
//                'text' => str_replace(array("\r\n", "\n", "\r"), '', $ingredient['name'])
//            );
//        }
//
//        $recipes = $query->getResult();


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
     * Lists all Unit entities.
     *
     * @Route("/list", name="list_result_json")
     * @Method("POST")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $calories = $request->request->get('calories','1000');
        $ingredients = $request->request->get('ingredients');
        if (!isset($ingredients)||is_null($ingredients)||$ingredients=='') $ingredients = array();

//        $repository = $this->getDoctrine()->getRepository('GurmeMainBundle:Recipe');
//        $query = $repository->createQueryBuilder('r')
//            ->leftJoin('r.coverPhoto','p')
//            ->select('r.id','r.name','r.calories','p.url')
//            ->where('r.calories <= :inputCal')
//            ->orderBy('r.calories', 'DESC')
//            ->setParameter('inputCal',$calories)
//            ->getQuery();
//
//        $recipes = $query->getResult();
//        exit (var_dump($ingredients));

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $where = '';
        foreach($ingredients as $q) {
            $where .= ($where=='') ? " AND ( ri.ingredient = '$q'" : " OR ri.ingredient = '$q'";
        }
        $where .= ($where!='') ? ' )' : '';

        $dql = "SELECT r.id,r.name,r.calories,p.url
                FROM ".(($where!='')?"GurmeMainBundle:RecipeIngredient ri JOIN ri.recipe":'GurmeMainBundle:Recipe')." r
                JOIN r.coverPhoto p
                WHERE r.calories < $calories".$where."
                ORDER BY r.calories";

        $recipes = $em->createQuery($dql)->getResult(); //i.name LIKE '%$query%' OR i.alias LIKE '%$query%'");

        $result = 'DB loaded';

        return new JsonResponse(array('status' => $result, 'recipes' => $recipes));
    }

    /**
     * Show recipe.
     *
     * @Route("/recipe/{id}", name="recipe_get")
     * @Method("GET")
     * @Template()
     */
    public function getRecipeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $recipe = $em->getRepository('GurmeMainBundle:Recipe')->find($id);
        $recipes = $em->getRepository('GurmeMainBundle:Recipe')->findAll();

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
                'suggestions' => $suggestions));
    }

}
