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
    }
}
