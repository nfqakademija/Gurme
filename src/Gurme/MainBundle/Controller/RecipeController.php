<?php

namespace Gurme\MainBundle\Controller;

use Gurme\MainBundle\Entity\Ingredient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gurme\MainBundle\Entity\Recipe;
use Gurme\MainBundle\Form\RecipeType;
use Gurme\MainBundle\Entity\RecipePhoto;
use Gurme\MainBundle\Entity\RecipeIngredient;
use Gurme\MainBundle\Entity\RecipeCategorie;


/**
 * Recipe controller.
 *
 * @Route("/data/recipe")
 */
class RecipeController extends Controller
{

    /**
     * Lists all Recipe entities.
     *
     * @Route("/", name="data_recipe")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GurmeMainBundle:Recipe')->findBy(array(), array('id'=>'desc'));

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Recipe entity.
     *
     * @Route("/", name="data_recipe_create")
     * @Method("POST")
     * @Template("GurmeMainBundle:Recipe:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Recipe();

        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            $entity->setUser($this->container->get('security.context')->getToken()->getUser());
            $entity->setCreatedAt(new \DateTime('NOW'));
//            exit(var_dump($entity));
        }
        else { return $this->redirect($this->generateUrl('fos_user_security_login')); }

        $form = $this->createCreateForm($entity);

        $form->handleRequest($request);
//        exit(var_dump($entity->getCategories()));
        $zeroTime = new \DateTime('1970-01-01 00:00:00');
        if ($entity->getReadyTime() == $zeroTime) {
            $interval1 = date_diff($zeroTime, $entity->getPrepTime());
            $interval2 = date_diff($zeroTime, $entity->getCookTime());
            $zeroTime->add($interval1)->add($interval2);
            $entity->setReadyTime($zeroTime);
        }

        $ingredientCheck = $this->checkIngredientInput($entity->getIngredients());
//        exit(var_dump($ingredientCheck));

        if (($form->isValid())&&($ingredientCheck['status'])) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $categories = array();
            foreach($entity->getCategories() as $selectedCat) {
                $recipeCat = new RecipeCategorie();
                $recipeCat->setCategory($em->getRepository('GurmeMainBundle:Categorie')->find($selectedCat));
                $recipeCat->setRecipe($entity);
                $categories[]=$recipeCat;
                $em->persist(end($categories));
            }
            $em->flush();

            $ingredients = array();
            $i=0;
            foreach ($ingredientCheck['ingredients'] as $ing) {
                $listItem = new RecipeIngredient();
                $listItem->setRecipe($entity);
                $listItem->setAmount($ing['amount']);
                $ingredient = $em->getRepository('GurmeMainBundle:Ingredient')
                    ->findOneBy(array('name' => $ing['ingredient']));

                if (is_null($ingredient)) {
                    $newIngredient = new Ingredient();
                    $newIngredient->setName($ing['ingredient']);
                    $em->persist($newIngredient);
                    $em->flush();
                    $listItem->setIngredient($newIngredient);
                } else $listItem->setIngredient($ingredient);

                if (isset($ing['notes'])) $listItem->setNote($ing['notes']);
                if (isset($ing['unitObj'])) $listItem->setUnit($ing['unitObj']);

                $ingredients[$i] = $listItem;
                $em->persist($ingredients[$i]);
                $i++;
            }
//            $em->flush();
//            exit(var_dump($ingredients));

            $photo = new RecipePhoto();
            $photo->setRecipe($entity);
            $photo->setUrl($entity->getWebPath());
            $photo->setUser($entity->getUser());
            $photo->setUploadedAt(new \DateTime('NOW'));

            $entity->setCoverPhoto($photo);

            $em->persist($photo);
            $em->persist($entity);
            $em->flush();

//            exit(var_dump($entity));
            return $this->redirect($this->generateUrl('data_recipe_show', array('id' => $entity->getId())));
        } else if (!$ingredientCheck['status']) {
            $form->get('ingredients')
                ->addError(new \Symfony\Component\Form\FormError('Bad ingredient input syntax.'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Recipe entity.
    *
    * @param Recipe $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Recipe $entity)
    {
        $form = $this->createForm(new RecipeType(), $entity, array(
            'action' => $this->generateUrl('data_recipe_create'),
            'method' => 'POST',
        ));

        $form->remove('approved');
        $form->remove('createdAt');
        $form->remove('rating');
        $form->remove('user');
        $form->remove('coverPhoto');

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('GurmeMainBundle:Categorie')->findAll();
        $categories = array();
        foreach($entities as $entity) {
            $categories[$entity->getId()]=$entity->getName();
        }

        $form->add('categories', 'choice', array(
            'choices'   => $categories,
            'multiple'  => true,
            'expanded'  => true,
        ));

        $form->add('file', 'file');
        $form->add('submit', 'submit', array(
            'label' => 'Add recipe',
            'attr' => array(
                'class' => 'btn btn-primary btn-lg',
                'formnovalidate' => 'formnovalida'
        )
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Recipe entity.
     *
     * @Route("/new", name="data_recipe_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Recipe();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Mano nauja funkcija.
     *
     * @Route("/ingredientCheck", name="data_recipe_ingredient_check")
     * @Method("POST")
     */
    public function ingredientCheckAction(Request $request)
    {

        $contents = $request->request->get('ingredients','makukaracha');


//        $contents = "1 (14.5 ounce) can whole berry cranberry sauce
// 1 cup apple jelly
// 1 tablespoon Dijon mustard
// 4 cubes chicken bouillon, crushed
// 1a/2 teaspoon prepared horseradish
// 2 teaspoons garlic powder
//2 tablespoons chopped fresh thyme
// 1 (4 pound) boneless pork loin roast
// 1 teaspoon salt
// 1 teaspoon ground black pepper";


//        exit (var_dump($ing));
//        return new JsonResponse(array('status' => $result,
//            'ingredients' => $ing));

        $response = $this->checkIngredientInput($contents);
        $i=0;
        foreach ($response['ingredients'] as $ii) {
            $response['ingredients'][$i]['unitObj'] = null;
            $i++;
        }
        return new JsonResponse($response);
    }

