<?php

  // Création d'un gestionnaire curl
  $ch = curl_init('http://test-fleger.prod.mobivillage.com/');
  
  // pas de sortie
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  // Pour la securité
  curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
  curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
  curl_setopt( $ch, CURLOPT_MAXREDIRS, 5 );

  // Exécution
  $reponse = curl_exec($ch);
  
  // Vérification si une erreur est survenue
  if(!curl_errno($ch))
  {
    $info = curl_getinfo($ch);
    echo '[';
    echo chr(39).date('H:i:s').chr(39);
    echo chr(44);
    echo $info['connect_time'];
    echo chr(44);
    echo $info['namelookup_time'];
    echo chr(44);
    echo $info['starttransfer_time'];
    echo chr(44);
    echo $info['total_time'];
    echo ']';
  }

  // Fermeture du gestionnaire
  curl_close($ch);