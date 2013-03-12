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
class LoadScenarioData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

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

        // Get how many projects & scenarios to create
        $nbProjects = $this->container->getParameter('nb_projects', 4);
        $nbScenarios = $this->container->getParameter('nb_scenarios', 5);

        // Creation of several scenarios
        for ($i = 1; $i <= $nbProjects; $i++) {
          for ($j = 1; $j <= $nbScenarios; $j++) {

            $sc = new scenario();

            $sc->setDateCreation(new DateTime());
            $sc->setEnabled(true);
            $sc->setName('FFN_fixtures_scenarios_'.$i.$j);
            $sc->setFrequency(rand(5,30));
            $sc->setProject($om->merge($this->getReference('proj'.$i)));

            $om->persist($sc);
            $om->flush();

            $this->setReference('sc'.$i.$j, $sc);

          }
        }

    }

    public function getOrder() {
        return(3);
    }
}
