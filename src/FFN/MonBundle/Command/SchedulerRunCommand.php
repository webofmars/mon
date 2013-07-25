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
        $this->setDescription('gets the captures already scheduled and launch them');
        $this->addArgument('idwatcher', InputArgument::OPTIONAL, 'Identifier of watcher', 1);
        $this->addArgument('nbwatcher', InputArgument::OPTIONAL, 'Number of simultaneous watchers', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        if (is_null($input->getArgument('idwatcher'))) {
            $output->writeln('error: missing argument idwatcher');
        }
        if (is_null($input->getArgument('nbwatcher'))) {
            $output->writeln('error: missing argument nbwatcher');
        }

        $output->writeln("begin of watcher #" . $input->getArgument('idwatcher') . " treatment (on " . $input->getArgument('nbwatcher') . " simultaneous)");

        $em = $this->getContainer()->get('doctrine')->getManager();
        $captures = $em->getRepository("FFNMonBundle:Capture")->findAllToBeExecuted($input->getArgument('idwatcher'), $input->getArgument('nbwatcher'));

        $nbCaptures = count($captures);
        $output->writeln("number of captures to catch : " . $nbCaptures);

        $counter = 0;
        foreach ($captures as $capture) {
            $progression = round(($counter++) * 100 / $nbCaptures) . '%';
            $output->writeln("- running control #" . $capture->getControl()->getId() . " (".$progression.")");
            $capture->setDateExecuted(new DateTime('now', new DateTimeZone('UTC')));
            // Actually run the sampler process
            try {
                FFNDaemon::run($capture);
            } catch (\Exception $e) {
                $this->getContainer()->get('logger')->err("Daemon: Error when getting the capture for control id="
                        . $capture->getControl()->getId());
                $this->getContainer()->get('logger')->err("curl_wrapper: " . $e->getMessage());
                $output->writeln("curl_wrapper: " . $e->getMessage());
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
                            $validator->getCriteria(), $capture->getCaptureDetail()->getContent());
                    $output->write("  + launching validator " . $validator->getSubValidator()->getName() . " : ");
                    $output->writeln($res);
                    $capture->setIsValid($res);
                }
            }
        }

        $output->writeln("end of watcher #" . $input->getArgument('idwatcher') . " treatment");

        $em->close();
    }

}