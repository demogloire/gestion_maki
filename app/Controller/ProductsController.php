<?php

/**
*
*/
class ProductsController extends AppController{

  
  #Enregistrement d'un produit
  public function enregistre_produit(){
    $this->layout ='admin';
    $this->loadModel('Category');
    #Chargement du model
    $title="Ets MAKI | Produit";
    $categories=$this->Category->find('list',array(
      'conditions'=>array('statut'=>true),
      'fields' => array('id', 'nom')));
    // var_dump($categories);
    // die();
    #Verification d'extance d'aumoins une catégorie
    if(empty($categories)){
      $this->Session->setFlash("Remplissez d'abord les catégories",'danger');
      return $this->redirect(array('controller'=>'categories','action'=>'index'));      
    }
    #Trqnsi;isstion des données
    $data=$this->request->data;

    if($this->request->is('post')){
      #Vérification des données vide
      if(empty($data['Product']['mesure']) or empty($data['Product']['emballage']) or empty($data['Product']['prix_detaille']) or empty($data['Product']['nombre_contenu']) or empty($data['Product']['nom_produit']) or  empty($data['Product']['cout_achat'])){
        $this->Session->setFlash("Vérifier les informations",'danger');
        return $this->redirect(array('controller'=>'products','action'=>'enregistre_produit'));   
      }
      #Vérification des nombre inférieur
      $prix_achat=(float)$data['Product']['cout_achat'];
      $prix_detail=(float)$data['Product']['prix_detaille'];
      $contenue_pro=(float)$data['Product']['nombre_contenu'];
      if($prix_achat <= 0 or $prix_detail<=0 or $contenue_pro <=0){
        $this->Session->setFlash("le nombre doit-être superieur à 0",'danger');
        return $this->redirect(array('controller'=>'products','action'=>'enregistre_produit'));   
      }
      #Varification de prix de vente inférieur à prix d'achet
      if($prix_detail < $prix_achat){
        $this->Session->setFlash("Impossible de ventre à perte",'danger');
        return $this->redirect(array('controller'=>'products','action'=>'enregistre_produit'));   
      }
      #Vérification de l'existence des informations
      $un_ver=$this->Product->find('all', array('conditions'=>array('nom'=>$data['Product']['nom_produit']), 
                                                                    'category_id'=>$data['Product']['category_id']));
      #Enregistrement du produit
      if($this->Product->save(array(
        'nom_produit'=>$data['Product']['nom_produit'],
        'description'=>$data['Product']['description'],
        'cout_achat'=>$data['Product']['cout_achat'],
        'prix_detaille'=>$data['Product']['prix_detaille'],
        'emballage'=>$data['Product']['emballage'],
        'mesure'=>$data['Product']['mesure'],
        'nombre_contenu'=>$data['Product']['nombre_contenu'],
        'category_id'=>$data['Product']['category_id'],
        'perrisable'=>false,
        'statut'=>true
      ))){
        $this->Session->setFlash("Ajout d'un produit avec succès",'succes');
        return $this->redirect(array('controller'=>'products','action'=>'index')); 
      }






    }
  
    if (!$this->request->data) {
      $this->set('categories',$categories);
    }

    $this->set('categories',$categories);
    $this->set('title',$title);
  }



