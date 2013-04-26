<?php

namespace FFN\MonBundle\Controller;

// to use the common method 'verifyProjectAccess'
use FFN\MonBundle\Controller\DefaultController;

use FFN\MonBundle\Entity\Scenario as ScenarioEntity;
use FFN\MonBundle\Entity\Control as ControlEntity;
use FFN\MonBundle\Entity\Weather;
use FFN\MonBundle\Form\ControlType;

/**
 * Description of ControlController
 *
 * @author Fabien Somnier <fabien.somnier@buongiorno.com>
 */
class ControlController extends DefaultController {

  /**
   * Add a new control and redirect to scenario page, or display control creation form
   * @param integer $id - scenario identifier
   */
  public function controlAddAction($id) {
    $em = $this->get('doctrine')->getManager();

    // get scenario entity
    $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($id);
    if (!($scenario instanceof ScenarioEntity)) {
      // scenario identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_scenario_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }
    $project = $scenario->getProject();

    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    $control = new ControlEntity();
    $form = $this->createForm(new ControlType($this->get('translator')), $control);
    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
      $form->bindRequest($request);
      // check if form is valid, and if so create the new Control, and redirect to scenario
      if ($form->isValid()) {
        $em = $this->get('doctrine')->getManager();
        $control->setConnectionTimeout(0);
        $control->setMimeType('html');
        $control->setResponseTimeout(0);
        $control->setScenario($scenario);
        $em->persist($control);
        $em->flush();
        $weather = new Weather();
        $weather->setObjectType(Weather::OBJECT_TYPE_CONTROL);
        $weather->setRefIdObject($control->getId());
        $weather->setWeatherState(Weather::WEATHER_UNKNOWN);
        $em->persist($weather);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_control_creation_validated'));
        return $this->redirect($this->generateUrl('mon_scenario_home', array('id' => $id)));
      } else {
        $this->get('session')->getFlashBag()->add('error', $form->getErrorsAsString());
      }
    }

    return $this->render('FFNMonBundle:Page:Control\control_add.html.twig', array(
                'form' => $form->createView(),
                'project' => $project,
                'scenario' => $scenario,
            ));
  }

  /**
   * Edit a control and redirect to its page, or display control edition form
   * @param integer $id - control identifier
   */
  public function controlEditAction($id) {
    $em = $this->get('doctrine')->getManager();

    $control = $em->getRepository('FFN\MonBundle\Entity\Control')->findOneById($id);
    if (!($control instanceof ControlEntity)) {
      // control identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_control_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($control->getScenario()->getId());
    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());

    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    $form = $this->createForm(new ControlType($this->get('translator')), $control);
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bindRequest($request);
      // check if form is valid, and if so edit Control, and redirect to its scenario page
      if ($form->isValid()) {
        $em->persist($control);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_control_edition_validated'));
        return $this->redirect($this->generateUrl('mon_scenario_home', array('id' => $scenario->getId())));
      } else {
        $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_control_edition_failed'));
      }
    }

    return $this->render('FFNMonBundle:Page:Control\control_edit.html.twig', array(
                'form' => $form->createView(),
                'project' => $project,
                'scenario' => $scenario,
                'control' => $control,
            ));
  }

  /**
   * Delete a control and redirect to its scenario page
   * @param integer $id - control identifier
   */
  public function controlDeleteAction($id) {
    $em = $this->get('doctrine')->getManager();

    $control = $em->getRepository('FFN\MonBundle\Entity\Control')->findOneById($id);
    if (!($control instanceof ControlEntity)) {
      // control identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_control_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    // get related scenario for final redirection
    $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($control->getScenario()->getId());
    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());

    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    // remove control
    $em->remove($control);
    $em->flush();

    // scenario page redirection
    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_control_deletion_validated'));
    return $this->redirect($this->generateUrl('mon_scenario_home', array('id' => $scenario->getId())));
  }

}

?>
