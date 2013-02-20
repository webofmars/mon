<?php

namespace FFN\MonBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FFN\MonBundle\Entity\Project;

/**
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
     * @ORM\OneToMany(targetEntity="Project", mappedBy="user", cascade={"persist"})
     */
    protected $projects;

    /**
     * @ORM\ManyToOne(targetEntity="Subscription")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     */
    private $subscription;

    public function __construct() {
        parent::__construct();
        // your own logic
        $this->projects = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

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
    
    public function getAllScenarii() {
        $scList = new ArrayCollection();
        foreach ($this->projects as $project) {
            $scList->add($project->getScenarios());
        }
        return $scList;
    }
    
    public function getAllControls() {
        $ctrlList = new ArrayCollection();
        foreach ($this->getScenarii() as $sc) {
            $ctrlList->add($sc->getControls());
        }
        return $ctrlList;
    }
}