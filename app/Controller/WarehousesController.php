<?php

/**
*Stockage de produit
*/
class WarehousesController extends AppController{

  public $components = array('Utilitaire','Paginator');
//Enregistrement stockage de produit
  public function stock_magasin(){
    $this->layout ='admin';
    //Title
    $title="Ets MAKI |Stock Magasin";
    $this->loadModel('Expiration');

    $produit=$this->Warehouse->Product->find('list', array(
      'conditions'=>array(
                          'statut'=>true),
      'fields'=>array('id','nom_produit')
      ));
    //Vérification que si le produit existes
    if(empty($produit)){
      return $this->redirect(array('action'=>'enregistre_produit'));   
    }
    //Enregistrement des informaton du stock
    if($this->request->is(array('put','post'))){
      $data=$this->request->data;
      //Les informatons sur la quantté et le prix et la date
      $prix_qte=(int)$data['Warehouse']['qte'];
      $prix_prix_unitaire=(float)$data['Warehouse']['prix_unit'];
      $date_operation=$data['Warehouse']['datep'];
      //Vérification
      if ($prix_qte < 1 or !empty($prix_prix_unitaire) and $prix_prix_unitaire<0){
        $this->Session->setFlash("Vérifier la quantité et le prix",'danger');
        return $this->redirect(array('action'=>'stock_magasin')); 
      }
      //Vérification des informations manquante
      if(empty($prix_qte) or empty($date_operation)){
        $this->Session->setFlash("Le champ qté et date sont obligatoire",'danger');
        return $this->redirect(array('action'=>'stock_magasin'));
      }
      $id_produit=(int)$data['Warehouse']['product_id'];
      $produit_operation=$this->Warehouse->Product->findById($id_produit);
      //Prix unitaire
      $prix_operation_encours=null;
      if(empty($prix_prix_unitaire)){
        $prix_operation_encours=$produit_operation['Product']['cout_achat'];
      }else{
        $prix_operation_encours=$prix_prix_unitaire;
      }
      //Le produit est un produit perrisable
      if($produit_operation['Product']['perrisable']==true){
        //Solde
        $produit_solde=$this->Warehouse->find('all', array(
          'conditions'=>array(
            'product_id'=>$data['Warehouse']['product_id'],
            'solde'=>true,
            'depot'=>true
          )
        ));
        //Enregistrement pour la première fois
        if(empty($produit_solde)){
          $this->Warehouse->create();
            if($this->Warehouse->save(array(
              'qte'=>$prix_qte,
              'prix_unit'=>(float)$prix_operation_encours,
              'valeur_total'=>(float)$prix_operation_encours*(float)$prix_qte,
              'qte_total'=>$prix_qte,
              'valeur'=>(float)$prix_operation_encours*(float)$prix_qte,
              'vente'=>false,
              'transfert'=>false,
              'correction'=>false,
              'erreur_stockage'=>false,
              'stockage'=>true,
              'solde'=>true,
              'date_op'=>$date_operation,
              'user_id'=>$this->Session->read('Auth.User.id'),
              'product_id'=>$data['Warehouse']['product_id'],
              'depot'=>true,
              'boutique'=>false 
            ))){

              $this->Expiration->create();
              if(
                  $this->Expiration->save(
                    array(
                      'qte'=>$prix_qte,
                      'date_op'=>$date_operation,
                      'product_id'=>$data['Warehouse']['product_id'],
                      'user_id'=>$this->Session->read('Auth.User.id'),
                      'depot'=>true,
                      'warehouse_id'=>$this->Warehouse->id,
                      'boutique'=>false))
                ){
                    $produit_session_perrisable=array(
                    'id_produit'=>$produit_operation['Warehouse']['perrisable'],
                    'date_operation'=>$date_operation,
                    'id_stockage'=>$this->Warehouse->id,
                    'id_expiration'=>$this->Expiration->id,
                    'prix_qte'=>prix_qte,
                    'valeur'=>(float)$prix_operation_encours*(float)$prix_qte,
                    );
                  
                    $this->Session->write('produit_perissable', $produit_session_perrisable);
                    $this->Session->setFlash("Completer la date d'expiration",'succes');
                    return $this->redirect(array('action'=>'dateoperation'));
                } 
                           
            }
        }else{
          $qte_total=(int)$produit_solde['0']['Warehouse']['qte_total'] + (int)$prix_qte;
          $valeur_totale=(float)$produit_solde['0']['Warehouse']['valeur'] + ((float)$prix_operation_encours*(float)$prix_qte);
          $this->Warehouse->id=$produit_solde['0']['Warehouse']['id'];
          if($this->Warehouse->save(array(
            'solde'=>false
          ))){
            $this->Warehouse->create();
            if($this->Warehouse->save(array(
                'qte'=>$prix_qte,
                'prix_unit'=>(float)$prix_operation_encours,
                'valeur_total'=>(float)$prix_operation_encours*(float)$prix_qte,
                'qte_total'=>$qte_total,
                'valeur'=>$valeur_totale,
                'vente'=>false,
                'transfert'=>false,
                'correction'=>false,
                'erreur_stockage'=>false,
                'stockage'=>true,
                'solde'=>true,
                'date_op'=>$date_operation,
                'user_id'=>$this->Session->read('Auth.User.id'),
                'product_id'=>$data['Warehouse']['product_id'],
                'depot'=>true,
                'boutique'=>false 
              ))){

                if($this->Expiration->save(
                  array(
                    'qte'=>$prix_qte,
                    'date_op'=>$date_operation,
                    'product_id'=>$data['Warehouse']['product_id'],
                    'user_id'=>$this->Session->read('Auth.User.id'),
                    'depot'=>true,
                    'warehouse_id'=>$this->Warehouse->id,
                    'boutique'=>false))){
                  
                      $produit_session_perrisable=array(
                        'id_produit'=>$produit_operation['Warehouse']['perrisable'],
                        'date_operation'=>$date_operation,
                        'id_stockage'=>$this->Warehouse->id,
                        'id_expiration'=>$this->Expiration->id,
                        'prix_qte'=>prix_qte,
                        'valeur'=>(float)$prix_operation_encours*(float)$prix_qte,
                        );
                  
                  $this->Session->write('produit_perissable', $produit_session_perrisable);
                  $this->Session->setFlash("Completer la date d'expiration",'succes');
                  return $this->redirect(array('action'=>'dateoperation'));

                }                  
            }
          }   
        }
      //Produit non perrisable
      }else{
        //Solde de produit non perrisable
        $produit_solde=$this->Warehouse->find('all', array(
          'conditions'=>array(
            'product_id'=>$data['Warehouse']['product_id'],
            'solde'=>true,
            'depot'=>true
          )
        ));

        //Enregistrement pour la première fois
        if(empty($produit_solde)){

            if($this->Warehouse->save(array(
              'qte'=>$prix_qte,
              'prix_unit'=>(float)$prix_operation_encours,
              'valeur_total'=>(float)$prix_operation_encours*(float)$prix_qte,
              'qte_total'=>$prix_qte,
              'valeur'=>(float)$prix_operation_encours*(float)$prix_qte,
              'vente'=>false,
              'transfert'=>false,
              'correction'=>false,
              'erreur_stockage'=>false,
              'stockage'=>true,
              'solde'=>true,
              'date_op'=>$date_operation,
              'user_id'=>$this->Session->read('Auth.User.id'),
              'product_id'=>$data['Warehouse']['product_id'],
              'depot'=>true,
              'boutique'=>false 
            ))){
              $this->Session->setFlash("Completer la date d'expiration",'succes');
              return $this->redirect(array('action'=>'stock_magasin'));
            }
        }else{
          $qte_total=(int)$produit_solde['0']['Warehouse']['qte_total'] + (int)$prix_qte;
          $valeur_totale=(float)$produit_solde['0']['Warehouse']['valeur'] + ((float)$prix_operation_encours*(float)$prix_qte);
          $this->Warehouse->id=$produit_solde['0']['Warehouse']['id'];
          if($this->Warehouse->save(array(
            'solde'=>false
          ))){

            if($this->Warehouse->save(array(
                'qte'=>$prix_qte,
                'prix_unit'=>(float)$prix_operation_encours,
                'valeur_total'=>(float)$prix_operation_encours*(float)$prix_qte,
                'qte_total'=>$qte_total,
                'valeur'=>$valeur_totale,
                'vente'=>false,
                'transfert'=>false,
                'correction'=>false,
                'erreur_stockage'=>false,
                'stockage'=>true,
                'solde'=>true,
                'date_op'=>$date_operation,
                'user_id'=>$this->Session->read('Auth.User.id'),
                'product_id'=>$data['Warehouse']['product_id'],
                'depot'=>true,
                'boutique'=>false 
              ))){
                $this->Session->setFlash("Completer la date d'expiration",'succes');
                return $this->redirect(array('action'=>'stock_magasin'));
              }
            }   
        }
      }
    }
    //Injection des données dans le formulaire
    if(!$this->request->data){
      $this->set('products',$produit);
    }
    //Title
    $this->set('title',$title);
    
  }
  //Enregistrement de la date de l'opération
  public function dateoperation(){
    $this->layout ='admin';
    $this->loadModel('Expiration');
    //Title
    $title="Ets MAKI |Stock Magasin";
    $this->set('title',$title);
    $var_ver=$this->Session->read('produit_perissable');
    //Nombre de tentation
    $nombre_tentation=1;

    if(empty($var_ver)){
      return $this->redirect(array('action'=>'stock_magasin'));
    }

    if($this->request->is(array('put','post'))){
      //Les donnees
      $data=$this->request->data;
      // Les dates entre
      $date_production=$data['Warehouse']['datepro'];
      $date_expiration=$data['Warehouse']['datexp'];
      //Difference entre les dates
       $this->Session->write('tentation',$nombre_tentation);
      $retour_date=$this->Utilitaire->NbJours($date_production,$date_expiration);
      
      if($retour_date<0){
        $produit_recup=$this->Warehouse->findById((int)$var_ver['id_stockage']);

        $reste_qte=$produit_recup['Warehouse']['qte_total'] - $produit_recup['Warehouse']['qte'];
        $reste_valeur=$produit_recup['Warehouse']['valeur'] - $produit_recup['Warehouse']['valeur_total'];
        $this->Warehouse->id=$produit_recup['Warehouse']['id'];
        //Solde en supression
        if($this->Warehouse->save(array(
          'solde'=>false
        ))){
          if($this->Warehouse->save(array(
            'qte'=>$produit_recup['Warehouse']['qte'],
            'prix_unit'=>$produit_recup['Warehouse']['prix_unit'],
            'valeur_total'=>$produit_recup['Warehouse']['valeur_total'],
            'qte_total'=>$reste_qte,
            'valeur'=>$reste_valeur,
            'vente'=>false,
            'transfert'=>false,
            'correction'=>false,
            'erreur_stockage'=>true,
            'stockage'=>false,
            'solde'=>true,
            'date_op'=>$produit_recup['Warehouse']['date_op'],
            'user_id'=>$this->Session->read('Auth.User.id'),
            'product_id'=>$produit_recup['Warehouse']['product_id'],
            'depot'=>true,
            'boutique'=>false,
          ))){
            $this->Expiration->delete((int)$var_ver['id_expiration']);
            $this->Session->setFlash("L'opération à été annulée",'danger');
            return $this->redirect(array('action'=>'stock_magasin'));
          }
        }
    }else{
         //ENREGISTREMENT DE LA DATE
         $this->Expiration->id=(int)$var_ver['id_expiration'];
         if($this->Expiration->save(array('date_expiration'=>$date_production,
         'date_production'=>$date_expiration,
          )))
          
          {
            $this->Session->delete('produit_perissable');
            $this->Session->delete('tentation');
            $this->Session->setFlash("Stockage avec succès",'succes');
            return $this->redirect(array('action'=>'stock_magasin'));

          }
      }
    }
  }
  //Dépôt
  public function index_depot(){
    $this->layout ='admin';
    //Title
    $title="Ets MAKI |Stock Magasin";
    $this->set('title',$title);

    $this->Paginator->settings =array(
      'fields'=> array(
      'Warehouse.qte_total',
      'Warehouse.valeur',
      'Warehouse.date_op',
      'Product.nom_produit',
      ),
      'conditions'=>array('depot'=>true,'solde'=>true, 'qte_total >'=>0),
      'limit' =>10,
      'order'=>array('Warehouse.qte_total'=>'DESC'),
      'paramType'=>'querystring'
      );
      //var_dump($this->Paginator->paginate('Warehouse'));
      //die();
      $this->set('stock_depot',$this->Paginator->paginate('Warehouse'));

  }
  //PDF
    public function index_pdf(){
      $this->layout ='pdfimp';
      //Title
      $title="Ets MAKI |PDF";
      $this->set('title',$title);
      $this->Paginator->settings =array(
        'fields'=> array(
        'Warehouse.qte_total',
        'Warehouse.valeur',
        'Warehouse.date_op',
        'Product.nom_produit',
        ),
        'conditions'=>array('depot'=>true,'solde'=>true, 'qte_total >'=>0),
        'order'=>array('Warehouse.qte_total'=>'DESC'),
        'paramType'=>'querystring'
        );
        //var_dump($this->Paginator->paginate('Warehouse'));
        //die();
        $this->set('stock_depot',$this->Paginator->paginate('Warehouse'));
  
    }
    //Impression
    public function index_impression(){
        $this->layout ='pdf';
        //Title
        $title="Ets MAKI |Impression";
        $this->set('title',$title);
        $this->Paginator->settings =array(
          'fields'=> array(
          'Warehouse.qte_total',
          'Warehouse.valeur',
          'Warehouse.date_op',
          'Product.nom_produit',
          ),
          'conditions'=>array('depot'=>true,'solde'=>true, 'qte_total >'=>0),
          'order'=>array('Warehouse.qte_total'=>'DESC'),
          'paramType'=>'querystring'
          );
          //var_dump($this->Paginator->paginate('Warehouse'));
          //die();
          $this->set('stock_depot',$this->Paginator->paginate('Warehouse'));
    
    }
    //Fonction de transfert
    public function transfert(){
      $this->layout ='admin';
      $this->loadModel('Expiration');
      //Title
      $title="Ets MAKI |Stock Magasin";
      $this->set('title',$title);
      
      $this->Paginator->settings =array(
        'fields'=> array(
        'Warehouse.qte',
        'Warehouse.prix_unit',
        'Warehouse.valeur',
        'Warehouse.qte_total',
        'Warehouse.valeur_total',
        'Warehouse.date_op',
        'Product.nom_produit',
        ),
        'conditions'=>array('depot'=>true,'transfert'=>true),
        'order'=>array('Warehouse.date_op'=>'DESC'),
        'limit'=>30,
        'paramType'=>'querystring'
        );
        //Pqginqtion
        $this->set('stock_depot',$this->Paginator->paginate('Warehouse'));
        // Le produit
        $produit=$this->Warehouse->Product->find('list', array(
          'conditions'=>array(
                              'statut'=>true),
          'fields'=>array('id','nom_produit')
          ));
        //Requête des produits
        if(!$this->request->data){
            $this->set('products',$produit);
        }
        //Information
        if($this->request->is(array('put','post'))){
          $data=$this->request->data;
          //Les informatons sur la quantté et le prix et la date
          $prix_qte=(int)$data['Warehouse']['qte'];
          $prix_prix_unitaire=(float)$data['Warehouse']['prix_unit'];
          $date_operation=$data['Warehouse']['datep'];
          //Vérification
          if ($prix_qte < 1 or !empty($prix_prix_unitaire) and $prix_prix_unitaire<0){
            $this->Session->setFlash("Vérifier la quantité et le prix",'danger');
            return $this->redirect(array('action'=>'transfert')); 
          }
          //Vérification des informations manquante
          if(empty($prix_qte) or empty($date_operation)){
            $this->Session->setFlash("Le champ qté et date sont obligatoire",'danger');
            return $this->redirect(array('action'=>'transfert'));
          }
          $id_produit=(int)$data['Warehouse']['product_id'];
          $produit_operation=$this->Warehouse->Product->findById($id_produit);
          //Prix unitaire
          $prix_detaille=null;

          if(empty($prix_prix_unitaire)){
            $prix_operation_encours=$produit_operation['Product']['prix_detaille'];
          }else{
            $prix_operation_encours=$prix_prix_unitaire;
          }
          //Solde de l'operation
          $solde_stock=$this->Warehouse->find('all',
          array('conditions'=>array('solde'=>true,'depot'=>true, 'product_id'=>$id_produit),
          'fields'=>array('Warehouse.qte_total','Warehouse.valeur','Product.perrisable','Product.nombre_contenu','id')));
          //Les quantites
          $solde_quantite=(int)$solde_stock['0']['Warehouse']['qte_total'];
          $solde_valeur=(int)$solde_stock['0']['Warehouse']['valeur'];
          $produit_type=$solde_stock['0']['Product']['perrisable'];
          //Prix de stockage
          $prix_stockage_produit=$this->Warehouse->find('all',
          array('conditions'=>array('stockage'=>true, 'depot'=>true, 'product_id'=>$id_produit),
          'fields'=>array('Warehouse.prix_unit'),
          'limit'=>2,
          'order'=>array('Warehouse.id'=>'ASC')));
          //Prix de stockage
          $prix_stock_entree_une=0;
          $prix_stock_entree_deux=0;
          if(count($prix_stockage_produit)>1){
            $prix_stock_entree_une=(float)$prix_stockage_produit['0']['Warehouse']['prix_unit'];
            $prix_stock_entree_deux=(float)$prix_stockage_produit['1']['Warehouse']['prix_unit'];;
          }elseif(count($prix_stockage_produit)<=1){
            $prix_stock_entree_une=(float)$prix_stockage_produit['0']['Warehouse']['prix_unit'];
          }

          //PERRISABLE
          if($produit_type==true){
            $nombre_contenu=(int)$solde_stock['0']['Product']['nombre_contenu'];
            $qte_transfert=null;
            $prix_de_vente=null;
            //Quantite et quantite
            if($nombre_contenu>1){
              $qte_transfert=(int)$prix_qte*$nombre_contenu;
              $prix_de_vente=($prix_operation_encours*(int)$prix_qte)/$qte_transfert;
            }else{
              $qte_transfert=$prix_qte;
              $prix_de_vente=$prix_operation_encours;
            }

        
            //Expiration des produits
            $produit_encours_expiration=$this->Expiration->find('all',array(
              'conditions'=>array('depot'=>true, 'product_id'=>$id_produit),
              'fields'=>array('qte','date_expiration','date_production','id'),
              'order'=>array('date_expiration'=>'ASC')));
            //Quantité demandée
            if($prix_qte>$solde_quantite){
              $this->Session->setFlash("Quantité à transferer est sup. à la qté disponible",'danger');
              return $this->redirect(array('action'=>'transfert'));
            }else{
              $longeur=count($produit_encours_expiration);
              
              if($longeur>1){

                //La premier entree
                if((int)$produit_encours_expiration['0']['Expiration']['qte']>=$prix_qte){
                  $this->Warehouse->id=(int)$solde_stock['0']['Warehouse']['id'];
                  if($this->Warehouse->save(array('solde'=>false))){
                      //Reduction des quantite
                      $nv_qte=$solde_quantite-$prix_qte;
                      $nv_val_unit=$prix_qte * $prix_stock_entree_une;
                      $nv_val=$solde_valeur - $nv_val_unit;
                      $this->Warehouse->create();
                      if($this->Warehouse->save(array(
                        'qte'=>$prix_qte,
                        'prix_unit'=>(float)$prix_stock_entree_une,
                        'valeur_total'=>(float)$nv_val_unit,
                        'qte_total'=>$nv_qte,
                        'valeur'=>$nv_val,
                        'vente'=>false,
                        'transfert'=>true,
                        'correction'=>false,
                        'erreur_stockage'=>false,
                        'stockage'=>false,
                        'solde'=>true,
                        'date_op'=>$date_operation,
                        'user_id'=>$this->Session->read('Auth.User.id'),
                        'product_id'=>$data['Warehouse']['product_id'],
                        'depot'=>true,
                        'boutique'=>false 
                      ))){
                        //============================== VERIFICATION DU STOCK===================
                        //MISE A JOUR DES INFORMATIONS BOUTIQUE
                        //Solde
                        $produit_solde=$this->Warehouse->find('all', array(
                          'conditions'=>array(
                            'product_id'=>$data['Warehouse']['product_id'],
                            'solde'=>true,
                            'boutique'=>true
                          )
                        ));
                        //Enregistrement des informations de la boutique
                        //SANS SOLDE
                        if(empty($produit_solde)){
                          $this->Warehouse->create();
                          if($this->Warehouse->save(array(
                            'qte'=>$qte_transfert,
                            'prix_unit'=>(float)$prix_de_vente,
                            'valeur_total'=>(float)$prix_de_vente * $qte_transfert,
                            'qte_total'=>$qte_transfert,
                            'valeur'=>(float)$prix_de_vente * $qte_transfert,
                            'vente'=>false,
                            'transfert'=>false,
                            'correction'=>false,
                            'erreur_stockage'=>false,
                            'stockage'=>true,
                            'solde'=>true,
                            'date_op'=>$date_operation,
                            'user_id'=>$this->Session->read('Auth.User.id'),
                            'product_id'=>$data['Warehouse']['product_id'],
                            'depot'=>false,
                            'boutique'=>true 
                          ))){
                            //Les quantites sont egales
                            if((int)$produit_encours_expiration['0']['Expiration']['qte']==$prix_qte){
                              $this->Expiration->delete((int)$produit_encours_expiration['0']['Expiration']['id']);
                              //Boutique
                              if($this->Expiration->save(
                                array(
                                  'qte'=>$qte_transfert,
                                  'date_op'=>$date_operation,
                                  'product_id'=>$data['Warehouse']['product_id'],
                                  'user_id'=>$this->Session->read('Auth.User.id'),
                                  'date_expiration'=>$produit_encours_expiration['0']['Expiration']['date_expiration'],
                                  'date_production'=>$produit_encours_expiration['0']['Expiration']['date_production'],
                                  'warehouse_id'=>$this->Warehouse->id,
                                  'depot'=>false,
                                  'boutique'=>true))){
                                    $this->Session->setFlash("Transefert réussi",'succes');
                                    return $this->redirect(array('action'=>'transfert'));
                                }

                            }else{
                              $vn_qte_expiration=(int)$produit_encours_expiration['0']['Expiration']['qte'] - $prix_qte;
                              $this->Expiration->id=(int)$produit_encours_expiration['0']['Expiration']['id'];
                              if($this->Expiration->save(array('qte'=>$vn_qte_expiration))){
                                $this->Expiration->create();
                                if($this->Expiration->save(
                                  array(
                                    'qte'=>$qte_transfert,
                                    'date_op'=>$date_operation,
                                    'product_id'=>$data['Warehouse']['product_id'],
                                    'user_id'=>$this->Session->read('Auth.User.id'),
                                    'date_expiration'=>$produit_encours_expiration['0']['Expiration']['date_expiration'],
                                    'date_production'=>$produit_encours_expiration['0']['Expiration']['date_production'],
                                    'warehouse_id'=>$this->Warehouse->id,
                                    'depot'=>false,
                                    'boutique'=>true))){
                                      $this->Session->setFlash("Transefert réussi",'succes');
                                      return $this->redirect(array('action'=>'transfert'));
                                  }
                              }
                            }
                          }
                        }else{
                          //AVEC SOLDE
                          $this->Warehouse->id=(int)$produit_solde['0']['Warehouse']['id'];
                          if($this->Warehouse->save(array('solde'=>false))){
                            $this->Warehouse->create();
                            if($this->Warehouse->save(array(
                              'qte'=>$qte_transfert,
                              'prix_unit'=>(float)$prix_de_vente,
                              'valeur_total'=>(float)$prix_de_vente * $qte_transfert,
                              'qte_total'=>$qte_transfert + (int)$produit_solde['0']['Warehouse']['qte'],
                              'valeur'=>((float)$prix_de_vente * $qte_transfert) + (float)$produit_solde['0']['Warehouse']['valeur'],
                              'vente'=>false,
                              'transfert'=>false,
                              'correction'=>false,
                              'erreur_stockage'=>false,
                              'stockage'=>true,
                              'solde'=>true,
                              'date_op'=>$date_operation,
                              'user_id'=>$this->Session->read('Auth.User.id'),
                              'product_id'=>$data['Warehouse']['product_id'],
                              'depot'=>false,
                              'boutique'=>true 
                            ))){
                              //Les quantites sont egales
                              if((int)$produit_encours_expiration['0']['Expiration']['qte']==$prix_qte){
                                $this->Expiration->delete((int)$produit_encours_expiration['0']['Expiration']['id']);
                                //Boutique
                                if($this->Expiration->save(
                                  array(
                                    'qte'=>$qte_transfert,
                                    'date_op'=>$date_operation,
                                    'product_id'=>$data['Warehouse']['product_id'],
                                    'user_id'=>$this->Session->read('Auth.User.id'),
                                    'date_expiration'=>$produit_encours_expiration['0']['Expiration']['date_expiration'],
                                    'date_production'=>$produit_encours_expiration['0']['Expiration']['date_production'],
                                    'warehouse_id'=>$this->Warehouse->id,
                                    'depot'=>false,
                                    'boutique'=>true))){
                                      $this->Session->setFlash("Transefert réussi",'succes');
                                      return $this->redirect(array('action'=>'transfert'));
                                  }

                              }else{
                                $vn_qte_expiration=(int)$produit_encours_expiration['0']['Expiration']['qte'] - $prix_qte;
                                $this->Expiration->id=(int)$produit_encours_expiration['0']['Expiration']['id'];
                                if($this->Expiration->save(array('qte'=>$vn_qte_expiration))){
                                  $this->Expiration->create();
                                  if($this->Expiration->save(
                                    array(
                                      'qte'=>$qte_transfert,
                                      'date_op'=>$date_operation,
                                      'product_id'=>$data['Warehouse']['product_id'],
                                      'user_id'=>$this->Session->read('Auth.User.id'),
                                      'date_expiration'=>$produit_encours_expiration['0']['Expiration']['date_expiration'],
                                      'date_production'=>$produit_encours_expiration['0']['Expiration']['date_production'],
                                      'warehouse_id'=>$this->Warehouse->id,
                                      'depot'=>false,
                                      'boutique'=>true))){
                                        $this->Session->setFlash("Transefert réussi",'succes');
                                        return $this->redirect(array('action'=>'transfert'));
                                    }
                                }
                              }
                            }
                          }
                        }
                        //=============================== VERIFICATION ==========================
                      }
                  }
                }else{
                  $reste=$prix_qte-(int)$produit_encours_expiration['0']['Expiration']['qte'];
                  $enregistrer=(int)$produit_encours_expiration['1']['Expiration']['qte']-(int)$reste;
                  $this->Warehouse->id=(int)$produit_encours_expiration['1']['Expiration']['id'];
                  $this->Expiration->save(
                    array(
                      'qte'=>$enregistrer,
                      'date_op'=>$produit_encours_expiration['1']['Expiration']['date_op'], 
                      'product_id'=>$data['Warehouse']['product_id'],
                      'user_id'=>$this->Session->read('Auth.User.id'),
                      'date_expiration'=>$produit_encours_expiration['1']['Expiration']['date_expiration'],
                      'date_production'=>$produit_encours_expiration['1']['Expiration']['date_production'],
                      'warehouse_id'=>(int)$produit_encours_expiration['1']['Expiration']['warehouse_id'],
                      'depot'=>true,
                      'boutique'=>false));

                  $this->Expiration->delete((int)$produit_encours_expiration['0']['Expiration']['id']);
                  $this->Warehouse->id=(int)$solde_stock['0']['Warehouse']['id'];
                  if($this->Warehouse->save(array('solde'=>false))){
                      //Reduction des quantite
                      $valeur_pro_sortie=($prix_qte*$prix_stock_entree_une) + ($prix_stock_entree_deux * $reste);
                      $nv_qte=$solde_quantite-$prix_qte;
                      $nv_val_unit=$prix_qte * $prix_stock_entree_deux;
                      $nv_val=$solde_valeur - $valeur_pro_sortie;
                      $this->Warehouse->create();
                      if($this->Warehouse->save(array(
                        'qte'=>$prix_qte,
                        'prix_unit'=>(float)$prix_stock_entree_deux,
                        'valeur_total'=>(float)$nv_val_unit,
                        'qte_total'=>$nv_qte,
                        'valeur'=>$nv_val,
                        'vente'=>false,
                        'transfert'=>true,
                        'correction'=>false,
                        'erreur_stockage'=>false,
                        'stockage'=>false,
                        'solde'=>true,
                        'date_op'=>$date_operation,
                        'user_id'=>$this->Session->read('Auth.User.id'),
                        'product_id'=>$data['Warehouse']['product_id'],
                        'depot'=>true,
                        'boutique'=>false 
                      ))){
                        //MISE A JOUR DES INFORMATIONS BOUTIQUE
                        //Solde
                        $produit_solde=$this->Warehouse->find('all', array(
                          'conditions'=>array(
                            'product_id'=>$data['Warehouse']['product_id'],
                            'solde'=>true,
                            'boutique'=>true
                          )
                        ));
                        //Enregistrement des informations de la boutique
                        //SANS SOLDE
                        if(empty($produit_solde)){
                          $this->Warehouse->create();
                          if($this->Warehouse->save(array(
                            'qte'=>$qte_transfert,
                            'prix_unit'=>(float)$prix_de_vente,
                            'valeur_total'=>(float)$prix_de_vente * $qte_transfert,
                            'qte_total'=>$qte_transfert,
                            'valeur'=>(float)$prix_de_vente * $qte_transfert,
                            'vente'=>false,
                            'transfert'=>false,
                            'correction'=>false,
                            'erreur_stockage'=>false,
                            'stockage'=>true,
                            'solde'=>true,
                            'date_op'=>$date_operation,
                            'user_id'=>$this->Session->read('Auth.User.id'),
                            'product_id'=>$data['Warehouse']['product_id'],
                            'depot'=>false,
                            'boutique'=>true 
                          ))){
                            //Les quantites sont egales
                            if(true){
                              $this->Expiration->id=(int)$produit_encours_expiration['1']['Expiration']['id'];
                              if($this->Expiration->save(
                                array(
                                  'qte'=>$enregistrer))){
                                    //Boutique
                                  if($this->Expiration->save(
                                    array(
                                      'qte'=>$qte_transfert,
                                      'date_op'=>$date_operation,
                                      'product_id'=>$data['Warehouse']['product_id'],
                                      'date_expiration'=>$produit_encours_expiration['1']['Expiration']['date_expiration'],
                                      'date_production'=>$produit_encours_expiration['1']['Expiration']['date_production'],
                                      'user_id'=>$this->Session->read('Auth.User.id'),
                                      'warehouse_id'=>$this->Warehouse->id,
                                      'depot'=>false,
                                      'boutique'=>true))){
                                        $this->Session->setFlash("Transefert réussi",'succes');
                                        return $this->redirect(array('action'=>'transfert'));
                                  }
                                }
                            }

                          }
                        }else{
                          //AVEC SOLDE
                          $this->Warehouse->id=(int)$produit_solde['0']['Warehouse']['id'];
                          if($this->Warehouse->save(array('solde'=>false))){
                            $this->Warehouse->create();
                            if($this->Warehouse->save(array(
                              'qte'=>$qte_transfert,
                              'prix_unit'=>(float)$prix_de_vente,
                              'valeur_total'=>(float)$prix_de_vente * $qte_transfert,
                              'qte_total'=>$qte_transfert + (int)$produit_solde['0']['Warehouse']['qte'],
                              'valeur'=>((float)$prix_de_vente * $qte_transfert) + (float)$produit_solde['0']['Warehouse']['valeur'],
                              'vente'=>false,
                              'transfert'=>false,
                              'correction'=>false,
                              'erreur_stockage'=>false,
                              'stockage'=>true,
                              'solde'=>true,
                              'date_op'=>$date_operation,
                              'user_id'=>$this->Session->read('Auth.User.id'),
                              'product_id'=>$data['Warehouse']['product_id'],
                              'depot'=>false,
                              'boutique'=>true 
                            ))){
                              //Les quantites sont egales

                              if(true){
                                $this->Expiration->id=(int)$produit_encours_expiration['1']['Expiration']['id'];
                                  if($this->Expiration->save(
                                    array(
                                  'qte'=>$enregistrer)))
                                //Boutique
                                if($this->Expiration->save(
                                  array(
                                    'qte'=>$qte_transfert,
                                    'date_op'=>$date_operation,
                                    'product_id'=>$data['Warehouse']['product_id'],
                                    'user_id'=>$this->Session->read('Auth.User.id'),
                                    'date_expiration'=>$produit_encours_expiration['1']['Expiration']['date_expiration'],
                                    'date_production'=>$produit_encours_expiration['1']['Expiration']['date_production'],
                                    'warehouse_id'=>$this->Warehouse->id,
                                    'depot'=>false,
                                    'boutique'=>true))){
                                      $this->Session->setFlash("Transefert réussi",'succes');
                                      return $this->redirect(array('action'=>'transfert'));
                                  }
                              }
                            }
                          }
                        }
                      }
                  }
                }
              }else{
                //AVEC UN SEUL ENREGISTREMENT DANS LA
                if((int)$produit_encours_expiration['0']['Expiration']['qte']>=$prix_qte){
                  $this->Warehouse->id=(int)$solde_stock['0']['Warehouse']['id'];
                  if($this->Warehouse->save(array('solde'=>false))){
                      //Reduction des quantite
                      $nv_qte=$solde_quantite-$prix_qte;
                      $nv_val_unit=$prix_qte * $prix_stock_entree_une;
                      $nv_val=$solde_valeur - $nv_val_unit;
                      $this->Warehouse->create();
                      if($this->Warehouse->save(array(
                        'qte'=>$prix_qte,
                        'prix_unit'=>(float)$prix_prix_unitaire,
                        'valeur_total'=>(float)$nv_val_unit,
                        'qte_total'=>$nv_qte,
                        'valeur'=>$nv_val,
                        'vente'=>false,
                        'transfert'=>true,
                        'correction'=>false,
                        'erreur_stockage'=>false,
                        'stockage'=>false,
                        'solde'=>true,
                        'date_op'=>$date_operation,
                        'user_id'=>$this->Session->read('Auth.User.id'),
                        'product_id'=>$data['Warehouse']['product_id'],
                        'depot'=>true,
                        'boutique'=>false 
                      ))){
                        
                        //MISE A JOUR DES INFORMATIONS BOUTIQUE
                        //Solde
                        $produit_solde=$this->Warehouse->find('all', array('conditions'=>array('product_id'=>$data['Warehouse']['product_id'],
                        'solde'=>true,'boutique'=>true)));
                        //Enregistrement des informations de la boutique
                        //SANS SOLDE
                        if(empty($produit_solde)){
                          $this->Warehouse->create();
                          if($this->Warehouse->save(array(
                            'qte'=>$qte_transfert,
                            'prix_unit'=>(float)$prix_de_vente,
                            'valeur_total'=>(float)$prix_de_vente * $qte_transfert,
                            'qte_total'=>$qte_transfert,
                            'valeur'=>(float)$prix_de_vente * $qte_transfert,
                            'vente'=>false,
                            'transfert'=>false,
                            'correction'=>false,
                            'erreur_stockage'=>false,
                            'stockage'=>true,
                            'solde'=>true,
                            'date_op'=>$date_operation,
                            'user_id'=>$this->Session->read('Auth.User.id'),
                            'product_id'=>$data['Warehouse']['product_id'],
                            'depot'=>false,
                            'boutique'=>true 
                          ))){
                            //Les quantites sont egales
                            if((int)$produit_encours_expiration['0']['Expiration']['qte']==$prix_qte){
                              $this->Expiration->delete((int)$produit_encours_expiration['0']['Expiration']['id']);
                              //Boutique
                              if($this->Expiration->save(
                                array(
                                  'qte'=>$qte_transfert,
                                  'date_op'=>$date_operation,
                                  'product_id'=>$data['Warehouse']['product_id'],
                                  'user_id'=>$this->Session->read('Auth.User.id'),
                                  'date_expiration'=>$produit_encours_expiration['0']['Expiration']['date_expiration'],
                                  'date_production'=>$produit_encours_expiration['0']['Expiration']['date_production'],
                                  'warehouse_id'=>$this->Warehouse->id,
                                  'depot'=>false,
                                  'boutique'=>true))){

                                    $this->Session->setFlash("Transefert réussi",'succes');
                                    return $this->redirect(array('action'=>'transfert'));
                                }
                            }else{
                              $vn_qte_expiration=(int)$produit_encours_expiration['0']['Expiration']['qte'] - $prix_qte;
                              $this->Expiration->id=(int)$produit_encours_expiration['0']['Expiration']['id'];
                              if($this->Expiration->save(array('qte'=>$vn_qte_expiration))){
                                $this->Expiration->create();
                                if($this->Expiration->save(
                                  array(
                                    'qte'=>$qte_transfert,
                                    'date_op'=>$date_operation,
                                    'product_id'=>$data['Warehouse']['product_id'],
                                    'user_id'=>$this->Session->read('Auth.User.id'),
                                    'date_expiration'=>$produit_encours_expiration['0']['Expiration']['date_expiration'],
                                    'date_production'=>$produit_encours_expiration['0']['Expiration']['date_production'],
                                    'warehouse_id'=>$this->Warehouse->id,
                                    'depot'=>false,
                                    'boutique'=>true))){
                                      $this->Session->setFlash("Transefert réussi",'succes');
                                      return $this->redirect(array('action'=>'transfert'));
                                  }
                              }
                            }
                          }
                        }else{
                          //AVEC SOLDE
                          $this->Warehouse->id=(int)$produit_solde['0']['Warehouse']['id'];
                          if($this->Warehouse->save(array('solde'=>false))){
                            $this->Warehouse->create();
                            if($this->Warehouse->save(array(
                              'qte'=>$qte_transfert,
                              'prix_unit'=>(float)$prix_de_vente,
                              'valeur_total'=>(float)$prix_de_vente * $qte_transfert,
                              'qte_total'=>$qte_transfert + (int)$produit_solde['0']['Warehouse']['qte'],
                              'valeur'=>((float)$prix_de_vente * $qte_transfert) + (float)$produit_solde['0']['Warehouse']['valeur'],
                              'vente'=>false,
                              'transfert'=>false,
                              'correction'=>false,
                              'erreur_stockage'=>false,
                              'stockage'=>true,
                              'solde'=>true,
                              'date_op'=>$date_operation,
                              'user_id'=>$this->Session->read('Auth.User.id'),
                              'product_id'=>$data['Warehouse']['product_id'],
                              'depot'=>false,
                              'boutique'=>true 
                            ))){
                              //Les quantites sont egales
                              if((int)$produit_encours_expiration['0']['Expiration']['qte']==$prix_qte){
                                $this->Expiration->delete((int)$produit_encours_expiration['0']['Expiration']['id']);
                                //Boutique
                                if($this->Expiration->save(
                                  array(
                                    'qte'=>$qte_transfert,
                                    'date_op'=>$date_operation,
                                    'product_id'=>$data['Warehouse']['product_id'],
                                    'user_id'=>$this->Session->read('Auth.User.id'),
                                    'date_expiration'=>$produit_encours_expiration['0']['Expiration']['date_expiration'],
                                    'date_production'=>$produit_encours_expiration['0']['Expiration']['date_production'],
                                    'warehouse_id'=>$this->Warehouse->id,
                                    'depot'=>false,
                                    'boutique'=>true))){
                                      $this->Session->setFlash("Transefert réussi",'succes');
                                      return $this->redirect(array('action'=>'transfert'));
                                  }

                              }else{
                                $vn_qte_expiration=(int)$produit_encours_expiration['0']['Expiration']['qte'] - $prix_qte;
                                $this->Expiration->id=(int)$produit_encours_expiration['0']['Expiration']['id'];
                                if($this->Expiration->save(array('qte'=>$vn_qte_expiration))){
                                  $this->Expiration->create();
                                  if($this->Expiration->save(
                                    array(
                                      'qte'=>$qte_transfert,
                                      'date_op'=>$date_operation,
                                      'product_id'=>$data['Warehouse']['product_id'],
                                      'user_id'=>$this->Session->read('Auth.User.id'),
                                      'date_expiration'=>$produit_encours_expiration['0']['Expiration']['date_expiration'],
                                      'date_production'=>$produit_encours_expiration['0']['Expiration']['date_production'],
                                      'warehouse_id'=>$this->Warehouse->id,
                                      'depot'=>false,
                                      'boutique'=>true))){
                                        $this->Session->setFlash("Transefert réussi",'succes');
                                        return $this->redirect(array('action'=>'transfert'));
                                    }
                                }
                              }
                            }
                          }
                        }
                      }
                  }
              }
            }
          }
        }else{
          //PRODUIT NON PERRISABLE----------------------------------------------------------------------
          $nombre_contenu=(int)$solde_stock['0']['Product']['nombre_contenu'];
          $qte_transfert=null;
          $prix_de_vente=null;
          //Quantite et quantite
          if($nombre_contenu>1){
            $qte_transfert=(int)$prix_qte*$nombre_contenu;
            $prix_de_vente=($prix_operation_encours*(int)$prix_qte)/$qte_transfert;
          }else{
            $qte_transfert=$prix_qte;
            $prix_de_vente=$prix_operation_encours;
          }

          //Quantité demandée
          if($prix_qte>$solde_quantite){
            $this->Session->setFlash("Quantité à transferer est sup. à la qté disponible",'danger');
            return $this->redirect(array('action'=>'transfert'));
          }else{
            //===========================================ENREGISTREMENT========================================
            if(empty($solde_stock)){

            }else{
              $this->Warehouse->id=(int)$solde_stock['0']['Warehouse']['id'];
                if($this->Warehouse->save(array('solde'=>false))){
                  //Reduction des quantite
                  $nv_qte=$solde_quantite-$prix_qte;
                  $nv_val_unit=$prix_qte * $prix_stock_entree_une;
                  $nv_val=$solde_valeur - $nv_val_unit;

                  $this->Warehouse->create();
                  if($this->Warehouse->save(array(
                    'qte'=>$prix_qte,
                    'prix_unit'=>(float)$prix_stock_entree_une,
                    'valeur_total'=>(float)$nv_val_unit,
                    'qte_total'=>$nv_qte,
                    'valeur'=>$nv_val,
                    'vente'=>false,
                    'transfert'=>true,
                    'correction'=>false,
                    'erreur_stockage'=>false,
                    'stockage'=>false,
                    'solde'=>true,
                    'date_op'=>$date_operation,
                    'user_id'=>$this->Session->read('Auth.User.id'),
                    'product_id'=>$data['Warehouse']['product_id'],
                    'depot'=>true,
                    'boutique'=>false 
                  ))){
                    //MISE A JOUR DES INFORMATIONS BOUTIQUE
                    //Solde
                    $produit_solde=$this->Warehouse->find('all', array(
                      'conditions'=>array(
                        'product_id'=>$data['Warehouse']['product_id'],
                        'solde'=>true,
                        'boutique'=>true
                      )
                    ));
                    //Enregistrement des informations de la boutique
                    //SANS SOLDE
                    if(empty($produit_solde)){
                      $this->Warehouse->create();
                      if($this->Warehouse->save(array(
                        'qte'=>$qte_transfert,
                        'prix_unit'=>(float)$prix_de_vente,
                        'valeur_total'=>(float)$prix_de_vente * $qte_transfert,
                        'qte_total'=>$qte_transfert,
                        'valeur'=>(float)$prix_de_vente * $qte_transfert,
                        'vente'=>false,
                        'transfert'=>false,
                        'correction'=>false,
                        'erreur_stockage'=>false,
                        'stockage'=>true,
                        'solde'=>true,
                        'date_op'=>$date_operation,
                        'user_id'=>$this->Session->read('Auth.User.id'),
                        'product_id'=>$data['Warehouse']['product_id'],
                        'depot'=>false,
                        'boutique'=>true 
                      ))){
                        //Les quantites sont egales
                        $this->Session->setFlash("Transefert réussi",'succes');
                        return $this->redirect(array('action'=>'transfert'));
                      }
                    }else{
                      //AVEC SOLDE
                      $this->Warehouse->id=(int)$produit_solde['0']['Warehouse']['id'];
                      if($this->Warehouse->save(array('solde'=>false))){
                        $this->Warehouse->create();
                        if($this->Warehouse->save(array(
                          'qte'=>$qte_transfert,
                          'prix_unit'=>(float)$prix_de_vente,
                          'valeur_total'=>(float)$prix_de_vente * $qte_transfert,
                          'qte_total'=>$qte_transfert + (int)$produit_solde['0']['Warehouse']['qte'],
                          'valeur'=>((float)$prix_de_vente * $qte_transfert) + (float)$produit_solde['0']['Warehouse']['valeur'],
                          'vente'=>false,
                          'transfert'=>false,
                          'correction'=>false,
                          'erreur_stockage'=>false,
                          'stockage'=>true,
                          'solde'=>true,
                          'date_op'=>$date_operation,
                          'user_id'=>$this->Session->read('Auth.User.id'),
                          'product_id'=>$data['Warehouse']['product_id'],
                          'depot'=>false,
                          'boutique'=>true 
                        ))){
                          //Les quantites sont egales
                          $this->Session->setFlash("Transefert réussi",'succes');
                          return $this->redirect(array('action'=>'transfert'));
                        }
                      }
                    }  
                  }
                }
              }
              
            }
          }
        }
    }

