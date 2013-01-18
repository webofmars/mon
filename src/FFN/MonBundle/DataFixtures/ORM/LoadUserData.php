<?php


namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\MonBundle\Entity\User;

/**
 * Description of LoadScenarioData
 *
 * @author frederic
 */
class LoadUserData  extends AbstractFixture implements OrderedFixtureInterface {
    
    public function load(ObjectManager $om) {
        
        $user = new User();
        
        $user->setUsername('admin');
        $user->setEmail('dev@null.cz');
        $user->setEnabled(true);
        $user->setPlainPassword('admin');
        $user->setLocked(false);
        $user->setExpired(false);
        
        $om->persist($user);
        $om->flush();
        
        $this->setReference('user', $user);
        
    }
    
    public function getOrder() {
        return(1);
    }
}
