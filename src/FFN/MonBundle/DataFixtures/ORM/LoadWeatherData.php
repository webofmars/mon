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
        $nbProjects = $this->container->getParameter('nb_projects', 4);
        $nbControls = $this->container->getParameter('nb_scenarii', 5);

        for ($i = 1; $i <= $nbProjects; $i++) {

            // projects
            $weather = new Weather();
            $weather->setObjectType(Weather::OBJECT_TYPE_PROJECT);
            $weather->setRefIdObject($this->getReference('proj' . $i)->getId());
            $weather->setWeatherState(rand(1, 5));
            $om->persist($weather);
            $om->flush();
            $this->addReference('weather_proj' . $i, $weather);

            for ($j = 1; $j <= $nbControls; $j++) {

                // scenarii
                $weather = new Weather();
                $weather->setObjectType(Weather::OBJECT_TYPE_SCENARIO);
                $weather->setRefIdObject($this->getReference('sc' . $i . $j)->getId());
                $weather->setWeatherState(rand(1, 5));
                $om->persist($weather);
                $om->flush();
                $this->addReference('weather_sc' . $i . $j, $weather);

                // controls
                $weather = new Weather();
                $weather->setObjectType(Weather::OBJECT_TYPE_CONTROL);
                $weather->setRefIdObject($this->getReference('ctrl' . $i . $j)->getId());
                $weather->setWeatherState(rand(1, 5));

                $om->persist($weather);
                $om->flush();
                $this->addReference('weather_ctrl' . $i . $j, $weather);
            }
        }
    }

    public function getOrder() {
        return(7);
    }

}