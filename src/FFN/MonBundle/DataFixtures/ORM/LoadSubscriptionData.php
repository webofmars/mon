<?php


namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use FFN\UserBundle\Entity\User;
use FFN\MonBundle\Entity\Subscription;

/**
 * Description of LoadSubscriptionData
 *
 * @author frederic
 */
class LoadSubscriptionData  extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $om) {

        $sub = new Subscription();
        $sub->setName('free user');
        $sub->setDescription('Default level of subscription');
        $sub->setMaxProjects(1);
        $sub->setMaxScenarios(5);
        $sub->setMaxControls(5);
        $om->persist($sub);
        $om->flush();
        $this->setReference('subscription_free', $sub);

        $sub = new Subscription();
        $sub->setName('premium user');
        $sub->setDescription('Highest level of subscription');
        $sub->setMaxProjects(NULL);
        $sub->setMaxScenarios(NULL);
        $sub->setMaxControls(NULL);
        $om->persist($sub);
        $om->flush();
        $this->setReference('subscription_premium', $sub);
    }

    public function getOrder() {
        return(1);
    }
}