    /**
     * Finds and displays a Recipe entity.
     *
     * @Route("/{id}", name="data_recipe_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GurmeMainBundle:Recipe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Recipe entity.
     *
     * @Route("/{id}/edit", name="data_recipe_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GurmeMainBundle:Recipe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Recipe entity.
    *
    * @param Recipe $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Recipe $entity)
    {
        $form = $this->createForm(new RecipeType(), $entity, array(
            'action' => $this->generateUrl('data_recipe_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Recipe entity.
     *
     * @Route("/{id}", name="data_recipe_update")
     * @Method("PUT")
     * @Template("GurmeMainBundle:Recipe:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GurmeMainBundle:Recipe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('data_recipe_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Recipe entity.
     *
     * @Route("/{id}", name="data_recipe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GurmeMainBundle:Recipe')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Recipe entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('data_recipe'));
    }

    /**
     * Creates a form to delete a Recipe entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('data_recipe_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    private function checkIngredientInput($contents)
    {
        $em = $this->getDoctrine()->getManager();
        $units = $em->getRepository('GurmeMainBundle:Unit')->findAll();

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
                if(preg_match_all($pattern, $line, $matches)){
                    $ing[$i]['notes'] = $matches[0][0];
                    $line = preg_replace($pattern, '', $line);
                }

                // Find any unit name in the line
                $line = ' ' . $line;
                $unitMatched = false;
                foreach($units as $unit){
                    // check singular
                    if (null !== $unit->getMain()) {
                        $pattern = '/\s('.$unit->getMain().'s?)\s/';
                        if(preg_match($pattern, $line, $matches)){
                            //exit($matches[0]);
                            $ing[$i]['unit'] = $matches[1];
                            $ing[$i]['unitObj'] = $unit;
                            $unitMatched = true;
                        }
                    }
                    // check plural
                    if ((null !== $unit->getPlural())&&($unit->getPlural()!=$unit->getMain())) {
                        $pattern = '/\s('.$unit->getPlural().'s?)\s/';
                        if(preg_match($pattern, $line, $matches)){
                            //exit($matches[0]);
                            $ing[$i]['unit'] = $matches[1];
                            $ing[$i]['unitObj'] = $unit;
                            $unitMatched = true;
                        }
                    }
                }

                // Get amount and ingredient data from line
                if ($unitMatched) {
                    preg_match('/^\s*([. 0-9\/]+) '.$ing[$i]['unit'].'/', $line, $matches);
                    if (isset($matches[1])) {
                        $ing[$i]['amount'] = $matches[1];
                    }
                    $pattern = '/^.*'.$ing[$i]['unit'].'\s*((.*),\s*(.*)|(.*))/';
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
                    if(preg_match('/^\s*([ 0-9\/]*)((.*),\s(.*)|(.*))/', $line, $matches)) {
                        if (($matches[3]=='')&&($matches[3]=='')) {
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
                            $ing[$i]['unitObj'] = $em->getRepository('GurmeMainBundle:Unit')
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

}
