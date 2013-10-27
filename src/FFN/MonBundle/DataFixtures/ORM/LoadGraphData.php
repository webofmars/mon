<?php

namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \DateTime;
use FFN\MonBundle\Entity\Capture;
use FFN\MonBundle\Entity\CaptureDetail;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadGraphData
 *
 * @author Frederic Leger <leger.frederic@gmail.com>
 */
class LoadGraphData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

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

    // Creation of several capture/capturedetail (= 1 per existing scenario)
    for ($i = 1; $i <= $nbUsers; $i++) {
      for ($j = 1; $j <= $nbProjects; $j++) {
        for ($k = 1; $k <= $nbScenarios; $k++) {
          for ($l = 1; $l <= $nbControls; $l++) {
            $cap = new Capture();
            $capd = new CaptureDetail();
            $cap->setOwner($om->merge($this->getReference('usr_' . $i)));
            $capd->setOwner($om->merge($this->getReference('usr_' . $i)));
            $cap->setDateExecuted(new DateTime('now'));
            $cap->setDateScheduled(new DateTime('now'));
            $time = (float) rand(0, 999) / 1000;
            $cap->setDns($time);
            $time += (float) rand(0, 999) / 1000;
            $cap->setFirstPacket($time);
            $time += (float) rand(0, 999) / 1000;
            $cap->setTcp($cap->getFirstPacket() + (float) rand(0, 999) / 1000);
            $time += (float) rand(0, 999) / 1000;
            $cap->setTotal($time);
            $cap->setIsTimeout(false);
            $cap->setIsValid(true);
            $cap->setResponseCode(200);
            $capd->setContent('this is a fixture content');
            $capd->setIsConnectionTimeout(false);
            $capd->setIsResponseTimeout(false);
            $capd->setValidators(NULL);
            $cap->setControl($om->merge($this->getReference('ctr_' . $i . '_' . $j . '_' . $k . '_' . $l)));
            $cap->setCaptureDetail($capd);
            $om->persist($capd);
            $om->persist($cap);
            $om->flush();
            $this->setReference('cap_' . $i . '_' . $j . '_' . $k . '_' . $l, $cap);
            $this->setReference('capdetail_' . $i . '_' . $j . '_' . $k . '_' . $l, $capd);
          }
        }
      }
    }
  }

  public function getOrder() {
    return(6);
  }

}
