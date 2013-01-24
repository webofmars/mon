<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use FFN\MonBundle\Entity\CaptureDetail;


/**
 * capture
 *
 * @ORM\Table(
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="const_dateSched_ctrl_idx", columns={"date_scheduled", "control_id"})
 *  })
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\captureRepository")
 * @UniqueEntity({ "dateScheduled", "control" })
 * 
 * 
 */
class capture {

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="date_scheduled", type="datetime", nullable=true)
   */
  private $dateScheduled = null;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="date_executed", type="datetime", nullable=true)
   */
  private $dateExecuted = null;
  
  /**
   * @var decimal
   *
   * @ORM\Column(name="dns", type="decimal", scale=3, nullable=true)
   */
  private $dns = 0.000;

  /**
   * @var decimal
   *
   * @ORM\Column(name="tcp", type="decimal", scale=3, nullable=true)
   */
  private $tcp = 0.000;

  /**
   * @var decimal
   *
   * @ORM\Column(name="first_packet", type="decimal", scale=3, nullable=true)
   */
  private $firstPacket = 0.000;

  /**
   * @var decimal
   *
   * @ORM\Column(name="total", type="decimal", scale=3, nullable=true)
   */
  private $total = 0.000;

  /**
   * @var integer
   *
   * @ORM\Column(name="response_code", type="integer", nullable=true)
   */
  private $responseCode = 0;

  /**
   * @var boolean
   *
   * @ORM\Column(name="is_valid", type="boolean", nullable=true)
   */
  private $isValid = false;

  /**
   * @var boolean
   *
   * @ORM\Column(name="is_timeout", type="boolean", nullable=true)
   */
  private $isTimeout = false;

  /**
   * @var ArrayCollection
   * 
   * @ORM\OneToMany(targetEntity="captureDetail", mappedBy="capture", cascade={"persist"})
   * @ORM\JoinColumn(name="capture_detail_id", referencedColumnName="id")
   * 
   */
  protected $captureDetail;

    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="control", inversedBy="captures")
     * @ORM\JoinColumn(name="control_id", referencedColumnName="id", nullable=false)
     * 
     */
    protected $control;
  
  
    public function __construct()
 {
    $this->captureDetail = new ArrayCollection();
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
   * Set dateScheduled
   *
   * @param \DateTime $dateScheduled
   * @return capture
   */
  public function setDateScheduled($date) {
    $this->dateScheduled = $date;

    return $this;
  }

  /**
   * Get dateScheduled
   *
   * @return \DateTime
   */
  public function getDateScheduled() {
    return $this->dateScheduled;
  }

  /**
   * Set dateExecuted
   *
   * @param \DateTime $dateExecuted
   * @return capture
   */
  public function setDateExecuted($date) {
    $this->dateExecuted = $date;

    return $this;
  }

  /**
   * Get dateExecuted
   *
   * @return \DateTime
   */
  public function getDateExecuted() {
    return $this->dateExecuted;
  }
  
  /**
   * Set dns
   *
   * @param integer $dns
   * @return capture
   */
  public function setDns($dns) {
    $this->dns = $dns;

    return $this;
  }

  /**
   * Get dns
   *
   * @return integer
   */
  public function getDns() {
    return $this->dns;
  }

  /**
   * Set tcp
   *
   * @param integer $tcp
   * @return capture
   */
  public function setTcp($tcp) {
    $this->tcp = $tcp;

    return $this;
  }

  /**
   * Get tcp
   *
   * @return integer
   */
  public function getTcp() {
    return $this->tcp;
  }

  /**
   * Set firstPacket
   *
   * @param integer $firstPacket
   * @return capture
   */
  public function setFirstPacket($firstPacket) {
    $this->firstPacket = $firstPacket;

    return $this;
  }

  /**
   * Get firstPacket
   *
   * @return integer
   */
  public function getFirstPacket() {
    return $this->firstPacket;
  }

  /**
   * Set total
   *
   * @param integer $total
   * @return capture
   */
  public function setTotal($total) {
    $this->total = $total;

    return $this;
  }

  /**
   * Get total
   *
   * @return integer
   */
  public function getTotal() {
    return $this->total;
  }

  /**
   * Set responseCode
   *
   * @param integer $responseCode
   * @return capture
   */
  public function setResponseCode($responseCode) {
    $this->responseCode = $responseCode;

    return $this;
  }

  /**
   * Get responseCode
   *
   * @return integer
   */
  public function getResponseCode() {
    return $this->responseCode;
  }

  /**
   * Set isValid
   *
   * @param boolean $isValid
   * @return capture
   */
  public function setIsValid($isValid) {
    $this->isValid = $isValid;

    return $this;
  }

  /**
   * Get isValid
   *
   * @return boolean
   */
  public function getIsValid() {
    return $this->isValid;
  }

  /**
   * Set isTimeout
   *
   * @param boolean $isTimeout
   * @return capture
   */
  public function setIsTimeout($isTimeout) {
    $this->isTimeout = $isTimeout;

    return $this;
  }

  /**
   * Get isTimeout
   *
   * @return boolean
   */
  public function getIsTimeout() {
    return $this->isTimeout;
  }
  

    /**
     * Set capture_detail
     *
     * @param \FFN\MonBundle\Entity\CaptureDetail $captureDetail
     * @return capture
     */
    public function setCaptureDetail(\FFN\MonBundle\Entity\CaptureDetail $captureDetail = null)
    {
        $this->captureDetail = $captureDetail;
    
        return $this;
    }

    /**
     * Get capture_detail
     *
     * @return \FFN\MonBundle\Entity\capture_detail 
     */
    public function getCaptureDetail()
    {
        return $this->captureDetail;
    }



    /**
     * Add captureDetail
     *
     * @param \FFN\MonBundle\Entity\CaptureDetail $captureDetail
     * @return capture
     */
    public function addCaptureDetail(\FFN\MonBundle\Entity\CaptureDetail $captureDetail)
    {
        $this->captureDetail[] = $captureDetail;
    
        return $this;
    }

    /**
     * Remove captureDetail
     *
     * @param \FFN\MonBundle\Entity\CaptureDetail $captureDetail
     */
    public function removeCaptureDetail(\FFN\MonBundle\Entity\CaptureDetail $captureDetail)
    {
        $this->captureDetail->removeElement($captureDetail);
    }

    /**
     * Set control
     *
     * @param \FFN\MonBundle\Entity\control $control
     * @return capture
     */
    public function setControl(\FFN\MonBundle\Entity\control $control = null)
    {
        $this->control = $control;
    
        return $this;
    }

    /**
     * Get control
     *
     * @return \FFN\MonBundle\Entity\control 
     */
    public function getControl()
    {
        return $this->control;
    }
}