<div class="row">
                            <div class="col-md-12">
                                <!-- DATA TABLE -->
                                <h3 class="title m-b-35 text-center">Ventes</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">

                                    </div>
                                    <div class="table-data__tool-right">
                                    <?php echo $this->Html->link('Vente',array('controller' =>'invoices','action'=>'facturenumero'), array('class'=>'au-btn au-btn-icon btn-info au-btn--small')); ?> 
                                    <?php echo $this->Html->link('Rapports',array('controller' =>'warehouses','action'=>'transfert'), array('class'=>'au-btn au-btn-icon btn-dark au-btn--small')); ?>  
                                    </div>
                                </div>
                                <?php if(count($factures)>0) {?>
                                    <div class="table-responsive table--no-card m-b-30">
                                        <table class="table table-borderless table-striped table-earning">
                                            <thead>
                                                <tr>
                                                    <th>date</th>
                                                    <th>Numero</th>
                                                    <th class="text-right">Valeur</th>
                                                    <th>Par</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($factures as $factures):?>
                                                <tr>
                                                    <td><?php echo date_format(date_create($factures['Invoice']['date_op']),'d-m-Y');?></td>
                                                    <td><?php echo $factures['Invoice']['code_facture']; ?></td>
                                                    <td class="text-right">$<?php echo $factures['Invoice']['valeur']; ?></td>
                                                    <td><?php echo $factures['User']['prenom']; ?></td>
                                                    <td> 
                                                    <div class="table-data-feature">
                                                        <?php echo $this->Html->link($this->Html->tag('i', ' ', array('class' => 'fas  fa-times')), array('controller' => 'users', 'action' => 'logout'), array('class'=>"item", 'data-toggle'=>"tooltip", 'data-placement'=>"top", 'title'=>"",'data-original-title'=>"Supprimer", 'escape' => false)); ?>
                                                        <?php echo $this->Html->link($this->Html->tag('i', ' ', array('class' => 'fas fa-print')), array('controller' => 'users', 'action' => 'logout'), array('class'=>"item", 'data-toggle'=>"tooltip", 'data-placement'=>"top", 'title'=>"",'data-original-title'=>"Imprimer", 'escape' => false)); ?>
                                                        <?php echo $this->Html->link($this->Html->tag('i', ' ', array('class' => 'fas fa-file')), array('controller' => 'users', 'action' => 'logout'), array('class'=>"item", 'data-toggle'=>"tooltip", 'data-placement'=>"top", 'title'=>"",'data-original-title'=>"PDF", 'escape' => false)); ?>
                                                    </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php }?>

                                <?php  if (count($factures) > 30) {?>
                                        <div class="box-footer clearfix">
                                            <ul class="pagination pagination-sm no-margin pull-right" style="margin-top:5px">
                                                <?php
                                                    echo $this->Paginator->prev(__('previous'), array('tag' => 'li'), null, array('class'=>'btn btn-success'));
                                                    echo $this->Paginator->numbers(array('separator'=>''));
                                                    echo $this->Paginator->next(__('Suivant'), array('tag' => 'li'), null, array('class'=>'btn btn-danger'));
                                                ?>

                                            </ul>
                                        </div>
                                <?php } ?>

                                <?php  if (count($factures) ==0) {?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <p class="text-center" style="margin-top:100px">
                                            <i class="la la-folder success font-large-5 text-center"></i>
                                            <h4 class="text-center">AUCUNE FACTURE .</h4>
                                            <br><br><br><br><br><br>
                                        </p>
                                    </div>
                                </div>
                                <?php } ?>


                              
                            </div>
                        </div>

                        