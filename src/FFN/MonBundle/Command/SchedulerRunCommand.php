<?php

namespace FFN\MonBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use FFN\MonBundle\Entity\capture as Capture;
use FFN\MonBundle\Entity\CaptureDetail;
use FFN\MonBundle\Common\Daemon as FFNDaemon;
use DateTime;
use DateTimeZone;

/**
 * Command that runs the scheduled captures
 * */
class SchedulerRunCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('mon:scheduler:run');
        $this->setDescription('gets the captures already scheduled');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $captures = $em->getRepository("FFNMonBundle:Capture")->findAll();

        foreach($captures as $capture) {
            $now = new DateTime('now', new DateTimeZone('UTC'));
            if ( ($capture->getDateScheduled() < $now) and is_null($capture->getDateExecuted()) ) {
                $output->writeln("- running control #".$capture->getControl()->getId());

                $capture->setDateExecuted(new DateTime());

                // Actully run the sampler process
                try {
                    FFNDaemon::run($capture);
                }
                catch (\Exception $e) {
                    $this->getContainer()->get('logger')->err("Daemon: Error when getting the capture for control id="
                            .$capture->getControl()->getId());
                    $this->getContainer()->get('logger')->err("curl_wrapper: ".$e->getMessage());
                    $output->writeln("curl_wrapper: ".$e->getMessage());
                    $em->persist($capture);
                    $em->flush();
                    continue;
                }

                $em->persist($capture);
                $em->persist($capture->getCaptureDetail());
                $em->flush();

                // Validators callback
                if (count($capture->getControl()->getValidators())) {
                    foreach ($capture->getControl()->getValidators() as $validator) {
                        $res = $validator->getSubValidator()->validate(
                                $validator->getCriteria(),
                                $capture->getCaptureDetail()->getContent());
                        $output->write("  + laucnhing validator ".$validator->getSubValidator()->getName()." : ");
                        $output->writeln($res);
                        $capture->setIsValid($res);
                    }
                }
            }
        }
        $em->close();
    }
}