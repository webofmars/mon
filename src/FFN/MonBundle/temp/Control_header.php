<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FFN\MonBundle\Entity\Control;

/**
 * Control_header
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\control_headerRepository")
 */
class Control_header
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
     *
     * @ORM\ManyToOne(targetEntity="FFN\MonBundle\Entity\Control", inversedBy="controlHeaders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $refIdControl;

    /**
     * @var string
     *
     * @ORM\Column(name="header_key", type="string", length=255)
     */
    private $headerKey;

    /**
     * @var string
     *
     * @ORM\Column(name="header_value", type="string", length=255)
     */
    private $headerValue;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


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
     * Set refIdControl
     *
     * @param Control $refIdControl
     * @return control_header
     */
    public function setRefIdControl(Control $refIdControl)
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

    /**
     * Set headerKey
     *
     * @param string $headerKey
     * @return control_header
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
     * @return control_header
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
     * @return control_header
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
}