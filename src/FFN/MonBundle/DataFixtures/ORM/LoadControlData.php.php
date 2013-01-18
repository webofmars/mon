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
        $ctrl->setFrequency(1);
        $ctrl->setScenario($om->getRepository('FFN\MonBundle\Entity\scenario')->find(1));
        
        $om->persist($ctrl);
        $om->flush();
    }
    
    public function getOrder() {
        return(3);
    }
}
