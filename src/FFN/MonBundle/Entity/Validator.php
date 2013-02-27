<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FFN\MonBundle\Common\SubValidator;
use FFN\MonBundle\Common\RegexpSubValidator;
use FFN\MonBundle\Entity\Control;

/**
 * Validator
 *
 * @ORM\Table(name="validator")
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\validatorRepository")
 */
class Validator
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
    private $type = 'Regexp';

    /**
     * @var string
     *
     * @ORM\Column(name="criteria", type="string", length=255)
     */
    private $criteria;
   
    /**
     *
     * @var SubValidator
     */
    private $subValidator;
    
    /**
     * @var Control
     *
     * @ORM\ManyToOne(targetEntity="FFN\MonBundle\Entity\Control", inversedBy="validators")
     * @ORM\JoinColumn(name="control_id", referencedColumnName="id")
     */
    private $control;
    
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
      * @param FFN\MonBundle\Entity\Control $control
      * @return this
      */
     public function setControl(Control $control)
     {
       $this->control = $control;
       return $this;
     }

     /**
      * @return FFN\MonBundle\Entity\Control
      */
     public function getControl()
     {
       return $this->control;
     }

     public function getSubValidator() {
         $class = '\FFN\MonBundle\Common\\'.$this->type.'SubValidator';
         if (class_exists($class)) {
            return new $class;   
         }
         else throw new \LogicException("Unknow validator class $class");
     }
}