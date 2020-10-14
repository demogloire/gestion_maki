<?php

/**
*
*/
class InvoicesController extends AppController{

  public $components = array('Utilitaire','Paginator');

  #Portail de la vente
  public function index(){
    #Chargement du layour
    $this->layout ='admin';
    $title="Ets MAKI | Vente";
    $this->Session->delete('facture_id');
    $this->Paginator->settings =array(
      'conditions'=>array('Invoice.valeur !='=>0, 'Invoice.valeur !='=>null),
      'order'=>array('Warehouse.date_op'=>'DESC'),
      'limit'=>30,
      'paramType'=>'querystring'
      );
      //Pqginqtion
    $this->set('factures',$this->Paginator->paginate('Invoice'));
    $this->set('title',$title);
 

  }

  #Vente
  public function facturenumero(){
    #Chargement du layour
    $this->layout ='admin';
    $title="Ets MAKI | Vente";
    $this->set('title',$title);
    //Code de la facture
    $code_facture=strtoupper($this->Utilitaire->numerofacture()).''.date('Ymd');
    //Verfication des informations
    $fact_id=$this->Session->read('facture_id');
    //Verification des informations l'ID 
    if(!empty($fact_id)){
      return $this->redirect(array('action'=>'vente'));
    }
    //Enregistrement des données
    if($this->request->is(array('put','post'))){
      //Transmission des donnees
      $data=$this->request->data;
      $date_operation=$data['Invoice']['datep'];
      if($this->Invoice->save(array('code_facture'=>$code_facture,'date_op'=>$date_operation,'user_id'=>$this->Session->read('Auth.User.id')))){
        $this->Session->write('facture_id', $this->Invoice->id);
        $this->Session->write('date_operation', $date_operation);
        $this->Session->setFlash("Completer la facture",'succes');
        return $this->redirect(array('action'=>'vente'));
      }
    }
  }


