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
 * @ORM\MappedSuperclass
 */
class RecipeBaseClass
{

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="directions", type="text", nullable=true)
     */
    protected $directions;

    /**
     * @var \string
     *
     * @ORM\Column(name="prep_time", type="time", nullable=true)
     */
    protected $prepTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cook_time", type="time", nullable=true)
     */
    protected $cookTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ready_time", type="time", nullable=true)
     */
    protected $readyTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="calories", type="integer", nullable=false)
     */
    protected $calories;

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
    protected $servings;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean", nullable=true)
     */
    protected $approved;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=true)
     */
    protected $rating;

    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean", nullable=true)
     */
    protected $private;

    /**
     * @var \NFQAkademija\BaseBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="NFQAkademija\BaseBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    protected $user;

    /**
     * @var \RecipePhotos
     *
     * @ORM\ManyToOne(targetEntity="RecipePhoto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cover_photo_id", referencedColumnName="id")
     * })
     */
    protected $coverPhoto;

    /**
     * @var float
     *
     * @ORM\Column(name="carbs", type="float", nullable=false)
     */
    protected $carbs;

    /**
     * @var float
     *
     * @ORM\Column(name="fat", type="float", nullable=false)
     */
    protected $fat;

    /**
     * @var float
     *
     * @ORM\Column(name="protein", type="float", nullable=false)
     */
    protected $protein;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    protected $about;

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
     * @return Recipe
     */
    public function setCreatedAt()
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

}

