<?php

namespace Gurme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecipeComments
 *
 * @ORM\Table(name="recipe_comments", indexes={@ORM\Index(name="fk_recipe_comments_1_idx", columns={"user_id"}), @ORM\Index(name="fk_recipe_comments_2_idx", columns={"recipe_id"})})
 * @ORM\Entity
 */
class RecipeComment
{
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
     *
     * @ORM\Column(name="comment", type="string", length=500, nullable=false)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="flaged", type="boolean", nullable=false)
     */
    private $flaged;

    /**
     * @var \NFQAkademija\BaseBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="NFQAkademija\BaseBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Gurme\MainBundle\Entity\Recipe
     *
     * @ORM\ManyToOne(targetEntity="Recipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     * })
     */
    private $recipe;



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
     * Set comment
     *
     * @param string $comment
     * @return RecipeComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set createdAt
     *
     * @return RecipeComment
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
     * Set flaged
     *
     * @param boolean $flaged
     * @return RecipeComment
     */
    public function setFlaged($flaged)
    {
        $this->flaged = $flaged;

        return $this;
    }

    /**
     * Get flaged
     *
     * @return boolean 
     */
    public function getFlaged()
    {
        return $this->flaged;
    }

    /**
     * Set user
     *
     * @param \NFQAkademija\BaseBundle\Entity\User $user
     * @return RecipeComment
     */
    public function setUser(\NFQAkademija\BaseBundle\Entity\User $user = null)
    {
        $this->user = $user;

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
     * Set recipe
     *
     * @param \Gurme\MainBundle\Entity\Recipe $recipe
     * @return RecipeComment
     */
    public function setRecipe(\Gurme\MainBundle\Entity\Recipe $recipe = null)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * Get recipe
     *
     * @return \Gurme\MainBundle\Entity\Recipe 
     */
    public function getRecipe()
    {
        return $this->recipe;
    }
}
