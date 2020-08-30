<?php
$counterpr=$this->requestAction(array('controller' => 'credits' , 'action' => 'view' ));

debug($counterpr);
 ?>
<ul class="list-group">

  <li class="list-group-item justify-content-between list-group-item-success">
    <h5 class="text-center">Les produits les plus credité</h5>
  </li>
  <?php foreach ($counterpr as $counterpr):;?>
    <li class="list-group-item justify-content-between list-group-item-warning">
      <?php  echo $counterpr['Product']['name'];?>
      <span class="badge badge-default badge-pill"><?php  echo $counterpr['0']['count_product_one'];?></span>
    </li>
  <?php endforeach ?>
  </ul>
