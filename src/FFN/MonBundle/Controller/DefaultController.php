<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FFN\MonBundle\Entity\User;
use FFN\MonBundle\Entity\Project as ProjectEntity;
use FFN\MonBundle\Model\Project as ProjectModel;
use FFN\MonBundle\Entity\Scenario as ScenarioEntity;
use FFN\MonBundle\Model\Scenario as ScenarioModel;
use FFN\MonBundle\Entity\Control as ControlEntity;
use FFN\MonBundle\Entity\Weather;
use FFN\MonBundle\Form\ControlType;
use FFN\MonBundle\Form\ProjectType;
use FFN\MonBundle\Form\ScenarioType;
use FFN\MonBundle\Form\UserType;
use DateTime;
use DateInterval;

class DefaultController extends Controller {

  /**
   * Display admin page (existing users list)
   */
  public function adminUserAction() {
    $em = $this->get('doctrine')->getManager();
    // list all existing users
    $users = $em->getRepository('FFN\MonBundle\Entity\User')->findAll();
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
    $user = $em->getRepository('FFN\MonBundle\Entity\User')->findOneById($id);
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
      $form->bindRequest($request);
      if ($form->isValid()) {
        $user_request_params = $request->get('user');
        $username = $user_request_params['username'];
        $password = $user_request_params['password'];
        $email = $user_request_params['email'];
        // Control if user already exists
        $is_exist = $em->getRepository('FFN\MonBundle\Entity\User')->findOneBy(array(
            'username' => $username));
        if ($is_exist == false) {
          $is_exist = $em->getRepository('FFN\MonBundle\Entity\User')->findOneBy(array(
              'email' => $email));
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
    $user = $em->getRepository('FFN\MonBundle\Entity\User')->findOneById($id);
    if ($user instanceof User) {
      $em->remove($user);
      $em->flush();
    }
    // Control if deletion has worked
    $user = $em->getRepository('FFN\MonBundle\Entity\User')->findOneById($id);
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
    // TODO : check that user can access to this control

    $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($control->getScenario()->getId());
    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());
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
    // TODO : check that user can access to this control
    // get related scenario for final redirection
    $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($control->getScenario()->getId());

    // remove control
    $em->remove($control);
    $em->flush();
    // scenario page redirection
    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_control_deletion_success'));
    return $this->redirect($this->generateUrl('mon_scenario_home', array('id' => $scenario->getId())));
  }

  /**
   * Display home page (existing projects list)
   */
  public function homeAction() {
    $em = $this->get('doctrine')->getManager();
    // get user and his/her projects list
    $user = $this->get('security.context')->getToken()->getUser();
    $project_entities = $em->getRepository('FFN\MonBundle\Entity\Project')->findBy(array('user' => $user));

    // TODO : implement case of none projects
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

    // TODO : check that user can access to this project
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
      $form->bindRequest($request);
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

    // TODO : check that user can access to this project

    $form = $this->createForm(new ProjectType($this->get('translator')), $project);
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bindRequest($request);
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
    if (!($project instanceof ScenarioEntity)) {
      // project identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_project_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    // TODO : check that user can access to this project
    // remove project
    $em->remove($project);
    $em->flush();
    // home page redirection
    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_project_deletion_success'));
    return $this->redirect($this->generateUrl('mon_home'));
  }

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

    // TODO : check that user can access to this scenario
    // get related project (entity)
    $project_entity = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario_entity->getProject()->getId());

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
    // TODO : check that user can access to this scenario

    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());
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
    // TODO : check that user can access to this scenario
    // get related project for final redirection
    $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());

    // remove scenario
    $em->remove($scenario);
    $em->flush();
    // project page redirection
    $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mon_scenario_deletion_success'));
    return $this->redirect($this->generateUrl('mon_project_home', array('id' => $project->getId())));
  }

}
