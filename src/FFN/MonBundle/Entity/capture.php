<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FFN\MonBundle\Entity\capture_detail;

/**
 * capture
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\captureRepository")
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

//  /**
//   * @var integer
//   *
//   * @ORM\ManyToOne(targetEntity="Control", inversedBy="validators")
//   * @ORM\JoinColumn(name="ref_id_control", referencedColumnName="id")
//   */
  private $refIdControl;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="date_scheduled", type="datetime")
   */
  private $dateScheduled;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="date_executed", type="datetime")
   */
  private $dateExecuted;
  
  /**
   * @var integer
   *
   * @ORM\Column(name="dns", type="integer")
   */
  private $dns;

  /**
   * @var integer
   *
   * @ORM\Column(name="tcp", type="integer")
   */
  private $tcp;

  /**
   * @var integer
   *
   * @ORM\Column(name="first_packet", type="integer")
   */
  private $firstPacket;

  /**
   * @var integer
   *
   * @ORM\Column(name="total", type="integer")
   */
  private $total;

  /**
   * @var integer
   *
   * @ORM\Column(name="response_code", type="integer")
   */
  private $responseCode;

  /**
   * @var boolean
   *
   * @ORM\Column(name="is_valid", type="boolean")
   */
  private $isValid;

  /**
   * @var boolean
   *
   * @ORM\Column(name="is_timeout", type="boolean")
   */
  private $isTimeout;

  /**
   * @ORM\OneToOne(targetEntity="capture_detail", cascade={"persist"})
   */
  private $capture_detail;

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set refIdControl
   *
   * @param integer $refIdControl
   * @return capture
   */
  public function setRefIdControl($refIdControl) {
    $this->refIdControl = $refIdControl;

    return $this;
  }

  /**
   * Get refIdControl
   *
   * @return integer
   */
  public function getRefIdControl() {
    return $this->refIdControl;
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
   * @param \FFN\MonBundle\Entity\capture_detail $captureDetail
   * @return capture
   */
  public function setCaptureDetail(\FFN\MonBundle\Entity\capture_detail $captureDetail = null) {
    $this->capture_detail = $captureDetail;

    return $this;
  }

  /**
   * Get capture_detail
   *
   * @return \FFN\MonBundle\Entity\capture_detail
   */
  public function getCaptureDetail() {
    return $this->capture_detail;
  }

}