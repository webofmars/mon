<?php

namespace FFN\MonBundle\Controller;

// to use the common method 'verifyProjectAccess'
use FFN\MonBundle\Controller\DefaultController;

use FFN\MonBundle\Entity\Project as ProjectEntity;
use FFN\MonBundle\Entity\Scenario as ScenarioEntity;
use FFN\MonBundle\Model\Scenario as ScenarioModel;
use FFN\MonBundle\Entity\Weather;
use FFN\MonBundle\Form\ScenarioType;
use DateTime;
use DateInterval;

/**
 * Description of ScenarioController
 *
 * @author Fabien Somnier <fabien.somnier@buongiorno.com>
 */
class ScenarioController extends DefaultController {

  /**
   * Display scenario page (existing controls list)
   * @param integer $id - scenario identifier
   */
  public function scenarioAction($id) {
    $em = $this->get('doctrine')->getManager();

    // initiate scenario entity
    $scenario_entity = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($id);
    if (!($scenario_entity instanceof ScenarioEntity)) {
      // scenario identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_scenario_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    // initiate associated scenario model
    $scenario_model = new ScenarioModel($em);
    $scenario_model->setIdScenario($scenario_entity->getId());
    $scenario_model->hydrate();

    // get related project (entity)
    $project_entity = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario_entity->getProject()->getId());
    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project_entity);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    // initiate useful display parameters
    $startTs = new DateTime();
    $stopTs = new DateTime();
    $startTs->sub(new DateInterval('PT12H'));
    $stopTs->add(new DateInterval('PT12H'));

    return $this->render('FFNMonBundle:Page:Scenario\scenario_home.html.twig', array(
                'project' => $project_entity,
                'scenario' => $scenario_model,
                'controls' => $scenario_model->getControls(),
                'startTs' => $startTs->format('Y-m-d H:i:s'),
                'stopTs' => $stopTs->format('Y-m-d H:i:s'),
            ));
  }

  /**
   * Add a new scenario and redirect to its page, or display scenario creation form
   * @param integer $id - related project identifier
   */
  public function scenarioAddAction($id) {
    $em = $this->get('doctrine')->getManager();

    // get related project
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

    $scenario = New ScenarioEntity();
    $form = $this->createForm(new ScenarioType($this->get('translator')), $scenario);
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bindRequest($request);
      // check if form is valid, and if so create the new Scenario, and redirect to its page
      if ($form->isValid()) {
        $em = $this->get('doctrine')->getManager();
        $scenario->setDateCreation(new \DateTime());
        $scenario->setEnabled(false);
        $scenario->setFrequency(0);
        $scenario->setProject($project);
        $em->persist($scenario);
        $em->flush();
        $weather = new Weather();
        $weather->setObjectType(Weather::OBJECT_TYPE_SCENARIO);
        $weather->setRefIdObject($scenario->getId());
        $weather->setWeatherState(Weather::WEATHER_UNKNOWN);
        $em->persist($weather);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_scenario_creation_validated'));
        return $this->redirect($this->generateUrl('mon_scenario_home', array('id' => $scenario->getId())));
      } else {
        $this->get('session')->getFlashBag()->add('error', $form->getErrorsAsString());
      }
    }

    return $this->render('FFNMonBundle:Page:Scenario\scenario_add.html.twig', array(
                'form' => $form->createView(),
                'project' => $project,
            ));
  }

  /**
   * Edit a scenario and redirect to its page, or display scenario edition form
   * @param integer $id - scenario identifier
   */
  public function scenarioEditAction($id) {
    $em = $this->get('doctrine')->getManager();

    $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($id);
    if (!($scenario instanceof ScenarioEntity)) {
      // scenario identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_scenario_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());
    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    $form = $this->createForm(new ScenarioType($this->get('translator')), $scenario);
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bindRequest($request);
      // check if form is valid, and if so edit Scenario, and redirect to its page
      if ($form->isValid()) {
        $em->persist($scenario);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_scenario_edition_validated'));
        return $this->redirect($this->generateUrl('mon_scenario_home', array('id' => $id)));
      } else {
        $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_scenario_edition_failed'));
      }
    }

    return $this->render('FFNMonBundle:Page:Scenario\scenario_edit.html.twig', array(
                'form' => $form->createView(),
                'project' => $project,
                'scenario' => $scenario,
            ));
  }

  /**
   * Delete a scenario and redirect to its project page
   * @param integer $id - scenario identifier
   */
  public function scenarioDeleteAction($id) {
    $em = $this->get('doctrine')->getManager();

    $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($id);
    if (!($scenario instanceof ScenarioEntity)) {
      // scenario identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_scenario_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    // get related project for control and final redirection
    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());

    // control that user is allowed to access to related project
    $verifyProject = $this->verifyProjectAccess($project);
    if ($verifyProject !== true) {
      return $verifyProject;
    }

    // remove scenario
    $em->remove($scenario);
    $em->flush();

    // project page redirection
    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_scenario_deletion_validated'));

    return $this->redirect($this->generateUrl('mon_project_home', array('id' => $project->getId())));
  }

}

?>
