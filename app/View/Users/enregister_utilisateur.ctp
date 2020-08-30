<section class="au-breadcrumb ">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="au-breadcrumb-content">
                        <div class="au-breadcrumb-left">
                            
                            <ul class="list-unstyled list-inline au-breadcrumb__list">
                                <li class="list-inline-item active">
                                    <a href="{{url_for('user.index')}}">Dashboard</a>
                                </li>
                                <li class="list-inline-item seprate">
                                    <span>/</span>
                                </li>
                                <li class="list-inline-item">Liste des utilisateurs</li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-primary mb-1" data-toggle="modal"
                            data-target="#largeModal">
                           <i class="fas fa-user"></i> Ajouter utilisateur
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
        <div class="row">
                <?php foreach ($utilisateur as $utilisateur): current($utilisateur);?>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title mb-3"><?php echo $utilisateur["User"]["prenom"];?> </strong>
                        </div>
                        <div class="card-body">
                        
                            <div class="mx-auto d-block">
                                <div class="text-center">
                                <?php echo $this->Html->image('de.png', array('alt' => 'CakePHP')); ?>
                                </div>
                            
                                <h5 class="text-sm-center mt-2 mb-1"><?php echo $utilisateur["User"]["nom"].' '.$utilisateur["User"]["post_nom"];?></h5>

                                <div class="location text-sm-center"><i class="fa fa-key"></i> <?php echo $utilisateur["User"]["role"];?></div>
                                <div class="location text-sm-center"><?php echo $utilisateur["User"]["username"];?></div>
                            </div>
                            <hr>
                            <div class="card-text text-sm-center">
                                <?php if ($utilisateur["User"]["statut"]==true){?>
                                    <?php echo $this->Html->link('<i class="fa fa-unlock"></i></a>',array
                                ('controller' =>'users','action'=>'statut',
                                $utilisateur['User']['id']), array('escape'=>false)); ?>                                    

                                <?php }else {?>
                                    <?php echo $this->Html->link('<i class="fa fa-lock"></i></a>',array
                                ('controller' =>'users','action'=>'statut',
                                $utilisateur['User']['id']), array('escape'=>false)); ?>  
                                <?php }?>

                                <?php echo $this->Html->link('<i class="fa fa-edit"></i></a>',array
                                ('controller' =>'users','action'=>'editer_utilisateur',
                                $utilisateur['User']['id']), array('escape'=>false)); ?> 

                                
                            </div>
                        </div>

                        
                    </div>
                    
                </div>

                
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<?php $this->start('modale'); ?>

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Ajouter un nouvel utilisateur</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body box-shadow--3dp">
                        <?php echo $this->Form->create('Users'); ?>
                
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label mb-1">Prénom</label>
                                    <input name="data[Users][prenom]" id="UsersRole" type="text" class="form-control" placeholder="Jado" >
                                </div>
                            </div> 
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Nom</label>
                                    <input name="data[Users][nom]" id="UsersNom" type="text" class="form-control" placeholder="BUKUZE" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Post-nom</label>
                                    <input name="data[Users][post_nom]" id="UsersPostNom" type="text" class="form-control" placeholder="CHIRIMWAMI" >
                                </div>
                            </div>                    
                        </div>

                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label mb-1">Fonction</label>
                                    <select name="data[Users][role]" id="UsersRole" class="form-control">
                                        <option>Selectionnez</option>
                                        <option value="Gérant">Gérant</option>
                                        <option value="Vendeur">Vendeur</option>
                                        <option value="Magasinier">Magasinier</option>
                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Nom d'utilisateur</label>
                                    <input name="data[Users][username]" id="UsersUsername" type="text" class="form-control" placeholder="jadochi" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label mb-1">Mot de passe</label>
                                    <input name="data[Users][password]" id="UsersPassword" type="password" class="form-control">
                                </div>
                            </div>                    
                        </div>

                        <div class="text-center">
                            <button id="submit" name="submit" type="submit" class="btn btn-outline-success">Enregister</button>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-danger">Fermer</button>
                        </div>
                        
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->end(); ?>