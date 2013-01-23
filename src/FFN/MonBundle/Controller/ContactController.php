<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller {
    
    public function showAction($culture) {
        return $this->render("FFNMonBundle::contact.$culture.html.twig");
    }
    
}
