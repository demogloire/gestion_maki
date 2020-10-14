<?php

class Invoice extends AppModel{

  public $belongsTo=array('User');
  public $hasMany=array('Value', 'Sale');


}




 ?>
