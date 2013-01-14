<?php

namespace FFN\MonBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use FFN\MonBundle\Entity\capture;
use FFN\MonBundle\Entity\CaptureDetail;
use \DateTime;


    /**
     * Command that fill up the capture scheduled dates based on their frequencies
     */
    class SchedulerUpdateCommand extends ContainerAwareCommand {
        
        protected function configure() 
        {
            $this->setName('mon:scheduler:update');
            $this->setDescription('update the captures scheduled date based on the control frequency');
        }
        
        protected function execute(InputInterface $input, OutputInterface $output)
        {
         
            $nbMinutes      = 60;
            $nbMinutesRound = 10;
            $startTS  = $this->minutes_round( time(), $nbMinutesRound, 'Y-m-d H:i:s');
            $stopTS   = $this->minutes_round( time() + $nbMinutes*60, $nbMinutesRound, 'Y-m-d H:i:s');
            
            $output->writeln("- Updating capture table beetween $startTS and $stopTS ...");
            
            // get all the scenarii frequencies
            $em = $this->getContainer()->get('doctrine')->getEntityManager();
            $scenarios = $em->getRepository("FFNMonBundle:Scenario")->findAll();
                        
            foreach ($scenarios as $scenario) {
                $output->writeln('-- updating schedule for scenario # '.$scenario->getId().' ('.$scenario->getName().')');
                
                // get all controls from the current scenario
                $controls = $scenario->getControls();
                
                foreach ($controls as $control) {
                    $output->write("--- updating schedule for control #". $control->getId());
                    $output->writeln(" (".$control->getName().").");
                    
                    // create schedules in DB                    
                    while ($startTS < $stopTS) {
                        
                        $output->writeln("---- added capture at $startTS");
                        $this->scheduleCapture($startTS);
                        $startTS += $control->getFrequency();
                    }
                }                
            }
        }
        
        // TODO: a externaliser
        protected function scheduleCapture($dateTime) {
            
            $em = $this->getContainer()->get('doctrine')->getEntityManager();
            
            $capDetails = new CaptureDetail();
            $cap = new capture();
            
            $cap->setDateScheduled(new DateTime($dateTime));
            $cap->setCaptureDetail($capDetails);
            
            $em->persist($capDetails);
            $em->persist($cap);
            $em->flush();
        }
        
        // TODO: a externaliser
        protected function minutes_round($epoch, $minutes = '10', $format = "H:i")
        {
            $rounded = round($epoch / ($minutes * 60)) * ($minutes * 60);
            return date($format, $rounded);
        }
        
    }
?>
