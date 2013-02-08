<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FFN\MonBundle\Entity\Control;
use \DateTime;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Description of GraphController
 *
 * @author frederic
 */
class GraphController extends Controller {

    public function showAction($control_id, $startTs = null , $stopTs = null) {
        
        $graphdata = array();
        
        // get the data from the DB
        $em = $this->getDoctrine()->getEntityManager();
        $captures_data = $em->getRepository('FFNMonBundle:Capture')
            ->findByIdAndTimeRange($control_id, $startTs, $stopTs);
        
        foreach ($captures_data as $cap) {
            
            $user = $this->get('security.context')->getToken()->getUser();
            
            if ($user === $cap->getControl()->getScenario()->getProject()->getUser()) {                
                array_push( $graphdata, $cap );
            } else {
                // TODO: trans
                throw new AccessDeniedException("Not allowed to see this graph");
            }
        }

        return $this->render('FFNMonBundle:Page:graph.html.twig', array(
                    'graphdata' => $graphdata,
                    'title' => "Control no $control_id"
        ));
    }
}