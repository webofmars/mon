<?php

namespace FFN\MonBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FFN\MonBundle\Entity\CaptureDetail;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * CaptureDetail controller.
 *
 */
class CaptureDetailController extends Controller
{

    /**
     * Finds and displays a CaptureDetail entity.
     * @Secure(roles="ROLE_ADMIN")
     * @Secure(roles="ROLE_USER")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FFNMonBundle:CaptureDetail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CaptureDetail entity.');
        }
        
        $user    = $this->get('security.context')->getToken()->getUser();
        $isSA    = $this->get('security.context')->isGranted('ROLE_ADMIN');
        
        if ( ($isSA == false) and ($user != $entity->getOwner()) )
        {
            throw new AccessDeniedException();
        }
        
        return $this->render('FFNMonBundle:Page:Capture\showDetails.html.twig', array(
                'entity' => $entity));
    }
}
