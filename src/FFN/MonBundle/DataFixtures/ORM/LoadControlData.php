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
        
        $ctrl = new Control();
       
        $ctrl->setName('FFN_fixtures_ctrl_1');
        $ctrl->setUrl('http://www.couchsurfing.org/');
        $ctrl->setMimeType('text/html');
        $ctrl->setConnectionTimeout(5);
        $ctrl->setResponseTimeout(10);
        
        $ctrl->setScenario($om->merge($this->getReference('sc')));
        
        $om->persist($ctrl);
        $om->flush();
        
        $this->addReference('ctrl', $ctrl);
        
    }
    
    public function getOrder() {
        return(4);
    }
}
