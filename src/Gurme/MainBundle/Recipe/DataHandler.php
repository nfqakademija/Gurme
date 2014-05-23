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
use Gurme\MainBundle\Entity\Unit;

class DataHandler {

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

    function test() {
        exit('fuck you');
    }

    /**
     * Function to validate ingredient input for database
     *
     * @param $contents
     * @return array
     */
    public function checkIngredientInput($contents)
    {
        $units = $this->em->getRepository('GurmeMainBundle:Unit')->findAll();
        $ing = array();
        $searchFor = '';

        // escape special characters in the query
        $pattern = preg_quote($searchFor, '/');
        $pattern = "/^.*$pattern.*\$/m";

        if(preg_match_all($pattern, $contents, $matches)){
            //exit(var_dump(trim($matches[0])));
            $lines = $matches[0];
            $i=0;
            foreach($lines as $line){

                if (trim($line) == '') { continue; }

                $ing[$i]['valid'] = 'remove';

                // Find any notes within brackets. e.g. (4 ponds)
                $pattern = '/\((.+)\)/';
                $matches = [];
                if(preg_match_all($pattern, $line, $matches)){
                    $ing[$i]['notes'] = $matches[0][0];
                    $line = preg_replace($pattern, '', $line);
                }

                // Find any unit name in the line
                $line = ' ' . $line;
                $unitMatched = false;
                /** @var Unit $unit */
                foreach($units as $unit){
                    if (null !== $unit->getMain()) {
                        $pattern = (null !== $unit->getPlural()) ? '('.$unit->getMain().'|'.$unit->getPlural().')' : $unit->getMain() ;
                        $pattern = '/\s('.$pattern.'s?)\s/';
                        $matches = [];
                        if (preg_match($pattern, $line, $matches)) {
                            //exit($matches[0]);
                            $ing[$i]['unit'] = $matches[1];
                            $ing[$i]['unitObj'] = $unit;
                            $unitMatched = true;
                        }
                    }
                }

                // Get amount and ingredient data from line
                if ($unitMatched) {
                    $matches = [];
                    preg_match('/^\s*([. 0-9\/]+) '.$ing[$i]['unit'].'/', $line, $matches);
                    if (isset($matches[1])) {
                        $ing[$i]['amount'] = $matches[1];
                    }
                    $pattern = '/^.*'.$ing[$i]['unit'].'\s*((.*),\s*(.*)|(.*))/';
                    $matches = [];
                    preg_match($pattern, $line, $matches);
                    if (isset($matches[3]) && $matches[3]!='')
                    {
                        $ing[$i]['ingredient'] = $matches[2];
                        $ing[$i]['notes'] = (isset($ing[$i]['notes']) ?
                            $matches[3] . ' ' . $ing[$i]['notes'] : $matches[3]);
                    }
                    else
                    {
                        $ing[$i]['ingredient'] = $matches[1];
                    }
                } else {
                    $matches = [];
                    if(preg_match('/^\s*([ 0-9\/]*)((.*),\s(.*)|(.*))/', $line, $matches)) {
                        if (isset($matches[3])&&($matches[3]=='')&&($matches[3]=='')) {
                            $ing[$i]['amount'] = $matches[1];
                            $ing[$i]['ingredient'] = $matches[2];
                        } else {
                            $ing[$i]['amount'] = $matches[1];
                            $ing[$i]['ingredient'] = $matches[3];
                            $ing[$i]['notes'] = (isset($ing[$i]['notes']) ?
                                $matches[4] . ' ' . $ing[$i]['notes'] : $matches[4]);
                        }
                        if(trim($ing[$i]['amount']) != '') {
                            $ing[$i]['unit'] = 'units';
                            $ing[$i]['unitObj'] = $this->em->getRepository('GurmeMainBundle:Unit')
                                ->findOneBy(array('main' => 'unit'));
                        } else $ing[$i]['valid'] = 'ok';
                    }
                }

                if (!is_null($ing[$i]['ingredient'])) trim($ing[$i]['ingredient']);

                // Convert amount to metric system, remove slashes
                if ((isset($ing[$i]['amount'])) && (trim($ing[$i]['amount']) != '')) {
                    $ing[$i]['amount'] = trim($ing[$i]['amount']);
                    $pattern = '/((([0-9]*)\s+([0-9]*)\/([0-9]*))|([0-9]*\.[0-9]*)|(([0-9]*)\/([0-9]*))|([0-9]*))/';
                    if (preg_match($pattern,$ing[$i]['amount'],$matches)) {
                        $ing[$i]['valid'] = 'ok';
                        if (isset($matches[10]) && ($matches[10]!='')) {
                            // sveikas skaičius pvz 2,8,40 (rodo kaip STRING)
                        } else if (isset($matches[9]) && ($matches[9]!='')){
                            $ing[$i]['amount'] = $matches[8] / $matches[9] ; // pvz "1/3" = 0.3(3)
                        } else if (isset($matches[6]) && ($matches[6]!='')){
                            // skaičius su kableliu pvz 2.5 (rodo kaip STRING)
                        } else if (isset($matches[5]) && ($matches[5]!='')){
                            $ing[$i]['amount'] = $matches[3] + $matches[4] / $matches[5] ; // "1 1/2" = 1.5
                        } else $ing[$i]['valid'] = 'remove';
                    }
                }
                $i++;
            }
            $result = true;
            foreach ($ing as $ingredient){
                if ($ingredient['valid']=='remove') {
                    $result = false;
                }
            }
        }
        else $result = false;

        return array('status' => $result, 'ingredients' => $ing);
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
            $this->em->persist(end($categories));
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
        if ($entity->getUser() !== null) {
            $photo->setUser($entity->getUser());
        }
        $entity->setCoverPhoto($photo);

        $this->em->persist($photo);
        $this->em->persist($entity);
        $this->em->flush();

        $this->recalculateCategories();
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
        $response = $recipe->getObjectVars();

        $response['favorite'] = false;
        if (!is_null($user)) {
            $response['favorite'] = $this->em->getRepository('GurmeMainBundle:UserFavorite')->findOneBy(array('user' => $user->getId(), 'recipe' => $id));
            $response['favorite'] = ($response['favorite']) ? true : false ;
        }

        $photo = $this->em->getRepository('GurmeMainBundle:RecipePhoto')->find($recipe->getCoverPhoto()->getId());
        if (!$photo) throw new NotFoundHttpException('Unable to find Recipe photo.');

        $response['photo'] = $photo->getUrl();

        /** @var RecipeRepository $repo */
        $repo = $this->em->getRepository('GurmeMainBundle:Recipe');
        $ingredients = $repo->getIngredients($id);

        $x=0;
        foreach ($ingredients as $i) {
            if (($i['amount'] != '')&&($i['amount'] > 1)) {
                $ingredients[$x]['main'] .= 's';
            }
            $x++;
        }

        $response['ingredients'] = $ingredients;
        $response['directions'] = '';
        $searchFor = '';
        // escape special characters in the query
        $pattern = preg_quote($searchFor, '/');
        $pattern = "/^.*$pattern.*\$/m";
        if(preg_match_all($pattern, $recipe->getDirections(), $matches)){
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
            /** @var $recipe \Gurme\MainBundle\Entity\Recipe */
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

} 