<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller {

  public function showAction() {

    $language = substr($this->getRequest()->getLocale(),0,2);

    return $this->render("FFNMonBundle::about.$language.html.twig");
  }

}
