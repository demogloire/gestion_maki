<?php



/**
 *
 */
class User extends AppModel{

    public $hasMany=array('Warehouse','Expiration','Invoice');

    public $validate = array(
      'nom' => array(
        'regleNom-1'=>array(
          'rule'=>'alphaNumeric',
          'message' => 'Lettres et chiffres uniquement',
          'last'=>true,
        ),

        'regleNom-2'=>array(
          'rule'=>array('minLength', 4),
          'message' => 'Au minimum 4 caractères',
        )),

        'post_nom' => array(
          'reglePostNom-1'=>array(
            'rule'=>'alphaNumeric',
            'message' => 'Lettres et chiffres uniquement',
            'last'=>true,
          ),
  
          'reglePostNom-2'=>array(
            'rule'=>array('minLength', 4),
            'message' => 'Au minimum 4 caractères',
          )),

          'prenom' => array(
            'reglePrenom-1'=>array(
              'rule'=>'alphaNumeric',
              'message' => 'Lettres et chiffres uniquement',
              'last'=>true,
            ),
    
            'reglePrenom-2'=>array(
              'rule'=>array('minLength', 4),
              'message' => 'Au minimum 4 caractères',
            )),

            'username' => array(
              'regleusername-1'=>array(
                'rule'=>'alphaNumeric',
                'message' => 'Lettres et chiffres uniquement',
                'last'=>true,
              ),
      
              'regleusername-2'=>array(
                'rule'=>array('minLength', 4),
                'message' => 'Au minimum 4 caractères',
              )),
            

              'password' => array(
                'reglepassword-1'=>array(
                  'rule'=>'alphaNumeric',
                  'message' => 'Lettres et chiffres uniquement',
                  'last'=>true,
                ),
        
                'reglepassword-2'=>array(
                  'rule'=>array('minLength', 6),
                  'message' => 'Au minimum 4 caractères',
                ))
      );
  
  
  
  }
  
  

?>