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
                                <li class="list-inline-item">Carte des produits</li>
                            </ul>
                        </div>
                        <?php echo $this->Html->link('Ajouter un produit',array
                                ('controller' =>'products','action'=>'statut'), array('class'=>'btn btn-primary mb-1','escape'=>false)); ?>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
	preview {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column; }
  @media screen and (max-width: 996px) {
    .preview {
      margin-bottom: 20px; } }

.preview-pic {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1; }

.preview-thumbnail.nav-tabs {
  border: none;
  margin-top: 15px; }
  .preview-thumbnail.nav-tabs li {
    width: 18%;
    margin-right: 2.5%; }
    .preview-thumbnail.nav-tabs li img {
      max-width: 100%;
      display: block; }
    .preview-thumbnail.nav-tabs li a {
      padding: 0;
      margin: 0; }
    .preview-thumbnail.nav-tabs li:last-of-type {
      margin-right: 0; }

.tab-content {
  overflow: hidden; }
  .tab-content img {
    width: 100%;
    -webkit-animation-name: opacity;
            animation-name: opacity;
    -webkit-animation-duration: .3s;
            animation-duration: .3s; }



@media screen and (min-width: 997px) {
  .wrapper {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex; } }

.details {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column; }

.colors {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1; }

.product-title, .price, .sizes, .colors {
  font-weight: bold; }

.checked, .price span {
  color: #ff9f1a; }

.product-title, .rating, .product-description, .vote, .sizes {
  margin-bottom: 15px; }

.product-title {
  margin-top: 0; }

.size {
  margin-right: 10px; }
  .size:first-of-type {
    margin-left: 40px; }

.color {
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;
  height: 2em;
  width: 2em;
  border-radius: 2px; }
  .color:first-of-type {
    margin-left: 20px; }

.add-to-cart, .like {
  background: #ff9f1a;
  padding: 1.2em 1.5em;
  border: none;
  text-transform: UPPERCASE;
  font-weight: bold;
  color: #fff;
  -webkit-transition: background .3s ease;
          transition: background .3s ease; }
  .add-to-cart:hover, .like:hover {
    background: #b36800;
    color: #fff; }

.not-available {
  text-align: center;
  line-height: 2em; }
  .not-available:before {
    font-family: fontawesome;
    content: "\f00d";
    color: #fff; }

.orange {
  background: #ff9f1a; }

.green {
  background: #85ad00; }

.blue {
  background: #0076ad; }

.tooltip-inner {
  padding: 1.3em; }

@-webkit-keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
            transform: scale(3); }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1); } }

@keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
            transform: scale(3); }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1); } }
</style>
<div class="main-content">
    <div class="section__content">
        <div class="container-fluid">
        <div class="row">
                <?php foreach ($produits  as $produits ): current($produits);?>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="text-center"> <?php echo substr($produits["Product"]["nom_produit"], 0, 2);?> </h1>
                        </div>
                        <div class="card-body">


							<div class="row">

								<div class="details col-md-12">
								Produit: <h5 class="product-title"><?php echo $produits["Product"]["nom_produit"] ?>  </h5>
									<div class="product-description">
										<?php echo strip_tags($produits["Product"]["description"]); ?> 
										<br><br>
										<p class="text-justify">Information sur l'emballage:<b>
											<?php echo $produits["Product"]["emballage"] ?> contenant 
											<?php 
											if($produits["Product"]["nombre_contenu"]>1){
												echo 'des '.$produits["Product"]["mesure"].'s';

											}else{
												echo 'de '.$produits["Product"]["mesure"];	
											}
											?> dont <?php echo $produits["Product"]["nombre_contenu"].' '.$produits["Product"]["mesure"] ?> par <?php echo $produits["Product"]["emballage"] ?> 

										</b></p>	
									</div>
									<h4 class="price">Prix d'achat: <span>$<?php echo $produits["Product"]["cout_achat"] ?></span></h4>
									<h4 class="price">Prix détail: <span>$<?php echo $produits["Product"]["prix_detaille"] ?></span></h4>
                  <h4 class="price">Catégorie: <span><?php echo $produits["Category"]["nom"] ?></span></h4>

								</div>
							</div>


                            <hr>
                            <div class="card-text text-sm-center">
                            <?php if ($produits["Product"]["statut"]==true){?>
                                    <?php echo $this->Html->link('<i class="fa fa-unlock"></i></a>',array
                                ('controller' =>'products','action'=>'statut',
                                $produits['Product']['id']), array('escape'=>false)); ?>                                    

                                <?php }else {?>
                                    <?php echo $this->Html->link('<i class="fa fa-lock"></i></a>',array
                                ('controller' =>'products','action'=>'statut',
                                $produits['Product']['id']), array('escape'=>false)); ?>  
                                <?php }?>


                                <?php if ($produits["Product"]["perrisable"]==true){?>
                                    <?php echo $this->Html->link('<i class="fa fa-spinner"></i></a>',array
                                ('controller' =>'products','action'=>'perrisable',
                                $produits['Product']['id']), array('escape'=>false)); ?>                                    

                                <?php }else {?>
                                    <?php echo $this->Html->link('<i class="fa fa-sun-o"></i></a>',array
                                ('controller' =>'products','action'=>'perrisable',
                                $produits['Product']['id']), array('escape'=>false)); ?>  
                                <?php }?>


                                
                                <?php echo $this->Html->link('<i class="fa fa-edit"></i></a>',array
                                ('controller' =>'products','action'=>'editer',
                                $produits['Product']['id']), array('escape'=>false)); ?> 

                                                      
                            </div>
                        </div>

                        
                    </div>
                    
                </div>
                
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
