<?php

/**
 * RegistrationFormHandler: Overrides FOS UserBundle registration logic
 *
 * @author frederic
 */

namespace FFN\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserInterface;
use FFN\MonBundle\Model\User as UserModel;

class RegistrationFormHandler extends BaseHandler
{
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        // Note: if you plan on modifying the user then do it before calling the
        // parent method as the parent method will flush the changes      
        
        parent::onSuccess($user, $confirmation);

        // otherwise add your functionality here
    }
}
