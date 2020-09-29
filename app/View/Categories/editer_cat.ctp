<section class="au-breadcrumb ">
    <div class="section__content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="au-breadcrumb-content">
                        <div class="au-breadcrumb-left">
                            
                            <ul class="list-unstyled list-inline au-breadcrumb__list">
                                <li class="list-inline-item active">
                                    <a href="">Dashboard</a>
                                </li>
                                <li class="list-inline-item seprate">
                                    <span>/</span>
                                </li>
                                <li class="list-inline-item">Modification catégorie</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="main-content">
    <div class="section__content">
        <div class="container-fluid">
            <div class="row">
            <div class="col-lg-9" id="formEditUser">
    <h2>Modification de la catégorie</h2>
    <div class="card">
        <div class="card-body box-shadow--3dp">
        <?php echo $this->Form->create('Category'); ?>
                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                        <label class="control-label mb-1">Nom de la catégorie</label>
                        <?php
                            echo $this->Form->input('nom',array('placeholder' => 'Huile','class'=>'form-control','label'=>false));
                        ?>
                        </div>
                    </div> 
                </div>
                
                <div class="text-center">
                    <button id="submit" name="submit" type="submit" class="btn btn-outline-success">Modifier</button>
                    <?php echo $this->Html->link('Annuler',array
                                ('action'=>'index'), array('class'=>'btn btn-outline-danger')); ?> 
                </div>
                
                </form>
        </div>
    </div>

            </div>
    </div>
</div>