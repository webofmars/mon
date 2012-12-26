<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * scenario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\scenarioRepository")
 */
class scenario
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
     * @ORM\Column(name="ref_id_project", type="integer")
     */
    private $refIdProject;

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
     * @ORM\Column(name="enable", type="boolean")
     */
    private $enable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;


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
     * Set refIdProject
     *
     * @param integer $refIdProject
     * @return scenario
     */
    public function setRefIdProject($refIdProject)
    {
        $this->refIdProject = $refIdProject;
    
        return $this;
    }

    /**
     * Get refIdProject
     *
     * @return integer 
     */
    public function getRefIdProject()
    {
        return $this->refIdProject;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return scenario
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
     * @return scenario
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
     * Set enable
     *
     * @param boolean $enable
     * @return scenario
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    
        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean 
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return scenario
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
}
