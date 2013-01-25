<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FFN\MonBundle\Entity\Control;
use \DateTime;

/**
 * Description of GraphController
 *
 * @author frederic
 */
class GraphController extends Controller {

    public function showAction( $control_id ) {

       /* if (is_null($start)) {
           $start = new DateTime(); // now
       }
       
       if (is_null($end)) {
           $end = new DateTime(); // now
       }*/
        
        // get the data from the DB
        $em = $this->getDoctrine()->getEntityManager();
        $captures_data = $em->getRepository('FFNMonBundle:Capture')->findBy(
                array('control' => $control_id),
                array('dateExecuted' => 'DESC'),
                100
        );
        
        if (is_null($captures_data)) {
            throw new Exception("No data found");
        }
        
        $graphdata = array(
            array('Time', 'DNS', 'TCP conn.', '1st packet', 'Total time'),
        );
        
        foreach ($captures_data as $cap) {
            array_push($graphdata, array(
                        $cap->getDateExecuted()->format('H:i:s'), 
                        (float) $cap->getDns(),
                        (float) $cap->getTcp(),
                        (float) $cap->getFirstPacket(),
                        (float) $cap->getTotal()
                    )
            );
        }

        return $this->render('FFNMonBundle:Page:graph.html.twig', array(
                    'graphdata' => json_encode($graphdata, ENT_QUOTES),
                    'title' => "Control no $control_id"
        ));
    }
}