<?php

/**
*Stockage de produit
*/
class WarehousesController extends AppController{


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
    if($this->request->is(array('post'))){
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
        $prix_operation_encours=prix_prix_unitaire;
      }
      //Le produit est un produit perrisable
      if($produit_operation['Warehouse']['perrisable']==true){
        //Solde
        $produit_solde=$this->Warehouse->find('all', array(
          'conditions'=>array(
            'product_id'=>$data['Warehouse']['product_id'],
            'solde'=>true
          )
        ));
        //Enregistrement pour la première fois
        if(empty($produit_solde)){
          $this->Warehouse->create();
            if($this->Warehouse->save(array(
              'qte'=>prix_qte,
              'prix_unit'=>(float)$prix_operation_encours,
              'valeur_total'=>(float)$prix_operation_encours*(float)prix_qte,
              'qte_total'=>prix_qte,
              'valeur'=>(float)$prix_operation_encours*(float)prix_qte,
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
              $this->Expiration->save(
                array(
                  'qte'=>prix_qte,
                  'date_op'=>$date_operation,
                  'product_id'=>$data['Warehouse']['product_id'],
                  'user_id'=>$this->Session->read('Auth.User.id'),
                  'depot'=>true,
                  'boutique'=>false));
              
              $produit_session_perrisable=array(
                    'id_produit'=>$produit_operation['Warehouse']['perrisable'],
                    'date_operation'=>$date_operation,
                    'id_expiration'=>$this->Warehouse->id

                  );
              
              $this->Session->write('produit_perissable', $produit_session_perrisable);
              $this->Session->setFlash("Completer la date d'expiration",'success');
              return $this->redirect(array('action'=>'stock_magasin'));
            }
        }else{
          $qte_total=(int)$produit_solde['Warehouse']['qte_total'] + (int)prix_qte;
          $valeur_totale=(float)$produit_solde['Warehouse']['qte_total'] + ((float)$prix_operation_encours*(float)prix_qte);
          $this->Warehouse->id=$produit_solde['Warehouse']['id'];
          if($this->Warehouse->save(array(
            'solde'=>false
          ))){
            $this->Warehouse->create();
            if($this->Warehouse->save(array(
                'qte'=>prix_qte,
                'prix_unit'=>(float)$prix_operation_encours,
                'valeur_total'=>(float)$prix_operation_encours*(float)prix_qte,
                'qte_total'=>qte_total,
                'valeur'=>valeur_totale,
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
                $this->Expiration->save(
                  array(
                    'qte'=>prix_qte,
                    'date_op'=>$date_operation,
                    'product_id'=>$data['Warehouse']['product_id'],
                    'user_id'=>$this->Session->read('Auth.User.id'),
                    'depot'=>true,
                    'boutique'=>false));
                  $produit_session_perrisable=array(
                      'id_produit'=>$produit_operation['Warehouse']['perrisable'],
                      'date_operation'=>$date_operation,
                      'id_expiration'=>$this->Warehouse->id
  
                    );
                
                $this->Session->write('produit_perissable', $produit_session_perrisable);
                $this->Session->setFlash("Completer la date d'expiration",'success');
                return $this->redirect(array('action'=>'stock_magasin'));
            }
          }   
        }
      //Produit non perrisable
      }else{
        //Solde de produit non perrisable
        $produit_solde=$this->Warehouse->find('all', array(
          'conditions'=>array(
            'product_id'=>$data['Warehouse']['product_id'],
            'solde'=>true
          )
        ));

        //Enregistrement pour la première fois
        if(empty($produit_solde)){
          $this->Warehouse->create();
            if($this->Warehouse->save(array(
              'qte'=>prix_qte,
              'prix_unit'=>(float)$prix_operation_encours,
              'valeur_total'=>(float)$prix_operation_encours*(float)prix_qte,
              'qte_total'=>prix_qte,
              'valeur'=>(float)$prix_operation_encours*(float)prix_qte,
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
              $this->Session->setFlash("Completer la date d'expiration",'success');
              return $this->redirect(array('action'=>'stock_magasin'));
            }
        }else{
          $qte_total=(int)$produit_solde['Warehouse']['qte_total'] + (int)prix_qte;
          $valeur_totale=(float)$produit_solde['Warehouse']['qte_total'] + ((float)$prix_operation_encours*(float)prix_qte);
          $this->Warehouse->id=$produit_solde['Warehouse']['id'];
          if($this->Warehouse->save(array(
            'solde'=>false
          ))){
            $this->Warehouse->create();
            if($this->Warehouse->save(array(
                'qte'=>prix_qte,
                'prix_unit'=>(float)$prix_operation_encours,
                'valeur_total'=>(float)$prix_operation_encours*(float)prix_qte,
                'qte_total'=>qte_total,
                'valeur'=>valeur_totale,
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
                $this->Session->setFlash("Completer la date d'expiration",'success');
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
}?>
