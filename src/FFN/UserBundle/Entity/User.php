<?php

namespace FFN\UserBundle\Entity;

use FFN\MonBundle\Entity\Project;
use FFN\MonBundle\Entity\Scenario;
use FFN\MonBundle\Entity\Control;
use FFN\MonBundle\Entity\Subscription;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 * @author Frederic Leger <leger.frederic@gmail.com>
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="FFN\MonBundle\Entity\Subscription")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     */
    protected $subscription;

    /**
     * @ORM\OneToMany(targetEntity="FFN\MonBundle\Entity\Project", mappedBy="user", cascade={"persist"})
     */
    protected $projects;

    /**
     * Add projects
     *
     * @param Project $projects
     * @return User
     */
    public function addProject(Project $projects) {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param Project $projects
     */
    public function removeProject(Project $projects) {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return Collection
     */
    public function getProjects() {
        return $this->projects;
    }

    public function getSubscription() {
        return $this->subscription;
    }

    public function setSubscription($subscription) {
        $this->subscription = $subscription;
    }

    public function getAllScenarios() {
        $scList = new ArrayCollection();
        foreach ($this->projects as $project) {
            $scList->add($project->getScenarios());
        }
        return $scList;
    }

    public function getAllControls() {
        $ctrlList = new ArrayCollection();
        foreach ($this->getAllScenarios() as $sc) {
            // TOFIX not working on ArrayCols
            //$ctrlList->add($sc->getControls());
        }
        return $ctrlList;
    }

    public function __construct() {
        parent::__construct();
        $this->subscription = 1;
        $this->projects = new ArrayCollection();
    }

}
