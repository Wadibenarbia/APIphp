<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require '../vendor/autoload.php';
/**
 * Description of MongoManager
 *
 * @author O
 */
class MongoManager {
    //put your code here
     protected $ClientMongo ;
     protected $Collection;
     
     
       function __construct() {
            $this->ClientMongo  = new MongoDB\Client("mongodb://localhost:27017") ;
          }
    
    
     function getClientMongo() {
        return $this->ClientMongo;
    }

    function getCollection() {
        return $this->Collection;
    }

    function setClientMongo($ClientMongo) {
        $this->ClientMongo = $ClientMongo;
    }

    function setCollection($Collection) {
        $this->Collection = $Collection;
    }

    function initMongo()
    {
        $this->ClientMongo  = new MongoDB\Client("mongodb://localhost:27017") ;
    }
    
    /**
* Find
* est une fonction qui permet de faire 2 chose. 
*  
     * 1.Elle permet d'afficher tous les élèment d'une collection Mongo Find()
     * 
     * 2.et d'afficher certain élèments respectant la conditions impossé 
     * 
     *Exemple : 
     * $condition =  [
     * "nom" => "Red"
     * ];
     * 
     * find($condition);
     * 
     * 
     * qui va nous afficher tous les élèment ayant comme nom "Red"
     * 
* @param Request $array Type Array 
* @return String
 */
    function find($array=[])
    {
        if (sizeof($array)<1){
            $result =  $this->Collection->find();
        }
       else
            $result =  $this->Collection->find($array);
       foreach ($result as $document) {
       $array[] = ($document) ;
       }
       return json_encode($array,true); 
    }
    /**
* findById
*permet de récuperer le premier object de la collection ayant sont Id identique à celui imposé 
* @param Request $id Type String
* @return String
 */
    
    function findById($id)
    {
        return $this->Collection->findOne([],["id=>$id"]);
    }
    
        function findOneAndDelete($id)
    {
        return $this->Collection->findOneAndDelete([],["id=>$id"]);
    }
    
    
    
 /**
* Insert
*Permet d'insérer UN object "seulement UN dans une collection
  * 
  * Exemple 
  *  
  *  $array = [
  * "nom"=>"Red",
  * "prenom"=>"san",
  * "age"=> "28",
  * "adresse" =>" Paris"
  * ];
    
  Insert($array);
  * 
* @param Request $Collection Type Array
* @return String
 */
    function Insert($Collection)
    {
        $this->Collection->insertOne($Collection);
    }
    
    
     /**
* DeleteOne
*Permet de Suprimmer le Premier object  d'une collection respectant les condition imposer 
  * 
  * Exemple 
  *  
  *  $arrayDelet = [
  * "nom"=>"Red",
  * "age"=> "28",
  * ];
  *  DeleteOne($arrayDelet);
      * 
      * Ce qui va supprimer le premier object de la collection ayant comme attribu Nom : red et age : 23
  * 
* @param Request $document Type Array
* @return String
 */
    
    function DeleteOne($document)
    {
        $this->Collection->deleteOne($document);
    }
    
    
/**
* DeleteMany
*Permet de Suprimmer Tous les  objects  d'une collection respectant les condition imposer 
  * 
  * Exemple 
  *  
  *  $arrayDelet = [
  * "nom"=>"Red",
  * "age"=> "28",
  * ];
 * DeleteMany($arrayDelet);
 * 
 * Ce qui va supprimer Tous les object de la collection ayant comme attribu Nom : red et age : 23
  * 
* @param Request $document Type Array
* @return String
 */
    
    function DeleteMany($document)
    {
        $this->Collection->deleteMany($document);
    }
    
    /**
* Drop
*Permet de Suprimmer Tout une Collection  
 */
    
    function Drop()
    {
        $this->Collection->drop();
    }
    
    
    
    function FindObject($document,$document2)
    {
       return $this->Collection->findOne($document,$document2,"asc");
       
    }
    
    
    /**
* UpdateObjec
*Permet de Modifier un objects  d'une collection respectant les condition imposer 
  * 
  * Exemple 
  * 
     * $newdata= array('$set' =>array("nom" => "red"));
     * 
     * Updateobjec(array("nom" => "blue"),$newdata );
  * 
     * Cette fonction vas rechercher dans la collection l'object ayant pour nom : red afin de remplacer son nom par blue
* @param Request $document Type Array
* @param Request $newdata Type Array
* @return String
 */
    
    function UpdateObjec($document,$newdata)
    {
        $this->Collection->updateOne($document,$newdata);
        
    }
    
    
     /**
* InsertMany
*Permet d'insérer plusieurs object dans une collection
  * 
  * Exemple 
  *  
  *  $array =[
   
                [   'nom'=>'Jack',
                    'prenom'=>'san',
                    'age'=> '39',
                    'adresse' => 'Paris',
                ],
                [
                    "nom"=>"Escobar",
                    "prenom"=>"Pablo",
                    "age"=> "40",
                    "adresse" =>"Colombie",


                ],
                [
                    "nom"=>"Walter",
                    "prenom"=>"White",
                    "age"=> "49",
                    "adresse" =>" US"


                  ],
            ];

    
   InsertMany($array);
      * 
      * Ces 3 object serons insérer dans la collection
  * 
* @param Request $Collection Type Array
* @return String
 */
    
    function InsertMany($Collection)
    {
         $this->Collection->insertMany($Collection);
    }
    
}
