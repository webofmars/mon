<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FAQController extends Controller {

  public function showAction() {

    $culture = $this->getRequest()->getLocale('cz');
    return $this->render("FFNMonBundle::FAQ.$culture.html.twig");
  }

}
