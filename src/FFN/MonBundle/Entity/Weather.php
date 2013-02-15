<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Weather
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FFN\MonBundle\Entity\WeatherRepository")
 */
class Weather
{
    const OBJECT_TYPE_PROJECT  = 1;
    const OBJECT_TYPE_SCENARIO = 2;
    const OBJECT_TYPE_CONTROL  = 3;
    
    const WEATHER_UNKNOWN = 5;
    const WEATHER_SUNNY   = 4;
    const WEATHER_MIXED   = 3;
    const WEATHER_RAIN    = 2;
    const WEATHER_STORM   = 1;
    
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
     * @ORM\Column(name="object_type", type="string", length=255)
     */
    private $objectType;

    /**
     * @var integer
     *
     * @ORM\Column(name="ref_id_object", type="integer")
     */
    private $refIdObject;

    /**
     * @var integer
     *
     * @ORM\Column(name="weather_state", type="integer")
     */
    private $weatherState;


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
     * Set objectType
     *
     * @param string $objectType
     * @return Weather
     */
    public function setObjectType($objectType)
    {
        
        if (!in_array($objectType, array(self::OBJECT_TYPE_PROJECT, 
                                         self::OBJECT_TYPE_SCENARIO, 
                                         self::OBJECT_TYPE_CONTROL))) {
            throw new \InvalidArgumentException("Invalid object type");
        }
        
        $this->objectType = $objectType;
    
        return $this;
    }

    /**
     * Get objectType
     *
     * @return string 
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * Set refIdObject
     *
     * @param integer $refIdObject
     * @return Weather
     */
    public function setRefIdObject($refIdObject)
    {
        $this->refIdObject = $refIdObject;
    
        return $this;
    }

    /**
     * Get refIdObject
     *
     * @return integer 
     */
    public function getRefIdObject()
    {
        return $this->refIdObject;
    }

    /**
     * Set weatherState
     *
     * @param integer $weatherState
     * @return Weather
     */
    public function setWeatherState($weatherState)
    {
       if (!in_array($weatherState, array(self::WEATHER_STORM, 
                                         self::WEATHER_RAIN,
                                         self::WEATHER_MIXED,
                                         self::WEATHER_SUNNY,
                                         self::WEATHER_UNKNOWN))) {
            throw new \InvalidArgumentException("Invalid weather value");
        }
        $this->weatherState = $weatherState;
    
        return $this;
    }

    /**
     * Get weatherState
     *
     * @return integer 
     */
    public function getWeatherState()
    {
        return $this->weatherState;
    }
}
