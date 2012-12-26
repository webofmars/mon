<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * control
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\controlRepository")
 */
class control
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
}
