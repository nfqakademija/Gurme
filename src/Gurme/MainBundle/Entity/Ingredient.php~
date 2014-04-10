<?php

namespace Gurme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ingredient
 *
 * @ORM\Table(name="ingredients")
 * @ORM\Entity(repositoryClass="Gurme\MainBundle\Entity\IngredientRepository")
 */
class Ingredient
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="alias", type="simple_array")
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_url", type="string", length=2083)
     */
    private $iconUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="popularity", type="integer", nullable=true)
     */
    private $popularity;


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
     * @return Ingredient
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
     * Set alias
     *
     * @param array $alias
     * @return Ingredient
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return array 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set iconUrl
     *
     * @param string $iconUrl
     * @return Ingredient
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * Get iconUrl
     *
     * @return string 
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * Set popularity
     *
     * @param integer $popularity
     * @return Ingredient
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;

        return $this;
    }

    /**
     * Get popularity
     *
     * @return integer 
     */
    public function getPopularity()
    {
        return $this->popularity;
    }
}
