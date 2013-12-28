<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CaptureDetail
 *
 * @ORM\Table(name="capture_detail")
 * @ORM\Entity
 */
class CaptureDetail {

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   *
   *
   */
  private $id;

  /**
   * @var \FFN\MonBundle\Entity\Capture
   * @ORM\OneToOne(targetEntity="FFN\MonBundle\Entity\Capture", inversedBy="captureDetail", cascade={"persist", "remove"})
   * @ORM\JoinColumn(name="capture_id", referencedColumnName="id")
   * 
   */
  private $capture;

  /**
   *
   * @var \FFN\UserBundle\Entity\User
   * @ORM\ManyToOne(targetEntity="FFN\UserBundle\Entity\User")
   * @ORM\JoinColumn(name="owner", referencedColumnName="id", nullable=false)
   * 
   */
  private $owner;

  /**
   * @var text
   *
   * @ORM\Column(name="content", type="text", nullable=true)
   */
  private $content = "";

  /**
   * @var boolean
   *
   * @ORM\Column(name="isConnectionTimeout", type="boolean", nullable=true)
   */
  private $isConnectionTimeout = false;

  /**
   * @var boolean
   *
   * @ORM\Column(name="isResponseTimeout", type="boolean", nullable=true)
   */
  private $isResponseTimeout = false;

  /**
   * @var string
   *
   * @ORM\Column(name="validators", type="string", length=255, nullable=true)
   */
  private $validators = "";

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Get capture
   * 
   * @return \FFN\MonBundle\Entity\Capture
   */
  public function getCapture() {
    return $this->capture;
  }

  /**
   * Set capture
   *
   * @param \FFN\MonBundle\Entity\Capture $capture
   * @return CaptureDetail
   */
  public function setCapture(\FFN\MonBundle\Entity\Capture $capture) {
    $this->capture = $capture;
    return $this;
  }

  /**
   * Set content
   *
   * @param text $content
   * @return CaptureDetail
   */
  public function setContent($content) {
    $this->content = $content;

    return $this;
  }

  /**
   * Get content
   *
   * @return text
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * Set isConnectionTimeout
   *
   * @param boolean $isConnectionTimeout
   * @return CaptureDetail
   */
  public function setIsConnectionTimeout($isConnectionTimeout) {
    $this->isConnectionTimeout = $isConnectionTimeout;

    return $this;
  }

  /**
   * Get isConnectionTimeout
   *
   * @return boolean
   */
  public function getIsConnectionTimeout() {
    return $this->isConnectionTimeout;
  }

  /**
   * Set isResponseTimeout
   *
   * @param boolean $isResponseTimeout
   * @return CaptureDetail
   */
  public function setIsResponseTimeout($isResponseTimeout) {
    $this->isResponseTimeout = $isResponseTimeout;

    return $this;
  }

  /**
   * Get isResponseTimeout
   *
   * @return boolean
   */
  public function getIsResponseTimeout() {
    return $this->isResponseTimeout;
  }

  /**
   * Set validators
   *
   * @param string $validators
   * @return CaptureDetail
   */
  public function setValidators($validators) {
    $this->validators = $validators;

    return $this;
  }

  /**
   * Get validators
   *
   * @return string
   */
  public function getValidators() {
    return $this->validators;
  }

  /**
   * Get owner
   *
   * @return \FFN\UserBundle\Entity\User 
   */
  public function getOwner() {
    return $this->owner;
  }

  /**
   * Set owner
   *
   * @param \FFN\UserBundle\Entity\User $user
   * @return Capture
   */
  public function setOwner(\FFN\UserBundle\Entity\User $user = null) {
    $this->owner = $user;

    return $this;
  }

}