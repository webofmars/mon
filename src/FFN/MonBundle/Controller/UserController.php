<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FFN\UserBundle\Entity\User;

/**
 * UserController
 *
 * @author Frederic Leger <leger.frederic@gmail.com>
 */
class UserController extends Controller {

  /**
   * Display admin page (existing users list)
   */
  public function adminUserAction() {
    $em = $this->get('doctrine')->getManager();
    // list all existing users
    $users = $em->getRepository('FFN\UserBundle\Entity\User')->findAll();
    // display existing users list
    return $this->render('FFNMonBundle:Page:Admin\admin_user.html.twig', array(
                'users' => $users,
            ));
  }

  /**
   * Activate/Desactivate user and display new status (true/false)
   * @param integer $id - user identifier
   * @param string $statut - new user activation status ('true' or 'false')
   */
  public function adminUserActivationAction($id, $statut) {
    $em = $this->get('doctrine')->getManager();
    // Control if user exists
    $user = $em->getRepository('FFN\UserBundle\Entity\User')->findOneById($id);
    $isActive = false;
    if ($user instanceof User) {
      if ($statut == 'true') {
        $user->setEnabled(true);
        $isActive = true;
      } else {
        $user->setEnabled(false);
      }
      $em->persist($user);
      $em->flush();
    }
    return $this->render('FFNMonBundle:Page:Admin\is_user_active.html.twig', array(
                'isActive' => $isActive
            ));
  }

  /**
   * Create a new user, and/or Display new user form
   */
  public function adminUserAddAction() {
    $em = $this->get('doctrine')->getManager();
    $user = new User();
    $form = $this->createForm(new UserType($this->get('translator')), $user);
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bind($request);
      if ($form->isValid()) {
        $user_request_params = $request->get('user');
        $username = $user_request_params['username'];
        $password = $user_request_params['password'];
        $email = $user_request_params['email'];
        // Control if user already exists
        $is_exist = $em->getRepository('FFN\UserBundle\Entity\User')->findOneBy(array('username' => $username));
        if ($is_exist == false) {
          $is_exist = $em->getRepository('FFN\UserBundle\Entity\User')->findOneBy(array('email' => $email));
          if ($is_exist == false) {
            $manipulator = $this->container->get('fos_user.util.user_manipulator');
            $res = $manipulator->create($username, $password, $email, true, false);
            if ($res instanceof User) {
              $subscription = $em->getRepository('FFN\MonBundle\Entity\Subscription')->find(1);
              $res->setSubscription($subscription);
              $em->persist($res);
              $em->flush();
              $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_user_created'));
            } else {
              $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_user_no_created'));
            }
          } else {
            $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_mail_already_used'));
          }
        } else {
          $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_user_already_exist'));
        }
      }
    }

    return $this->render('FFNMonBundle:Page:Admin\admin_user_add.html.twig', array(
                'form' => $form->createView(),
            ));
  }

  /**
   * Delete a user, and redirect to users list
   */
  public function adminUserDeleteAction($id) {
    $em = $this->get('doctrine')->getManager();
    // Control if user exists, and if so delete user
    $user = $em->getRepository('FFN\UserBundle\Entity\User')->findOneById($id);
    if ($user instanceof User) {
      $em->remove($user);
      $em->flush();
    }
    // Control if deletion has worked
    $user = $em->getRepository('FFN\UserBundle\Entity\User')->findOneById($id);
    if ($user instanceof User) {
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_user_delete_failed'));
    } else {
      $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_user_delete_user_confirmed'));
    }
    return $this->redirect($this->generateUrl('mon_admin_user'));
  }

  /**
   * Edit a user (TODO)
   */
  public function adminUserEditAction($id) {
    echo 'TODO';
    return $this->render('FFNMonBundle:Page:home.html.twig', array(
            ));
  }

}
