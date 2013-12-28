<?php

namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\UserBundle\Entity\User as User;
use FFN\MonBundle\Model\User as UserModel;
use FFN\MonBundle\Entity\Subscription as Subscription;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadUserData
 *
 * @author frederic
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

  /**
   * @var ContainerInterface
   */
  private $container;

  /**
   * {@inheritDoc}
   */
  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  public function load(ObjectManager $om) {

    // Get how many other users to create
    $nbUsers        = $this->container->getParameter('nb_users', 1);
    
    // Creation of users (first one is 'admin')
    for ($i = 1; $i <= $nbUsers; $i++) {
      $user = new User($om);
      
      if ($i == 1) {
        // admin user
        $user->setUsername('admin');
        $user->setEmail('dev@null.cz');
        $user->setPlainPassword('JABE6mA3JUw7BSvQPCfG');
        $user->setRoles(array($user::ROLE_SUPER_ADMIN));
        $user->setSubscription($om->merge($this->getReference('subscription_premium')));
      } else {
        // other user
        $user->setUsername('user_' . $i);
        $user->setEmail('user_' . $i . '@null.cz');
        $user->setPlainPassword('user_' . $i);
        $user->setRoles(array($user::ROLE_DEFAULT));
        $user->setSubscription($om->merge($this->getReference('subscription_free')));
      }
      $user->setEnabled(true);
      $user->setLocked(false);
      $user->setExpired(false);
      
      $om->persist($user);
      $om->flush();
            
      $this->setReference('usr_' . $i, $user);
    }
  }

  public function getOrder() {
    return(2);
  }

}
