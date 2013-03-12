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
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $om) {

        $nbProjects = $this->container->getParameter('nb_projects', 4);
        $nbControls = $this->container->getParameter('nb_scenarios', 5);
        for ($i = 1; $i <= $nbProjects; $i++) {
          for ($j = 1; $j <= $nbControls; $j++) {

            $cap  = new Capture();
            $capd = new CaptureDetail();

            $cap->setDateExecuted(new DateTime('now'));
            $cap->setDateScheduled(new DateTime('now'));

            $time = (float) rand(0, 999)/1000;
            $cap->setDns($time);
            $time += (float) rand(0, 999)/1000;
            $cap->setFirstPacket($time);
            $time += (float) rand(0, 999)/1000;
            $cap->setTcp($cap->getFirstPacket() + (float) rand(0, 999)/1000);
            $time += (float) rand(0, 999)/1000;
            $cap->setTotal($time);

            $cap->setIsTimeout(false);
            $cap->setIsValid(true);
            $cap->setResponseCode(200);

            $capd->setContent('this is a fixture content');
            $capd->setIsConnectionTimeout(false);
            $capd->setIsResponseTimeout(false);
            $capd->setValidators(NULL);

            $cap->setControl($om->merge($this->getReference('ctrl'.$i.$j)));
            $cap->setCaptureDetail($capd);
            $om->persist($capd);
            $om->persist($cap);
            $om->flush();

            $this->addReference('cap'.$i.$j, $cap);
            $this->addReference('capdetail'.$i.$j, $capd);
          }
        }
    }

    public function getOrder() {
        return(5);
    }
}
