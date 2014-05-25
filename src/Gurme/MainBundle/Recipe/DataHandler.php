<?php

namespace Gurme\MainBundle\Recipe;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManager;
use NFQAkademija\BaseBundle\Entity\User;
use Gurme\MainBundle\Entity\Categorie;
use Gurme\MainBundle\Entity\Ingredient;
use Gurme\MainBundle\Entity\Recipe;
use Gurme\MainBundle\Entity\RecipeRepository;
use Gurme\MainBundle\Entity\RecipeCategorie;
use Gurme\MainBundle\Entity\RecipeIngredient;
use Gurme\MainBundle\Entity\RecipePhoto;

class DataHandler
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param $em EntityManager
     */
    public function __construct($em) {

        $this->em = $em;
        $this->repository = $em->getRepository('GurmeMainBundle:Recipe');
    }

    /**
     * Add recipe to database.
     *
     * @param Recipe $entity
     * @param $ingredientCheck
     */
    public function addRecipe($entity,$ingredientCheck)
    {
        $this->em->persist($entity);
        $this->em->flush();
        $categories = array();
        foreach($entity->getCategories() as $selectedCat) {
            $recipeCat = new RecipeCategorie();
            /** @var Categorie $category */
            $category = $this->em->getRepository('GurmeMainBundle:Categorie')->find($selectedCat);
            $recipeCat->setCategory($category);
            $recipeCat->setRecipe($entity);
            $categories[]=$recipeCat;
            if (end($categories)) $this->em->persist(end($categories));
        }
        $this->em->flush();

        $ingredients = array();
        $i=0;
        foreach ($ingredientCheck['ingredients'] as $ing) {
            $listItem = new RecipeIngredient();
            $listItem->setRecipe($entity);
            $listItem->setAmount($ing['amount']);
            /** @var Ingredient $ingredient */
            $ingredient = $this->em->getRepository('GurmeMainBundle:Ingredient')
                ->findOneBy(array('name' => $ing['ingredient']));

            if (is_null($ingredient)) {
                $newIngredient = new Ingredient();
                $newIngredient->setName($ing['ingredient']);
                $this->em->persist($newIngredient);
                $this->em->flush();
                $listItem->setIngredient($newIngredient);
            } else $listItem->setIngredient($ingredient);

            if (isset($ing['notes'])) $listItem->setNote($ing['notes']);
            if (isset($ing['unitObj'])) $listItem->setUnit($ing['unitObj']);

            $ingredients[$i] = $listItem;
            $this->em->persist($ingredients[$i]);
            $i++;
        }

        $photo = new RecipePhoto();
        $photo->setRecipe($entity);
        $photo->setUrl($entity->getWebPath());
        $photo->setUploadedAt(new \DateTime('NOW'));
        $photo->setUser($entity->getUser());
        $entity->setCoverPhoto($photo);

        $this->em->persist($photo);
        $this->em->persist($entity);
        $this->em->flush();

        $this->recalculateCategories();
    }

    /**
     * Checks zero time.
     *
     * @param Recipe $entity The entity
     *
     * @return Recipe $entity The entity
     */
    public function checkReadyTime($entity)
    {
        $zeroTime = new \DateTime('1970-01-01 00:00:00');
        if ($entity->getReadyTime() == $zeroTime) {
            $interval1 = date_diff($zeroTime, $entity->getPrepTime(), true);
            $interval2 = date_diff($zeroTime, $entity->getCookTime(), true);
            $zeroTime->add($interval1)->add($interval2);
            $entity->setReadyTime($zeroTime);
        }
        return $entity;
    }

    /**
     * Recalculate recipe number in each category.
     */
    public function recalculateCategories()
    {
        $categories = $this->em->getRepository('GurmeMainBundle:Categorie')->findAll();
        $recipeCategories = $this->em->getRepository('GurmeMainBundle:RecipeCategorie')->findAll();
        $counter = array_fill(1,count($categories)+2,0);
        /** @var \Gurme\MainBundle\Entity\RecipeCategorie $category */
        foreach ($recipeCategories as $category) {
            $counter[$category->getCategory()->getId()]++;
        }
        /** @var \Gurme\MainBundle\Entity\Categorie $category */
        foreach ($categories as $category) {
            $category->setRecipes($counter[$category->getId()]);
            $this->em->persist($category);
        }
        $this->em->flush();
    }

    /**
     * Get full recipe description with ingredients, photo url etc.
     *
     * @param integer $id
     * @param User $user
     * @return array
     *
     * @throws NotFoundHttpException if the any entity is not found.
     */
    public function getFullDescription($id,$user)
    {
        /** @var Recipe $recipe */
        $recipe = $this->em->getRepository('GurmeMainBundle:Recipe')->find($id);
        if (!$recipe) throw new NotFoundHttpException('Unable to find Recipe entity.');
//                exit('fuck');
        $response = $recipe->getObjectVars();

        $response['favorite'] = $this->checkUserFavorite($id,$user);

        $photo = $this->em->getRepository('GurmeMainBundle:RecipePhoto')->find($recipe->getCoverPhoto()->getId());
        if (!$photo) throw new NotFoundHttpException('Unable to find Recipe photo.');

        $response['photo'] = $photo->getUrl();

        /** @var RecipeRepository $repo */
        $repo = $this->em->getRepository('GurmeMainBundle:Recipe');
        $response['ingredients'] = $repo->getIngredients($id);

        $response['directions'] = '';
        $searchFor = '';
        // escape special characters in the query
        $pattern = preg_quote($searchFor, '/');
        $pattern = "/^.*$pattern.*\$/m";
        if (preg_match_all($pattern, $recipe->getDirections(), $matches)) {
            $response['directions'] = $matches[0];
        }

        return $response;
    }

    /**
     * Get given $number random recipes.
     *
     * @param integer $number
     * @return array
     *
     * @throws NotFoundHttpException if the any entity is not found.
     */
    public function getRandomRecipes($number)
    {
        $recipes = $this->em->getRepository('GurmeMainBundle:Recipe')->findAll();
        $suggestions = array();

        for ($i = 0; $i < $number; $i++) {
            /** @var $recipe Recipe */
            $rand = rand(0,count($recipes)-1);
            $recipe = $recipes[$rand];
            if (!is_null($recipe)) {
                $suggestions[$i]['id']      =   $recipe->getId();
                $suggestions[$i]['name']    =   $recipe->getName();
                $suggestions[$i]['calories']=   $recipe->getCalories();
                $photo = $this->em->getRepository('GurmeMainBundle:RecipePhoto')->find($recipe->getCoverPhoto()->getId());
                if (!$photo) throw new NotFoundHttpException('Unable to find Recipe photo entity.');
                $suggestions[$i]['url']=$photo->getUrl();
                $recipes[$rand] = null;
            } else $i--;
        }

        return $suggestions;
    }

    /**
     * Check if recipe is user favorite.
     *
     * @param integer $id
     * @param User $user
     * @return boolean
     */
    public function checkUserFavorite($id,$user)
    {
        $response = false;
        if (!is_null($user)
            && $this->em->getRepository('GurmeMainBundle:UserFavorite')
                ->findOneBy(array('user' => $user->getId(), 'recipe' => $id))) {
            $response = true ;
        }

        return $response;
    }

} 