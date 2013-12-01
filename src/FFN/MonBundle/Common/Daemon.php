<?php

namespace FFN\MonBundle\Common;

use FFN\MonBundle\Entity\Capture;
use FFN\MonBundle\Entity\CaptureDetail;

class Daemon {

    public static function run(Capture $capture) {

        $cd = $capture->getCaptureDetail();
        
        try {
            $res = self::curl_wrapper($capture->getControl()->getUrl());
        }
        catch (Excetion $e) {
            echo("daemon: $e->toString()\n");
        }

        if ($res[0] != false) {
            $capture->setResponseCode($res[2]);
            $capture->setTcp($res[3]);
            $capture->setDns($res[4]);
            $capture->setFirstPacket($res[5]);
            $capture->setTotal($res[6]);
            $capture->setIsTimeout(false);
            
            $cd->setContent($res[1]);
            $cd->setIsConnectionTimeout(false);
            $cd->setIsResponseTimeout(false);

            // TODO: add callback for validator
            $cd->setValidators("tuti va bene!");
            $capture->setIsValid(true);
        }
        else {

            $capture->setIsValid(false);

            // gère les différents timeout
            if (in_array($res[1], array(1, 2, 3, 4, 5, 6, 7, 12, 28))) {
                $capture->setIsTimeout(true);
                switch ($res[1]) {
                    case ($res[1]<7):
                        $cd->setIsConnectionTimeout(true);
                        $cd->setIsResponseTimeout(false);
                        break;
                    case 7:
                        $cd->setIsConnectionTimeout(true);
                        $cd->setIsResponseTimeout(false);
                        break;
                    case 12:
                        $cd->setIsConnectionTimeout(true);
                        $cd->setIsResponseTimeout(false);
                        break;
                    case 28:
                        $cd->setIsConnectionTimeout(false);
                        $cd->setIsResponseTimeout(true);
                        break;
                }
            }

            $cd->setContent('error: '.$res[2].'. ('.$res[1].')');
        }
        
        $capture->setCaptureDetail($cd);
        $cd->setCapture($capture);
    }

    /**
     *
     * @param type $url
     *
     * Wraper arround libcurl
     * returns an array :
     * Success:
     *   0 : true
     *   1 : reponse,
     *   2 : response_code,
     *   3 : connect_time,
     *   4 :  namelookup_time,
     *   5 :  starttransfer_time,
     *   6 :  total_time
     * Faillure:
     *  0 : false
     *  1 : error code
     *  2 : error message
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
        
        //TODO: The values must come from the config
        //TODO: is there any RFC header for monitoring system ???
        // For tracing our solution
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-WoM-mon-version: v1.0',
            'X-WoM-mon-system-id: xxDEVDEVDEVxx'
        ));

        // Exécution
        $reponse = curl_exec($ch);

        // Vérification si une erreur est survenue
        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            $results = array(
                true,
                $reponse,
                $info['http_code'],
                $info['connect_time'],
                $info['namelookup_time'],
                $info['starttransfer_time'],
                $info['total_time']
            );
        }
        else {
            $results =array(
                false,
                curl_errno($ch),
                curl_error($ch));
        }

        curl_close($ch);
        return($results);
    }

}
