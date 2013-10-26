<?php

namespace FFN\MonBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FFN\MonBundle\Entity\CaptureDetail;

/**
 * CaptureDetail controller.
 *
 */
class CaptureDetailController extends Controller
{

    /**
     * Finds and displays a CaptureDetail entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FFNMonBundle:CaptureDetail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CaptureDetail entity.');
        }
        
        // TODO: need to implement security here
        return $this->render('FFNMonBundle:Page:Capture\showDetails.html.twig', array(
                'entity' => $entity));
    }
}
