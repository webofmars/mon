<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * Control
 *
 * @ORM\Table()
 * @ORM\Entity
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
    
    /**
     * @var array
     * 
     * @ORM\OneToMany(targetEntity="FFN\MonBundle\Entity\ControlHeader", mappedBy="control",cascade={"persist"})
     */
    private $controlHeaders; // Ici commentaires prend un « s », car un article a plusieurs commentaires !

    /**
     * @var array
     * 
     * @ORM\OneToMany(targetEntity="FFN\MonBundle\Entity\validator", mappedBy="control", cascade={"persist"})
     */
    private $validators; // Ici commentaires prend un « s », car un article a plusieurs commentaires !

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="scenario", inversedBy="controls", cascade={"persist"})
     * @ORM\JoinColumn(name="scenario_id", referencedColumnName="id")
     */
    protected $scenario;


    /**
     * Constructor
     */
    public function __construct()
    {
      $this->controlHeaders = new \Doctrine\Common\Collections\ArrayCollection();
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
    * @param FFN\MonBundle\Entity\ControlHeader $controlHeader
    * @return this
    */
    public function addControlHeader(ControlHeader $controlHeader){
      $controlHeader->setControl($this);
      $this->controlHeaders[] = $controlHeader;
      return $this;
    }

    /**
    * @param FFN\MonBundle\Entity\ControlHeader $controlHeader
    */
    public function removeControlHeader(ControlHeader $controlHeader)
    {
      $this->controlHeaders->removeElement($controlHeader);
    }

    /**
    * @return Doctrine\Common\Collections\Collection
    */
    public function getControlHeaders()
    {
      return $this->controlHeaders;
    }
    
    /**
     * @param FFN\MonBundle\Entity\validator $validator
     * @return this
     */
    public function addValidator(validator $validator){
      $validator->setControl($this);
      $this->validators[] = $validator;
      return $this;
    }

    /**
     * @param FFN\MonBundle\Entity\validator $validator
     */
    public function removeValidator(validator $validator)
    {
      $this->validators->removeElement($validator);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getValidators()
    {
      return $this->validators;
    }


    /**
     * Set scenario
     *
     * @param \FFN\MonBundle\Entity\scenario $scenario
     * @return Control
     */
    public function setScenario(\FFN\MonBundle\Entity\scenario $scenario = null)
    {
        $this->scenario = $scenario;
    
        return $this;
    }

    /**
     * Get scenario
     *
     * @return \FFN\MonBundle\Entity\scenario 
     */
    public function getScenario()
    {
        return $this->scenario;
    }
}