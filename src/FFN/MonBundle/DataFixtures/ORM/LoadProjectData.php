<?php

namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\MonBundle\Entity\Project;

/**
 * Description of LoadProjectData
 *
 * @author frederic
 */
class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface {
    
    public function load(ObjectManager $om) {

        // Creation of several projects
        for ($i = 1; $i <= 3; $i++) {

          $proj = new Project();

          $proj->setName('ffn_fixture_project_'.$i);
          $proj->setDateCreation(new DateTime());
          $proj->setEnabled(true);
          $proj->setDescription('Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.');
          $proj->setUser($om->merge($this->getReference('user')));

          $om->persist($proj);
          $om->flush();

          $this->addReference('proj'.$i, $proj);
        
        }

    }
    
    public function getOrder() {
        return(2);
    }

}
