<?php


namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\MonBundle\Entity\scenario;

/**
 * Description of LoadScenarioData
 *
 * @author frederic
 */
class LoadScenarioData  extends AbstractFixture implements OrderedFixtureInterface {
    
    public function load(ObjectManager $om) {
        
        $sc = new scenario();
        $sc->setDateCreation(new DateTime());
        $sc->setEnabled(true);
        $sc->setName('FFN_fixtures_scenarios_1');
        $sc->setFrequency(1);
        $sc->setProject($om->merge($this->getReference('proj')));
        
        $om->persist($sc);
        $om->flush();
        
        $this->setReference('sc', $sc);
        
    }
    
    public function getOrder() {
        return(3);
    }
}
