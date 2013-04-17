<?php

namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\MonBundle\Entity\Scenario;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadScenarioData
 *
 * @author frederic
 */
class LoadScenarioData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

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

    // Creation of several scenarios
    for ($i = 1; $i <= $nbUsers; $i++) {
      for ($j = 1; $j <= $nbProjects; $j++) {
        for ($k = 1; $k <= $nbScenarios; $k++) {
          $sc = new scenario();
          $sc->setDateCreation(new DateTime());
          $sc->setEnabled(true);
          $sc->setName('FFN_fixt_scn_' . $k . '_'.$i . '_'.$j);
          $sc->setFrequency(rand(5, 30));
          $sc->setProject($om->merge($this->getReference('prj_' . $i . '_' . $j)));
          $om->persist($sc);
          $om->flush();
          $this->setReference('scn_' . $i . '_'. $j . '_'. $k, $sc);
        }
      }
    }
  }

  public function getOrder() {
    return(4);
  }

}
