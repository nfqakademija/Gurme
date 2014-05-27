<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gurme\MainBundle\Entity\RecipeRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Front end controller.
 *
 * @Route("/")
 */
class FrontEndController extends Controller
{
    /**
     * Index page action.
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
     * Show recipe by id.
     *
     * @Route("/recipe/{id}", name="recipe")
     * @Method("GET")
     * @Template()
     */
    public function recipeAction($id)
    {
        /** @var \Gurme\MainBundle\Recipe\DataHandler $service */
        $service = $this->get('gurme_main.recipe');
        $recipe = $service->getFullDescription($id,$this->container->get('security.context')->getToken()->getUser());
        $suggestions = $service->getRandomRecipes(3);

        return array('recipe' => $recipe, 'suggestions' => $suggestions);
    }

    /**
     * Load categories DIV.
     *
     * @Template()
     */
    public function categoriesDivAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('GurmeMainBundle:Categorie')->findAll();

        return array('categories' => $categories);
    }

    /**
     * Lists all recipes in particular category.
     *
     * @Route("/category/{category}", name="category_recipes")
     * @Method("GET")
     * @Template()
     */
    public function categoryRecipesAction($category)
    {
        if (preg_match("/^\d+/",$category, $matches)) {
            $categoryId = $matches[0];

            /** @var RecipeRepository $repo */
            $repo = $this->getDoctrine()->getManager()->getRepository('GurmeMainBundle:Recipe');
            $recipes = $repo->searchByCategory($categoryId);

            $normalizer = new GetSetMethodNormalizer();
            $encoder = new JsonEncoder();
            $serializer = new Serializer(array($normalizer), array($encoder));
            $recipes = $serializer->serialize($recipes, 'json');
            $recipes = str_replace("'", "&#39;", $recipes);

            return array('recipesJson' => $recipes);

        } else return $this->redirect($this->generateUrl('index'));
    }

}
