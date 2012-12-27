<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * validator
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\validatorRepository")
 */
class validator
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="criteria", type="string", length=255)
     */
    private $criteria;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Control", inversedBy="validators")
     * @ORM\JoinColumn(name="ref_id_control", referencedColumnName="id")
     */
    private $refIdControl;


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
     * Set type
     *
     * @param string $type
     * @return validator
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set criteria
     *
     * @param string $criteria
     * @return validator
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    
        return $this;
    }

    /**
     * Get criteria
     *
     * @return string 
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Set refIdControl
     *
     * @param integer $refIdControl
     * @return validator
     */
    public function setRefIdControl($refIdControl)
    {
        $this->refIdControl = $refIdControl;
    
        return $this;
    }

    /**
     * Get refIdControl
     *
     * @return integer 
     */
    public function getRefIdControl()
    {
        return $this->refIdControl;
    }
}