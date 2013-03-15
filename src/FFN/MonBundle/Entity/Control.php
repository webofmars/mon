<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Control
 *
 * @ORM\Table(name="control")
 * @ORM\Entity
 */
class Control {

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
    private $controlHeaders;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="FFN\MonBundle\Entity\Validator", mappedBy="control", cascade={"persist"})
     */
    private $validators;

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Scenario", inversedBy="controls", cascade={"persist"})
     * @ORM\JoinColumn(name="scenario_id", referencedColumnName="id")
     */
    protected $scenario;

    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="FFN\MonBundle\Entity\Capture", mappedBy="control", cascade={"persist"})
     * 
     */
    protected $captures;
    // @TODO: externalisser dans la conf
    /**
     *
     * @var type float
     * @ORM\Column(name="dns_threshold", type="float", nullable=true)
     * 
     */
    private $dnsThreshold = 1.0;

    /**
     *
     * @var type float
     * @ORM\Column(name="tcp_threshold", type="float", nullable=true)
     */
    private $tcpThreshold = 1.5;

    /**
     *
     * @var type float
     * @ORM\Column(name="first_packet_threshold", type="float", nullable=true)
     * 
     */
    private $firstPacketThreshold = 2.0;

    /**
     *
     * @var type float
     * @ORM\Column(name="total_time_threshold", type="float", nullable=true)
     */
    private $totalTimeThreshold = 5.0;

    /**
     * Constructor
     */
    public function __construct() {
        $this->captures = new ArrayCollection();
        $this->controlHeaders = new ArrayCollection();
        $this->validators = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return control
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return control
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return control
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * Set connectionTimeout
     *
     * @param integer $connectionTimeout
     * @return control
     */
    public function setConnectionTimeout($connectionTimeout) {
        $this->connectionTimeout = $connectionTimeout;

        return $this;
    }

    /**
     * Get connectionTimeout
     *
     * @return integer 
     */
    public function getConnectionTimeout() {
        return $this->connectionTimeout;
    }

    /**
     * Set responseTimeout
     *
     * @param integer $responseTimeout
     * @return control
     */
    public function setResponseTimeout($responseTimeout) {
        $this->responseTimeout = $responseTimeout;

        return $this;
    }

    /**
     * Get responseTimeout
     *
     * @return integer 
     */
    public function getResponseTimeout() {
        return $this->responseTimeout;
    }

    /**
     * @param FFN\MonBundle\Entity\ControlHeader $controlHeader
     * @return this
     */
    public function addControlHeader(ControlHeader $controlHeader) {
        $controlHeader->setControl($this);
        $this->controlHeaders[] = $controlHeader;
        return $this;
    }

    /**
     * @param FFN\MonBundle\Entity\ControlHeader $controlHeader
     */
    public function removeControlHeader(ControlHeader $controlHeader) {
        $this->controlHeaders->removeElement($controlHeader);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getControlHeaders() {
        return $this->controlHeaders;
    }

    /**
     * @param FFN\MonBundle\Entity\validator $validator
     * @return this
     */
    public function addValidator(validator $validator) {
        $validator->setControl($this);
        $this->validators->add($validator);
        return $this;
    }

    /**
     * @param FFN\MonBundle\Entity\validator $validator
     */
    public function removeValidator(validator $validator) {
        $this->validators->removeElement($validator);
    }

    /**
     * @return ArrayCollection
     */
    public function getValidators() {
        return $this->validators;
    }

    /**
     * Set scenario
     *
     * @param \FFN\MonBundle\Entity\Scenario $scenario
     * @return Control
     */
    public function setScenario(\FFN\MonBundle\Entity\Scenario $scenario = null) {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * Get scenario
     *
     * @return \FFN\MonBundle\Entity\Scenario 
     */
    public function getScenario() {
        return $this->scenario;
    }

    /**
     * Set frequency
     *
     * @param integer $frequency
     * @return Control
     */
    public function setFrequency($frequency) {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return integer 
     */
    public function getFrequency() {
        return $this->frequency;
    }

    /**
     * Add captures
     *
     * @param \FFN\MonBundle\Entity\capture $captures
     * @return Control
     */
    public function addCapture(\FFN\MonBundle\Entity\capture $captures) {
        $this->captures[] = $captures;

        return $this;
    }

    /**
     * Remove captures
     *
     * @param \FFN\MonBundle\Entity\capture $captures
     */
    public function removeCapture(\FFN\MonBundle\Entity\capture $captures) {
        $this->captures->removeElement($captures);
    }

    /**
     * Get captures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCaptures() {
        return $this->captures;
    }

    public function getDnsThreshold() {
        return $this->dnsThreshold;
    }

    public function setDnsThreshold($dnsThreshold) {
        $this->dnsThreshold = $dnsThreshold;
    }

    public function getTcpThreshold() {
        return $this->tcpThreshold;
    }

    public function setTcpThreshold($tcpThreshold) {
        $this->tcpThreshold = $tcpThreshold;
    }

    public function getFirstPacketThreshold() {
        return $this->firstPacketThreshold;
    }

    public function setFirstPacketThreshold($firstPacketThreshold) {
        $this->firstPacketThreshold = $firstPacketThreshold;
    }

    public function getTotalTimeThreshold() {
        return $this->totalTimeThreshold;
    }

    public function setTotalTimeThreshold($totalTimeThreshold) {
        $this->totalTimeThreshold = $totalTimeThreshold;
    }
    
}