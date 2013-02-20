<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FFN\MonBundle\Entity\Control;
use FFN\MonBundle\Entity\ControlHeader;
use FFN\MonBundle\Entity\Project;
use FFN\MonBundle\Entity\Scenario;
use FFN\MonBundle\Entity\User;
use FFN\MonBundle\Entity\Weather;
use FFN\MonBundle\Form\ControlType;
use FFN\MonBundle\Form\ProjectType;
use FFN\MonBundle\Form\ScenarioType;
use FFN\MonBundle\Form\UserType;
use Doctrine\ORM\EntityManager;
use DateTime;
use DateInterval;

class DefaultController extends Controller {

    public function adminUserAction() {
        $em = $this->get('doctrine')->getManager();
        $users = $em->getRepository('FFN\MonBundle\Entity\User')->findAll();

        return $this->render('FFNMonBundle:Page:admin_user.html.twig', array(
                    'users' => $users,
                ));
    }

    public function adminUserActivationAction($id, $statut) {
        $isActive = false;
        $em = $this->get('doctrine')->getManager();
        //Control if parameters already exist
        $is_exist = $em->getRepository('FFN\MonBundle\Entity\User')->findOneById($id);
        if ($is_exist instanceof User) {
            if ($statut == 'true') {
                $is_exist->setEnabled(true);
                $isActive = true;
            } else {
                $is_exist->setEnabled(false);
            }
            $em->persist($is_exist);
            $em->flush();
        }
        return $this->render('FFNMonBundle:Page:is_user_active.html.twig', array(
                    'isActive' => $isActive
                ));
    }

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
                //Control if parameters already exist
                $is_exist = $em->getRepository('FFN\MonBundle\Entity\User')->findOneBy(array(
                    'username' => $username
                        ));
                if ($is_exist == false) {
                    $is_exist = $em->getRepository('FFN\MonBundle\Entity\User')->findOneBy(array(
                        'email' => $email
                            ));
                    if ($is_exist == false) {
                        $manipulator = $this->container->get('fos_user.util.user_manipulator');
                        $res = $manipulator->create($username, $password, $email, true, false);
                        if ($res instanceof User) {
                            $this->get('session')->setFlash('success_msg', $this->get('translator')->trans('mon_user_created'));
                            $em->persist($res);
                            $em->flush();
                        } else {
                            $this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_user_no_created'));
                        }
                    } else {
                        $this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_mail_already_used'));
                    }
                } else {
                    $this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_user_already_exist'));
                }
            }
        }

        return $this->render('FFNMonBundle:Page:admin_user_add.html.twig', array(
                    'form' => $form->createView(),
                ));
    }

    public function adminUserDeleteAction($id) {
        $em = $this->get('doctrine')->getManager();
        //Control if parameters already exist
        $is_exist = $em->getRepository('FFN\MonBundle\Entity\User')->findOneById($id);
        if ($is_exist instanceof User) {
            $em->remove($is_exist);
            $em->flush();
        }
        $is_exist = $em->getRepository('FFN\MonBundle\Entity\User')->findOneById($id);
        if ($is_exist instanceof User) {
            $this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_user_delete_failed'));
        } else {
            $this->get('session')->setFlash('success_msg', $this->get('translator')->trans('mon_user_delete_user_confirmed'));
        }
        return $this->adminUserAction();
    }

    public function adminUserEditAction($id) {
        echo 'TODO';
        return $this->render('FFNMonBundle:Page:home.html.twig', array(
                ));
    }

    public function controlAddAction($id) {
        $em = $this->get('doctrine')->getManager();
        $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($id);

        $project = $scenario->getProject();

        $control = New Control();

        $form = $this->createForm(new ControlType($this->get('translator')), $control);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $control->setConnectionTimeout(0);
                $control->setMimeType('html');
                $control->setResponseTimeout(0);
                $control->setScenario($scenario);

                $em->persist($control);
                $em->flush();

                $data = $form->getData();

                $this->get('session')->setFlash('success_msg', $this->get('translator')->trans('mon_control_creation_validated'));
                return $this->redirect($this->generateUrl('mon_scenario_home', array('id' => $id)));

            } else {
                //$this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_control_creation_failed'));
                $this->get('session')->setFlash('error_msg', $form->getErrorsAsString());
            }
        }
        return $this->render('FFNMonBundle:Page:control_add.html.twig', array(
                    'form' => $form->createView(),
                    'project' => $project,
                    'scenario' => $scenario,
                ));
    }

    public function homeAction() {
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $projects = $em->getRepository('FFN\MonBundle\Entity\Project')->findBy(array('user' => $user));

        $projects_weather = array();
        foreach ($projects as $project) {
            $project_weather  = $em->getRepository('FFN\MonBundle\Entity\Weather')->findOneBy(array('objectType'    => Weather::OBJECT_TYPE_PROJECT,
                                                                                                     'refIdObject'   => $project->getId()));
            $scenarii_weather = array();
            foreach ($project->getScenarios() as $scenario) {
                $scenario_weather  = $em->getRepository('FFN\MonBundle\Entity\Weather')->findOneBy(array('objectType'    => Weather::OBJECT_TYPE_SCENARIO,
                                                                                                         'refIdObject'   => $scenario->getId()));

                $scenarii_weather[$scenario->getId()] = $scenario_weather;
            }
            $projects_weather[$project->getId()] = array($project_weather, $scenarii_weather);
        }

        return $this->render('FFNMonBundle:Page:home.html.twig', array(
                    'projects' => $projects,
                    'projects_weather'  => $projects_weather));
    }

    public function projectAction($id) {
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($id);

        // Récupération de tous les scénarios associés au projet
        $scenarii = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findByProject($project);
        // Récupération de la météo des scénarii puis des contrôles
        $scenarii_weather = array();
        foreach ($scenarii as $scenario) {
            $scenario_weather  = $em->getRepository('FFN\MonBundle\Entity\Weather')->findOneBy(array('objectType'    => Weather::OBJECT_TYPE_SCENARIO,
                                                                                                     'refIdObject'   => $scenario->getId()));
           $controls_weather = array();
            foreach ($scenario->getControls() as $control) {
                $control_weather  = $em->getRepository('FFN\MonBundle\Entity\Weather')->findOneBy(array('objectType'    => Weather::OBJECT_TYPE_CONTROL,
                                                                                                        'refIdObject'   => $control->getId()));
                $controls_weather[$control->getId()] = $control_weather;
            }
            $scenarii_weather[$scenario->getId()] = array( $scenario_weather, $controls_weather);
        }

        return $this->render('FFNMonBundle:Page:project_home.html.twig', array(
                    'project'           => $project,
                    'scenarii'          => $scenarii,
                    'scenarii_weather'  => $scenarii_weather,
                ));
    }

    public function projectAddAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $project = New Project();
        $form = $this->createForm(new ProjectType($this->get('translator')), $project);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $project->setDateCreation(new \DateTime());
                $project->setEnabled(false);
                $project->setUser($user);
                $em->persist($project);
                $em->flush();
                $id = $project->getId();
                $this->get('session')->setFlash('success_msg', $this->get('translator')->trans('mon_project_creation_validated'));
                return $this->redirect($this->generateUrl('mon_project_home', array('id' => $id)));
            } else {
                //$this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_project_creation_failed'));
                $this->get('session')->setFlash('error_msg', $form->getErrorsAsString());
            }
        }
        return $this->render('FFNMonBundle:Page:project_add.html.twig', array(
                    'form' => $form->createView(),
                ));
    }

    // FS 20130125 : TODO
    public function projectEditAction($id) {
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($id);
        $form = $this->createForm(new ProjectType($this->get('translator')), $project);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $em->persist($project);
                $em->flush();
                $this->get('session')->setFlash('success_msg', $this->get('translator')->trans('mon_project_edition_validated'));
                return $this->redirect($this->generateUrl('mon_project_home', array('id' => $id)));
            } else {
                $this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_project_edition_failed'));
            }
        }
        return $this->render('FFNMonBundle:Page:project_edit.html.twig', array(
                    'form' => $form->createView(),
                    'project' => $project,
                ));
    }

    public function scenarioAction($id) {
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($id);
        $startTs = new DateTime();
        $stopTs = new DateTime();
        $startTs->sub(new DateInterval('PT12H'));
        $stopTs->add(new DateInterval('PT12H'));

        if ($scenario instanceof scenario) {
            // Recuperation du projet associe
            $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());
            // Récupération de tous les controles associés au Scenario
            $controls = $em->getRepository('FFN\MonBundle\Entity\Control')->findByScenario($scenario);
            $weather  = $em->getRepository('FFN\MonBundle\Entity\Weather')->findOneBy(array('objectType'    => Weather::OBJECT_TYPE_SCENARIO,
                                                                                            'refIdObject'   => $scenario->getId()));
        }

        return $this->render('FFNMonBundle:Page:scenario_home.html.twig', array(
                    'project'   => $project,
                    'scenario'  => $scenario,
                    'controls'  => $controls,
                    'weather'   => $weather->getWeatherState(),
                    'startTs'   => $startTs->format('Y-m-d H:i:s'),
                    'stopTs'    => $stopTs->format('Y-m-d H:i:s'),
                ));
    }

    public function scenarioAddAction($id) {
        $em = $this->get('doctrine')->getManager();
        $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($id);

        $scenario = New Scenario();
        $form = $this->createForm(new ScenarioType($this->get('translator')), $scenario);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $scenario->setDateCreation(new \DateTime());
                $scenario->setEnabled(false);
                $scenario->setFrequency(0);
                $scenario->setProject($project);
                $em->persist($scenario);
                $em->flush();
                $this->get('session')->setFlash('success_msg', $this->get('translator')->trans('mon_scenario_creation_validated'));
                return $this->redirect($this->generateUrl('mon_project_home', array('id' => $id)));
            } else {
                //$this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_scenario_creation_failed'));
                $this->get('session')->setFlash('error_msg', $form->getErrorsAsString());
            }
        }
        return $this->render('FFNMonBundle:Page:scenario_add.html.twig', array(
                    'form' => $form->createView(),
                    'project' => $project,
                ));
    }

    public function scenarioEditAction($id) {
        $em = $this->get('doctrine')->getManager();
        $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($id);
        if ($scenario instanceof scenario) {
            $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());
            $form = $this->createForm(new ScenarioType($this->get('translator')), $scenario);
            $request = $this->getRequest();
            if ($request->getMethod() == 'POST') {
                $form->bindRequest($request);
                if ($form->isValid()) {
                    $em->persist($scenario);
                    $em->flush();
                    $this->get('session')->setFlash('success_msg', $this->get('translator')->trans('mon_scenario_edit_validated'));
                } else {
                    $this->get('session')->setFlash('error_msg', $this->get('translator')->trans('mon_scenario_edit_failed'));
                }
            }
            return $this->render('FFNMonBundle:Page:scenario_edit.html.twig', array(
                        'form' => $form->createView(),
                        'project' => $project,
                        'scenario' => $scenario,
                    ));
        } else {
            return $this->redirect($this->generateUrl('mon_home'));
        }
    }

    public function scenarioDeleteAction($id) {
        $em = $this->get('doctrine')->getManager();
        $scenario = $em->getRepository('FFN\MonBundle\Entity\Scenario')->findOneById($id);
        if ($scenario instanceof scenario) {
            $project = $em->getRepository('FFN\MonBundle\Entity\Project')->findOneById($scenario->getProject()->getId());
            $em->remove($scenario);
            $em->flush();
            return $this->projectAction($project->getId());
        } else {
            return $this->redirect($this->generateUrl('mon_home'));
        }
    }

}
