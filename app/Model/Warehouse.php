<?php

class Warehouse extends AppModel{

  public $hasMany=array('Expiration');
  public $belongsTo=array('Product','User');



}




 ?>
