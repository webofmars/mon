<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ControlHeader
 *
 * @ORM\Table(name="control_header")
 * @ORM\Entity
 */
class ControlHeader
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
     * @ORM\Column(name="headerKey", type="string", length=255)
     */
    private $headerKey;

    /**
     * @var string
     *
     * @ORM\Column(name="headerValue", type="string", length=255)
     */
    private $headerValue;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
    * @ORM\ManyToOne(targetEntity="FFN\MonBundle\Entity\Control", inversedBy="controlHeaders", cascade={"persist"})
    * @ORM\JoinColumn(nullable=false)
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
     * Set headerKey
     *
     * @param string $headerKey
     * @return ControlHeader
     */
    public function setHeaderKey($headerKey)
    {
        $this->headerKey = $headerKey;
    
        return $this;
    }

    /**
     * Get headerKey
     *
     * @return string 
     */
    public function getHeaderKey()
    {
        return $this->headerKey;
    }

    /**
     * Set headerValue
     *
     * @param string $headerValue
     * @return ControlHeader
     */
    public function setHeaderValue($headerValue)
    {
        $this->headerValue = $headerValue;
    
        return $this;
    }

    /**
     * Get headerValue
     *
     * @return string 
     */
    public function getHeaderValue()
    {
        return $this->headerValue;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ControlHeader
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
}