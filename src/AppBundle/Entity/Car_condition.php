<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Car_condition
 *
 * @ORM\Table(name="car_condition")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Car_conditionRepository")
 */
class Car_condition
{

    /**
     * @ORM\OneToMany(targetEntity="Auto", mappedBy="car_condition")
     */
    protected $automobiles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->automobiles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Car_condition
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
     * Add automobile
     *
     * @param \AppBundle\Entity\Auto $automobile
     *
     * @return Car_condition
     */
    public function addAutomobile(\AppBundle\Entity\Auto $automobile)
    {
        $this->automobiles[] = $automobile;

        return $this;
    }

    /**
     * Remove automobile
     *
     * @param \AppBundle\Entity\Auto $automobile
     */
    public function removeAutomobile(\AppBundle\Entity\Auto $automobile)
    {
        $this->automobiles->removeElement($automobile);
    }

    /**
     * Get automobiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAutomobiles()
    {
        return $this->automobiles;
    }
}
