<?php

namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\MonBundle\Entity\Weather;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadWeatherData
 *
 * @author Frederic Leger <leger.frederic@gmail.com>
 */
class LoadWeatherData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

  /**
   * @var ContainerInterface
   */
  private $container;

  /**
   * {@inheritDoc}
   */
  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  public function load(ObjectManager $om) {

    // Get how many things to create
    $nbUsers = $this->container->getParameter('nb_users', 1);
    $nbProjects = $this->container->getParameter('nb_projects', 1);
    $nbScenarios = $this->container->getParameter('nb_scenarios', 1);
    $nbControls = $this->container->getParameter('nb_controls', 1);

    for ($i = 1; $i <= $nbUsers; $i++) {
      for ($j = 1; $j <= $nbProjects; $j++) {
        // projects
        $weather = new Weather();
        $weather->setObjectType(Weather::OBJECT_TYPE_PROJECT);
        $weather->setRefIdObject($this->getReference('prj_' . $i . '_' . $j)->getId());
        $weather->setWeatherState(rand(1, 5));
        $om->persist($weather);
        $om->flush();
        $this->setReference('wth_prj_' . $i . '_' . $j, $weather);
        for ($k = 1; $k <= $nbScenarios; $k++) {
          // scenarios
          $weather = new Weather();
          $weather->setObjectType(Weather::OBJECT_TYPE_SCENARIO);
          $weather->setRefIdObject($this->getReference('scn_' . $i . '_' . $j . '_' . $k)->getId());
          $weather->setWeatherState(rand(1, 5));
          $om->persist($weather);
          $om->flush();
          $this->setReference('wth_scn_' . $i . '_' . $j . '_' . $k, $weather);
          for ($l = 1; $l <= $nbControls; $l++) {
            // controls
            $weather = new Weather();
            $weather->setObjectType(Weather::OBJECT_TYPE_CONTROL);
            $weather->setRefIdObject($this->getReference('ctr_' . $i . '_' . $j . '_' . $k . '_' . $l)->getId());
            $weather->setWeatherState(rand(1, 5));
            $om->persist($weather);
            $om->flush();
            $this->setReference('wth_ctr_' . $i . '_' . $j . '_' . $k . '_' . $l, $weather);
          }
        }
      }
    }
  }

  public function getOrder() {
    return(7);
  }

}

