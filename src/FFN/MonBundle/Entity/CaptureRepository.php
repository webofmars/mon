<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * captureRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class captureRepository extends EntityRepository {

    public function findByIdAndTimeRange($ctrl_id, $start, $stop) {

        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT c FROM FFNMonBundle:Capture c
            WHERE c.dateExecuted > :start
            AND c.dateExecuted < :stop
            AND c.control = :control_id
            ORDER BY c.dateExecuted ASC');

        $query->setParameter('control_id', $ctrl_id);
        $query->setParameter('start', $start);
        $query->setParameter('stop', $stop);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findLasts($max) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT c FROM FFNMonBundle:Capture c
                                    ORDER BY c.dateExecuted ASC');

        $query->setMaxResults($max);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findLastsByControl($ctrl_id, $max) {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT c FROM FFNMonBundle:Capture c
                                    WHERE c.control = :control_id
                                    ORDER BY c.dateExecuted ASC');

        $query->setParameter('control_id', $ctrl_id);
        $query->setMaxResults($max);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}
