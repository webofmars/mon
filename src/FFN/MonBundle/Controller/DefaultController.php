<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FFN\MonBundle\Entity\User;

class DefaultController extends Controller
{
  public function adminUserAction(){
    $em = $this->get('doctrine')->getEntityManager();
    $users = $em->getRepository('FFN\MonBundle\Entity\User')->findAll();
    
    return $this->render('FFNMonBundle:Page:admin_user.html.twig', array(
      'users' => $users,
    ));
  }
  
  public function adminUserAddAction(){
    return $this->render('FFNMonBundle:Page:home.html.twig', array(
    ));
  }
  
  public function adminUserDelete($id){
    echo 'TODO';
    return $this->render('FFNMonBundle:Page:home.html.twig', array(
    ));
  }
    
  public function adminUserEditAction($id){
    echo 'TODO';
    return $this->render('FFNMonBundle:Page:home.html.twig', array(
    ));
  }
  
  public function homeAction(){
    echo 'TODO';
    return $this->render('FFNMonBundle:Page:home.html.twig', array(
    ));
  }
}
