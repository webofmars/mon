<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\EntityRepository;
use FFN\MonBundle\Entity\Weather;

/**
 * WeatherRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WeatherRepository extends EntityRepository
{
    public function findOneByProject($id_project) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT w FROM FFNMonBundle:Weather w
                                    WHERE w.objectType = :object_type
                                    AND w.refIdObject = :project_id
                                    ORDER BY w.id ASC');

        $query->setParameter('object_type', Weather::OBJECT_TYPE_PROJECT);
        $query->setParameter('project_id', $id_project);
        $query->setMaxResults(1);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
        }
    }

    public function findOneByControl($id_control) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT w FROM FFNMonBundle:Weather w
                                    WHERE w.objectType = :object_type
                                    AND w.refIdObject = :control_id
                                    ORDER BY w.id ASC');

        $query->setParameter('object_type', Weather::OBJECT_TYPE_CONTROL);
        $query->setParameter('control_id', $id_control);
        $query->setMaxResults(1);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
        }
    }

    public function findOneByScenario($id_scenario) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT w FROM FFNMonBundle:Weather w
                                    WHERE w.objectType = :object_type
                                    AND w.refIdObject = :scenario_id
                                    ORDER BY w.id ASC');

        $query->setParameter('object_type', Weather::OBJECT_TYPE_SCENARIO);
        $query->setParameter('scenario_id', $id_scenario);
        $query->setMaxResults(1);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
        }
    }

    public function findByScenarioIds(Array $ids) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT w FROM FFNMonBundle:Weather w
                                    WHERE w.objectType = :object_type
                                    AND w.refIdObject IN (:ids)
                                    ORDER BY w.id ASC');

        $query->setParameter('object_type', Weather::OBJECT_TYPE_SCENARIO);
        $query->setParameter('ids', $ids);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
        }
    }
}
