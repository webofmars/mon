<?php

namespace FFN\MonBundle\Model;

use Doctrine\ORM\EntityManager;
use FFN\UserBundle\Entity\User as UserEntity;
use FFN\MonBundle\Entity\Subscription as SubscriptionEntity;

/**
 * Description of Control
 *
 * @author fabien.somnier
 */
class User {

  private $entity_manager;
  private $id_user;
  private $user_entity;
  private $subscription_entity;

  /**
   * Constructor
   * @param \Doctrine\ORM\EntityManager $em
   * @return boolean
   */
  function __construct(EntityManager $em) {
    $this->setEntityManager($em);
    return true;
  }

  /* ----------------
   * SETTERS
    -------------- */

  /**
   * Set the entity manager
   * @param \Doctrine\ORM\EntityManager $entity_manager
   */
  private function setEntityManager($entityManager) {
    $this->entity_manager = $entityManager;
  }

  /**
   * Set the User identifier
   * @param integer $UserId
   */
  public function setUserId($UserId) {
    $this->user_entity = $UserId;
  }

  /**
   * Set the user entity after hydrate
   * @param UserEntity $user
   * @return boolean
   */
  public function setUserEntity(UserEntity $user) {
    $this->user_entity = $user;
    return true;
  }

  /**
   * Set the Subscription entity after hydrate
   * @param SubscriptionEntity $subscription
   * @return boolean
   */
  public function setSubscriptionEntity(SubscriptionEntity $subscription) {
    $this->subscription_entity = $subscription;
    return true;
  }

  /* ----------------
   * GETTERS
    -------------- */

  /**
   * Get the entity manager
   * @return EntityManager
   */
  private function getEntityManager() {
    return $this->entity_manager;
  }

  /**
   * Get the User identifier
   * @return integer
   */
  public function getId() {
    return $this->id_user;
  }

  /**
   * Get the User entity
   * @return UserEntity
   */
  public function getEntity() {
    return $this->user_entity;
  }

  /**
   * Get the Subscription entity
   * @return SubscriptionEntity
   */
  public function getSubscriptionEntity() {
    if ($this->subscription_entity instanceof SubscriptionEntity) {
      return $this->subscription_entity;
    }
    return false;
  }

  /* ----------------
   * OTHERS
    -------------- */

  /**
   * Launch User recognition with id
   * @return boolean
   */
  public function hydrate() {

    if ($this->getId() > 0) {
      // Init from id_user
      $user = $this->getEntityManager()
              ->getRepository('FFN\UserBundle\Entity\User')
              ->findOneById($this->getId());
      if ($user instanceof UserEntity) {
        $this->setUserEntity($user);
        $subscription = $this->getEntityManager()
                ->getRepository('FFN\MonBundle\Entity\Subscription')
                ->findOneByControl($this->getIdSubscription());
        if ($subscription instanceof SubscriptionEntity) {
          $this->setSubscriptionEntity($subscription);
        }
        return true;
      } else {
        // No subscription found with the id provided
        return false;
      }
    }
    // No enough information to hydrate
    return false;
  }
}