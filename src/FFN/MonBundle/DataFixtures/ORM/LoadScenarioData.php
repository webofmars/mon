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
        
        // Creation of several scenarii
        for ($i = 1; $i <= 3; $i++) {
          for ($j = 1; $j <= $i; $j++) {

            $sc = new scenario();

            $sc->setDateCreation(new DateTime());
            $sc->setEnabled(true);
            $sc->setName('FFN_fixtures_scenarios_'.$i.$j);
            $sc->setFrequency(1);
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
