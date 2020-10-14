<div class="row">
                            <div class="col-md-12">
                                <!-- DATA TABLE -->
                                <h3 class="title m-b-35 text-center">Opération de transfert</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">

                                    </div>

                                    <div class="table-data__tool-right">
                                    <?php echo $this->Html->link('Stocker',array('controller' =>'warehouses','action'=>'index_depot'), array('class'=>'au-btn au-btn-icon btn-info au-btn--small')); ?> 
                                    <?php echo $this->Html->link('Transfert',array('controller' =>'warehouses','action'=>'transfert'), array('class'=>'au-btn au-btn-icon btn-dark au-btn--small')); ?> 
                                    <?php echo $this->Html->link('Corriger',array('controller' =>'warehouses','action'=>'correction'), array('class'=>'au-btn au-btn-icon btn-success au-btn--small')); ?>
                                    <?php echo $this->Html->link('Produit perrisable',array('controller' =>'warehouses','action'=>'perrisable'), array('class'=>'au-btn au-btn-icon btn-primary au-btn--small')); ?>
                                    
                                    </div>
                                </div>
                              
                                <div class="row">
                                    <div class="col-lg-8">
                                        <?php  if (count($stock_depot) > 0) {?>

                                        <div class="table-responsive table-responsive-data2">
                                            <table class="table table-striped table-bordered">
                                                
                                                    <tr>
                                                        <th class="text-center" colspan="2">Date & Produit</th>
                                                        <th class="text-center" colspan="3">Transfert</th>
                                                        <th class="text-center" colspan="2">Disponible</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th >Produit</th>
                                                        <th class="text-right">Qté</th>
                                                        <th class="text-right">P.U</th>
                                                        <th class="text-right">Valeur</th>
                                                        <th class="text-right">Qté</th>
                                                        <th class="text-right">Valeur</th>
                                                    </tr>
                                                
                                                <tbody>
                                                <?php foreach ($stock_depot as $stock_depot): current($stock_depot);?>
                                                    <tr class="tr-shadow">
                                                        <td><?php 
                                                        echo date_format(date_create($stock_depot['Warehouse']['date_op']),'d-m-Y');
                                                        ?></td>
                                                        <td><?php echo $stock_depot['Product']['nom_produit']; ?></td>
                                                        <td class="text-right"><?php echo $stock_depot['Warehouse']['qte']; ?></td>
                                                        <td class="text-right"><?php echo $stock_depot['Warehouse']['prix_unit']; ?></td>
                                                        <td class="text-right"><?php echo $stock_depot['Warehouse']['valeur_total']; ?></td>
                                                        <td class="text-right"><?php echo $stock_depot['Warehouse']['qte_total']; ?></td>
                                                        <td class="text-right"><?php echo $stock_depot['Warehouse']['valeur']; ?></td>
                                                    </tr>
                                                <?php endforeach ?>

                                                </tbody>
                                            </table>
                                            </div>
                                            <?php  if (count($stock_depot) > 30) {?>
                                            <div class="box-footer clearfix">
                                            <ul class="pagination pagination-sm no-margin pull-right" style="margin-top:5px">
                                                <?php
                                                    echo $this->Paginator->prev(__('previous'), array('tag' => 'li'), null, array('class'=>'btn btn-success'));
                                                    echo $this->Paginator->numbers(array('separator'=>''));
                                                    echo $this->Paginator->next(__('Suivant'), array('tag' => 'li'), null, array('class'=>'btn btn-danger'));

                                                ?>

                                            </ul>
                                            <?php } ?>
                                            <?php }else{ ?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">
                                                                            <p class="text-center" style="margin-top:100px">
                                                                                <i class="la la-folder success font-large-5 text-center"></i>
                                                                                <h4 class="text-center">AUCUN TRANSFERT .</h4>
                                                                                <br><br><br><br><br><br>
                                                                            </p>
                                                    </div>
                                                </div>


                                             <?php } ?>

                                        </div>
                                    
                                    

                                

                                
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-title text-center" style="padding:10px">
                                            <h4>Transfert (Boutique)</h4>
                                        </div>
                                        <div class="card-body box-shadow--3dp">
                                        <?php echo $this->Form->create('Warehouse'); ?>



                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-1">Produit</label>
                                                                    <?php
                                                                        echo $this->Form->input('product_id',array('placeholder' => '10.5','class'=>'form-control','label'=>false));
                                                                    ?>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-1">Qté</label>
                                                                    <?php
                                                                        echo $this->Form->input('qte',array('placeholder' => '10.5','class'=>'form-control','label'=>false));
                                                                    ?>
                                                                </div>
                                                            </div> 
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-1">Prix d'achat</label>
                                                                    <?php
                                                                        echo $this->Form->input('prix_unit',array('placeholder' => '11.5','class'=>'form-control','label'=>false));
                                                                    ?>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-1">Date</label>
                                                                    <?php
                                                                        echo $this->Form->input('datep',array('id' => 'datepicker','class'=>'form-control','label'=>false));
                                                                    ?>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                
                                                <div class="text-center">
                                                    <button id="submit" name="submit" type="submit" class="btn btn-outline-success">Transfert</button>
                                                    <?php echo $this->Html->link('Annuler',array
                                                                ('controller' =>'warehouses','action'=>'index_depot'), array('class'=>'btn btn-outline-danger')); ?> 
                                                </div>
                                                
                                                </form>
                                        </div>
                                    </div>
                                    </div>
                                
                                    </div>

                                </div>
                                <!-- END DATA TABLE -->
                            </div>
                        </div>
                        


                        <?php $this->start('modale'); ?>
<script>
     $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        icons: {
            rightIcon: '<i class="fa fa-calendar"></i>'
        },
        format: 'yyyy-mm-dd'
    });
</script>
<?php $this->end(); ?>