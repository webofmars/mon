<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FFN\MonBundle\Entity\User;
use FFN\MonBundle\Entity\scenario;

/**
 * Project
 *
 * @ORM\Table()
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="projects")
     * @ORM\JoinColumn(name="ref_id_user", referencedColumnName="id")
     */
    protected $refIdUser;

    /**
     * @ORM\OneToMany(targetEntity="scenario", mappedBy="id", cascade={"persist"})
     */
    protected $scenarios;

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
     * Set refIdUser
     *
     * @param User $refIdUser
     * @return Project
     */
    public function setRefIdUser(User $refIdUser = null)
    {
        $this->refIdUser = $refIdUser;
    
        return $this;
    }

    /**
     * Get refIdUser
     *
     * @return User 
     */
    public function getRefIdUser()
    {
        return $this->refIdUser;
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
}