<?php

namespace Gurme\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gurme\MainBundle\Entity\RecipePhoto;
use Gurme\MainBundle\Entity\RecipeIngredient;

/**
 * Recipes
 *
 * @ORM\Table(name="recipes", indexes={@ORM\Index(name="fk_recipes_2_idx", columns={"cover_photo_id"}), @ORM\Index(name="fk_recipes_1_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Gurme\MainBundle\Entity\RecipeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Recipe
{
    public function __construct(){
        $this->ingredient = new ArrayCollection();
    }
    ///////////////////////////////////////////////////////////
    // Database variables
    ///////////////////////////////////////////////////////////
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="directions", type="text", nullable=true)
     */
    private $directions;

    /**
     * @var \string
     *
     * @ORM\Column(name="prep_time", type="time", nullable=true)
     */
    private $prepTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cook_time", type="time", nullable=true)
     */
    private $cookTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ready_time", type="time", nullable=true)
     */
    private $readyTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="calories", type="integer", nullable=false)
     */
    private $calories;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\Range( min = 1, max = 100,
     *      minMessage = "You must enter positive number",
     *      maxMessage = "Your servings number doesnt make sense"
     * )
     *
     * @ORM\Column(name="servings", type="smallint", nullable=false)
     */
    private $servings;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean", nullable=true)
     */
    private $approved;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=true)
     */
    private $rating;

    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean", nullable=true)
     */
    private $private;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="NFQAkademija\BaseBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \RecipePhotos
     *
     * @ORM\ManyToOne(targetEntity="RecipePhoto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cover_photo_id", referencedColumnName="id")
     * })
     */
    private $coverPhoto;

    /**
     * @var \RecipeIngredient
     *
     * @ORM\OneToMany(targetEntity="RecipeIngredient", mappedBy="recipe")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     *
     */
    private $ingredient;

    /**
     * @var float
     *
     * @ORM\Column(name="carbs", type="float", nullable=false)
     */
    private $carbs;

    /**
     * @var float
     *
     * @ORM\Column(name="fat", type="float", nullable=false)
     */
    private $fat;

    /**
     * @var float
     *
     * @ORM\Column(name="protein", type="float", nullable=false)
     */
    private $protein;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    private $about;


    ///////////////////////////////////////////////////////////
    // Virtual variables
    ///////////////////////////////////////////////////////////

    /**
     * @var array
     *
     */
    private $categories;
    /**
     * @var \RecipePhotos
     *
     * @ORM\ManyToMany(targetEntity="RecipeIngredient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $ingredients;

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
     * @Assert\File(maxSize="6000000")
     */
    private $file;

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

    public $path;

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

    private $temp;

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    ///////////////////////////////////////////////////////////
    // Database variables' getters and setters
    ///////////////////////////////////////////////////////////

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Recipe
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set prepTime
     *
     * @param \DateTime $prepTime
     * @return Recipe
     */
    public function setPrepTime($prepTime)
    {
        $this->prepTime = $prepTime;

        return $this;
    }

    /**
     * Get prepTime
     *
     * @return \DateTime
     */
    public function getPrepTime()
    {
        return $this->prepTime;
    }

    /**
     * Set directions
     *
     * @param string $directions
     * @return Recipe
     */
    public function setDirections($directions)
    {
        $this->directions = $directions;

        return $this;
    }

    /**
     * Get directions
     *
     * @return string 
     */
    public function getDirections()
    {
        return $this->directions;
    }

    /**
     * Set cookTime
     *
     * @param \DateTime $cookTime
     * @return Recipe
     */
    public function setCookTime($cookTime)
    {
        $this->cookTime = $cookTime;

        return $this;
    }

    /**
     * Get cookTime
     *
     * @return \DateTime 
     */
    public function getCookTime()
    {
        return $this->cookTime;
    }

    /**
     * Set readyTime
     *
     * @param \DateTime $readyTime
     * @return Recipe
     */
    public function setReadyTime($readyTime)
    {
        $this->readyTime = $readyTime;

        return $this;
    }

    /**
     * Get readyTime
     *
     * @return \DateTime 
     */
    public function getReadyTime()
    {
        return $this->readyTime;
    }

    /**
     * Set calories
     *
     * @param integer $calories
     * @return Recipe
     */
    public function setCalories($calories)
    {
        $this->calories = $calories;

        return $this;
    }

    /**
     * Get calories
     *
     * @return integer 
     */
    public function getCalories()
    {
        return $this->calories;
    }

    /**
     * Set servings
     *
     * @param integer $servings
     * @return Recipe
     */
    public function setServings($servings)
    {
        $this->servings = $servings;

        return $this;
    }

    /**
     * Get servings
     *
     * @return integer 
     */
    public function getServings()
    {
        return $this->servings;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return Recipe
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Recipe
     */
    public function setCreatedAt($createdAt)
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new \DateTime('NOW');
        }

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set rating
     *
     * @param float $rating
     * @return Recipe
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set private
     *
     * @param boolean $private
     * @return Recipe
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean 
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set user
     *
     * @param \NFQAkademija\BaseBundle\Entity\User $user
     * @return Recipe
     */
    public function setUser(\NFQAkademija\BaseBundle\Entity\User $user = null)
    {
        if(!isset($this->user)){
            $this->user = $user;
        }

        return $this;
    }

    /**
     * Get user
     *
     * @return \NFQAkademija\BaseBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set coverPhoto
     *
     * @param \Gurme\MainBundle\Entity\RecipePhoto $coverPhoto
     * @return Recipe
     */
    public function setCoverPhoto(\Gurme\MainBundle\Entity\RecipePhoto $coverPhoto = null)
    {
        $this->coverPhoto = $coverPhoto;

        return $this;
    }

    /**
     * Get coverPhoto
     *
     * @return \Gurme\MainBundle\Entity\RecipePhoto 
     */
    public function getCoverPhoto()
    {
        return $this->coverPhoto;
    }

    /**
     * @param float $carbs
     */
    public function setCarbs($carbs)
    {
        $this->carbs = $carbs;
    }

    /**
     * @return float
     */
    public function getCarbs()
    {
        return $this->carbs;
    }

    /**
     * @param float $fat
     */
    public function setFat($fat)
    {
        $this->fat = $fat;
    }

    /**
     * @return float
     */
    public function getFat()
    {
        return $this->fat;
    }

    /**
     * @param float $protein
     */
    public function setProtein($protein)
    {
        $this->protein = $protein;
    }

    /**
     * @return float
     */
    public function getProtein()
    {
        return $this->protein;
    }

    /**
     * Set about
     *
     * @param string $about
     * @return Recipe
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
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

    /**
     * Get ingredient
     *
     * @return \Gurme\MainBundle\Entity\RecipeIngredient
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

}

