

<?php $notification=$this->requestAction(array('controller' => 'warehouses', 'action' => 'notification'));?>


<span class="quantity"> <?php echo $notification['not']; ?></span>

                                        <?php
                                        if((int)$notification['not']>=1){
                                            
                                        ?>
                                        <div class="notifi-dropdown js-dropdown">
                                        <div class="notifi__footer">
                                        <?php echo $this->Html->link("Produit proche d'expiration",array('controller' =>'warehouses','action'=>'perrisable')); ?> 
                                            </div>
                                            </div>
                                        <?php
                                        }
                                        
                                        ?>

                                            

                                            
                                       