<?php

namespace FFN\MonBundle\Controller;

// to use the common method 'verifyProjectAccess'
use FFN\MonBundle\Controller\DefaultController;

use FFN\MonBundle\Entity\Project as ProjectEntity;
use FFN\MonBundle\Model\Project as ProjectModel;
use FFN\MonBundle\Entity\Weather;
use FFN\MonBundle\Form\ProjectType;

/**
 * Description of ProjectController
 *
 * @author Fabien Somnier <fabien.somnier@buongiorno.com>
 */
class ProjectController extends DefaultController {

  /**
   * Display project page (existing scenarios list)
   */
  public function projectAction($id) {
    $em = $this->get('doctrine')->getManager();

    // initiate project entity
    $project_entity = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($id);
    if (!($project_entity instanceof ProjectEntity)) {
      // project identifier unknow : redirect to home
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_project_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project_entity);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    // initiate associated project model
    $project_model = new ProjectModel($em);
    $project_model->setIdProject($project_entity->getId());
    $project_model->hydrate();

    return $this->render('FFNMonBundle:Page:Project\project_home.html.twig', array(
                'project' => $project_model,
                'scenarios' => $project_model->getScenarios(),
            ));
  }

  /**
   * Add a new project and redirect to its page, or display project creation form
   */
  public function projectAddAction() {

    // get user
    $user = $this->get('security.context')->getToken()->getUser();

    // initiate project entity to initiate form
    $project = new ProjectEntity();
    $form = $this->createForm(new ProjectType($this->get('translator')), $project);
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bind($request);
      // check if form is filled & valid, and if so create the new Project, and redirect to its page
      if ($form->isValid()) {
        $em = $this->get('doctrine')->getManager();
        $project->setDateCreation(new \DateTime());
        $project->setEnabled(false);
        $project->setUser($user);
        $em->persist($project);
        $em->flush();
        $weather = new Weather();
        $weather->setObjectType(Weather::OBJECT_TYPE_PROJECT);
        $weather->setRefIdObject($project->getId());
        $weather->setWeatherState(Weather::WEATHER_UNKNOWN);
        $em->persist($weather);
        $em->flush();
        $id = $project->getId();
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_project_creation_validated'));
        return $this->redirect($this->generateUrl('mon_project_home', array('id' => $id)));
      } else {
        $this->get('session')->getFlashBag()->add('error', $form->getErrorsAsString());
      }
    }

    return $this->render('FFNMonBundle:Page:Project\project_add.html.twig', array(
                'form' => $form->createView(),
            ));
  }

  /**
   * Edit a project and redirect to its page, or display project edition form
   * @param integer $id - project identifier
   */
  public function projectEditAction($id) {
    $em = $this->get('doctrine')->getManager();

    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($id);
    if (!($project instanceof ProjectEntity)) {
      // project identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_project_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    $form = $this->createForm(new ProjectType($this->get('translator')), $project);
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bind($request);
      // check if form is valid, and if so edit the Project, and redirect to its page
      if ($form->isValid()) {
        $em = $this->get('doctrine')->getManager();
        $em->persist($project);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_project_edition_validated'));
        return $this->redirect($this->generateUrl('mon_project_home', array('id' => $id)));
      } else {
        $this->get('session')->getFlashBag()->add('error', $form->getErrorsAsString());
      }
    }

    return $this->render('FFNMonBundle:Page:Project\project_edit.html.twig', array(
                'form' => $form->createView(),
                'project' => $project,
            ));
  }

  /**
   * Delete a project and redirect to home page
   * @param integer $id - project identifier
   */
  public function projectDeleteAction($id) {
    $em = $this->get('doctrine')->getManager();

    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($id);
    if (!($project instanceof ProjectEntity)) {
      // project identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_project_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    // remove project
    $em->remove($project);
    $em->flush();

    // home page redirection
    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_project_deletion_validated'));

    return $this->redirect($this->generateUrl('mon_home'));
  }

}

?>
