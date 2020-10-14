<section class="au-breadcrumbd ">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
        </div>
    </div>
</section>


<div class="col-lg-7"  id="formEditUser">
    <h2>Stockage du dépôt.</h2>
    <div class="card">
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
                                        echo $this->Form->input('qte',array('placeholder' => '10','class'=>'form-control','label'=>false));
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
                    <button id="submit" name="submit" type="submit" class="btn btn-outline-success">Stocker</button>
                    <?php echo $this->Html->link('Annuler',array
                                ('controller' =>'warehouses','action'=>'index_depot'), array('class'=>'btn btn-outline-danger')); ?> 
                </div>
                
                </form>
        </div>
    </div>
</div>

<div style="height: 200px;"></div>

<style>
    #formEditUser{
        margin-top: 30px;
    }
    #formEditUser h2{
        margin-bottom: 20px;
    }
</style>

<?php $this->start('modale'); ?>
<script>
     $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        icons: {
            rightIcon: '<i class="fas fa-calendar"></i>'
        },
        format: 'yyyy-mm-dd'
    });
</script>
<?php $this->end(); ?>





