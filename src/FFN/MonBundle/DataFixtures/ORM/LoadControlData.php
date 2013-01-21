<?php


namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\MonBundle\Entity\Control;

/**
 * Description of LoadControlData
 *
 * @author frederic
 */
class LoadControlData  extends AbstractFixture implements OrderedFixtureInterface {
    
    public function load(ObjectManager $om) {

        // Creation of several scenarii
        for ($i = 1; $i <= 3; $i++) {
          for ($j = 1; $j <= $i; $j++) {

            $ctrl = new Control();

            $ctrl->setName('FFN_fixtures_ctrl_'.$i.$j);
            $ctrl->setUrl('http://www.couchsurfing.org/');
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
