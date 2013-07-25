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

        $subscription = $this->user->getSubscription();

        if ($object instanceof Project) {

            if (is_null($subscription)) {
                $this->context->addViolation("ffn.mon.unknown_subscription");
                return;
            }

            if (!is_null($subscription->getMaxProjects()) && count($this->user->getProjects()) >= $subscription->getMaxProjects()) {

                $this->context->addViolation();
            }
        }

        if ($object instanceof Scenario) {
            if (!is_null($subscription->getMaxScenarios()) && count($this->user->getAllScenarios()) >= $subscription->getMaxScenarios()) {

                $this->context->addViolation('You have reached the maximum limit of scenarios.' .
                        'Your current subscription is allowing you up to '
                        . $subscription->getMaxScenarios() . ' scenarios'
                        . ' (' . count($this->user->getAllScenarios()) . ').', array(), null);
            }
        }

        if ($object instanceof Control) {
            if (!is_null($subscription->getMaxControls()) && count($this->user->getAllControls()) >= $subscription->getMaxControls()) {

                $this->context->addViolation('You have reached the maximum limit of controls. you subscription is allowing you up to '
                        . $subscription->getMaxControls() . ' controls.', array(), null);
            }
        }
    }

}