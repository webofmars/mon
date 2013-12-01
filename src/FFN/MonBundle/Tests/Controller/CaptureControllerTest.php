<?php

namespace FFN\MonBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FFN\MonBundle\Entity\Capture;

/**
 * Description of CaptureControllerTest
 *
 * @author frederic leger
 */
class CaptureControllerTest extends WebTestCase {

  public function testShow() {
    // client creation, to simulate navigator
    $client = static::createClient();

    
    $crawler = $client->request('GET', '/capture/show/543');
    $this->assertEquals('200',$client->getResponse()->getStatus());
    $this->assertEquals(16, $crawler->filter('tr')->count());

  }

}

?>
