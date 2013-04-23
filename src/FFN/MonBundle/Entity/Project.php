<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FFN\UserBundle\Entity\User;
use FFN\MonBundle\Entity\Scenario;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\ProjectRepository")
 */
class Project
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var ArrayCollection scenarios
     * @ORM\OneToMany(targetEntity="Scenario", mappedBy="project", cascade={"persist", "remove"})
     */
    protected $scenarios;

    /**
     * @ORM\ManyToOne(targetEntity="FFN\UserBundle\Entity\User", inversedBy="projects", cascade={"persist"})
     */
    protected $user;


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
     * @return Project
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Project
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
     * @return Project
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
    public function getEnabled()
    {
        return $this->enabled;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scenarios = new ArrayCollection();
    }

    /**
     * Add scenarios
     *
     * @param scenario $scenarios
     * @return Project
     */
    public function addScenario(scenario $scenarios)
    {
        $this->scenarios[] = $scenarios;

        return $this;
    }

    /**
     * Remove scenarios
     *
     * @param scenario $scenarios
     */
    public function removeScenario(scenario $scenarios)
    {
        $this->scenarios->removeElement($scenarios);
    }

    /**
     * Get scenarios
     *
     * @return Collection
     */
    public function getScenarios()
    {
        return $this->scenarios;
    }

    /**
     * Set user
     *
     * @param \FFN\UserBundle\Entity\User $user
     * @return Project
     */
    public function setUser(\FFN\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \FFN\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}