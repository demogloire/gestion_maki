<div class="row">
                            <div class="col-md-12">
                                <!-- DATA TABLE -->
                                <h3 class="title m-b-35 text-center">Stock disponible</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                    <?php  if (count($stock_depot) > 0) {?>
                                        <?php echo $this->Html->link('Télecharger PDF',array
                                        ('controller' =>'warehouses','action'=>'index_pdf'), array('class'=>'btn-sm btn-info', 'target'=>'_blank')); ?> 
                                    <?php echo $this->Html->link('Imprimer',array
                                        ('controller' =>'warehouses','action'=>'index_impression'), array('class'=>'btn-sm btn-danger', 'target'=>'_blank')); ?> 

                                    <?php } ?>
                                    </div>
                                    <div class="table-data__tool-right">
                                    <?php echo $this->Html->link('Stocker',array('controller' =>'warehouses','action'=>'stock_magasin'), array('class'=>'au-btn au-btn-icon btn-info au-btn--small')); ?> 
                                    <?php echo $this->Html->link('Transfert',array('controller' =>'warehouses','action'=>'transfert'), array('class'=>'au-btn au-btn-icon btn-dark au-btn--small')); ?> 
                                    <?php echo $this->Html->link('Corriger',array('controller' =>'warehouses','action'=>'correction'), array('class'=>'au-btn au-btn-icon btn-success au-btn--small')); ?>
                                    <?php echo $this->Html->link('Produit perrisable',array('controller' =>'warehouses','action'=>'perrisable'), array('class'=>'au-btn au-btn-icon btn-primary au-btn--small')); ?>
                                    
                                    </div>
                                </div>
                              
                                <?php  if (count($stock_depot) > 0) {?>

                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th>Periode</th>
                                                <th >Produit</th>
                                                <th class="text-right">Qté</th>
                                                <th class="text-right">Valeur</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($stock_depot as $stock_depot): current($stock_depot);?>
                                            <tr class="tr-shadow">
                                                <td><?php 
                                                echo $this->Timeago->timeagos($stock_depot['Warehouse']['date_op']);
                                                ?></td>
                                                <td><?php echo $stock_depot['Product']['nom_produit']; ?></td>
                                                <td class="text-right"><?php echo $stock_depot['Warehouse']['qte_total']; ?></td>
                                                <td class="text-right"><?php echo $stock_depot['Warehouse']['valeur']; ?></td>
                                            </tr>
                                        <?php endforeach ?>

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

                        