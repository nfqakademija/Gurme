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

use NFQAkademija\BaseBundle\Entity\User;
use Gurme\MainBundle\Entity\Ingredient;
use Gurme\MainBundle\Entity\UserFavorite;
use Gurme\MainBundle\Entity\Recipe;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

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
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('GurmeMainBundle:Categorie')->findAll();

        $qb = $em->getRepository('GurmeMainBundle:Tip')->createQueryBuilder('tip');
        $qb->select('COUNT(tip)');
        $count = $qb->getQuery()->getSingleScalarResult();
        $tip = $em->getRepository('GurmeMainBundle:Tip')->find(rand(1,$count));

        $name = "Gurme";
        return array('name' => $name, 'categories' => $categories, 'tip' => $tip);
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
            $id = $matches[0];
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT r.id,r.name,r.calories,p.url
                FROM 'GurmeMainBundle:RecipeCategorie' rc
                JOIN rc.recipe r
                JOIN r.coverPhoto p
                WHERE rc.category = $id
                ORDER BY r.calories";
            $recipes = $em->createQuery($dql)->getResult();

            $normalizer = new GetSetMethodNormalizer();
            $encoder = new JsonEncoder();

            $serializer = new Serializer(array($normalizer), array($encoder));
            $recipes = $serializer->serialize($recipes, 'json'); // Output: {"name":"foo"}
            $recipes = str_replace("'", "&#39;", $recipes);
//            exit(var_dump($recipes));

//            $name = "Gurme";
            return array('recipesJson' => $recipes);
//            return new JsonResponse($recipes));

        } else return $this->redirect($this->generateUrl('index'));
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
        /** @var $em \Doctrine\ORM\EntityManager */
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
