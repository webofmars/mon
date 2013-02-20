<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FAQController extends Controller {
    
    public function showAction() {
        
        $culture = $this->getRequest()->getLocale('en');
        return $this->render("FFNMonBundle::FAQ.$culture.html.twig");
    }
    
}
