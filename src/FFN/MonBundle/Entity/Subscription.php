<?php

namespace FFN\MonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Subscription
 *
 * @ORM\Table(name="subscription")
 * @ORM\Entity
 *
 */
class Subscription {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = '';

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="max_pr", type="integer", nullable=true)
     */
    private $maxProjects = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_sc", type="integer", nullable=true)
     */
    private $maxScenarios = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_ctrl", type="integer", nullable=true)
     */
    private $maxControls = 5;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getMaxProjects() {
        return $this->maxProjects;
    }

    public function setMaxProjects($maxProjects) {
        $this->maxProjects = $maxProjects;
    }

    public function getMaxScenarios() {
        return $this->maxScenarios;
    }

    public function setMaxScenarios($maxScenarios) {
        $this->maxScenarios = $maxScenarios;
    }

    public function getMaxControls() {
        return $this->maxControls;
    }

    public function setMaxControls($maxControls) {
        $this->maxControls = $maxControls;
    }
}