<section class="au-breadcrumb ">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="au-breadcrumb-content">
                        <div class="au-breadcrumb-left">
                           
                            <ul class="list-unstyled list-inline au-breadcrumb__list">
                                <li class="list-inline-item active">
                                    <a href="">Utilisateur</a>
                                </li>
                                <li class="list-inline-item seprate">
                                    <span>/</span>
                                </li>
                                <li class="list-inline-item">Editer utilisateur</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="col-lg-9" id="formEditUser">
    <h2>Modification utilisateur</h2>
    <div class="card">
        <div class="card-body box-shadow--3dp">
        <?php echo $this->Form->create('User'); ?>
                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                        <label class="control-label mb-1">Prenom</label>
                        <?php
                            echo $this->Form->input('prenom',array('placeholder' => 'Jado','class'=>'form-control','label'=>false));
                        ?>
                        </div>
                    </div> 
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label class="control-label mb-1">Nom</label>
                        <?php
                            echo $this->Form->input('nom',array('placeholder' => 'BUKUZE','class'=>'form-control','label'=>false));
                        ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label class="control-label mb-1">Nom</label>
                        <?php
                            echo $this->Form->input('post_nom',array('placeholder' => 'BUKUZE','class'=>'form-control','label'=>false));
                        ?>
                        </div>
                    </div>                    
                </div>

                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="control-label mb-1">Fonction</label>
                            <?php
                                $options = array('Gérant' => 'Gérant', 'Vendeur' => 'Vendeur','Magasinier'=>'Magasinier');
                                echo $this->Form->select('role', $options,array('default'=>$role_d,'class'=>'form-control', 'empty'=>$role_d))
                            ?>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="control-label mb-1">Nom d'utilisateur</label>
                            <?php
                            echo $this->Form->input('username',array('placeholder' => 'jadochi','class'=>'form-control','label'=>false));
                            ?>
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
