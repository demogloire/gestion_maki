<?php

App::uses('Component','Controller');

class UtilitaireComponent extends Component{

    function NbJours($debut, $fin) {

        $tDeb = explode("-", $debut);
        $tFin = explode("-", $fin);
        
        @$diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
        
        return(($diff / 86400));
        
    }

    function numerofacture() {
        return uniqid('#');    
    }


}



?>