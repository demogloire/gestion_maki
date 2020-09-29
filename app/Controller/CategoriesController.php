<?php

/**
*
*/
class CategoriesController extends AppController{

  #Enregistrement une categorie
  public function index(){
    #Chargement du layour
    $this->layout ='admin';
    $title="Ets MAKI |Catégorie";
    #Reception des informations depuis le formulaire
    if($this->request->is(array('post','put'))){
      $data=$this->request->data;
      #Vérification de l'existence des informations
      $un_ver=$this->Category->find('all', array('conditions'=>array('nom'=>$data['Categories']['nom'])));
      if(empty($un_ver)){
        if($this->Category->save(
          array(
            'nom'=>$data["Categories"]["nom"],
            'statut'=>true
          )
        )){
          $this->Session->setFlash("Ajout d'une catégorie avec succès",'succes');
          return $this->redirect(array('action'=>'index'));
        }
      }else{
        $this->Session->setFlash("Cet utilisateur existe déjà",'danger');
        return $this->redirect(array('action'=>'index'));
      }
    }
    $categorie=$this->Category->find('all');
    #Envoi des informations 
    $this->set('title',$title);
    $this->set('categorie',$categorie);
  }

  #Statut du produit
  public function statut($id){
    $this->layout ='admin';
    $title="Ets MAKI |Statut";

    #Vérification de l'ID dans le paramtere 
    if(!$id){
      return $this->redirect(array('action'=>'index'));
    }
    #Id de l'utilisateur
    $id=$id; 
    #Vérification du statut de l'utilisateur
    $statut=$this->Category->findById($id);
    if($statut['Category']['statut']==true){
      $this->Category->id=$id;
      if($this->Category->save(array('statut'=>false))){
        $this->Session->setFlash("Vous avez desactivé la catégorie",'succes');
        return $this->redirect(array('action'=>'index'));
      }
    }else{
      $this->Category->id=$id;
      if($this->Category->save(array('statut'=>true))){
        $this->Session->setFlash("Vous avez activé la catégorie",'succes');
        return $this->redirect(array('action'=>'index'));
      }
    }
  }

  public function editer_cat($id){
    $this->layout ='admin';
    $title="Ets MAKI | Editer";
    #Sans parametre de l'ID
    if(!$id){
      return $this->redirect(array('action'=>'index'));
    }
    #Selection par ID
    $id=$id;
    $categorie=$this->Category->findById($id);
    #requête pour la mise à jour
    if($this->request->is(array('post','put'))){
      #Requete de la catégorie
      $data=$this->request->data;
       #Vérification de la catégorie
      $un_ver=$this->Category->find('all', array('conditions'=>array('nom'=>$data['Category']['nom'])));
      #Vérification du username
      if(empty($un_ver)){
        $this->Category->id=$id;
        if($this->Category->save(array(
          'nom'=>$data["Category"]["nom"],         
        ))){
          $this->Session->setFlash("Modification avec succès",'succes');
          return $this->redirect(array('action'=>'index'));

        }
      }else{
        $this->Session->setFlash("Cette catégorie existe déjà",'danger');
          return $this->redirect(array('action'=>'editer_cat'));
      }

    } 

    #Envoi des données dans le formulaire
    if(!$this->request->data){
      $this->request->data=$categorie;
      $this->set('nom',$categorie['Category']['nom']);
    }
    $this->set('title',$title);


    
  }


}

?>
