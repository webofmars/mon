<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller {
    
    public function showAction() {
        
        $culture = $this->getRequest()->getLocale('en');
        
        return $this->render("FFNMonBundle::about.$culture.html.twig");
    }
    
}
