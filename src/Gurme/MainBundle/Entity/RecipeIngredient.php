<?php

namespace Gurme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecipeIngredients
 *
 * @ORM\Table(name="recipe_ingredients", indexes={@ORM\Index(name="fk_recipe_ingredients_1_idx", columns={"ingredient_id"}), @ORM\Index(name="fk_recipe_ingredients_2_idx", columns={"recipe_id"}), @ORM\Index(name="fk_recipe_ingredients_3_idx", columns={"unit_id"})})
 * @ORM\Entity
 */
class RecipeIngredient
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
     * @ORM\Column(name="amount", type="string", length=10, nullable=false)
     */
    private $amount;

    /**
     * @var \Units
     *
     * @ORM\ManyToOne(targetEntity="Unit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

    /**
     * @var \Ingredients
     *
     * @ORM\ManyToOne(targetEntity="Ingredient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id")
     * })
     */
    private $ingredient;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=45, nullable=true)
     */
    private $note;

    /**
     * @var \Recipes
     *
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="ingredient")
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
     * Set amount
     *
     * @param string $amount
     * @return RecipeIngredient
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return RecipeIngredient
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set ingredient
     *
     * @param \Gurme\MainBundle\Entity\Ingredient $ingredient
     * @return RecipeIngredient
     */
    public function setIngredient(\Gurme\MainBundle\Entity\Ingredient $ingredient = null)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get ingredient
     *
     * @return \Gurme\MainBundle\Entity\Ingredient 
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * Set recipe
     *
     * @param \Gurme\MainBundle\Entity\Recipe $recipe
     * @return RecipeIngredient
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

    /**
     * Set unit
     *
     * @param \Gurme\MainBundle\Entity\Unit $unit
     * @return RecipeIngredient
     */
    public function setUnit(\Gurme\MainBundle\Entity\Unit $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return \Gurme\MainBundle\Entity\Unit 
     */
    public function getUnit()
    {
        return $this->unit;
    }
}
