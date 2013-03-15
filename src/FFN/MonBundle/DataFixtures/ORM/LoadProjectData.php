<?php

namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\MonBundle\Entity\Project;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadProjectData
 *
 * @author frederic
 */
class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

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

        // Get how many projects to create
        $nbProjects = $this->container->getParameter('nb_projects', 1);

        // Creation of several projects
        for ($i = 1; $i <= $nbProjects; $i++) {

          $proj = new Project();
          $proj->setName('ffn_fixture_project_'.$i);
          $proj->setDateCreation(new DateTime());
          $proj->setEnabled(true);
          $proj->setDescription('Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.');
          $proj->setUser($om->merge($this->getReference('user')));

          $om->persist($proj);
          $om->flush();

          $this->addReference('proj'.$i, $proj);

        }

    }

    public function getOrder() {
        return(3);
    }

}
