<?php

namespace FFN\MonBundle\Model;

use Doctrine\ORM\EntityManager;
use FFN\MonBundle\Entity\Control as ControlEntity;
use FFN\MonBundle\Entity\Weather as WeatherEntity;

/**
 * Description of Control
 *
 * @author fabien.somnier
 */
class Control {

  private $entity_manager;
  private $id_control;
  private $control_entity;
  private $weather_entity;

  /**
   * Constructor
   * @param \Doctrine\ORM\EntityManager $em
   * @param type $locale
   * @return boolean
   */
  function __construct(EntityManager $em) {
    $this->setEntityManager($em);
    return true;
  }

  /* ----------------
   * SETTERS
    -------------- */

  /**
   * Set the entity manager
   * @param type $entity_manager
   */
  private function setEntityManager($entityManager) {
    $this->entity_manager = $entityManager;
  }

  /**
   * Set the control identifier
   * @param integer $idControl
   */
  public function setIdControl($idControl) {
    $this->id_control = $idControl;
  }

  /**
   * Set the control entity after hydrate
   * @param ControlEntity $control
   * @return boolean
   */
  private function setControlEntity(ControlEntity $control) {
    $this->control_entity = $control;
    return true;
  }

  /**
   * Set the weather entity after hydrate
   * @param WeatherEntity $weather
   * @return boolean
   */
  private function setWeatherEntity(WeatherEntity $weather) {
    $this->weather_entity = $weather;
    return true;
  }

  /* ----------------
   * GETTERS
    -------------- */

  /**
   * Get the entity manager
   * @return EntityManager
   */
  private function getEntityManager() {
    return $this->entity_manager;
  }

  /**
   * Get the control identifier
   * @return integer
   */
  public function getIdControl() {
    return $this->id_control;
  }

  /**
   * Get the control entity
   * @return ControlEntity
   */
  public function getControlEntity() {
    return $this->control_entity;
  }

  /**
   * Get the weather entity
   * @return WeatherEntity
   */
  public function getWeatherEntity() {
    if ($this->weather_entity instanceof WeatherEntity) {
      return $this->weather_entity;
    }
    return false;
  }

  /* ----------------
   * OTHERS
    -------------- */

  /**
   * Launch control recognition with id
   * @return boolean
   */
  public function hydrate() {

    if ($this->getIdControl() > 0) {
      // Init from id_control
      $control = $this->getEntityManager()
              ->getRepository('FFN\MonBundle\Entity\Control')
              ->findOneById($this->getIdControl());
      if ($control instanceof ControlEntity) {
        $this->setControlEntity($control);
        $weather = $this->getEntityManager()
                ->getRepository('FFN\MonBundle\Entity\Weather')
                ->findOneByControl($this->getIdControl());
        if ($weather instanceof WeatherEntity) {
          $this->setWeatherEntity($weather);
        }
        return true;
      } else {
        // None found control with the id
        return false;
      }
    }
    // Not enough information to hydrate
    return false;
  }

}