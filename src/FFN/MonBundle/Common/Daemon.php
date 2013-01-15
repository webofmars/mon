<?php

namespace FFN\MonBundle\Common;

use FFN\MonBundle\Entity\capture as Capture;

class Daemon {

    public static function run(Capture $capture) {
        return Daemon::curl_wrapper($capture->getControl()->getUrl());
    }

    /**
     * 
     * @param type $url
     * 
     * Wraper arround libcurl
     * returns array or null
     *      reponse,
     *      response_code,
     *      connect_time,
     *      namelookup_time,
     *      starttransfer_time,
     *      total_time
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
        }
        // TODO : gestion des erreurs
        
        // Fermeture du gestionnaire
        curl_close($ch);
        
        return($results);
    }

}