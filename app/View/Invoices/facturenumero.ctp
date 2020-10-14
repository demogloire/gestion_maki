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
                                    <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-title text-center" style="padding:10px">
                                            <h4>Date d'opération</h4>
                                        </div>
                                        <div class="card-body box-shadow--3dp">
                                        <?php echo $this->Form->create('Invoice'); ?>



                                                   
                                                
                                                      
                                                
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
                                                    <button id="submit" name="submit" type="submit" class="btn btn-outline-success">Créer la facture</button>
                                                    <?php echo $this->Html->link('Annuler',array
                                                                ('controller' =>'invoices','action'=>'index'), array('class'=>'btn btn-outline-danger')); ?> 
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
     $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        icons: {
            rightIcon: '<i class="fa fa-calendar"></i>'
        },
        format: 'yyyy-mm-dd'
    });
</script>
<?php $this->end(); ?>