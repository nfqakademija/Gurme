<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManager;
use NFQAkademija\BaseBundle\Entity\User;
use Gurme\MainBundle\Entity\Recipe;
use Gurme\MainBundle\Entity\RecipeRepository;
use Gurme\MainBundle\Entity\UserFavorite;

/**
 * Unit controller.
 *
 * @Route("/")
 */
class JsonController extends Controller
{
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

        /** @var RecipeRepository $repo */
        $repo = $em->getRepository('GurmeMainBundle:Recipe');

        if ($ingredients==array()) {
            $recipes = $repo->searchByCalories($calories);
        } else {
            $recipes = $repo->searchByCaloriesAndIngredients($calories,$ingredients);
        }

        $result = 'DB loaded';

        return new JsonResponse(array('status' => $result, 'recipes' => $recipes));
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

        /** @var RecipeRepository $repo */
        $repo = $this->getDoctrine()->getManager()->getRepository('GurmeMainBundle:Recipe');
        $result = $repo->searchIngredients($query);

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
     * Toggle recipe in favorites list.
     *
     * @Route("/recipe/{id}/toggleFavorite", name="data_recipe_add_to_favorites")
     * @Method("GET")
     */
    public function toggleFavoriteAction($id)
    {
        $result = 'Redirecting...';
        if (!is_null($this->getUser())) {
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GurmeMainBundle:UserFavorite')
                ->findOneBy(array('user' => $this->getUser()->getId(), 'recipe' => $id));
            if (!$entity) {
                $entity = new UserFavorite();
                if ($this->getUser() instanceof User) {
                    $entity->setUser($this->container->get('security.context')->getToken()->getUser());
                }
                /** @var $recipe Recipe */
                $recipe = $em->getRepository('GurmeMainBundle:Recipe')->find($id);
                if (!$recipe) {
                    throw $this->createNotFoundException('Unable to find Recipe entity.');
                }
                $entity->setRecipe($recipe);
                $entity->setAddedAt(new \DateTime('NOW'));
                $em->persist($entity);
                $em->flush();
                $result = 'Added to favorites';
            } else {
                $em->remove($entity);
                $em->flush();
                $result = 'Removed from favorites';
            }
        }
        return new JsonResponse(array('r' => $result));
    }
}
