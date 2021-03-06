<?php

namespace Gurme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Units
 *
 * @ORM\Table(name="units")
 * @ORM\Entity
 */
class Unit
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
     * @ORM\Column(name="main", type="string", length=10, nullable=false)
     */
    private $main;

    /**
     * @var string
     *
     * @ORM\Column(name="plural", type="string", length=45, nullable=true)
     */
    private $plural;

    /**
     * @var string
     *
     * @ORM\Column(name="system", type="string", length=6, nullable=true)
     */
    private $system;


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
     * Set main
     *
     * @param string $main
     * @return Unit
     */
    public function setMain($main)
    {
        $this->$main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return string 
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set plural
     *
     * @param string $plural
     * @return Unit
     */
    public function setPlural($plural)
    {
        $this->$plural = $plural;

        return $this;
    }

    /**
     * Get plural
     *
     * @return string 
     */
    public function getPlural()
    {
        return $this->plural;
    }

    /**
     * Set system
     *
     * @param string $system
     * @return Unit
     */
    public function setSystem($system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * @return string
     */
    public function getSystem()
    {
        return $this->system;
    }
}
