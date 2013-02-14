<?php

namespace FFN\MonBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use FFN\MonBundle\Entity\Capture;
use FFN\MonBundle\Entity\CaptureDetail;
use FFN\MonBundle\Entity\Weather;
use FFN\MonBundle\Common\WeatherCalculator;

/**
 * Command that calculates weather of any object
 */
class WeatherCalculateCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('mon:weather:calculate');
        $this->setDescription('calculates a weathermap of the monitored urls based on a custom logiq');
    }

    public function execute(InputInterface $input, OutputInterface $output) {

        /* is going from controls, through scenarii, to projects */

        // get all the controls
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $controls = $em->getRepository("FFNMonBundle:Control")->findAll();

        // Controls
        foreach ($controls as $control) {

            $captures = $em->getRepository("FFNMonBundle:Capture")->findLastsByControl($control->getId(), $this->getContainer()->getParameter('weather_default_window_size'));
            
            $output->writeln('+ calculating score for control #' . $control->getId());
            $score = 0;
            
            foreach ($captures as $capture) {
                $output->writeln('  + calculating score for capture #' . $capture->getId());
                $score = $score + WeatherCalculator::getWeatherScore($capture, 
                                                            $this->getContainer()->getParameter('weather_default_valid_codes'), 
                                                            (float) $this->getContainer()->getParameter('weather_default_dns_threshold'), 
                                                            (float) $this->getContainer()->getParameter('weather_default_tcp_threshold'), 
                                                            (float) $this->getContainer()->getParameter('weather_default_first_packet_threshold'), 
                                                            (float) $this->getContainer()->getParameter('weather_default_total_time_threshold'));
                $output->writeln('      + score: ' . $score);
            }
            $score = $score/count($captures);
            $output->writeln('  + average score: '.$score);

            if ($score >= $this->getContainer()->getParameter('weather_very_good_score')) {
                $ctrl_weather = Weather::WEATHER_SUNNY;
            } elseif ($score >= $this->getContainer()->getParameter('weather_average_score')) {
                $ctrl_weather = Weather::WEATHER_MIXED;
            } elseif ($score >= $this->getContainer()->getParameter('weather_poor_score')) {
                $ctrl_weather = Weather::WEATHER_RAIN;
            } else {
                $ctrl_weather = Weather::WEATHER_STORM;
            }
            
            $this->updateWeather(Weather::OBJECT_TYPE_CONTROL, $capture->getControl()->getId(), $ctrl_weather);
        }

        // scenarios
        $scenarii = $em->getRepository("FFNMonBundle:Scenario")->findAll();
        $sc_weather = Weather::WEATHER_UNKNOWN;
        foreach ($scenarii as $scenario) {
            $output->writeln('+ calculating score for scenario #' . $scenario->getId());
            foreach ($scenario->getControls() as $control) {
                $control_weather = $em->getRepository("FFNMonBundle:Weather")->findOneByControl($control->getId());
                $sc_weather = \min($sc_weather, $control_weather->getWeatherState());
                $output->writeln(' + weather state : ' .$sc_weather);
            }
            
            $this->updateWeather(Weather::OBJECT_TYPE_SCENARIO, $scenario->getId(), $sc_weather);
        }
        
        // projects
        $projects = $em->getRepository("FFNMonBundle:Project")->findAll();
        $pj_weather = Weather::WEATHER_UNKNOWN;
        foreach ($projects as $project) {
            $output->writeln('+ calculating score for project #' . $project->getId());
            foreach ($project->getScenarios() as $scenario) {
                $scenario_weather = $em->getRepository("FFNMonBundle:Weather")->findOneByScenario($scenario->getId());
                $pj_weather = \min($pj_weather, $scenario_weather->getWeatherState());
                $output->writeln(' + weather state : ' .$sc_weather);
            }
            
            $this->updateWeather(Weather::OBJECT_TYPE_PROJECT, $project->getId(), $pj_weather);
        }
    }
    
    private function updateWeather($objectType, $refIdObject, $weatherStatus) {
        
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $weather = $em->getRepository("FFNMonBundle:Weather")->findOneBy(array('objectType' => $objectType, 
                                                                'refIdObject' => $refIdObject));
        if ($weather === NULL) {
        
            $weather = new Weather();
            $weather->setObjectType($objectType);
            $weather->setRefIdObject($refIdObject);
        }
        
        $weather->setWeatherState($weatherStatus);
        $em->persist($weather);
        $em->flush();    
    }
}
