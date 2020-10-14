<div class="row">
                            <div class="col-md-12">
                                <!-- DATA TABLE -->
                                <h3 class="title m-b-35 text-center">Etablissement facture</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                    </div>
                                    <div class="table-data__tool-right">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                    
                                        <div class="card box-shadow--4dp">
                                            <div class="text-center">
                                            <svg id="barcode"></svg>
                                            </div>
                                        
                                            <div class="card-header p-4">
                                                <a class="pt-2 d-inline-block" href="index.html" data-abc="true">MAKI GESTION</a>
                                                <div class="float-right">

                                                    Date: <?php echo date_format(date_create($facture['Invoice']['date_op']),'d-m-Y');?>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-4">
                                                    <div class="col-sm-6">
                                                        <h5 class="mb-3">De:</h5>
                                                        <h3 class="text-dark mb-1">Ets MAKI</h3>
                                                        <div>103ème, Avenue Kinshasa</div>
                                                        <div>Quartier le volcan</div>
                                                        <div>Ville de Goma</div>
                                                        <div>Nord-Kivu / Nord-Kivu</div>
                                                    </div>
                                                    <div class="col-sm-6 ">
                                                        <h5 class="mb-3">A:</h5>
                                                        <h3 class="text-dark mb-1">Anonyme</h3>
                                                    </div>
                                                </div>
                                                <div class="table-responsive-sm">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Action</th>
                                                                <th>Produit</th>
                                                                <th class="center">Qté</th>
                                                                <th class="right">P.U</th>
                                                                <th class="right">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($vente as $vente):?>
                                                            <tr>
                                                                <td class="left strong">Iphone 10X</td>
                                                                <td class="left"><?php echo $vente['Product']['nom_produit']; ?></td>
                                                                <td class="center"><?php echo $vente['Sale']['qte']; ?></td>
                                                                <td class="right"><?php echo $vente['Sale']['prix_unit']; ?></td>
                                                                <td class="right">$<?php echo $vente['Sale']['valeur']; ?></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-sm-3">
                                                    </div>
                                                    <div class="col-lg-4 col-sm-5 ml-auto">
                                                        <table class="table table-clear">
                                                            <tbody>
                                                                                                                              
                                                                <tr>
                                                                    <td class="left">
                                                                        <strong class="text-dark">Total</strong> </td>
                                                                    <td class="text-left">
                                                                        <strong class="text-dark">$
                                                                            <?php
                                                                            if($facture['Invoice']['valeur']==null){
                                                                                echo 0;
                                                                            }else{
                                                                                echo $facture['Invoice']['valeur'];
                                                                            }
                                                                            ?>
                                                                        </strong>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white">
                                                <p class="mb-0">MAKI-REF:<?php echo $facture['Invoice']['code_facture']; ?></p>
                                            </div>
                                        </div>
                                  
                                    </div>
                                    <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-title text-center" style="padding:10px">
                                            <h4>Ajouter sur la facture</h4>
                                        </div>
                                        <div class="card-body box-shadow--3dp">
                                        <?php echo $this->Form->create('Invoice'); ?>



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
                                                                        echo $this->Form->input('qte',array('placeholder' => '10','class'=>'form-control','label'=>false));
                                                                    ?>
                                                                </div>
                                                            </div> 
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-1">Prix de vente</label>
                                                                    <?php
                                                                        echo $this->Form->input('prix_unit',array('placeholder' => '11.5','class'=>'form-control','label'=>false));
                                                                    ?>
                                                                </div>
                                                            </div> 
                                                        </div>
                                
                                                
                                                <div class="text-center">
                                                    <button id="submit" name="submit" type="submit" class="btn btn-outline-success"><i class="fas fa-shopping-cart"></i>Ajouter</button>
                                                    <?php echo $this->Html->link('Terminer',array('controller' =>'warehouses','action'=>'index_depot'), array('class'=>'btn btn-outline-danger')); ?> 
                                                </div>
                                                
                                                </form>
                                        </div>
                                    </div>
                                    </div>
                                
                                </div>

                            </div>
                        </div>



<?php $this->start('modale'); ?>
<script>
    JsBarcode("#barcode", <?php echo $facture_numero; ?>,{
        height: 40,
        background: "#dddddd",
        displayValue: false
    });
</script>
<?php $this->end(); ?>
                        