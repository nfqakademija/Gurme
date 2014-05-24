<?php

namespace Gurme\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gurme\MainBundle\Entity\RecipePhoto;
use Gurme\MainBundle\Entity\RecipeIngredient;
use NFQAkademija\BaseBundle\Entity\User;

/**
 * Recipes
 *
 * @ORM\Table(name="recipes", indexes={@ORM\Index(name="fk_recipes_2_idx", columns={"cover_photo_id"}), @ORM\Index(name="fk_recipes_1_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Gurme\MainBundle\Entity\RecipeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RecipeExtention extends Recipe
{

    public function __construct(){
        $this->ingredient = new ArrayCollection();
    }

    public function getObjectVars() {
        return get_object_vars($this);
    }

    /**
     * -------------------------
     * Variables for recipe form
     * -------------------------
     */

    /**
     * @var array
     *
     */
    private $categories;

    /**
     * @var mixed
     *
     */
    private $ingredients;

//    /**
//     * @var \RecipeIngredient
//     *
//     * @ORM\OneToMany(targetEntity="RecipeIngredient", mappedBy="recipe")
//     * @ORM\JoinColumn(name="id", referencedColumnName="id")
//     *
//     */
//    private $ingredient;

    /**
     * @param mixed $ingredients
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @return mixed
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Set categories
     *
     * @param array $categories
     * @return Recipe
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

//    /**
//     * Get ingredient
//     *
//     * @return \Gurme\MainBundle\Entity\RecipeIngredient
//     */
//    public function getIngredient()
//    {
//        return $this->ingredient;
//    }


    /**
     * ---------------------------------------
     * Variables and functions for file upload
     * ---------------------------------------
     */

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    public $path;

    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initialalala';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
//            $filename = sha1(uniqid(mt_rand(), true));
//            $filename = uniqid(mt_rand());
            $filename = uniqid();
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->path = $this->id . '-' . $this->path;
        $this->getFile()->move($this->getUploadRootDir(), $this->path);
//        exit(var_dump($this));
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;

    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'images/dishes';
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

}

