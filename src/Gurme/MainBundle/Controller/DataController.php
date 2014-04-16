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

}
