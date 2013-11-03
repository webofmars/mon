<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Scenario
 *
 * @ORM\Table(name="scenario")
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\ScenarioRepository")
 */
class Scenario
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="scenarios")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="frequency", type="integer")
     */
    private $frequency;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Control", mappedBy="scenario", cascade={"persist", "remove"})
     */
    protected $controls;

    /**
     * constructor
     */
    public function __construct() {
        $this->controls = new ArrayCollection;
    }

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
     * @return Scenario
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
     * Set frequency
     *
     * @param integer $frequency
     * @return Scenario
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return integer
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Scenario
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Scenario
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }


    /**
     * Set project
     *
     * @param \FFN\MonBundle\Entity\Project $project
     * @return Scenario
     */
    public function setProject(\FFN\MonBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \FFN\MonBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add controls
     *
     * @param \FFN\MonBundle\Entity\Control $controls
     * @return Scenario
     */
    public function addControl(\FFN\MonBundle\Entity\Control $controls)
    {
        $this->controls[] = $controls;

        return $this;
    }

    /**
     * Remove controls
     *
     * @param \FFN\MonBundle\Entity\Control $controls
     */
    public function removeControl(\FFN\MonBundle\Entity\Control $controls)
    {
        $this->controls->removeElement($controls);
    }

    /**
     * Get controls
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getControls()
    {
        return $this->controls;
    }
}