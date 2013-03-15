<?php

namespace FFN\MonBundle\Common;

use FFN\MonBundle\Entity\Capture;
use FFN\MonBundle\Entity\CaptureDetail;

/**
 * 
 * WeatherCalculator: calculate a weather score from a given capture
 *
 * @author Frederic Leger <leger.frederic@gmail.com>
 */
class WeatherCalculator {

    public static function getWeatherScore(Capture $capture, array $validCodes, $dns_max, $tcp_max, 
                                            $first_packet_max, $total_time_max) {
        
        $score = 0;
        
        // 1) should be valid
        if (in_array($capture->getResponseCode(), $validCodes)) {
            $score = 1000;
        }
        
        // 2) validators
        if ($capture->getIsValid()) {
            $score += 100;
        }
        
        // 3) DNS
        if ($capture->getDns() <= $dns_max) {
            $score +=10;
        }
        
        // 4) TCP
        if ($capture->getTcp() <= $tcp_max) {
            $score +=10;
        }
        
        // 5) 1st packet
        if ($capture->getFirstPacket() <= $first_packet_max) {
            $score +=10;
        }
        
        // 6) Total Time
        if ($capture->getTotal() <= $total_time_max) {
            $score +=10;
        }
        
        return $score;
    }
}