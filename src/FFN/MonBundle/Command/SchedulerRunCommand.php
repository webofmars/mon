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

/**
 * Command that runs the scheduled captures
 * */
class SchedulerRunCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('mon:scheduler:run');
        $this->setDescription('gets the captures already scheduled');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $captures = $em->getRepository("FFNMonBundle:capture")->findAll();
        
        foreach($captures as $capture) {
            if ( is_null($capture->getDateExecuted()) ) {
                $output->writeln("- running control #".$capture->getControl()->getId());
                $results = FFNDaemon::run($capture);
            }
        }
    }

}