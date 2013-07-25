<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller {

  public function showAction() {
    $language = substr($this->getRequest()->getLocale(),0,2);
    return $this->render("FFNMonBundle::contact.$language.html.twig");
  }

}
