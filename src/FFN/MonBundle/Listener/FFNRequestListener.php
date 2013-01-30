<?php

namespace FFN\MonBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * FFNRequestListener
 *
 * @author Frederic Leger <leger.frederic@gmail.com>
 */
class FFNRequestListener {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        // cas des sous requêtes
        if (HttpKernel::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        // si pas de session deja definie
        if (!$this->container->has('session')) {
            $session = new Session();
            $session->start();
            $this->container->set('session', $session);
        }

        // accept-language dans la requete
        $userLocale = $event->getRequest()->getPreferredLanguage(array('fr', 'en'));
        
        // si pas de locale definie
        if ( !$this->container->get('session')->has('_locale') or $this->container->get('session')->get('_locale') != $userLocale) {
            $event->getRequest()->setLocale($userLocale);
            $this->container->get('session')->set('_locale', $userLocale);
        }
        return;
    }

}
