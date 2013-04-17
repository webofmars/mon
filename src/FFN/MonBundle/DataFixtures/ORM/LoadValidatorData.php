<?php


namespace FFN\MonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FFN\MonBundle\Entity\Validator;

/**
 * Description of LoadValidatorData
 *
 * @author frederic
 */
class LoadValidatorData  extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $om) {

        // a validator that will suceed
        $validator = new Validator();

        $validator->setType('Regexp');
        $validator->setCriteria('/meta/i');

        $ctrl= $om->merge($this->getReference('ctr_1_1_1_1'));
        $ctrl->addValidator($validator);

        $om->persist($ctrl);
        $om->persist($validator);
        $om->flush();

        $this->setReference('validator-regexp-ok', $validator);

        // a validator that will fail
        $validator = new Validator();

        $validator->setType('Regexp');
        $validator->setCriteria('/napoleon/');

        $ctrl= $om->merge($this->getReference('ctr_1_1_1_1'));
        $ctrl->addValidator($validator);

        $om->persist($ctrl);
        $om->persist($validator);
        $om->flush();

        $this->setReference('validator-regexp-ko', $validator);
    }

    public function getOrder() {
        return(10);
    }
}