  #Vente
  public function vente(){
    #Chargement du layour
    $this->layout ='admin';
    $title="Ets MAKI | Vente";

    $this->loadModel('Product');
    $this->loadModel('Warehouse');
    $this->loadModel('Expiration');
    $this->loadModel('Value');
    $this->loadModel('Sale');

    $this->set('title',$title);
    //Verfication des informations
    $fact_id=$this->Session->read('facture_id');
    //Verification des informations l'ID 
    if(empty($fact_id)){
      return $this->redirect(array('action'=>'facturenumero'));
    }   

    
    //Enregistrement des informations sur la facture
    if($this->request->is(array('put','post'))){
      $data=$this->request->data;
      //Les informatons sur la quantté et le prix et la date
      $prix_qte=(int)$data['Invoice']['qte'];
      $prix_prix_unitaire=(float)$data['Invoice']['prix_unit'];
      //Vérification
      if ($prix_qte < 1 or !empty($prix_prix_unitaire) and $prix_prix_unitaire<0){
        $this->Session->setFlash("Vérifier la quantité et le prix",'danger');
        return $this->redirect(array('action'=>'vente')); 
      }
      //Produit encours de vente
      $verification_produit=$this->Warehouse->find('all',array('conditions'=>array(
        'product_id'=>$data['Invoice']['product_id'],'solde'=>true,'boutique'=>true
      )));
      //Prix unitaire.
      $prix_unitaire_boutique=null;
      if(empty($prix_prix_unitaire)){
        $prix_unitaire_boutique=((float)$verification_produit['0']['Product']['prix_detaille']/(int)$verification_produit['0']['Product']['nombre_contenu']);
      }else{
        $prix_unitaire_boutique=$prix_prix_unitaire;
      }
      //VERIFICATION DU PRODUIT PERRISABLE
      if($verification_produit['0']['Product']['perrisable']==true){
        $qte_solde=(int)$verification_produit['0']['Warehouse']['qte_total'];
        //Verification des expirations
        $expiration_produit=$this->Expiration->find('all',array('conditions'=>array(
          'product_id'=>$data['Invoice']['product_id'],'boutique'=>true),
          'order'=>array('Expiration.date_expiration'=>'ASC'),
          'limit'=>2));         
        //Verification de la quantité
        if($qte_solde > $prix_qte){          
          $expiration_produit_premier=(int)$expiration_produit['0']['Expiration']['qte'];
          if($expiration_produit_premier > $prix_qte){
            $reste=$expiration_produit_premier - $prix_qte;

            $id_entrepot=(int)$expiration_produit['0']['Expiration']['warehouse_id'];
            $prix_de_la_sanction=$this->Warehouse->findById($id_entrepot);
            //PRODUIT PERRISABLE MISE A JOUR
            $this->Expiration->id=(int)$expiration_produit['0']['Expiration']['id'];
            if($this->Expiration->save(array('qte'=>$reste))){
              $prix_de_op=(float)$prix_de_la_sanction['Warehouse']['prix_unit'];
              $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
              if($this->Warehouse->save(array('solde'=>false))){
                //ENREGISTREMENT DU STOCKAGE
                $this->Warehouse->create();
                if($this->Warehouse->save(array(
                  'qte'=>$prix_qte,
                  'prix_unit'=>(float)$prix_unitaire_boutique,
                  'valeur_total'=>(float)$prix_unitaire_boutique * $prix_qte,
                  'qte_total'=>(int)$prix_de_la_sanction['Warehouse']['qte_total'] - $prix_qte,
                  'valeur'=>(float)$prix_de_la_sanction['Warehouse']['valeur'] - ((float)$prix_de_op * $prix_qte),
                  'vente'=>true,
                  'transfert'=>false,
                  'correction'=>false,
                  'erreur_stockage'=>false,
                  'stockage'=>false,
                  'solde'=>true,
                  'date_op'=>$this->Session->read('date_operation'),
                  'user_id'=>$this->Session->read('Auth.User.id'),
                  'product_id'=>$data['Invoice']['product_id'],
                  'depot'=>false,
                  'boutique'=>true))){
                    //ENREGISTREMENT DES VALEURS FACTURE
                    $facture=$this->Invoice->findById((int)$fact_id);
                    $valeur_total=null;
                    if(empty($facture['Invoice']['valeur'])){
                      $valeur_total=0;
                    }else{
                      $valeur_total=(float)$facture['Invoice']['valeur'];
                    }
                    $this->Invoice->id=$fact_id;
                    if($this->Invoice->save(array('valeur'=>$valeur_total + ((float)$prix_unitaire_boutique * $prix_qte)))){
                      //MISE A JOUR DE LA VALEUR DU PRODUIT
                      $valeur_vendue=$this->Sale->find('all',array('conditions'=>array('product_id'=>$data['Invoice']['product_id'],'invoice_id'=>(int)$fact_id)));
                      if(empty($valeur_vendue)){
                        $this->Sale->create();
                        if($this->Sale->save(array(
                          'qte'=>$prix_qte,
                          'prix_unit'=>(float)$prix_unitaire_boutique,
                          'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                          'invoice_id'=>(int)$fact_id,
                          'product_id'=>$data['Invoice']['product_id']
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            if($this->Value->save(array(
                              'qte'=>$prix_qte,
                              'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                $this->Session->setFlash("Produit ajouter",'success');
                                return $this->redirect(array('action'=>'vente')); 
                            }
                        }
                      }else{
                        if((float)$prix_unitaire_boutique==(float)$valeur_vendue['0']['Sale']['prix_unit']){
                          $this->Sale->id=(int)$valeur_vendue['0']['Sale']['id'];
                          if($this->Sale->save(array(
                          'qte'=>(int)$valeur_vendue['0']['Sale']['qte'] + $prix_qte,
                          'valeur'=>((float)$valeur_vendue['0']['Sale']['valeur']) +((float)$prix_unitaire_boutique * $prix_qte),
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            $this->Value->create();
                            if($this->Value->save(array(
                              'qte'=>$prix_qte,
                              'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                $this->Session->setFlash("Produit ajouter",'success');
                                return $this->redirect(array('action'=>'vente')); 
                            }
                          }
                        }else{
                          $this->Sale->create();
                          if($this->Sale->save(array(
                            'qte'=>$prix_qte,
                            'prix_unit'=>(float)$prix_unitaire_boutique,
                            'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                            'invoice_id'=>(int)$fact_id,
                            'product_id'=>$data['Invoice']['product_id']
                            ))){
                              //VALEUR DE STOCKAGE D'ORIGINE
                              if($this->Value->save(array(
                                'qte'=>$prix_qte,
                                'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                                'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                                'product_id'=>$data['Invoice']['product_id'],
                                'invoice_id'=>(int)$fact_id,
                                'sale_id'=>$this->Sale->id,
                                ))){
                                  $this->Session->setFlash("Produit ajouter",'success');
                                  return $this->redirect(array('action'=>'vente')); 
                              }
                          }
                        }
                      }
                    }
                  }
              }
            }

          }elseif($expiration_produit_premier == $prix_qte){
            //PRODUIT EGALE ENTRE LA QUANTITE DISPONIBLE ET LA QUANTITE ENTREE
            $id_entrepot=(int)$expiration_produit['0']['Expiration']['warehouse_id'];
            $prix_de_la_sanction=$this->Warehouse->findById($id_entrepot);
            //PRODUIT PERRISABLE MISE A JOUR
            if($this->Expiration->delete((int)$expiration_produit['0']['Expiration']['id'])){
              $prix_de_op=(float)$prix_de_la_sanction['Warehouse']['prix_unit'];
              $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
              if($this->Warehouse->save(array('solde'=>false))){
                //ENREGISTREMENT DU STOCKAGE
                $this->Warehouse->create();
                if($this->Warehouse->save(array(
                  'qte'=>$prix_qte,
                  'prix_unit'=>(float)$prix_unitaire_boutique,
                  'valeur_total'=>(float)$prix_unitaire_boutique * $prix_qte,
                  'qte_total'=>(int)$prix_de_la_sanction['Warehouse']['qte_total'] - $prix_qte,
                  'valeur'=>(float)$prix_de_la_sanction['Warehouse']['valeur'] - ((float)$prix_de_op * $prix_qte),
                  'vente'=>true,
                  'transfert'=>false,
                  'correction'=>false,
                  'erreur_stockage'=>false,
                  'stockage'=>false,
                  'solde'=>true,
                  'date_op'=>$this->Session->read('date_operation'),
                  'user_id'=>$this->Session->read('Auth.User.id'),
                  'product_id'=>$data['Invoice']['product_id'],
                  'depot'=>false,
                  'boutique'=>true))){
                    //ENREGISTREMENT DES VALEURS FACTURE
                    $facture=$this->Invoice->findById((int)$fact_id);
                    $valeur_total=null;
                    if(empty($facture['Invoice']['valeur'])){
                      $valeur_total=0;
                    }else{
                      $valeur_total=(float)$facture['Invoice']['valeur'];
                    }
                    $this->Invoice->id=$fact_id;
                    if($this->Invoice->save(array('valeur'=>$valeur_total + ((float)$prix_unitaire_boutique * $prix_qte)))){
                      //MISE A JOUR DE LA VALEUR DU PRODUIT
                      $valeur_vendue=$this->Sale->find('all',array('conditions'=>array('product_id'=>$data['Invoice']['product_id'],'invoice_id'=>(int)$fact_id)));
                      if(empty($valeur_vendue)){
                        $this->Sale->create();
                        if($this->Sale->save(array(
                          'qte'=>$prix_qte,
                          'prix_unit'=>(float)$prix_unitaire_boutique,
                          'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                          'invoice_id'=>(int)$fact_id,
                          'product_id'=>$data['Invoice']['product_id']
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            if($this->Value->save(array(
                              'qte'=>$prix_qte,
                              'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                $this->Session->setFlash("Produit ajouter",'success');
                                return $this->redirect(array('action'=>'vente')); 
                            }
                        }
                      }else{
                        if((float)$prix_unitaire_boutique==(float)$valeur_vendue['0']['Sale']['prix_unit']){
                          $this->Sale->id=(int)$valeur_vendue['0']['Sale']['id'];
                          if($this->Sale->save(array(
                          'qte'=>(int)$valeur_vendue['0']['Sale']['qte'] + $prix_qte,
                          'valeur'=>((float)$valeur_vendue['0']['Sale']['valeur']) +((float)$prix_unitaire_boutique * $prix_qte),
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            $this->Value->create();
                            if($this->Value->save(array(
                              'qte'=>$prix_qte,
                              'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                $this->Session->setFlash("Produit ajouter",'success');
                                return $this->redirect(array('action'=>'vente')); 
                            }
                          }
                        }else{
                          $this->Sale->create();
                          if($this->Sale->save(array(
                            'qte'=>$prix_qte,
                            'prix_unit'=>(float)$prix_unitaire_boutique,
                            'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                            'invoice_id'=>(int)$fact_id,
                            'product_id'=>$data['Invoice']['product_id']
                            ))){
                              //VALEUR DE STOCKAGE D'ORIGINE
                              if($this->Value->save(array(
                                'qte'=>$prix_qte,
                                'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                                'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                                'product_id'=>$data['Invoice']['product_id'],
                                'invoice_id'=>(int)$fact_id,
                                'sale_id'=>$this->Sale->id,
                                ))){
                                  $this->Session->setFlash("Produit ajouter",'success');
                                  return $this->redirect(array('action'=>'vente')); 
                              }
                          }
                        }
                      }
                    }
                  }
              }
            }
          }else{
            //PRODUIT INFERIEURE ENTRE LA QUANTITE DISPONIBLE ET LA QUANTITE ENTREE
            $id_entrepot=(int)$expiration_produit['1']['Expiration']['warehouse_id'];
            $qte_une=(int)$expiration_produit['0']['Expiration']['qte_total'];
            $qte_deux=(int)$expiration_produit['1']['Expiration']['qte_total'];
            //RESTE DES QUANTITES
            $reste=$prix_qte-$expiration_produit_premier;
            $enregistre_reste=$qte_deux-$reste;
            //Mise à jour des informations de l'entrepot
            $prix_de_la_sanction=$this->Warehouse->findById($id_entrepot);
            //PRODUIT PERRISABLE MISE A JOUR
            if($this->Expiration->delete((int)$expiration_produit['0']['Expiration']['id'])){
              $prix_de_op=(float)$prix_de_la_sanction['Warehouse']['prix_unit'];
              $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
              if($this->Warehouse->save(array('solde'=>false))){
                //ENREGISTREMENT DU STOCKAGE
                $this->Warehouse->create();
                if($this->Warehouse->save(array(
                  'qte'=>$prix_qte,
                  'prix_unit'=>(float)$prix_unitaire_boutique,
                  'valeur_total'=>(float)$prix_unitaire_boutique * $prix_qte,
                  'qte_total'=>(int)$prix_de_la_sanction['Warehouse']['qte_total'] - $prix_qte,
                  'valeur'=>(float)$prix_de_la_sanction['Warehouse']['valeur'] - ((float)$prix_de_op * $prix_qte),
                  'vente'=>true,
                  'transfert'=>false,
                  'correction'=>false,
                  'erreur_stockage'=>false,
                  'stockage'=>false,
                  'solde'=>true,
                  'date_op'=>$this->Session->read('date_operation'),
                  'user_id'=>$this->Session->read('Auth.User.id'),
                  'product_id'=>$data['Invoice']['product_id'],
                  'depot'=>false,
                  'boutique'=>true))){
                    //ENREGISTREMENT DES VALEURS FACTURE
                    $facture=$this->Invoice->findById((int)$fact_id);
                    $valeur_total=null;
                    if(empty($facture['Invoice']['valeur'])){
                      $valeur_total=0;
                    }else{
                      $valeur_total=(float)$facture['Invoice']['valeur'];
                    }
                    $this->Invoice->id=$fact_id;
                    if($this->Invoice->save(array('valeur'=>$valeur_total + ((float)$prix_unitaire_boutique * $prix_qte)))){
                      //MISE A JOUR DE LA VALEUR DU PRODUIT
                      $valeur_vendue=$this->Sale->find('all',array('conditions'=>array('product_id'=>$data['Invoice']['product_id'],'invoice_id'=>(int)$fact_id)));
                      if(empty($valeur_vendue)){
                        $this->Sale->create();
                        if($this->Sale->save(array(
                          'qte'=>$prix_qte,
                          'prix_unit'=>(float)$prix_unitaire_boutique,
                          'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                          'invoice_id'=>(int)$fact_id,
                          'product_id'=>$data['Invoice']['product_id']
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            if($this->Value->save(array(
                              'qte'=>$prix_qte,
                              'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                //Reste de la soustraction
                                if($enregistre_reste==0){
                                  if($this->Expiration->delete((int)$expiration_produit['1']['Expiration']['id'])){
                                    $this->Session->setFlash("Produit ajouter",'success');
                                    return $this->redirect(array('action'=>'vente')); 
                                  }
                                }else{
                                  $this->Expiration->id=(int)$expiration_produit['1']['Expiration']['id'];
                                  if($this->Expiration->save(array('qte'=>$enregistre_reste))){
                                    $this->Session->setFlash("Produit ajouter",'success');
                                    return $this->redirect(array('action'=>'vente')); 
                                  }
                                }  
                            }
                        }
                      }else{
                        if((float)$prix_unitaire_boutique==(float)$valeur_vendue['0']['Sale']['prix_unit']){
                          $this->Sale->id=(int)$valeur_vendue['0']['Sale']['id'];
                          if($this->Sale->save(array(
                          'qte'=>(int)$valeur_vendue['0']['Sale']['qte'] + $prix_qte,
                          'valeur'=>((float)$valeur_vendue['0']['Sale']['valeur']) +((float)$prix_unitaire_boutique * $prix_qte),
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            $this->Value->create();
                            if($this->Value->save(array(
                              'qte'=>$prix_qte,
                              'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                $this->Expiration->id=(int)$expiration_produit['1']['Expiration']['id'];
                                if($this->Expiration->save(array('qte'=>$enregistre_reste))){
                                  $this->Session->setFlash("Produit ajouter",'success');
                                  return $this->redirect(array('action'=>'vente')); 
                                }
                            }
                          }
                        }else{
                          $this->Sale->create();
                          if($this->Sale->save(array(
                            'qte'=>$prix_qte,
                            'prix_unit'=>(float)$prix_unitaire_boutique,
                            'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                            'invoice_id'=>(int)$fact_id,
                            'product_id'=>$data['Invoice']['product_id']
                            ))){
                              //VALEUR DE STOCKAGE D'ORIGINE
                              if($this->Value->save(array(
                                'qte'=>$prix_qte,
                                'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                                'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                                'product_id'=>$data['Invoice']['product_id'],
                                'invoice_id'=>(int)$fact_id,
                                'sale_id'=>$this->Sale->id,
                                ))){
                                  $this->Expiration->id=(int)$expiration_produit['1']['Expiration']['id'];
                                  if($this->Expiration->save(array('qte'=>$enregistre_reste))){
                                    $this->Session->setFlash("Produit ajouter",'success');
                                    return $this->redirect(array('action'=>'vente')); 
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
        }elseif($qte_solde == $prix_qte){
          //Vrerification du procedure de stockage
          if(count($expiration_produit)==1){
            $expiration_produit_premier=(int)$expiration_produit['0']['Expiration']['qte'];
            $id_entrepot=(int)$expiration_produit['0']['Expiration']['warehouse_id'];
            $prix_de_la_sanction=$this->Warehouse->findById($id_entrepot);
            //Modification du solde
            $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
            if($this->Warehouse->save(array('solde'=>false))){
              $this->Warehouse->create();
              if($this->Warehouse->save(array(
                'qte'=>$prix_qte,
                'prix_unit'=>(float)$prix_unitaire_boutique,
                'valeur_total'=>(float)$prix_unitaire_boutique * $prix_qte,
                'qte_total'=>0,
                'valeur'=>0,
                'vente'=>true,
                'transfert'=>false,
                'correction'=>false,
                'erreur_stockage'=>false,
                'stockage'=>false,
                'solde'=>true,
                'date_op'=>$this->Session->read('date_operation'),
                'user_id'=>$this->Session->read('Auth.User.id'),
                'product_id'=>$data['Invoice']['product_id'],
                'depot'=>false,
                'boutique'=>true))){

                  $facture=$this->Invoice->findById((int)$fact_id);
                  $valeur_total=null;
                  if(empty($facture['Invoice']['valeur'])){
                    $valeur_total=0;
                  }else{
                    $valeur_total=(float)$facture['Invoice']['valeur'];
                  }
                  $this->Invoice->id=$fact_id;
                  if($this->Invoice->save(array('valeur'=>$valeur_total + ((float)$prix_unitaire_boutique * $prix_qte)))){
                    //MISE A JOUR DE LA VALEUR DU PRODUIT
                    $valeur_vendue=$this->Sale->find('all',array('conditions'=>array('product_id'=>$data['Invoice']['product_id'],'invoice_id'=>(int)$fact_id)));
                    if(empty($valeur_vendue)){
                      $this->Sale->create();
                      if($this->Sale->save(array(
                        'qte'=>$prix_qte,
                        'prix_unit'=>(float)$prix_unitaire_boutique,
                        'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                        'invoice_id'=>(int)$fact_id,
                        'product_id'=>$data['Invoice']['product_id']
                        ))){
                          //VALEUR DE STOCKAGE D'ORIGINE
                          if($this->Value->save(array(
                            'qte'=>$prix_qte,
                            'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                            'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                            'product_id'=>$data['Invoice']['product_id'],
                            'invoice_id'=>(int)$fact_id,
                            'sale_id'=>$this->Sale->id,
                            ))){
                              //SUPPRESSION DES VENTES
                              $this->Expiration->delete((int)$expiration_produit['0']['Expiration']['id']);
                              $this->Session->setFlash("Produit ajouter",'success');
                              return $this->redirect(array('action'=>'vente')); 
                          }
                      }
                    }else{
                      if((float)$prix_unitaire_boutique==(float)$valeur_vendue['0']['Sale']['prix_unit']){
                        $this->Sale->id=(int)$valeur_vendue['0']['Sale']['id'];
                        if($this->Sale->save(array(
                        'qte'=>(int)$valeur_vendue['0']['Sale']['qte'] + $prix_qte,
                        'valeur'=>((float)$valeur_vendue['0']['Sale']['valeur']) +((float)$prix_unitaire_boutique * $prix_qte),
                        ))){
                          //VALEUR DE STOCKAGE D'ORIGINE
                          $this->Value->create();
                          if($this->Value->save(array(
                            'qte'=>$prix_qte,
                            'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                            'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                            'product_id'=>$data['Invoice']['product_id'],
                            'invoice_id'=>(int)$fact_id,
                            'sale_id'=>$this->Sale->id,
                            ))){
                              $this->Expiration->delete((int)$expiration_produit['0']['Expiration']['id']);
                              $this->Session->setFlash("Produit ajouter",'success');
                              return $this->redirect(array('action'=>'vente')); 
                          }
                        }
                      }else{
                        $this->Sale->create();
                        if($this->Sale->save(array(
                          'qte'=>$prix_qte,
                          'prix_unit'=>(float)$prix_unitaire_boutique,
                          'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                          'invoice_id'=>(int)$fact_id,
                          'product_id'=>$data['Invoice']['product_id']
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            if($this->Value->save(array(
                              'qte'=>$prix_qte,
                              'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'montant'=>$prix_qte * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                $this->Expiration->delete((int)$expiration_produit['0']['Expiration']['id']);
                                $this->Session->setFlash("Produit ajouter",'success');
                                return $this->redirect(array('action'=>'vente')); 
                            }
                        }
                      }
                    }
                  }

                  
              }
            }


          }elseif(count($expiration_produit)>1){
            //Les valeurs de la supression une
            $id_entrepot_un=(int)$expiration_produit['0']['Expiration']['warehouse_id'];
            $prix_de_la_sanction=$this->Warehouse->findById($id_entrepot_un);
            //Les valeurs de la supression deuxieme
            $id_entrepot_deux=(int)$expiration_produit['1']['Expiration']['warehouse_id'];
            $prix_de_la_sanction_deux=$this->Warehouse->findById($id_entrepot_deux);

            //Modification du solde
            $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
            if($this->Warehouse->save(array('solde'=>false))){
              $this->Warehouse->create();
              if($this->Warehouse->save(array(
                'qte'=>$prix_qte,
                'prix_unit'=>(float)$prix_unitaire_boutique,
                'valeur_total'=>(float)$prix_unitaire_boutique * $prix_qte,
                'qte_total'=>0,
                'valeur'=>0,
                'vente'=>true,
                'transfert'=>false,
                'correction'=>false,
                'erreur_stockage'=>false,
                'stockage'=>false,
                'solde'=>true,
                'date_op'=>$this->Session->read('date_operation'),
                'user_id'=>$this->Session->read('Auth.User.id'),
                'product_id'=>$data['Invoice']['product_id'],
                'depot'=>false,
                'boutique'=>true))){

                  $facture=$this->Invoice->findById((int)$fact_id);
                  $valeur_total=null;
                  if(empty($facture['Invoice']['valeur'])){
                    $valeur_total=0;
                  }else{
                    $valeur_total=(float)$facture['Invoice']['valeur'];
                  }
                  $this->Invoice->id=$fact_id;
                  if($this->Invoice->save(array('valeur'=>$valeur_total + ((float)$prix_unitaire_boutique * $prix_qte)))){
                    //MISE A JOUR DE LA VALEUR DU PRODUIT
                    $valeur_vendue=$this->Sale->find('all',array('conditions'=>array('product_id'=>$data['Invoice']['product_id'],'invoice_id'=>(int)$fact_id)));
                    if(empty($valeur_vendue)){
                      $this->Sale->create();
                      if($this->Sale->save(array(
                        'qte'=>$prix_qte,
                        'prix_unit'=>(float)$prix_unitaire_boutique,
                        'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                        'invoice_id'=>(int)$fact_id,
                        'product_id'=>$data['Invoice']['product_id']
                        ))){
                          //VALEUR DE STOCKAGE D'ORIGINE
                          if($this->Value->save(array(
                            'qte'=>(int)$expiration_produit['0']['Expiration']['qte'],
                            'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                            'montant'=>(int)$expiration_produit['0']['Expiration']['qte'] * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                            'product_id'=>$data['Invoice']['product_id'],
                            'invoice_id'=>(int)$fact_id,
                            'sale_id'=>$this->Sale->id,
                            ))){
                              //SUPPRESSION DES VENTES
                              if($this->Value->save(array(
                                'qte'=>(int)$expiration_produit['1']['Expiration']['qte'],
                                'prix_unit'=>(float)$prix_de_la_sanction_deux['Warehouse']['prix_unit'],
                                'montant'=>(int)$expiration_produit['1']['Expiration']['qte'] * (float)$prix_de_la_sanction_deux['Warehouse']['prix_unit'],
                                'product_id'=>$data['Invoice']['product_id'],
                                'invoice_id'=>(int)$fact_id,
                                'sale_id'=>$this->Sale->id,
                                ))){

                                  $this->Expiration->delete((int)$expiration_produit['0']['Expiration']['id']);
                                  $this->Expiration->delete((int)$expiration_produit['1']['Expiration']['id']);
                                  $this->Session->setFlash("Produit ajouter",'success');
                                  return $this->redirect(array('action'=>'vente')); 

                                }   
                          }
                      }
                    }else{
                      if((float)$prix_unitaire_boutique==(float)$valeur_vendue['0']['Sale']['prix_unit']){
                        $this->Sale->id=(int)$valeur_vendue['0']['Sale']['id'];
                        if($this->Sale->save(array(
                        'qte'=>(int)$valeur_vendue['0']['Sale']['qte'] + $prix_qte,
                        'valeur'=>((float)$valeur_vendue['0']['Sale']['valeur']) +((float)$prix_unitaire_boutique * $prix_qte),
                        ))){
                          //VALEUR DE STOCKAGE D'ORIGINE
                          $this->Value->create();
                          if($this->Value->save(array(
                            'qte'=>(int)$expiration_produit['0']['Expiration']['qte'],
                            'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                            'montant'=>(int)$expiration_produit['0']['Expiration']['qte'] * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                            'product_id'=>$data['Invoice']['product_id'],
                            'invoice_id'=>(int)$fact_id,
                            'sale_id'=>$this->Sale->id,
                            ))){
                              //SUPPRESSION DES VENTES
                              if($this->Value->save(array(
                                'qte'=>(int)$expiration_produit['1']['Expiration']['qte'],
                                'prix_unit'=>(float)$prix_de_la_sanction_deux['Warehouse']['prix_unit'],
                                'montant'=>(int)$expiration_produit['1']['Expiration']['qte'] * (float)$prix_de_la_sanction_deux['Warehouse']['prix_unit'],
                                'product_id'=>$data['Invoice']['product_id'],
                                'invoice_id'=>(int)$fact_id,
                                'sale_id'=>$this->Sale->id,
                                ))){

                                  $this->Expiration->delete((int)$expiration_produit['0']['Expiration']['id']);
                                  $this->Expiration->delete((int)$expiration_produit['1']['Expiration']['id']);
                                  $this->Session->setFlash("Produit ajouter",'success');
                                  return $this->redirect(array('action'=>'vente')); 

                                }   
                          }
                        }
                      }else{
                        $this->Sale->create();
                        if($this->Sale->save(array(
                          'qte'=>$prix_qte,
                          'prix_unit'=>(float)$prix_unitaire_boutique,
                          'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                          'invoice_id'=>(int)$fact_id,
                          'product_id'=>$data['Invoice']['product_id']
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            if($this->Value->save(array(
                              'qte'=>(int)$expiration_produit['0']['Expiration']['qte'],
                              'prix_unit'=>(float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'montant'=>(int)$expiration_produit['0']['Expiration']['qte'] * (float)$prix_de_la_sanction['Warehouse']['prix_unit'],
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                //SUPPRESSION DES VENTES
                              if($this->Value->save(array(
                                'qte'=>(int)$expiration_produit['1']['Expiration']['qte'],
                                'prix_unit'=>(float)$prix_de_la_sanction_deux['Warehouse']['prix_unit'],
                                'montant'=>(int)$expiration_produit['1']['Expiration']['qte'] * (float)$prix_de_la_sanction_deux['Warehouse']['prix_unit'],
                                'product_id'=>$data['Invoice']['product_id'],
                                'invoice_id'=>(int)$fact_id,
                                'sale_id'=>$this->Sale->id,
                                ))){

                                  $this->Expiration->delete((int)$expiration_produit['0']['Expiration']['id']);
                                  $this->Expiration->delete((int)$expiration_produit['1']['Expiration']['id']);
                                  $this->Session->setFlash("Produit ajouter",'success');
                                  return $this->redirect(array('action'=>'vente')); 

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
            //ERREUR DE VENTE SUR LE PRODUIT
            $this->Session->setFlash("La quantité disponible est de ".$qte_solde.' '.$verification_produit['0']['Product']['mesure'],'danger');
            return $this->redirect(array('action'=>'vente')); 
        }
      }else{
        //VERIFICATION DU PRODUIT NON PERRISABLE
        $produit_non_perrisable_stockage=$this->Warehouse->find('all', array('conditions'=>array(
          'product_id'=>$data['Invoice']['product_id'],'stockage'=>true),'limit'=>2));
        //Verification des nombres des produits de stockages
        $qte_solde=(int)$verification_produit['0']['Warehouse']['qte_total']; //SOLDE
        //les prix des produit du stockage
        $prix_une=(float)$produit_non_perrisable_stockage['0']['Warehouse']['prix_unit'];
        $prix_deux=(float)$produit_non_perrisable_stockage['1']['Warehouse']['prix_unit'];

        if($qte_solde >= $prix_qte){
          //LES DONNES PERRISABLE
          $solde_disponible=$qte_solde - $prix_qte;
          //MISE A JOUR DES QUANTITES
          if((int)$verification_produit['0']['Warehouse']['qte']>=$prix_qte){
          //REDUCTION DE LA QANTITE PAR
            $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
            if($this->Warehouse->save(array('solde'=>false))){
              $this->Warehouse->create();
              if($this->Warehouse->save(array(
                    'qte'=>$prix_qte,
                    'prix_unit'=>(float)$prix_unitaire_boutique,
                    'valeur_total'=>(float)$prix_unitaire_boutique * $prix_qte,
                    'qte_total'=>$solde_disponible,
                    'valeur'=>(float)$verification_produit['0']['Warehouse']['valeur'] - ((float)$prix_une * $prix_qte),
                    'vente'=>true,
                    'transfert'=>false,
                    'correction'=>false,
                    'erreur_stockage'=>false,
                    'stockage'=>false,
                    'solde'=>true,
                    'date_op'=>$this->Session->read('date_operation'),
                    'user_id'=>$this->Session->read('Auth.User.id'),
                    'product_id'=>$data['Invoice']['product_id'],
                    'depot'=>false,
                    'boutique'=>true
                  ))){

                  //ENREGISTREMENT DES VALEURS FACTURE
                  $facture=$this->Invoice->findById((int)$fact_id);
                  $valeur_total=null;
                  if(empty($facture['Invoice']['valeur'])){
                    $valeur_total=0;
                  }else{
                    $valeur_total=(float)$facture['Invoice']['valeur'];
                  }
                  $this->Invoice->id=$fact_id;
                  if($this->Invoice->save(array('valeur'=>$valeur_total + ((float)$prix_unitaire_boutique * $prix_qte)))){
                    //MISE A JOUR DE LA VALEUR DU PRODUIT
                    $valeur_vendue=$this->Sale->find('all',array('conditions'=>array('product_id'=>$data['Invoice']['product_id'],'invoice_id'=>(int)$fact_id)));
                    if(empty($valeur_vendue)){
                      $this->Sale->create();
                      if($this->Sale->save(array(
                        'qte'=>$prix_qte,
                        'prix_unit'=>(float)$prix_unitaire_boutique,
                        'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                        'invoice_id'=>(int)$fact_id,
                        'product_id'=>$data['Invoice']['product_id']
                        ))){
                          //VALEUR DE STOCKAGE D'ORIGINE
                          if($this->Value->save(array(
                            'qte'=>$prix_qte,
                            'prix_unit'=>$prix_une,
                            'montant'=>$prix_qte * (float)$prix_une,
                            'product_id'=>$data['Invoice']['product_id'],
                            'invoice_id'=>(int)$fact_id,
                            'sale_id'=>$this->Sale->id,
                            ))){
                              $this->Session->setFlash("Produit ajouter",'success');
                              return $this->redirect(array('action'=>'vente')); 
                          }
                      }
                    }else{
                      if((float)$prix_unitaire_boutique==(float)$valeur_vendue['0']['Sale']['prix_unit']){
                        $this->Sale->id=(int)$valeur_vendue['0']['Sale']['id'];
                        if($this->Sale->save(array(
                        'qte'=>(int)$valeur_vendue['0']['Sale']['qte'] + $prix_qte,
                        'valeur'=>((float)$valeur_vendue['0']['Sale']['valeur']) +((float)$prix_unitaire_boutique * $prix_qte),
                        ))){
                          //VALEUR DE STOCKAGE D'ORIGINE
                          $this->Value->create();
                          if($this->Value->save(array(
                            'qte'=>$prix_qte,
                            'prix_unit'=>(float)$prix_une,
                            'montant'=>$prix_qte * (float)$prix_une,
                            'product_id'=>$data['Invoice']['product_id'],
                            'invoice_id'=>(int)$fact_id,
                            'sale_id'=>$this->Sale->id,
                            ))){
                              $this->Session->setFlash("Produit ajouter",'success');
                              return $this->redirect(array('action'=>'vente')); 
                          }
                        }
                      }else{
                        $this->Sale->create();
                        if($this->Sale->save(array(
                          'qte'=>$prix_qte,
                          'prix_unit'=>(float)$prix_unitaire_boutique,
                          'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                          'invoice_id'=>(int)$fact_id,
                          'product_id'=>$data['Invoice']['product_id']
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            if($this->Value->save(array(
                              'qte'=>$prix_qte,
                              'prix_unit'=>(float)$prix_une,
                              'montant'=>$prix_qte * (float)$prix_une,
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
                                $this->Session->setFlash("Produit ajouter",'success');
                                return $this->redirect(array('action'=>'vente')); 
                            }
                        }
                      }
                    }
                  }
              }
            }
          }elseif ((int)$verification_produit['0']['Warehouse']['qte']<$prix_qte) {
            //valeur de la quantite une
            $qte_une=(int)$verification_produit['0']['Warehouse']['qte'];
            $valeur_qte_une=(int)$verification_produit['0']['Warehouse']['qte'] * $prix_une;
            // valeur de la quantite deux
            $qte_deux=(int)$verification_produit['1']['Warehouse']['qte']-$prix_qte;
            $valeur_qte_deux=(int)$verification_produit['1']['Warehouse']['qte'] * $prix_deux;
            //somme de valeur
            $somme_valeur=$valeur_qte_une + $valeur_qte_deux;
            $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
            if($this->Warehouse->save(array('solde'=>false))){
              $this->Warehouse->create();
              if($this->Warehouse->save(array(
                    'qte'=>$prix_qte,
                    'prix_unit'=>(float)$prix_unitaire_boutique,
                    'valeur_total'=>(float)$prix_unitaire_boutique * $prix_qte,
                    'qte_total'=>$solde_disponible,
                    'valeur'=>(float)$verification_produit['0']['Warehouse']['valeur'] - ((float)somme_valeur),
                    'vente'=>true,
                    'transfert'=>false,
                    'correction'=>false,
                    'erreur_stockage'=>false,
                    'stockage'=>false,
                    'solde'=>true,
                    'date_op'=>$this->Session->read('date_operation'),
                    'user_id'=>$this->Session->read('Auth.User.id'),
                    'product_id'=>$data['Invoice']['product_id'],
                    'depot'=>false,
                    'boutique'=>true
                  ))){

                  //ENREGISTREMENT DES VALEURS FACTURE
                  $facture=$this->Invoice->findById((int)$fact_id);
                  $valeur_total=null;
                  if(empty($facture['Invoice']['valeur'])){
                    $valeur_total=0;
                  }else{
                    $valeur_total=(float)$facture['Invoice']['valeur'];
                  }
                  $this->Invoice->id=$fact_id;
                  if($this->Invoice->save(array('valeur'=>$valeur_total + ((float)$prix_unitaire_boutique * $prix_qte)))){
                    //MISE A JOUR DE LA VALEUR DU PRODUIT
                    $valeur_vendue=$this->Sale->find('all',array('conditions'=>array('product_id'=>$data['Invoice']['product_id'],'invoice_id'=>(int)$fact_id)));
                    if(empty($valeur_vendue)){
                      $this->Sale->create();
                      if($this->Sale->save(array(
                        'qte'=>$prix_qte,
                        'prix_unit'=>(float)$prix_unitaire_boutique,
                        'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                        'invoice_id'=>(int)$fact_id,
                        'product_id'=>$data['Invoice']['product_id']
                        ))){
                          //VALEUR DE STOCKAGE D'ORIGINE
                          if($this->Value->save(array(
                            'qte'=>$qte_une,
                            'prix_unit'=>$prix_une,
                            'montant'=>$prix_qte * (float)$prix_une,
                            'product_id'=>$data['Invoice']['product_id'],
                            'invoice_id'=>(int)$fact_id,
                            'sale_id'=>$this->Sale->id,
                            ))){

                              if($this->Value->save(array(
                                'qte'=>$qte_deux,
                                'prix_unit'=>$prix_deux,
                                'montant'=>$qte_deux * (float)$prix_deux,
                                'product_id'=>$data['Invoice']['product_id'],
                                'invoice_id'=>(int)$fact_id,
                                'sale_id'=>$this->Sale->id,
                                ))){
                                  $this->Session->setFlash("Produit ajouter",'success');
                                  return $this->redirect(array('action'=>'vente')); 
                                }
                              
                          }
                      }
                    }else{
                      if((float)$prix_unitaire_boutique==(float)$valeur_vendue['0']['Sale']['prix_unit']){
                        $this->Sale->id=(int)$valeur_vendue['0']['Sale']['id'];
                        if($this->Sale->save(array(
                        'qte'=>(int)$valeur_vendue['0']['Sale']['qte'] + $prix_qte,
                        'valeur'=>((float)$valeur_vendue['0']['Sale']['valeur']) +((float)$prix_unitaire_boutique * $prix_qte),
                        ))){
                          //VALEUR DE STOCKAGE D'ORIGINE
                          $this->Value->create();
                          if($this->Value->save(array(
                            'qte'=>$qte_une,
                            'prix_unit'=>(float)$prix_une,
                            'montant'=>$qte_une * (float)$prix_une,
                            'product_id'=>$data['Invoice']['product_id'],
                            'invoice_id'=>(int)$fact_id,
                            'sale_id'=>$this->Sale->id,
                            ))){
                              if($this->Value->save(array(
                                'qte'=>$qte_deux,
                                'prix_unit'=>$prix_deux,
                                'montant'=>$qte_deux * (float)$prix_deux,
                                'product_id'=>$data['Invoice']['product_id'],
                                'invoice_id'=>(int)$fact_id,
                                'sale_id'=>$this->Sale->id,
                                ))){
                                  $this->Session->setFlash("Produit ajouter",'success');
                                  return $this->redirect(array('action'=>'vente')); 
                                }
                          }
                        }
                      }else{
                        $this->Sale->create();
                        if($this->Sale->save(array(
                          'qte'=>$prix_qte,
                          'prix_unit'=>$prix_une,
                          'valeur'=>(float)$prix_unitaire_boutique * $prix_qte,
                          'invoice_id'=>(int)$fact_id,
                          'product_id'=>$data['Invoice']['product_id']
                          ))){
                            //VALEUR DE STOCKAGE D'ORIGINE
                            if($this->Value->save(array(
                              'qte'=>$qte_une,
                              'prix_unit'=>$prix_une,
                              'montant'=>$prix_qte * (float)$prix_une,
                              'product_id'=>$data['Invoice']['product_id'],
                              'invoice_id'=>(int)$fact_id,
                              'sale_id'=>$this->Sale->id,
                              ))){
  
                                if($this->Value->save(array(
                                  'qte'=>$qte_deux,
                                  'prix_unit'=>$prix_deux,
                                  'montant'=>$qte_deux * (float)$prix_deux,
                                  'product_id'=>$data['Invoice']['product_id'],
                                  'invoice_id'=>(int)$fact_id,
                                  'sale_id'=>$this->Sale->id,
                                  ))){
                                    $this->Session->setFlash("Produit ajouter",'success');
                                    return $this->redirect(array('action'=>'vente')); 
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
          $this->Session->setFlash("La quantité disponible est de ".$qte_solde.' '.$verification_produit['0']['Product']['mesure'],'danger');
          return $this->redirect(array('action'=>'vente')); 
        }
      } 
    }

    //Injection des données dans le formulaire
    if(!$this->request->data){
      $this->set('products',$produit=$this->Product->find('list', array('conditions'=>array('statut'=>true),'fields'=>array('id','nom_produit'))));
    }

    $this->set('facture_numero',$fact_id);
    $this->set('facture',$this->Invoice->findById($fact_id));
    $this->set('vente',$this->Sale->find('all',array('conditions'=>array('invoice_id'=>$fact_id))));
  }

  #Statut du produit
  public function supprimer($id){
    $this->layout ='admin';
    $title="Ets MAKI |Vente";

    $this->loadModel('Product');
    $this->loadModel('Warehouse');
    $this->loadModel('Expiration');
    $this->loadModel('Value');
    $this->loadModel('Sale');

    #Vérification de l'ID dans le paramtere 
    if(!$id){
      return $this->redirect(array('action'=>'vente'));
    }
    #Id du produit
    $id=$id; 
    $valeur_du_stock=$this->Value->find('all',array('conditions'=>array('sale_id'=>$id)));
    //Produit ID
    $produit_id=$valeur_du_stock['0']['Value']['product_id'];
    //Produit encours de vente
    $verification_produit=$this->Warehouse->find('all',array('conditions'=>array(
      'product_id'=>(int)$produit_id,'solde'=>true,'boutique'=>true
    )));
    $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
    if($this->Warehouse->save(array('solde'=>false))){
      if($this->Warehouse->save(array(
        'qte'=>(int)$valeur_du_stock['0']['Value']['qte'],
        'prix_unit'=>(float)$valeur_du_stock['0']['Value']['prix_unit'],
        'valeur_total'=>(float)$valeur_du_stock['0']['Value']['montant'],
        'qte_total'=>(int)$valeur_du_stock['0']['Value']['qte']+(int)$verification_produit['0']['Warehouse']['qte_total'],
        'valeur'=>(float)$valeur_du_stock['0']['Value']['montant']+(float)$verification_produit['0']['Warehouse']['valeur'],
        'annul_facture'=>true,
        'solde'=>true,
        'boutique'=>true,
        'vente'=>false,
        'transfert'=>false,
        'correction'=>false,
        'erreur_stockage'=>false,
        'stockage'=>false,
        'product_id'=>$produit_id,
        'user_id'=>$this->Session->read('Auth.User.id'),
        'date_op'=>date('Y-m-d')
      ))){
        $this->Invoice->id=(int)$valeur_du_stock['0']['Invoice']['id'];
        if($this->Invoice->save(array('valeur'=>(float)$valeur_du_stock['0']['Invoice']['valeur']-(float)$valeur_du_stock['0']['Sale']['valeur']))){
          $this->Sale->delete((int)$valeur_du_stock['0']['Sale']['id']);
          $this->Value->delete((int)$valeur_du_stock['0']['Value']['id']);

          $this->Session->setFlash("Supprimer sur la facture",'danger');
          return $this->redirect(array('action'=>'vente'));
        }
      }
    }
  }


  public function annuler($id){
    $this->layout ='admin';
    $title="Ets MAKI |Vente";

    $this->loadModel('Product');
    $this->loadModel('Warehouse');
    $this->loadModel('Expiration');
    $this->loadModel('Value');
    $this->loadModel('Sale');

    #Vérification de l'ID dans le paramtere 
    if(!$id){
      return $this->redirect(array('action'=>'vente'));
    }
    #Id du produit
    $id=$id; 

    $fac_encours=$this->Invoice->findById($id);
    
    foreach ($fac_encours['Value'] as $value) {
      
      foreach ($fac_encours['Sale'] as $values) {
      $verification_produit=$this->Warehouse->find('all',array('conditions'=>array(
        'product_id'=>$values['product_id'],'solde'=>true,'boutique'=>true
      )));

      $this->Warehouse->id=(int)$verification_produit['0']['Warehouse']['id'];
      if($this->Warehouse->save(array('solde'=>false))){
        if($this->Warehouse->save(array(
          'qte'=>(int)$value['qte'],
          'prix_unit'=>(float)$value['prix_unit'],
          'valeur_total'=>(float)$value['montant'],
          'qte_total'=>(int)$value['qte']+(int)$verification_produit['0']['Warehouse']['qte_total'],
          'valeur'=>(float)$value['montant']+(float)$verification_produit['0']['Warehouse']['valeur'],
          'annul_facture'=>true,
          'solde'=>true,
          'boutique'=>true,
          'vente'=>false,
          'transfert'=>false,
          'correction'=>false,
          'erreur_stockage'=>false,
          'stockage'=>false,
          'product_id'=>$produit_id,
          'user_id'=>$this->Session->read('Auth.User.id'),
          'date_op'=>date('Y-m-d')
        ))){
          $this->Sale->delete($values['id']);
          $this->Value->delete($value['id']);

          
          
        }

      }   
    }
  }
  $this->Invoice->delete($id);
  $this->Session->setFlash("Supprimer avec succès",'success');
  return $this->redirect(array('action'=>'index'));
  
  }

  public function rapports(){
    $this->layout ='admin';
    $title="Ets MAKI |Vente";

    $this->loadModel('Product');
    $this->loadModel('Warehouse');
    $this->loadModel('Expiration');
    $this->loadModel('Value');
    $this->loadModel('Sale');
    $date='%'.date('Y-m-d').'%';
    var_dump($date);
    var_dump($this->Invoice->find('all', array('conditions'=>array('Invoice.date_op LIKE'=>$date))));
    die();

    
  
  }

}

?>
