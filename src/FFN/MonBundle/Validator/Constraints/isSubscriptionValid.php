<?php

namespace FFN\MonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * isSubscriptionValid
 */
class isSubscriptionValid extends Constraint {

    public $message = 'This is not authorized by your subscription level: ';

    // Permet de creer un validateur de classe et non de propriete
    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

    // nom du service qui fera la validation
    public function validatedBy() {
        return 'is_subscription_valid';
    }
}
