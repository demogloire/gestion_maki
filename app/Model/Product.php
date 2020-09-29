<?php

class Product extends AppModel{

  public $belongsTo=array('Category');

  public $validate = array(
    'emballage' => array(
        'alphaNumeric' => array(
            'rule' => '',
            'required' => true,
            'message' => 'Chiffres et lettres uniquement !'
        ), 
    
    'mesure' => array(
            'alphaNumeric' => array(
                'rule' => '',
                'required' => true,
                'message' => 'Chiffres et lettres uniquement !'
        ), 
    'nombre_contenu' => array(
            'Numeric' => array(
                'rule' => '',
                'required' => true,
                'message' => 'Chiffres uniquement !'
        )    
    
    ))));
 

}




 ?>
