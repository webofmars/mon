<?php

namespace FFN\MonBundle\Validator\Constraints;

use \FFN\MonBundle\Entity\Project;
use \FFN\MonBundle\Entity\Scenario;
use \FFN\MonBundle\Entity\Control;
use \Symfony\Component\Validator\Constraint;
use \Symfony\Component\Validator\ConstraintValidator;
use \Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * isSubscriptionValidValidator
 */
class isSubscriptionValidValidator extends ConstraintValidator {

    public $securityContext;
    private $user;

    public function __construct(SecurityContextInterface $securityContext) {
        $this->securityContext = $securityContext;
        $this->user = $this->securityContext->getToken()->getUser();
    }

    // fait la validation
    public function validate($object, Constraint $constraint) {

        if ($object instanceof Project) {
            if (count($this->user->getProjects()) >= $this->user->getSubscription()->getMaxProjects()) {
                                
                $this->context->addViolation('You have reached the maximum limit of projects. you subscription is allowing you up to '                        
                        . $this->user->getSubscription()->getMaxProjects() . ' projects.');
            }
        }
        
        if ($object instanceof Scenario) {
            if (count($this->user->getAllScenarii()) >= $this->user->getSubscription()->getMaxScenarii()) {
                                
                $this->context->addViolation('You have reached the maximum limit of scenarii.'.
                                             'Your current subscription is allowing you up to '                        
                                             . $this->user->getSubscription()->getMaxScenarii() . ' scenarii'
                                            .' ('.count($this->user->getAllScenarii()).').');
                
            }
        }
        
        if ($object instanceof Control) {
            if (count($this->user->getAllControls()) >= $this->user->getSubscription()->getMaxControls()) {
                                
                $this->context->addViolation('You have reached the maximum limit of controls. you subscription is allowing you up to '                        
                        . $this->user->getSubscription()->getMaxControls() . ' controls.');
            }
        }
    }

}