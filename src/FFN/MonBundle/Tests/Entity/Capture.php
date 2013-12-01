<?php

use FFN\MonBundle\Entity\Capture;
use FFN\MonBundle\Entity\CaptureDetail;

/**
 * Test the ORM mapping of capture & captureDetail
 *
 * @author frederic leger
 */

class Capture extends \PHPUnit_Framework_TestCase {
  
  public function testCaptureToCaptureDetailMapping() {
    $cap = new Capture();
    $cap->setIsTimeout(true);
    $cap->getCaptureDetail()->setContent('test content');
    
    $this->assert("test content", $cap->getCaptureDetail()->getContent());
    $this->assert(true, $cap->getCaptureDetail()->getCapture()->getIsTimeout());
  }
  
}