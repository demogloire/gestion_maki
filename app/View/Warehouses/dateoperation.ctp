<section class="au-breadcrumbd ">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
        </div>
    </div>
</section>


<div class="col-lg-7"  id="formEditUser">
    <h2>Stockage du dépôt (Expiration).</h2>
    <div class="card">
        <div class="card-body box-shadow--3dp">
        <?php echo $this->Form->create('Warehouse'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Date de production</label>
                                    <?php
                                        echo $this->Form->input('datepro',array('class'=>'form-control','id'=>'datepickerb','label'=>false));
                                    ?>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Date d'expiration</label>
                                    <?php
                                        echo $this->Form->input('datexp',array('class'=>'form-control','id'=>'datepicker','label'=>false));
                                    ?>
                                </div>
                            </div> 
                        </div>
            
                <div class="text-center">
                    <button id="submit" name="submit" onclick="if (confirm('Une mauvaise date annulation opération')) { return true; } return false;" type="submit" class="btn btn-outline-success">Enregistrer </button>
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
            rightIcon: '<i class="la la-calendar"></i>'
        },
        format: 'yyyy-mm-dd'
    });

    $('#datepickerb').datepicker({
        uiLibrary: 'bootstrap4',
        icons: {
            rightIcon: '<i class="fa fa-calendar"></i>'
        },
        format: 'yyyy-mm-dd'
    });
</script>
<?php $this->end(); ?>





