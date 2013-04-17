<?php

namespace FFN\MonBundle\Model;

use Doctrine\ORM\EntityManager;
use FFN\MonBundle\Entity\Scenario as ScenarioEntity;
use FFN\MonBundle\Entity\Control as ControlEntity;
use FFN\MonBundle\Entity\Weather as WeatherEntity;
use FFN\MonBundle\Model\Control as ControlModel;

/**
 * Description of Scenario
 *
 * @author fabien.somnier
 */
class Scenario {

  private $entity_manager;
  private $id_scenario;
  private $scenario_entity;
  private $weather_entity;
  private $controls;

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
   * Set the scenario identifier
   * @param integer $idScenario
   */
  public function setIdScenario($idScenario) {
    $this->id_scenario = $idScenario;
  }

  /**
   * Set the scenario entity after hydrate
   * @param ScenarioEntity $scenario
   * @return boolean
   */
  private function setScenarioEntity(ScenarioEntity $scenario) {
    $this->scenario_entity = $scenario;
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
   * Get the scenario identifier
   * @return integer
   */
  public function getIdScenario() {
    return $this->id_scenario;
  }

  /**
   * Get the scenario entity
   * @return ScenarioEntity
   */
  public function getScenarioEntity() {
    return $this->scenario_entity;
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

  /**
   * Get all related controls
   * @return false|array
   */
  public function getControls() {

    if (is_array($this->controls) && count($this->controls) > 0) {
      return $this->controls;
    }

    return false;
  }

  /* ----------------
   * OTHERS
    -------------- */

  /**
   * Launch scenario recognition with id
   * @return boolean
   */
  public function hydrate() {

    if ($this->getIdScenario() > 0) {
      // Init from id_scenario
      $scenario = $this->getEntityManager()
              ->getRepository('FFN\MonBundle\Entity\Scenario')
              ->findOneById($this->getIdScenario());
      if ($scenario instanceof ScenarioEntity) {
        $this->setScenarioEntity($scenario);
        $weather = $this->getEntityManager()
                ->getRepository('FFN\MonBundle\Entity\Weather')
                ->findOneByScenario($this->getIdScenario());
        if ($weather instanceof WeatherEntity) {
          $this->setWeatherEntity($weather);
        }
        // Get related controls
        return $this->hydrateControls();
      } else {
        // None found scenario with the id
        return false;
      }
    }
    // Not enough information to hydrate
    return false;
  }

  /**
   * Search and initiate related controls
   * @return boolean
   */
  public function hydrateControls() {

    $return = true;
    $scenario = $this->getScenarioEntity();
    if ($scenario instanceof ScenarioEntity) {
      foreach ($scenario->getControls() as $controlEntity) {
        if ($controlEntity instanceof ControlEntity) {
          $controlModel = new ControlModel($this->getEntityManager());
          $controlModel->setIdControl($controlEntity->getId());
          if ($controlModel->hydrate()) {
            if ($this->addControl($controlModel) === false) {
              $return = false;
            }
          } else {
            $return = false;
          }
        } else {
          $return = false;
        }
      }
    } else {
      $return = false;
    }

    // return true if none problem has been encountered
    return $return;
  }

  /**
   * Add another related control
   * @return boolean
   */
  public function addControl(ControlModel $control) {

    if (!isset($this->controls[$control->getIdControl()])) {
      $this->controls[$control->getIdControl()] = $control;
    }

    return true;
  }
}