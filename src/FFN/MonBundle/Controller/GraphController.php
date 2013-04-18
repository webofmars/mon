<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FFN\MonBundle\Entity\Project as ProjectEntity;
use FFN\MonBundle\Entity\Scenario as ScenarioEntity;
use FFN\MonBundle\Entity\Control as ControlEntity;
use \DateTime;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Description of GraphController
 *
 * @author frederic
 */
class GraphController extends Controller {

  public function showAction($control_id, $startTs = null, $stopTs = null) {
    $em = $this->get('doctrine')->getManager();

    // get related control/scenario/project entities for breadcrumb
    $control = $em->getRepository('FFN\MonBundle\Entity\Control')->findOneById($control_id);
    if ($control instanceof ControlEntity) {
      $scenario = $control->getScenario();
      $project = $scenario->getProject();
      $title = $control->getName();
    } else {
      // control identifier unknow
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_control_unknown'));
      return $this->redirect($this->generateUrl('mon_home'));
    }

    // get graphic data from DB
    $graphdata = array();
    $captures_data = $em->getRepository('FFN\MonBundle\Entity\Capture')->findByIdAndTimeRange($control_id, $startTs, $stopTs);
    if (count($captures_data) > 0) {
      foreach ($captures_data as $capture) {
        $user = $this->get('security.context')->getToken()->getUser();
        if ($user === $capture->getControl()->getScenario()->getProject()->getUser()) {
          array_push($graphdata, $capture);
          if (!($control instanceof ControlEntity)) {
            $control = $capture->getControl();
            $scenario = $control->getScenario();
            $project = $scenario->getProject();
            $title = $control->getName();
          }
        } else {
          // TODO: trans
          throw new AccessDeniedException("Not allowed to see this graph");
        }
      }
    } else {
      $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('mon_graph_empty'));
      return $this->render('FFNMonBundle:Page:Control\graph_empty.html.twig', array(
                  'project' => $project,
                  'scenario' => $scenario,
                  'control' => $control,
          ));
    }

    return $this->render('FFNMonBundle:Page:Control\graph.html.twig', array(
                'graphdata' => $graphdata,
                'title' => $title,
                'project' => $project,
                'scenario' => $scenario,
                'control' => $control,
        ));
  }

}