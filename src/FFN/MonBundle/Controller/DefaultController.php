<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FFN\MonBundle\Model\Project as ProjectModel;
use FFN\MonBundle\Entity\Project as ProjectEntity;

class DefaultController extends Controller {

  /**
   * Display home page (existing projects list)
   */
  public function homeAction() {
    $em = $this->get('doctrine')->getManager();
    // get user and his/her projects list
    $user = $this->get('security.context')->getToken()->getUser();
    if ($user->hasRole('ROLE_SUPER_ADMIN')) {
      $project_entities = $em->getRepository('FFN\MonBundle\Entity\Project')->findAll();
      $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('mon_logged_as_admin'));
    }
    else {
      $project_entities = $em->getRepository('FFN\MonBundle\Entity\Project')->findBy(array('user' => $user));
    }

    // TODO : implement case of no projects found
    // initiate and hydrate project models
    $project_models = array();
    $project_key = 0;
    foreach ($project_entities as $project_entity) {
      $project_models[$project_key] = new ProjectModel($em);
      $project_models[$project_key]->setIdProject($project_entity->getId());
      $project_models[$project_key]->hydrate();
      $project_key++;
    }

    return $this->render('FFNMonBundle:Page:home.html.twig', array(
                'projects' => $project_models));
  }

  /**
   * Control if current user can access to given project
   * @param ProjectEntity $project - project to control
   * @return true|redirect
   */
  public function verifyProjectAccess(ProjectEntity $project) {
    $user = $this->get('security.context')->getToken()->getUser();
    if ( ($user !== $project->getUser()) and (!$user->hasRole('ROLE_SUPER_ADMIN')) ) {
      // no right to access to this project
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_project_denied'));
      return $this->redirect($this->generateUrl('mon_home'));
    }
    return true;
  }

}
