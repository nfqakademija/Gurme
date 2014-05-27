<?php

namespace Gurme\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Gurme\MainBundle\Entity\Recipe;
use Gurme\MainBundle\Recipe\DataHandler;
use Gurme\MainBundle\Entity\Categorie;
use Gurme\MainBundle\Form\RecipeType;

/**
 * Recipe controller.
 *
 * @Route("/editor/recipe")
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

        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $form = $this->createCreateForm($entity);

        $form->handleRequest($request);

        /** @var DataHandler $service */
        $service = $this->get('gurme_main.recipe');
        $entity = $service->checkReadyTime($entity);

        $entity->setUser($this->container->get('security.context')->getToken()->getUser());
        $entity->setCreatedAt(); // sets current date by default

        $ingredientCheck = $this->get('gurme_main.ingredient_input_validation')->validate($entity->getIngredients());

        if (($form->isValid())&&($ingredientCheck['status'])) {
            $service->addRecipe($entity,$ingredientCheck);
            return $this->redirect($this->generateUrl('data_recipe_show', array('id' => $entity->getId())));
        } else if (!$ingredientCheck['status']) {
            $form->get('ingredients')->addError(new FormError('Bad ingredient input syntax.'));
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
        /** @var Categorie $entity */
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

        /** @var Recipe $entity */
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
        /** @var Recipe $entity */
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

    /**
     * Validate ingredient input.
     *
     * @Route("/new/ingredientCheck", name="data_recipe_ingredient_check")
     * @Method("POST")
     */
    public function ingredientCheckAction(Request $request)
    {
        $contents = $request->request->get('ingredients','');
        $response = $this->get('gurme_main.ingredient_input_validation')->validate($contents);
        for ($i = 0; $i < count($response['ingredients']); $i++) {
            $response['ingredients'][$i]['unitObj'] = null;
        }
        return new JsonResponse($response);
    }

}
