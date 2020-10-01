<?php $this->start('modale'); ?>
<?php echo $this->Html->css('select2/select2'); ?>
<?php $this->end(); ?>

<section class="au-breadcrumb ">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="au-breadcrumb-content">
                        <div class="au-breadcrumb-left">
                           
                            <ul class="list-unstyled list-inline au-breadcrumb__list">
                                <li class="list-inline-item active">
                                    <a href="">Produit</a>
                                </li>
                                <li class="list-inline-item seprate">
                                    <span>/</span>
                                </li>
                                <li class="list-inline-item">Modifier produit</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="col-lg-12"  id="formEditUser">
    <h2>Ajouter un produit</h2>
    <div class="card">
        <div class="card-body box-shadow--3dp">
        <?php echo $this->Form->create('Product'); ?>
                
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label mb-1">Nom produit</label>
                                    <?php
                                        echo $this->Form->input('nom_produit',array('placeholder' => 'Huile','class'=>'form-control','label'=>false));
                                    ?>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label mb-1">Description</label>
                                    <?php
                                        echo $this->Form->input('description',array('placeholder' => 'Description du produit','class'=>'form-control','label'=>false));
                                    ?>
                                </div>
                            </div> 
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Prix d'achat</label>
                                    <?php
                                        echo $this->Form->input('cout_achat',array('placeholder' => '10.5','class'=>'form-control','label'=>false));
                                    ?>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Prix de vente detaille</label>
                                    <?php
                                        echo $this->Form->input('prix_detaille',array('placeholder' => '11.5','class'=>'form-control','label'=>false));
                                    ?>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Emballage</label>
                                    <?php
                                        echo $this->Form->input('emballage',array('placeholder' => 'Carton','class'=>'form-control','label'=>false));
                                    ?>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Mesure</label>
                                    <?php
                                        echo $this->Form->input('mesure',array('placeholder' => 'Pièce','class'=>'form-control','label'=>false));
                                    ?>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Continue par emballage</label>
                                    <?php
                                        echo $this->Form->input('nombre_contenu',array('placeholder' => '12','class'=>'form-control','label'=>false));
                                    ?>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label mb-1">Catégorie</label>
                                    <?php
                                        echo $this->Form->input('category_id',array('class'=>'form-control js-example-basic-single','label'=>false));
                                    ?>
                                </div>
                            </div> 
                        </div>

                        
                    </div>



                </div>
                


                <div class="text-center">
                    <button id="submit" name="submit" type="submit" class="btn btn-outline-success">Modifier</button>
                    <?php echo $this->Html->link('Annuler',array
                                ('controller' =>'users','action'=>'enregister_utilisateur'), array('class'=>'btn btn-outline-danger')); ?> 
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
     $(document).ready(function() {
    $('js-example-basic-single').select2();
}
</script>
<?php $this->end(); ?>





