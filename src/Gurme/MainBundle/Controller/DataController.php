<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * Lists all Unit entities.
     *
     * @Route("/home", name="home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $name = "swswsws";
        return $this->render('GurmeMainBundle:Data:index.html.twig', array('name' => $name));
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
//        $calories = $request->request->get('calories','0');

        $inputCal = $request->request->get('calories','1000');
        $repository = $this->getDoctrine()->getRepository('GurmeMainBundle:Recipe');
        $query = $repository->createQueryBuilder('r')
            ->leftJoin('r.coverPhoto','p')
            ->select('r.id','r.name','r.calories','p.url')
            ->where('r.calories <= :inputCal')
            ->orderBy('r.calories', 'DESC')
            ->setParameter('inputCal',$inputCal)
            ->getQuery();

        $recipes = $query->getResult();
//        $em = $this->getDoctrine()->getManager();
//        $qb = $em->createQueryBuilder();
//        $qb->select('*')
//            ->from('Recipes', 'u')
//            ->where('u.id = ?1')
//            ->orderBy('u.name ASC');
//
//        $em->createQueryBuilder()
//            ->from('Project\Entities\Item', 'i')
//            ->select("*");
//        exit (var_dump($recipes));
        $result = 'DB loaded';

        return new JsonResponse(array('status' => $result, 'recipes' => $recipes));
    }

    /**
     * Lists all Unit entities.
     *
     * @Route("/recipe/{id}", name="recipeV2_get")
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

        $searchFor = '';
        // escape special characters in the query
        $pattern = preg_quote($searchFor, '/');
        $pattern = "/^.*$pattern.*\$/m";
        if(preg_match_all($pattern, $recipe->getDirections(), $matches)){
            $directions = $matches[0];
        }

//        exit (var_dump($photo));

//        $recipe = 'asdasdasd';
        return $this->render('GurmeMainBundle:Default:recipeV2.html.twig',
            array('recipe' => $recipe,
                'ingredients' => $ingredients,
                'directions' => $directions,
                'photoUrl' => $photo,
                'suggestions' => $suggestions));
    }

}
