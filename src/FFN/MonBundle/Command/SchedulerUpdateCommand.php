<?php

namespace FFN\MonBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

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
            $output->writeln("- Updating capture table ...");
            
            // get all the scenarii frequencies
            $em = $this->getContainer()->get('doctrine')->getEntityManager();
            $scenarios = $em->getRepository("FFNMonBundle:Scenario")->findAll();
                        
            foreach ($scenarios as $scenario) {
                $output->writeln('-- updating schedule for scenario # '.$scenario->getId().' ('.$scenario->getName().')');
                
                // get all controls from the current scenario
                $controls = $scenario->getControls();
                
                foreach ($controls as $control) {
                    $output->writeln("--- updating schedule for control #". $control->getId());
                }
                
            }
            
            // 
            
        }
        
    }
?>
