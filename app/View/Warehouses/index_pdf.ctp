<div class="row">
<?php echo $this->Html->link('Télecharger PDF',array
                                        (), array('class'=>'btn-sm btn-info', 'onclick'=>"generatePDF()")); ?> 
</div>
<div class="row">
                            <div class="col-md-12" id="rapport">
                                <!-- DATA TABLE -->
                                <h3 class="title m-b-35 text-center">Stock disponible</h3>
                              
                                <?php  if (count($stock_depot) > 0) {?>

                                <div class="table-responsive table-responsive-data2">
                                    <table class="table">
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

                        