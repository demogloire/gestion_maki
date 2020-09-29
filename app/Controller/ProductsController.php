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






    }
  
    if (!$this->request->data) {
      $this->set('categories',$categories);
    }

    $this->set('categories',$categories);
    $this->set('title',$title);
  }

  #Statut de l'Agent utilisateur
//   public function statut($id){
//     $this->layout ='admin';
//     $title="Ets MAKI |Statut";

//     #Vérification de l'ID dans le paramtere 
//     if(!$id){
//       $this->redirect(array('action'=>'enregister_utilisateur'));
//     }
//     #Id de l'utilisateur
//     $id=$id; 
//     #Vérification du statut de l'utilisateur
//     $statut=$this->User->findById($id);

//     if($statut['User']['statut']==true){
//       $this->User->id=$id;
//       if($this->User->save(array('statut'=>false))){
//         $this->Session->setFlash("Vous avez desactivé l'utilisateur",'succes');
//         $this->redirect(array('action'=>'enregister_utilisateur'));

//       }
//     }else{
//       $this->User->id=$id;
//       if($this->User->save(array('statut'=>true))){
//         $this->Session->setFlash("Vous avez activé l'utilisateur",'succes');
//         $this->redirect(array('action'=>'enregister_utilisateur'));
//       }
//     }
//   }

//   public function editer_utilisateur($id){
//     $this->layout ='admin';
//     $title="Ets MAKI | Editer";
//     #Sans parametre de l'ID
//     if(!$id){
//       $this->redirect(array('action'=>'enregister_utilisateur'));
//     }
//     #Les identifiants
//     $id=$id;
//     $utilisateur=$this->User->findById($id);
//     #requête pour la mise à jour
//     if($this->request->is(array('post','put'))){
//       #Requete de l'utilisateur
//       $data=$this->request->data;
//       #Vérification de l'utilisateur
//       $un_ver=$this->User->find('all', array('conditions'=>array('username'=>$data['User']['username'])));
//       #Vérification du username
//       if(!empty($un_ver)){
//         $this->User->id=$id;
//         if($this->User->save(array(
//           'nom'=>$data["User"]["nom"],
//           'post_nom'=>$data["User"]["post_nom"],
//           'prenom'=>$data["User"]["prenom"],
//           'role'=>$data["User"]["role"],
          
//         ))){
//           $this->Session->setFlash("Modification avec succès",'succes');
//           $this->redirect(array('action'=>'enregister_utilisateur'));

//         }
//       }else{
//         $this->User->id=$id;
//         if($this->User->save(array(
//           'nom'=>strtoupper($data["User"]["nom"]),
//           'post_nom'=>strtoupper($data["User"]["post_nom"]),
//           'prenom'=>ucfirst($data["User"]["prenom"]),
//           'role'=>$data["User"]["role"],
//           'username'=>$data["User"]["username"],
//         ))){
//           $this->Session->setFlash("Modification avec succès",'succes');
//           return $this->redirect(array('action'=>'enregister_utilisateur'));

//         }

//       }

//     } 

//     #Envoi des données dans le formulaire
//     if(!$this->request->data){
//       $this->request->data=$utilisateur;
//       $this->set('role_d',$utilisateur['User']['role']);
//     }
//     $this->set('role_d',$utilisateur['User']['role']);
//     $this->set('title',$title);

    
//   }





// public function login(){

//   $this->layout ='login';

//   //Vérification de la connextion
//   if($this->Session->read('Auth.User.id') !=null){
//     return $this->redirect(array('action'=>'enregister_utilisateur'));
//   }

//   if ($this->request->is('post')) {
//     //Requête de la connexion
//     $data=$this->request->data;
    
//     if ($this->Auth->login()){
//       return $this->redirect($this->Auth->redirectUrl('/dashbord'));
//     }else{
//       $utilisateur=$this->User->find('all',array('conditions'=>array('username'=>$data['User']['username'])));
//       if(!empty($utilisateur)){
//         $this->Session->setFlash("Mot de passe incorrect",'danger');
//         return $this->redirect(array('action'=>'login'));
//       }else{
//         $this->Session->setFlash("Nom d'utilisateur incorrect",'danger');
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
