<?php

namespace FFN\MonBundle\Model;

use Doctrine\ORM\EntityManager;
use FFN\MonBundle\Entity\Project as ProjectEntity;
use FFN\MonBundle\Entity\Scenario as ScenarioEntity;
use FFN\MonBundle\Entity\Weather as WeatherEntity;
use FFN\MonBundle\Model\Scenario as ScenarioModel;

/**
 * Description of Project
 *
 * @author fabien.somnier
 */
class Project {

  private $entity_manager;
  private $id_project;
  private $project_entity;
  private $weather_entity;
  private $scenarios;

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
   * Set the project identifier
   * @param integer $idProject
   */
  public function setIdProject($idProject) {
    $this->id_project = $idProject;
  }

  /**
   * Set the project entity after hydrate
   * @param ProjectEntity $project
   * @return boolean
   */
  private function setProjectEntity(ProjectEntity $project) {
    $this->project_entity = $project;
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
   * Get the project identifier
   * @return integer
   */
  public function getIdProject() {
    return $this->id_project;
  }

  /**
   * Get the project entity
   * @return ProjectEntity
   */
  public function getProjectEntity() {
    return $this->project_entity;
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
   * Get all related scenarios
   * @return false|array
   */
  public function getScenarios() {

    if (is_array($this->scenarios) && count($this->scenarios) > 0) {
      return $this->scenarios;
    }

    return false;
  }

  /* ----------------
   * OTHERS
    -------------- */

  /**
   * Launch project recognition with id
   * @return boolean
   */
  public function hydrate() {

    if ($this->getIdProject() > 0) {
      // Init from id_project
      $project = $this->getEntityManager()
              ->getRepository('FFN\MonBundle\Entity\Project')
              ->findOneById($this->getIdProject());
      if ($project instanceof ProjectEntity) {
        $this->setProjectEntity($project);
        $weather = $this->getEntityManager()
                ->getRepository('FFN\MonBundle\Entity\Weather')
                ->findOneByProject($this->getIdProject());
        if ($weather instanceof WeatherEntity) {
          $this->setWeatherEntity($weather);
        }
        // Get related scenarios
        return $this->hydrateScenarios();
      } else {
        // None found project with the id
        return false;
      }
    }

    // Not enough information to hydrate
    return false;
  }

  /**
   * Search and initiate related scenarios
   * @return boolean
   */
  public function hydrateScenarios() {

    $return = true;
    $project = $this->getProjectEntity();
    if ($project instanceof ProjectEntity) {
      foreach ($project->getScenarios() as $scenarioEntity) {
        if ($scenarioEntity instanceof ScenarioEntity) {
          $scenarioModel = new ScenarioModel($this->getEntityManager());
          $scenarioModel->setIdScenario($scenarioEntity->getId());
          if ($scenarioModel->hydrate()) {
            if ($this->addScenario($scenarioModel) === false) {
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
   * Add another related scenario
   * @return boolean
   */
  public function addScenario(ScenarioModel $scenario) {

    if (!isset($this->scenarios[$scenario->getIdScenario()])) {
      $this->scenarios[$scenario->getIdScenario()] = $scenario;
    }

    return true;
  }

}