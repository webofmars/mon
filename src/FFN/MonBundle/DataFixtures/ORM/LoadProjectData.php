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
class LoadProjectData  extends AbstractFixture implements OrderedFixtureInterface {
    
    public function load(ObjectManager $om) {
        $proj = new Project();
        $proj->setName('ffn_fixture_project_1');
        $proj->setDateCreation(new DateTime());
        $proj->setEnabled(true);
        $proj->setUser(null);
        
        $om->persist($proj);
        $om->flush();
    }
    
    public function getOrder() {
        return(1);
    }

}
