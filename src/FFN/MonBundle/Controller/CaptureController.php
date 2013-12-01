<?php

namespace FFN\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FFN\MonBundle\Entity\Capture;
use FFN\MonBundle\Entity\CaptureDetail;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Capture controller.
 *
 */
class CaptureController extends Controller
{

    /**
     * Finds and displays a Capture entity.
     * @Secure(roles="ROLE_ADMIN")
     * @Secure(roles="ROLE_USER")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $capture = $em->getRepository('FFNMonBundle:Capture')->find($id);

        if (!$capture) {
            throw $this->createNotFoundException('Unable to find Capture entity.');
        }
        
        $user    = $this->get('security.context')->getToken()->getUser();
        $isSA    = $this->get('security.context')->isGranted('ROLE_ADMIN');
        
        if ( ($isSA == false) and ($user != $capture->getOwner()) )
        {
            throw new AccessDeniedException();
        }
        
        $details = $capture->getCaptureDetail();
        
        return $this->render('FFNMonBundle:Page:Capture\showCapture.html.twig', 
                array( 'capture' =>  $capture , 
                       'details' => $details ));
    }
}
