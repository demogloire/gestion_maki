<section class="au-breadcrumb ">
    <div class="section__content section__content--p30">
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
                                <li class="list-inline-item">Categorisation de produit</li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-primary mb-1" data-toggle="modal"
                            data-target="#largeModal">
                           <i class="fas fa-archive"></i> Ajouter une catégorie
                        </button>
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
            <div class="col-lg-8">
                                <!-- TOP CAMPAIGN-->
                                <div class="top-campaign">
                                    <h3 class=" m-b-30">Liste des catégorie</h3>
                                    <div class="table-responsive">
                                        <table class="table table-top-campaign">
                                            <tbody>
                                                <tr>
                                                    <td>Nom de la catégorie</td>
                                                    <td>Statut</td>
                                                </tr>
                                                <?php foreach ($categorie as $categorie): current($categorie);?>

                                                <tr>
                                                    <td><?php echo $categorie['Category']['nom']; ?>  
                                                    <?php echo $this->Html->link('<i class="fa fa-edit"></i></a>',array
                                                    ('action'=>'editer_cat',
                                                    $categorie['Category']['id']), array('escape'=>false)); ?> 



                                                    </td>
                                                    <td>
                                                    <?php if ($categorie["Category"]["statut"]==true){?>
                                                    <?php echo $this->Html->link('<i class="fa fa-unlock"></i></a>',array
                                                    ('action'=>'statut',
                                                    $categorie['Category']['id']), array('escape'=>false)); ?>                                    

                                                    <?php }else {?>
                                                        <?php echo $this->Html->link('<i class="fa fa-lock"></i></a>',array
                                                    ('action'=>'statut',
                                                    $categorie['Category']['id']), array('escape'=>false)); ?>  
                                                    <?php }?> 
                                                    </td>
                                                </tr>
                                            
                                                <?php endforeach ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--  END TOP CAMPAIGN-->
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
                <h4 class="modal-title" id="largeModalLabel">Nouvelle catégorie</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body box-shadow--3dp">
                        <?php echo $this->Form->create('Categories'); ?>
                
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label mb-1">Nom de la catégorie</label>
                                    <input name="data[Categories][nom]" id="CategoriesNom" type="text" class="form-control" placeholder="Huile" >
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
