<div class="row">
                            <div class="col-md-12">
                                <!-- DATA TABLE -->
                                <h3 class="title m-b-35 text-center">Produit perrisable</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                    </div>
                                    <div class="table-data__tool-right">
                                    <?php echo $this->Html->link('Stocker',array('controller' =>'warehouses','action'=>'stock_magasin'), array('class'=>'au-btn au-btn-icon btn-info au-btn--small')); ?> 
                                    <?php echo $this->Html->link('Transfert',array('controller' =>'warehouses','action'=>'transfert'), array('class'=>'au-btn au-btn-icon btn-dark au-btn--small')); ?> 
                                    <?php echo $this->Html->link('Corriger',array('controller' =>'warehouses','action'=>'correction'), array('class'=>'au-btn au-btn-icon btn-success au-btn--small')); ?>
                                    <?php echo $this->Html->link('Produit perrisable',array('controller' =>'warehouses','action'=>'perrisable'), array('class'=>'au-btn au-btn-icon btn-primary au-btn--small')); ?>
                                    
                                    </div>
                                </div>
                              
                                <?php  if (count($stock_depot) > 0) {?>

                                <div class="table-responsive table--no-card m-b-30">
                                    <table class="table table-borderless table-striped table-earning table-bordered">
                                        <thead>
                                            <tr >
                                                <th >Produit</th>
                                                <th class="text-right">Qté total</th>
                                                <th class="text-center">Expiration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($stock_depot as $stock_depot)?>
                                            <?php if( $this->Njr->Nbjours($stock_depot['Expiration']['date_production'],$stock_depot['Expiration']['date_expiration']) < 3){
                                                ?>
                                            <tr>

                                                <td><?php echo $stock_depot['Product']['nom_produit']; ?></td>
                                                <td class="text-right">
                                                    <?php echo $stock_depot['Expiration']['qte'].' '.$stock_depot['Product']['emballage']; ?>
                                            
                                                </td>
                                                <td class="text-center">
                                                <?php
                                                if($this->Njr->Nbjours(date('Y-m-d'),$stock_depot['Expiration']['date_expiration'])>0){
                                                ?>
                                                 Expire dans <span class="badge badge-danger"><?php echo $this->Njr->Nbjours(date('Y-m-d'),$stock_depot['Expiration']['date_expiration']); ?> jours</span> 
                                                
                                                <?php
                                                }else{
                                                ?>
                                                 <span class="badge badge-danger">Expirer</span> 
                                                
                                                <?php
                                                }
                                                ?>
                                               
                                                </td>
                                            </tr>
                                               
                                            <?php}

                                            ?>
 
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                    <?php  if (count($stock_depot) > 9) {?>
                                    <div class="box-footer clearfix">
                                
                                    <?php } ?>
                                    <?php }else{ ?>
                                    <div class="row">
                                                            <div class="col-lg-12 col-md-12">
                                                                <p class="text-center" style="margin-top:100px">
                                                                    <i class="la la-folder success font-large-5 text-center"></i>
                                                                    <h4 class="text-center">AUCUN STOCK DISPONIBLE .</h4>
                                                                    <br><br><br><br><br><br>
                                                                </p>
                                                            </div>
                                                        </div>
                                
                                
                                <?php } ?>
   
                        </div>

                                </div>
                                <!-- END DATA TABLE -->
                            </div>
                        </div>

                        