 #Affichage de tous les produits
 public function index(){
  $this->layout ='admin';
  $title="Ets MAKI |Produit";
  $produits=$this->Product->find('all');




  $this->set('title',$title);
  $this->set('produits',$produits);

 }

#Statut du produit
public function statut($id){
    $this->layout ='admin';
    $title="Ets MAKI |Produit";

    #Vérification de l'ID dans le paramtere 
    if(!$id){
      return $this->redirect(array('action'=>'index'));
    }
    #Id du produit
    $id=$id; 
    #Vérification du statut du produit
    $statut=$this->Product->findById($id);

     if($statut['Product']['statut']==true){
      $this->Product->id=$id;
       if($this->Product->save(array('statut'=>false))){
         $this->Session->setFlash("Vous avez desactivé le produit",'succes');
        return $this->redirect(array('action'=>'index'));

       }
     }else{
      $this->Product->id=$id;
       if($this->Product->save(array('statut'=>true))){
        $this->Session->setFlash("Vous avez activé le produit",'succes');
        return $this->redirect(array('action'=>'index'));
      }
     }
}

#Statut du produit
public function perrisable($id){
  $this->layout ='admin';
  $title="Ets MAKI |Produit";
  $this->loadModel('Category');

  #Vérification de l'ID dans le paramtere 
  if(!$id){
    return $this->redirect(array('action'=>'index'));
  }
  #Id du produit
  $id=$id; 
  #Vérification du perrisable du produit
  $statut=$this->Product->findById($id);
   if($statut['Product']['perrisable']==true){
     if(empty($statut['Expiration'])){
      $this->Product->id=$id;
      if($this->Product->save(array('perrisable'=>false))){
        $this->Session->setFlash("Le produit deviens non perrisable",'succes');
       return $this->redirect(array('action'=>'index'));
      }else{
        $this->Session->setFlash("Ce produit à un statut perrisable dans le stock",'danger');
        return $this->redirect(array('action'=>'index'));
      }
     }
   }else{
    $this->Product->id=$id;
     if($this->Product->save(array('perrisable'=>true))){
      $this->Session->setFlash("Le produit deviens perrisable",'succes');
      return $this->redirect(array('action'=>'index'));
    }
   }
}


public function editer($id){
    $this->layout ='admin';
    $title="Ets MAKI | produit";
    #Sans parametre de l'ID
    if(!$id){
      return $this->redirect(array('action'=>'index'));
    }
    #Les identifiants
    $id=$id;
    $produit=$this->Product->findById($id);
    #requête pour la mise à jour
    if($this->request->is(array('post','put'))){
      #Requete de l'produit
      $data=$this->request->data;
      #Vérification des données vide
      if(empty($data['Product']['mesure']) or empty($data['Product']['emballage']) or empty($data['Product']['prix_detaille']) or empty($data['Product']['nombre_contenu']) or empty($data['Product']['nom_produit']) or  empty($data['Product']['cout_achat'])){
        $this->Session->setFlash("Vérifier les informations",'danger');
        return $this->redirect(array('controller'=>'products','action'=>'index'));   
      }
      #Vérification des nombre inférieur
      $prix_achat=(float)$data['Product']['cout_achat'];
      $prix_detail=(float)$data['Product']['prix_detaille'];
      $contenue_pro=(float)$data['Product']['nombre_contenu'];
      if($prix_achat <= 0 or $prix_detail<=0 or $contenue_pro <=0){
        $this->Session->setFlash("le nombre doit-être superieur à 0",'danger');
        return $this->redirect(array('controller'=>'products','action'=>'index'));   
      }
      #Varification de prix de vente inférieur à prix d'achet
      if($prix_detail < $prix_achat){
        $this->Session->setFlash("Impossible de ventre à perte",'danger');
        return $this->redirect(array('controller'=>'products','action'=>'index'));   
      }
      #Vérification de l'existence des informations
      $un_ver=$this->Product->find('all', array('conditions'=>array('nom'=>$data['Product']['nom_produit']), 
                                                                    'category_id'=>$data['Product']['category_id']));
      #Vérification du username
      if(!empty($un_ver)){
        $this->Product->id=$id;
        if($this->Product->save(array(
          'description'=>$data['Product']['description'],
          'cout_achat'=>$data['Product']['cout_achat'],
          'prix_detaille'=>$data['Product']['prix_detaille'],
          'emballage'=>$data['Product']['emballage'],
          'mesure'=>$data['Product']['mesure'],
          'nombre_contenu'=>$data['Product']['nombre_contenu'],         
        ))){
          $this->Session->setFlash("Modification avec succès",'succes');
          $this->redirect(array('action'=>'index'));

        }
      }else{
        $this->Product->id=$id;
        if($this->Product->save(array(
          'nom_produit'=>$data['Product']['nom_produit'],
          'description'=>$data['Product']['description'],
          'cout_achat'=>$data['Product']['cout_achat'],
          'prix_detaille'=>$data['Product']['prix_detaille'],
          'emballage'=>$data['Product']['emballage'],
          'mesure'=>$data['Product']['mesure'],
          'nombre_contenu'=>$data['Product']['nombre_contenu'],
          'category_id'=>$data['Product']['category_id'],
          'perrisable'=>false,
          'statut'=>true
        ))){
          $this->Session->setFlash("Modification avec succès",'succes');
          return $this->redirect(array('action'=>'index'));

        }

      }

    } 

    $categories=$this->Product->Category->find('list',array(
      'conditions'=>array('statut'=>true),
      'fields' => array('id', 'nom')

      ));

    #Envoi des données dans le formulaire
    if(!$this->request->data){
      $this->request->data=$produit;
      $this->set('categories',$categories);
    }
    $this->set('title',$title);

    
  }





// public function login(){

//   $this->layout ='login';

//   //Vérification de la connextion
//   if($this->Session->read('Auth.Product.id') !=null){
//     return $this->redirect(array('action'=>'enregister_produit'));
//   }

//   if ($this->request->is('post')) {
//     //Requête de la connexion
//     $data=$this->request->data;
    
//     if ($this->Auth->login()){
//       return $this->redirect($this->Auth->redirectUrl('/dashbord'));
//     }else{
//       $produit=$this->Product->find('all',array('conditions'=>array('username'=>$data['Product']['username'])));
//       if(!empty($produit)){
//         $this->Session->setFlash("Mot de passe incorrect",'danger');
//         return $this->redirect(array('action'=>'login'));
//       }else{
//         $this->Session->setFlash("Nom d'produit incorrect",'danger');
//         return $this->redirect(array('action'=>'login'));
//       }
//     }}


// }

// public function logout(){
//     $this->Auth->logout();
//     return $this->redirect('/');
//   }


//

}?>
