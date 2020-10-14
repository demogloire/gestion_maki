<?php
class DateHelper extends AppHelper{

    public $days	 = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi','Dimanche');
    public $months	 = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

    function french($datetime){
    	$tmstamp = strtotime($datetime);
    	$date = $this->days[date('N',$tmstamp)-1]." ".date('d',$tmstamp).' '.$this->months[date('n',$tmstamp)-1].' '.date('Y',$tmstamp);
    	$date .= " à ".date('H:i',$tmstamp);
    	return $date;
    }

}
