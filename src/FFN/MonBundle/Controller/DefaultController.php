<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

  public function homeAction(){
    return $this->render('FFNMonBundle:Page:home.html.twig', array(
    ));
  }
  
  public function userAddAction(){
    echo 'TODO';
    return $this->render('FFNMonBundle:Page:home.html.twig', array(
    ));
  }
  
  public function userManageAction(){
    echo 'TODO';
    return $this->render('FFNMonBundle:Page:home.html.twig', array(
    ));
  }
}
