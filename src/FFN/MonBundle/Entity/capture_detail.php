<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * capture_detail
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\capture_detailRepository")
 */
class capture_detail
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="validators", type="text")
     */
    private $validators;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_connection_timeout", type="boolean")
     */
    private $isConnectionTimeout;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_response_timeout", type="boolean")
     */
    private $isResponseTimeout;


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
     * Set content
     *
     * @param string $content
     * @return capture_detail
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set validators
     *
     * @param string $validators
     * @return capture_detail
     */
    public function setValidators($validators)
    {
        $this->validators = $validators;
    
        return $this;
    }

    /**
     * Get validators
     *
     * @return string 
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * Set isConnectionTimeout
     *
     * @param boolean $isConnectionTimeout
     * @return capture_detail
     */
    public function setIsConnectionTimeout($isConnectionTimeout)
    {
        $this->isConnectionTimeout = $isConnectionTimeout;
    
        return $this;
    }

    /**
     * Get isConnectionTimeout
     *
     * @return boolean 
     */
    public function getIsConnectionTimeout()
    {
        return $this->isConnectionTimeout;
    }

    /**
     * Set isResponseTimeout
     *
     * @param boolean $isResponseTimeout
     * @return capture_detail
     */
    public function setIsResponseTimeout($isResponseTimeout)
    {
        $this->isResponseTimeout = $isResponseTimeout;
    
        return $this;
    }

    /**
     * Get isResponseTimeout
     *
     * @return boolean 
     */
    public function getIsResponseTimeout()
    {
        return $this->isResponseTimeout;
    }
}
