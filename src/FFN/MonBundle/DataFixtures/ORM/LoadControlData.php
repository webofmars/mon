<?php


namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\MonBundle\Entity\Control;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadControlData
 *
 * @author frederic
 */
class LoadControlData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

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

        // Get how many projects & controls to create
        $nbProjects = $this->container->getParameter('nb_projects', 4);
        $nbControls = $this->container->getParameter('nb_scenarii', 5);
        $urls = array(
                    'http://www.yahoo.fr', 
                    'http://www.google.com',
                    'http://www.allocine.fr',
                    'http://www.microsoft.com',
                    'http://www.rueducommerce.fr',
                );
        

        // Creation of several controls (= 1 per existing scenario)
        for ($i = 1; $i <= $nbProjects; $i++) {
          for ($j = 1; $j <= $nbControls; $j++) {

            $ctrl = new Control();

            $ctrl->setName('FFN_fixtures_ctrl_'.$i.$j);
            $ctrl->setUrl(rand(0, count($urls)));
            $ctrl->setMimeType('text/html');
            $ctrl->setConnectionTimeout(5);
            $ctrl->setResponseTimeout(10);
            $ctrl->setScenario($om->merge($this->getReference('sc'.$i.$j)));

            $om->persist($ctrl);
            $om->flush();

            $this->addReference('ctrl'.$i.$j, $ctrl);

          }
        }

    }

    public function getOrder() {
        return(4);
    }
}
