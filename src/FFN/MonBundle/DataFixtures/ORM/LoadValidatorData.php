<?php


namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FFN\MonBundle\Entity\Validator;

/**
 * Description of LoadUseroData
 *
 * @author frederic
 */
class LoadValidatorData  extends AbstractFixture implements OrderedFixtureInterface {
    
    public function load(ObjectManager $om) {
        
        $validator = new Validator();
        
        $validator->setType('Regexp');
        $validator->setCriteria('/meta/i');
        
        $ctrl= $om->merge($this->getReference('ctrl11'));
        $ctrl->addValidator($validator);
        
        $om->persist($ctrl);
        $om->persist($validator);
        $om->flush();
        
        $this->setReference('validator-regexp', $validator);
        
    }
    
    public function getOrder() {
        return(10);
    }
}
