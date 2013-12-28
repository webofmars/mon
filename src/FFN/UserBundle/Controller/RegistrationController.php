<?php

/*
 * This file overrides the original FOSUserBundle registration controller.
 * @author: frederic leger
 */

namespace FFN\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

/**
 * Overrides the original FOS UserBundle controller managing the registration
 *
 * @author Frederic Leger <webofmars@gmail.com>
 */
class RegistrationController extends BaseController
{
    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        $em = $this->container->get('doctrine')->getManager();
        $defaultSubscription = $em->getRepository('FFNMonBundle:Subscription')->findOneByName('free user');
        
        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();
                        
            // This is why we want to override this controller i.e setting the default subscription
            if (!$defaultSubscription) {
              throw $this->createNotFoundException('The default subscription can\'t be associated with your account');
            }
            else {
              $user->setSubscription($defaultSubscription);
              $em->persist($user);
              $em->flush();
              $this->container->get('logger')->info(
                sprintf('Assigning default subscription on the fly to new user. user %s -> subscription %s', 
                        $user->getEmail(), $defaultSubscription->getName()));
            }
                        
            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $authUser = true;
                $route = 'fos_user_registration_confirmed';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }

    protected function getEngine()
    {
        return parent::getEngine();
    }
}