    //Correction
    public function correction(){
      $this->layout ='admin';
      $this->loadModel('Expiration');
      //Title
      $title="Ets MAKI |Stock Magasin";
      $this->set('title',$title);
      
      $this->Paginator->settings =array(
        'fields'=> array(
        'Warehouse.qte',
        'Warehouse.prix_unit',
        'Warehouse.valeur',
        'Warehouse.qte_total',
        'Warehouse.valeur_total',
        'Warehouse.date_op',
        'Product.nom_produit',
        'Warehouse.mvm',
        ),
        'conditions'=>array('depot'=>true,'correction'=>true),
        'order'=>array('Warehouse.date_op'=>'DESC'),
        'limit'=>30,
        'paramType'=>'querystring'
        );
        //Pqginqtion
        $this->set('stock_depot',$this->Paginator->paginate('Warehouse'));
        // Le produit
        $produit=$this->Warehouse->Product->find('list', array(
          'conditions'=>array('statut'=>true),'fields'=>array('id','nom_produit')));
        $aujourd_hui= date("Y-m-d");
        $this->set('products',$produit);
        $this->set('datep',$aujourd_hui);
        //Information
        if($this->request->is(array('put','post'))){
          $data=$this->request->data;
          //Les informations du produit
          $produit_correction=$this->Warehouse->find('all', array(
            'conditions'=>array('product_id'=>(int)$data['Warehouse']['product_id'],'solde'=>true,'depot'=>true),
            'fields'=> array(
              'Warehouse.qte',
              'Warehouse.prix_unit',
              'Warehouse.valeur',
              'Warehouse.qte_total',
              'Warehouse.valeur_total',
              'Warehouse.date_op',
              'Product.nom_produit',
              'Product.perrisable',
              'Warehouse.id', 
              
          )));

         //Vérification de la quantité.
          if($produit_correction['0']['Product']['perrisable']==true){
            $quantite_dispo=(int)$produit_correction['0']['Warehouse']['qte_total'];
            $quantite_modifier=(int)$data['Warehouse']['qte'];

            if($data['Warehouse']['aug']==true and $data['Warehouse']['dim']==true){
              $this->Session->setFlash("Choisir une option",'danger');
              return $this->redirect(array('action'=>'transfert'));
            }elseif($data['Warehouse']['aug']==false and $data['Warehouse']['dim']==false){
              $this->Session->setFlash("Choisir une option",'danger');
              return $this->redirect(array('action'=>'transfert'));
            }

            if($data['Warehouse']['aug']==true){
              //Prix de l'operation
              $prix_de_operation=null;
              if(empty($data['Warehouse']['prix_unit'])){
                $prix_de_operation=(float)$produit_correction['0']['Product']['prix_unit'];
              }else{
                $prix_de_operation=(float)$data['Warehouse']['prix_unit'];
              }
              //La mise a jour des informations
              $qte_total_aug=(int)$produit_correction['0']['Warehouse']['qte_total']+(int)$data['Warehouse']['qte'];
              $valeur_totale_augm=(float)$produit_correction['0']['Warehouse']['valeur'] + ((int)$data['Warehouse']['qte'] * (float)$prix_de_operation);

              $this->Warehouse->id=(int)$produit_correction['0']['Warehouse']['id'];

              if($this->Warehouse->save(array('solde'=>false))){
                $this->Warehouse->create();
                if($this->Warehouse->save(array(
                        'qte'=>(int)$data['Warehouse']['qte'],
                        'prix_unit'=>(float)$prix_de_operation,
                        'valeur_total'=>(int)$data['Warehouse']['qte'] * (float)$prix_de_operation,
                        'qte_total'=>$qte_total_aug,
                        'valeur'=>$valeur_totale_augm,
                        'vente'=>false,
                        'transfert'=>false,
                        'correction'=>true,
                        'erreur_stockage'=>false,
                        'stockage'=>false,
                        'solde'=>true,
                        'date_op'=>$data['Warehouse']['datep'],
                        'user_id'=>$this->Session->read('Auth.User.id'),
                        'product_id'=>$data['Warehouse']['product_id'],
                        'depot'=>true,
                        'boutique'=>false,
                        'mvm'=>true
                ))){
                  //=====================
                  $produit_perrisable_encours=$this->Expiration->find('all',array(
                    'conditions'=>array('product_id'=>(int)$data['Warehouse']['product_id'],'depot'=>true),
                    'order'=>array('Expiration.id'=>'DESC'),
                    'limit'=>1,
                  ));
                  //Mise a jour d'expiration du produit
                  $this->Expiration->id=(int)$produit_perrisable_encours['0']['Expiration']['id'];
                  $qte_nve=(int)$produit_perrisable_encours['0']['Expiration']['qte'] + $data['Warehouse']['qte'];
                  if($this->Expiration->save(array('qte'=>$qte_nve))){
                      $this->Session->setFlash("Correction par augmentation réussie",'success');
                      return $this->redirect(array('action'=>'correction'));
                  }
                }

              }
            //DIMUNITION DE LA QUANTITE
            }else {
              //Prix de l'operation
              $prix_de_operation=null;
              if(empty($data['Warehouse']['prix_unit'])){
                $prix_de_operation=(float)$produit_correction['0']['Product']['prix_unit'];
              }else{
                $prix_de_operation=(float)$data['Warehouse']['prix_unit'];
              }
              //Verification des quantites
              if((int)$produit_correction['0']['Warehouse']['qte_total'] < (int)$data['Warehouse']['qte']){
                  $this->Session->setFlash("La quantité à reduire est superieur au disponible",'danger');
                  return $this->redirect(array('action'=>'correction'));
              }

              //La mise a jour des informations
              $qte_total_aug=(int)$produit_correction['0']['Warehouse']['qte_total']-(int)$data['Warehouse']['qte'];
              $valeur_totale_augm=(float)$produit_correction['0']['Warehouse']['valeur'] - ((int)$data['Warehouse']['qte'] * (float)$prix_de_operation);

              $this->Warehouse->id=(int)$produit_correction['0']['Warehouse']['id'];
              if($this->Warehouse->save(array('solde'=>false))){
                $this->Warehouse->create();
                if($this->Warehouse->save(array(
                        'qte'=>(int)$data['Warehouse']['qte'],
                        'prix_unit'=>(float)$prix_de_operation,
                        'valeur_total'=>(int)$data['Warehouse']['qte'] * (float)$prix_de_operation,
                        'qte_total'=>$qte_total_aug,
                        'valeur'=>$valeur_totale_augm,
                        'vente'=>false,
                        'transfert'=>false,
                        'correction'=>true,
                        'erreur_stockage'=>false,
                        'stockage'=>false,
                        'solde'=>true,
                        'date_op'=>$data['Warehouse']['datep'],
                        'user_id'=>$this->Session->read('Auth.User.id'),
                        'product_id'=>$data['Warehouse']['product_id'],
                        'depot'=>true,
                        'boutique'=>false,
                        'mvm'=>false
                ))){
                  //=====================
                  $produit_perrisable_encours=$this->Expiration->find('all',array(
                    'conditions'=>array('product_id'=>(int)$data['Warehouse']['product_id'],'depot'=>true),
                    'order'=>array('Expiration.id'=>'DESC'),
                    'limit'=>1,
                  ));
                  //Mise a jour d'expiration du produit
                  $this->Expiration->id=(int)$produit_perrisable_encours['0']['Expiration']['id'];
                  $qte_nve=(int)$produit_perrisable_encours['0']['Expiration']['qte'] - $data['Warehouse']['qte'];
                  if($this->Expiration->save(array('qte'=>$qte_nve))){
                      $this->Session->setFlash("Correction par diminution réussie",'success');
                      return $this->redirect(array('action'=>'correction'));
                  }
                }

              }


            }

          }else {
            //========================== PRODUIT NON PERRISABLE=====================================
            $quantite_dispo=(int)$produit_correction['0']['Warehouse']['qte_total'];
            $quantite_modifier=(int)$data['Warehouse']['qte'];

            if($data['Warehouse']['aug']==true and $data['Warehouse']['dim']==true){
              $this->Session->setFlash("Choisir une option",'danger');
              return $this->redirect(array('action'=>'transfert'));
            }elseif($data['Warehouse']['aug']==false and $data['Warehouse']['dim']==false){
              $this->Session->setFlash("Choisir une option",'danger');
              return $this->redirect(array('action'=>'transfert'));
            }

            if($data['Warehouse']['aug']==true){
              //Prix de l'operation
              $prix_de_operation=null;
              if(empty($data['Warehouse']['prix_unit'])){
                $prix_de_operation=(float)$produit_correction['0']['Product']['prix_unit'];
              }else{
                $prix_de_operation=(float)$data['Warehouse']['prix_unit'];
              }
              //La mise a jour des informations
              $qte_total_aug=(int)$produit_correction['0']['Warehouse']['qte_total']+(int)$data['Warehouse']['qte'];
              $valeur_totale_augm=(float)$produit_correction['0']['Warehouse']['valeur'] + ((int)$data['Warehouse']['qte'] * (float)$prix_de_operation);

              $this->Warehouse->id=(int)$produit_correction['0']['Warehouse']['id'];

              if($this->Warehouse->save(array('solde'=>false))){
                $this->Warehouse->create();
                if($this->Warehouse->save(array(
                        'qte'=>(int)$data['Warehouse']['qte'],
                        'prix_unit'=>(float)$prix_de_operation,
                        'valeur_total'=>(int)$data['Warehouse']['qte'] * (float)$prix_de_operation,
                        'qte_total'=>$qte_total_aug,
                        'valeur'=>$valeur_totale_augm,
                        'vente'=>false,
                        'transfert'=>false,
                        'correction'=>true,
                        'erreur_stockage'=>false,
                        'stockage'=>false,
                        'solde'=>true,
                        'date_op'=>$data['Warehouse']['datep'],
                        'user_id'=>$this->Session->read('Auth.User.id'),
                        'product_id'=>$data['Warehouse']['product_id'],
                        'depot'=>true,
                        'boutique'=>false,
                        'mvm'=>true
                ))){
                  //=====================
                  $this->Session->setFlash("Correction par augmentation réussie",'success');
                      return $this->redirect(array('action'=>'correction'));
                }

              }
            //DIMUNITION DE LA QUANTITE
            }else {
              //Prix de l'operation
              $prix_de_operation=null;
              if(empty($data['Warehouse']['prix_unit'])){
                $prix_de_operation=(float)$produit_correction['0']['Product']['prix_unit'];
              }else{
                $prix_de_operation=(float)$data['Warehouse']['prix_unit'];
              }
              //Verification des quantites
              if((int)$produit_correction['0']['Warehouse']['qte_total'] < (int)$data['Warehouse']['qte']){
                  $this->Session->setFlash("La quantité à reduire est superieur au disponible",'danger');
                  return $this->redirect(array('action'=>'correction'));
              }

              //La mise a jour des informations
              $qte_total_aug=(int)$produit_correction['0']['Warehouse']['qte_total']-(int)$data['Warehouse']['qte'];
              $valeur_totale_augm=(float)$produit_correction['0']['Warehouse']['valeur'] - ((int)$data['Warehouse']['qte'] * (float)$prix_de_operation);

              $this->Warehouse->id=(int)$produit_correction['0']['Warehouse']['id'];
              if($this->Warehouse->save(array('solde'=>false))){
                $this->Warehouse->create();
                if($this->Warehouse->save(array(
                        'qte'=>(int)$data['Warehouse']['qte'],
                        'prix_unit'=>(float)$prix_de_operation,
                        'valeur_total'=>(int)$data['Warehouse']['qte'] * (float)$prix_de_operation,
                        'qte_total'=>$qte_total_aug,
                        'valeur'=>$valeur_totale_augm,
                        'vente'=>false,
                        'transfert'=>false,
                        'correction'=>true,
                        'erreur_stockage'=>false,
                        'stockage'=>false,
                        'solde'=>true,
                        'date_op'=>$data['Warehouse']['datep'],
                        'user_id'=>$this->Session->read('Auth.User.id'),
                        'product_id'=>$data['Warehouse']['product_id'],
                        'depot'=>true,
                        'boutique'=>false,
                        'mvm'=>false
                ))){
                  //=====================
                  $this->Session->setFlash("Correction par diminution réussie",'success');
                  return $this->redirect(array('action'=>'correction'));
                }
              }
            }  
          }
        }
    }

  //Produit perrisable
  public function perrisable(){
    $this->layout ='admin';
    //Title
    $title="Ets MAKI |Stock Magasin";
    $this->set('title',$title);
    $this->loadModel('Expiration');
    $this->loadModel('Warehouse');
    $this->loadModel('Product');
    //vérification de la date
    $produit_perrisable_check=$this->Expiration->find('all',
    array('Expiration.date_expiration >='=>date('Y-m-d')));
    $this->set('stock_depot',$produit_perrisable_check);

      

  }

    //Produit perrisable
    public function notification(){
      //Title
      $this->layout ='admin';
      $this->loadModel('Expiration');
      $this->loadModel('Warehouse');
      $this->loadModel('Product');
      //vérification de la date
      $produit_perrisable_check=$this->Expiration->find('all',
      array('Expiration.date_expiration >='=>date('Y-m-d')));

      $notification=0;

      foreach ($produit_perrisable_check as $produit_perrisable_check) {
        if($this->Utilitaire->NbJours(date('Y-m-d'),$produit_perrisable_check['Expiration']['date_expiration'])<3){
          $notification=$notification+1;
        }
      }
      $donnees=array('not'=>$notification,'donnees'=>$produit_perrisable_check);
      return $donnees;        
    }
}?>
