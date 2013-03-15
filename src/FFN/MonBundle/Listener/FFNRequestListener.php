<?php

namespace FFN\MonBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Router;

/**
 * FFNRequestListener
 *
 * @author Frederic Leger <leger.frederic@gmail.com>
 */
class FFNRequestListener {

    private $container;
    private $router;
    
    public function __construct(ContainerInterface $container, Router $router) {
        $this->container = $container;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
                
        /*
        $route  = $this->container->get('request')->get('_route');
         
        $home =  'mon_home';
        $url = $this->router->generate($route, array(), true);
        $homeUrl = $this->router->generate($home, array(), true);
        $token =  $event->getRequest()->attributes->get('auth_token');
        
        // si on a demandé la home de base on fait un redirect sur la version localisée
        if ($homeUrl == $url) {
            $url = $this->router->generate('mon_home_i18n');
            $this->container->get('logger')->info('- Redirectiong to localized home: '.$url);
            $response = new RedirectResponse($url);
            $response->attributes->set('auth_token', $token);
            $event->setResponse($response);
        }
       * 
       */
    }
}
