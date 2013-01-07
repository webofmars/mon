<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use FFN\MonBundle\Entity\capture;
use FFN\MonBundle\Entity\Control_header;
use FFN\MonBundle\Entity\validator;

/**
 * Control
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\ControlRepository")
 */
class Control
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=255)
     */
    private $mimeType;

    /**
     * @var integer
     *
     * @ORM\Column(name="connection_timeout", type="integer")
     */
    private $connectionTimeout;

    /**
     * @var integer
     *
     * @ORM\Column(name="response_timeout", type="integer")
     */
    private $responseTimeout;

//    /**
//     * @ORM\OneToMany(targetEntity="validator", mappedBy="id", cascade={"persist"})
//     */
    protected $validators;

//    /**
//     * @ORM\OneToMany(targetEntity="FFN\MonBundle\Entity\control_header", mappedBy="ref_id_control")
//     */
//    protected $controlHeaders;
   /**
    * @ORM\OneToMany(targetEntity="FFN\MonBundle\Entity\Control_header", mappedBy="refIdControl")    */
   protected $controlHeaders;

//    /**
//     * @ORM\OneToMany(targetEntity="capture", mappedBy="id", cascade={"persist"})
//     */
    protected $captures;
    
    /**
     * Constructor
     */
    public function __construct()
    {
      //$this->controlHeaders = new \Doctrine\Common\Collections\ArrayCollection();
      $this->validators = new ArrayCollection();
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
     * @return control
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
     * Set url
     *
     * @param string $url
     * @return control
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return control
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    
        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set connectionTimeout
     *
     * @param integer $connectionTimeout
     * @return control
     */
    public function setConnectionTimeout($connectionTimeout)
    {
        $this->connectionTimeout = $connectionTimeout;
    
        return $this;
    }

    /**
     * Get connectionTimeout
     *
     * @return integer 
     */
    public function getConnectionTimeout()
    {
        return $this->connectionTimeout;
    }

    /**
     * Set responseTimeout
     *
     * @param integer $responseTimeout
     * @return control
     */
    public function setResponseTimeout($responseTimeout)
    {
        $this->responseTimeout = $responseTimeout;
    
        return $this;
    }

    /**
     * Get responseTimeout
     *
     * @return integer 
     */
    public function getResponseTimeout()
    {
        return $this->responseTimeout;
    }
    /**
     * Add validators
     *
     * @param \FFN\MonBundle\Entity\ control
     */
    public function addValidator(validator $validators)
    {
        $this->validators[] = $validators;
    
        return $this;
    }

    /**
     * Remove validators
     *
     * @param validator $validators
     */
    public function removeValidator(validator $validators)
    {
        $this->validators->removeElement($validators);
    }

    /**
     * Get validators
     *
     * @return Collection 
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * Add controlHeader
     *
     * @param FFN\MonBundle\Entity\Control_header $controlHeader
     * @return control
     */
    public function addControlHeader(FFN\MonBundle\Entity\Control_header $controlHeader)
    {
        $this->controlHeaders[] = $controlHeader;
    
        return $this;
    }

    /**
     * Remove controlHeaders
     *
     * @param FFN\MonBundle\Entity\Control_header $controlHeader
     */
    public function removeControlHeader(FFN\MonBundle\Entity\Control_header $controlHeader)
    {
        $this->controlHeaders->removeElement($controlHeader);
    }

    /**
     * Get controlHeaders
     *
     * @return Collection 
     */
    public function getControlHeaders()
    {
        return $this->controlHeaders;
    }

    /**
     * Add captures
     *
     * @param capture $captures
     * @return control
     */
    public function addCapture(capture $captures)
    {
        $this->captures[] = $captures;
    
        return $this;
    }

    /**
     * Remove captures
     *
     * @param capture $captures
     */
    public function removeCapture(capture $captures)
    {
        $this->captures->removeElement($captures);
    }

    /**
     * Get captures
     *
     * @return Collection 
     */
    public function getCaptures()
    {
        return $this->captures;
    }
}