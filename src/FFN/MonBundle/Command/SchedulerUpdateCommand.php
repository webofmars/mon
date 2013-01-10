<?php

namespace FFN\MonBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

    /**
     * Command that fill up the capture scheduled dates based on their frequencies
     */
    class SchedulerUpdateCommand extends Command {
        
        protected function configure() 
        {
            $this->setName('mon:scheduler:update');
            $this->setDescription('update the captures scheduled date based on the control frequency');
        }
        
        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $output->writeln("Updating capture table ...");
            
            // get all the scenarii frequencies
            $em = $this->get("doctrine.orm.entity_manager");
            
            // 
            
        }
        
    }
?>
