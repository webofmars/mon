<?php

namespace FFN\MonBundle\Common;

use FFN\MonBundle\Entity\Capture;
use \FFN\MonBundle\Entity\CaptureDetail;

class Daemon {

    public static function run(Capture $capture) {
        
        try {
            $res = Daemon::curl_wrapper($capture->getControl()->getUrl());
        }
        catch (Excetion $e) {
            echo("daemon: $e->toString()\n");
        }
        
        if ($res != false) {
            $capture->setResponseCode($res[1]);        
            $capture->setTcp($res[2]);
            $capture->setDns($res[3]);
            $capture->setFirstPacket($res[4]);
            $capture->setTotal($res[5]);
            $capture->setIsTimeout(false);
            
            $cd = new CaptureDetail();
            $cd->setContent($res[0]);
            $cd->setIsConnectionTimeout(false);
            $cd->setIsResponseTimeout(false);
            
            // TODO: add callback for validator
            $cd->setValidators("tuti va bene!");
            $capture->setIsValid(true);
            $capture->setCaptureDetail($cd);
        }
        else {
            $capture->setIsTimeout(true);
        }
    }

    /**
     * 
     * @param type $url
     * 
     * Wraper arround libcurl
     * returns array or null
     *   0   reponse,
     *   1   response_code,
     *   2   connect_time,
     *   3   namelookup_time,
     *   4   starttransfer_time,
     *   5   total_time
     */
    public static function curl_wrapper($url) {
        
        $results = array();
        
        // Création d'un gestionnaire curl
        $ch = curl_init($url);

        // pas de sortie
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Pour la securité
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        // Exécution
        $reponse = curl_exec($ch);
        
        // Vérification si une erreur est survenue
        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            $results = array(
                $reponse,
                $info['http_code'],
                $info['connect_time'],
                $info['namelookup_time'],
                $info['starttransfer_time'],
                $info['total_time']
            );
            curl_close($ch);
        }
        else {
            throw new \Exception("curl_wrapper: ".curl_error($ch));
            curl_close($ch);
        }
                
        return($results);
    }

}