<?php

/**
*
*/
class UsersController extends AppController{

  #Enregistrement de l'Agent utilisateur
  public function enregister_utilisateur(){
    $this->layout ='admin';
    $title="Ets MAKI | Utilisateurs";

    $utilisateur=$this->User->find('all');
    #Ajouter de l'utilisateur
    if($this->request->is('post')){
      $data=$this->request->data; #Récuperation des données du formulaire
      #Récuperation du champs utilisateur
      $username=$data["Users"]["username"];
      #Vérification de l'utilisateur
      $utilisateur=$this->User->find('all',array('conditions'=>array('username'=>$username)));
      if(empty($utilisateur)){
        $this->User->create($data, true);
        if($this->User->save(array(
          'nom'=>strtoupper($data["Users"]["nom"]),
          'post_nom'=>strtoupper($data["Users"]["post_nom"]),
          'prenom'=>ucfirst($data["Users"]["prenom"]),
          'role'=>$data["Users"]["role"],
          'username'=>$data["Users"]["username"],
          'password'=>$this->Auth->password($data["Users"]["password"]),  
          "statut"=>true
        ))){
          $this->Session->setFlash("Succès d'enregistrement",'succes');
          return $this->redirect(array('action'=>'enregister_utilisateur'));
        }else{
          $this->Session->setFlash("Tous le champs en alpha numerique avec 4 caractères au minimu sauf mot de passe 6 caractères",'danger');
          return $this->redirect(array('action'=>'enregister_utilisateur'));
        }

      }else{
        $this->Session->setFlash("Ce nom d'utilisateur existe",'danger');
        return $this->redirect(array('action'=>'enregister_utilisateur'));
      }

    }

    #Envoi des informations 
    $this->set('title',$title);
    $this->set('utilisateur',$utilisateur);

  }

  #Statut de l'Agent utilisateur
  public function statut($id){
    $this->layout ='admin';
    $title="Ets MAKI |Statut";

    #Vérification de l'ID dans le paramtere 
    if(!$id){
      $this->redirect(array('action'=>'enregister_utilisateur'));
    }
    #Id de l'utilisateur
    $id=$id; 
    #Vérification du statut de l'utilisateur
    $statut=$this->User->findById($id);

    if($statut['User']['statut']==true){
      $this->User->id=$id;
      if($this->User->save(array('statut'=>false))){
        $this->Session->setFlash("Vous avez desactivé l'utilisateur",'succes');
        $this->redirect(array('action'=>'enregister_utilisateur'));

      }
    }else{
      $this->User->id=$id;
      if($this->User->save(array('statut'=>true))){
        $this->Session->setFlash("Vous avez activé l'utilisateur",'succes');
        $this->redirect(array('action'=>'enregister_utilisateur'));
      }
    }
  }

  public function editer_utilisateur($id){
    $this->layout ='admin';
    $title="Ets MAKI | Editer";
    #Sans parametre de l'ID
    if(!$id){
      $this->redirect(array('action'=>'enregister_utilisateur'));
    }
    #Les identifiants
    $id=$id;
    $utilisateur=$this->User->findById($id);
    #requête pour la mise à jour
    if($this->request->is(array('post','put'))){
      #Requete de l'utilisateur
      $data=$this->request->data;
      #Vérification de l'utilisateur
      $un_ver=$this->User->find('all', array('conditions'=>array('username'=>$data['User']['username'])));
      #Vérification du username
      if(!empty($un_ver)){
        $this->User->id=$id;
        if($this->User->save(array(
          'nom'=>$data["User"]["nom"],
          'post_nom'=>$data["User"]["post_nom"],
          'prenom'=>$data["User"]["prenom"],
          'role'=>$data["User"]["role"],
          
        ))){
          $this->Session->setFlash("Modification avec succès",'succes');
          $this->redirect(array('action'=>'enregister_utilisateur'));

        }
      }else{
        $this->User->id=$id;
        if($this->User->save(array(
          'nom'=>strtoupper($data["User"]["nom"]),
          'post_nom'=>strtoupper($data["User"]["post_nom"]),
          'prenom'=>ucfirst($data["User"]["prenom"]),
          'role'=>$data["User"]["role"],
          'username'=>$data["User"]["username"],
        ))){
          $this->Session->setFlash("Modification avec succès",'succes');
          return $this->redirect(array('action'=>'enregister_utilisateur'));

        }

      }

    } 

    #Envoi des données dans le formulaire
    if(!$this->request->data){
      $this->request->data=$utilisateur;
      $this->set('role_d',$utilisateur['User']['role']);
    }
    $this->set('role_d',$utilisateur['User']['role']);
    $this->set('title',$title);

    
  }





public function login(){

$this->layout ='login';

//Vérification de la connextion
if($this->Session->read('Auth.User.id') !=null){
  return $this->redirect(array('action'=>'enregister_utilisateur'));
}

if ($this->request->is('post')) {
  //Requête de la connexion
  $data=$this->request->data;
   
  if ($this->Auth->login()){
    return $this->redirect($this->Auth->redirectUrl('/dashbord'));
  }else{
    $utilisateur=$this->User->find('all',array('conditions'=>array('username'=>$data['User']['username'])));
    if(!empty($utilisateur)){
      $this->Session->setFlash("Mot de passe incorrect",'danger');
      return $this->redirect(array('action'=>'login'));
    }else{
      $this->Session->setFlash("Nom d'utilisateur incorrect",'danger');
      return $this->redirect(array('action'=>'login'));
    }
  }}


}

  public function logout()
  {
    $this->Auth->logout();
    $this->Session->delete('facture_id');
    return $this->redirect('/');
  }


}

?>
