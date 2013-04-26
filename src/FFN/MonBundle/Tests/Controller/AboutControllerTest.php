<?php

namespace FFN\MonBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of AboutControllerTest
 *
 * @author Fabien Somnier <fabien.somnier@buongiorno.com>
 */
class AboutControllerTest extends WebTestCase {

  public function testShow() {
    // client creation, to simulate navigator
    $client = static::createClient();

    // client request and test of the page - french case
    $crawler = $client->request('GET', '/fr/about');
    $this->assertTrue($crawler->filter('html:contains("MON est un projet de monitoring externe")')->count() > 0);

    // client request and test of the page - english case
    $crawler = $client->request('GET', '/en/about');
    $this->assertTrue($crawler->filter('html:contains("MON is an external monitoring tool")')->count() > 0);
  }

}

?>
