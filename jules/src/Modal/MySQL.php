<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MySQL
 *
 * @author Menyas
 */
class MySQL {
    

    protected  $conn ; 
    protected $sql;
    protected $error = -2;
    protected $error_Request = -3;
    protected $valid = 1;
    protected $Desc = "DESC";
    protected $ASC = "ASC";
    protected $C1 = "champs1";
    protected $C2 = "champs2";
    protected $operation = "operations";
    protected $condition = "conditions";
    protected $Request = "";


    //put your code here
function __construct() 
{
      
        $this->ConnectionToBDD("localhost", "root", "", "jules");
}

protected function ConnectionToBDD($servername, $id, $mdp, $dbname) 
{
        
    // Create connection
    $this->conn = new mysqli($servername, $id,$mdp, $dbname);
    // Check connection
    if ($this->conn->connect_error)
        {
        die("Connection failed: " . $conn->connect_error);
        }         
}
 

/**
* insert
* est une fonction Générique qui permet d"insérer de nouvelle données dans la la table choisie  $nametable 
* @param Request $nametable Type String 
* @param Request $valeur Type Array 
* @return String
 */

public function insert($nametable,$valeur){
   
    $tchamp = array();    
    $tvaleur = array();    
    $x = 0 ; 
    foreach($valeur as $key=>$value)
    {
              //var_dump($key);die;
        if($x < sizeof($valeur)-1)
            {
               $tvaleur[] = $value.", ";
               $tchamp [] = $key.", ";
            }
 
        else
            {
               $tvaleur[] = $value." ";
               $tchamp [] = $key." "; 
           }
           $x++;
    }
    
    $tvaleurs=  implode('', ($tvaleur));
    $tchamps = implode('', ($tchamp));
    

//     var_dump($this->sql= "INSERT INTO ".$nametable." (".$tchamps.") VALUES (".$tvaleurs.")");die;
         
    $this->sql= "INSERT INTO ".$nametable." (".$tchamps.") VALUES (".$tvaleurs.")";
    
    if ($this->conn->query($this->sql) === TRUE) {
        echo "New record created successfully";
    } 
    else {
        echo "Error: " . $this->sql . "<br>" . $this->conn->error;
    }

    $this->conn->close();die;
}

/**
* Delete
* est une fonction Générique qui permet de Suprimmer un/des élèment d'une table choisie  $nameTable
 * 
* Exemple pour suprimmer toute la table $Mysql->Delete($nameTable) "on insère seulement le nom de la table
 *  
 *         $where[] = [
 * 
            "champs1" => "nom",
 * 
            "operations" => "=",
 * 
            "conditions" => "OR",
 * 
            "champs2" => "32"
 * 
            ] ;
 * 
* Exemple pour suprimmer un ou des èlémente en particulier $Mysql->Delete($nameTable,$where,false) on inserer le tableau de condditions $where;
 * 
* @param Request $nameTable Type String 
* @param Request $where Type Array 
* @param Request $isAll Type Booléen 
* @return String
 */
public function Delete($nameTable,$where="",$isAll=true){
    
    
    if(!$isAll && ($nameTable == "" || $where ==""))
        {
            return $this->error;
        }
    
    elseif($isAll && $nameTable == "") 
        {
            return $this->error;       
        }
    
    if(!$isAll)
        {
        
            $tWheredl=array(); 
         
                foreach($where as $key=>$value)
                    {
                        if($key < sizeof($where)-1)
                            $tWheredl[] =$value[$this->C1]." ".$value[$this->operation]." ".$value[$this->C2]." ".$value[$this->condition]." ";
                        else
                            $tWheredl [] =$value[$this->C1]." ".$value[$this->operation]." ".$value[$this->C2]." "; 
                    }
            
            $tWheredls=  implode('', ($tWheredl));
           
        $this->sql="DELETE FROM ".$nameTable." WHERE ".$tWheredls;
        }
    
    else 
        {
            $this->sql="DELETE FROM ".$nameTable;
        }

       // var_dump($this->sql="DELETE FROM ".$nameTable." WHERE ".$tWheredls);die;
    return ($this->conn->query($this->sql));
    
}



/**
* Select
* est une fonction Générique qui permet de Selectionner un/des élèment d'une table choisie  $nameTable
 * 
* Exemple pour Selectionner toute la table on fais : $Mysql->Select($nameTable) "on insère seulement le nom de la table
 *  
 *         $where[] = [
 * 
            "champs1" => "id",
 * 
            "operations" => "=",
 * 
            "conditions" => "OR",
 * 
            "champs2" => "26"
 * 
            ] ;
 * 
* Exemple pour Selectionner un ou des èlémente en particulier $Mysql->Select($nameTable, $isAll, "", $Where); on inserer le tableau de condditions $where;
 * 
 * On peut aussi rajouter un Groupby,having,orderby etc... tous depent de la requête 
 * $Mysql->Select($nameTable,$isAll,$champSelected,$Where,$groupBy,$having,$orderBy,$isDESC);
 * 
* @param Request $nameTable Type String 
* @param Request $where Type Array 
* @param Request $isAll Type Booléen 
* @param Request $champSelected Type Array
* @param Request $Where Type Array
* @param Request $groupBy Type String
* @param Request $having Type Array
* @param Request $orderBy Type String
* @param Request $isDESC Type Booléen
* @return String
 */

public function Select($nameTable,$champSelected="",$Where="",$groupBy="",$having="",$orderBy="",$isDESC=true,$isAll=true){

    try
    {
    if($isAll && $nameTable !="" ){
       $this->sql="SELECT * FROM ".$nameTable;  
    }
    else if ($nameTable !="" && $champSelected !="")
    {
        $champs= implode(",", $champSelected);
        $this->sql="SELECT ".$champs." FROM ".$nameTable;  
    }
    else
    {   
          return $this->error;
    }


if ($Where != "") {
     $condition;
     
    foreach($Where as $key=>$value)
    {

        if($key < sizeof($Where)-1)
             $condition[] =$value[$this->C1]." ".$value[$this->operation]." ".$value[$this->C2]." ".$value[$this->condition]." ";
        else
            $condition [] =$value[$this->C1]." ".$value[$this->operation]." ".$value[$this->C2]." ";    
    }
    

    $conditions=  implode('', ($condition));
     

    $this->sql.=" WHERE ".$conditions;
    
}


    if($groupBy !=""){
        if($having !=""){
            
            $conditionhaving=array();
            
            foreach($having as $key=>$value)
            {

                if($key < sizeof($having)-1)
                     $conditionhaving[] =$value[$this->C1]." ".$value[$this->operation]." ".$value[$this->C2]." ".$value[$this->condition]." ";
                else
                    $conditionhaving [] =$value[$this->C1]." ".$value[$this->operation]." ".$value[$this->C2]." "; 
            }
           
            $conditionhavings=  implode('', ($conditionhaving));
           
            $this->sql.=" GROUP BY ".$groupBy." HAVING ".$conditionhavings ;
            
        }
        else {
             $this->sql.=" GROUP BY ".$groupBy ;
        }
      
    }

    if ($orderBy!="")
    {
        if($isDESC) $Order = $this->Desc ; 
        else $Order = $this->ASC ; 
      $this->sql.= " ORDER BY ".$orderBy." ".$Order; 
    }
    

  
    //var_dump($this->sql);die;
 
 $result = $this->conn->query($this->sql) ; 

 if (!$result)
 {
     return $this->error;
 }
 
return ($this->ReadDataFromSelect($result));    
    }
 catch (\Exception $ex)
 {
     return $ex->getMessage();
 }
}

/**
* Update
* est une fonction Générique qui permet de modifier un/des élèment d'une table choisie  $nameTable
 * 
* Exemple pour Modifier l'èlément de la table ayant pour id = 23  OR id = 26 nous créeons un tableau de conditions $where comme ci-dessous 
 *  
 *         $where[] = [
 * 
            "champs1" => "id",
 * 
            "operations" => "=",
 * 
            "conditions" => "OR",
 * 
            "champs2" => "23"
 * 
            ] ;
 * 
 * 
 * 
 *   $where[] = [
 * 
            "champs1" => "id",
 * 
            "operations" => "=",
 * 
            "conditions" => "OR",
 * 
            "champs2" => "26"
 * 
            ] ;
 * 
 * Mais aussi un tableau de Valeur ( qui serons les nouvelles modifications )
 * 
 * $valeur= [
 * 
 * "nom" => "'itssss'",
 * 
 * "lien"=>"'Worksss'"
 * 
 * ] ;
 * 
 * Mysql->Update($nameTable, $valeur, $whereup);
 * 
 *
* @param Request $nameTable Type String 
* @param Request $Whereup Type Array 
* @param Request $valeur Type Array 
* @return String
 */
public function Update ($nameTable,$valeur,$Whereup ){
    
     if($nameTable =="" || $valeur == "" || $Whereup =="" ) {
        return $this->error;
    }
    

//    tableau de conditions
            $tWhereup=array(); 
            foreach($Whereup as $key=>$value)
            {
                if($key < sizeof($Whereup)-1)
                     $tWhereup[] =$value[$this->C1]." ".$value[$this->operation]." ".$value[$this->C2]." ".$value[$this->condition]." ";
                else
                    $tWhereup [] =$value[$this->C1]." ".$value[$this->operation]." ".$value[$this->C2]." "; 
            }
            $tWhereups=  implode('', ($tWhereup));
            
            
            
      //    tableau de valeurs  
             
//    $tvaleur= implode(", ", $valeur);
            

      
      $tvaleur = array();    
      $x = 0 ; 
      foreach($valeur as $key=>$value)
      {
              //var_dump($key);die;
           if($x < sizeof($valeur)-1)
               $tvaleur[] =$key." = ".$value.", ";
           else
               $tvaleur [] =$key." = ".$value." "; 
           $x++;
      }
     $tvaleurs=  implode('', ($tvaleur));
    
     

    $this->sql="UPDATE ".$nameTable." SET ".$tvaleurs." WHERE ".$tWhereups;  
//        var_dump($this->sql="UPDATE ".$nameTable." SET ".$tvaleurs." WHERE ".$tWhereups);die;
    
    return ($this->conn->query($this->sql));
       
    
}

public function QuerySQL($SelectData) {
    if ($SelectData)
    {
        if ($this->Request=="")
            return $this->error_Request;

        $result = $this->conn->query($this->Request);


        return ($this->ReadDataFromSelect($result));
    }
    else {
            if ($this->Request=="")
            return $this->error_Request;

        $result = $this->conn->query($this->Request);


        return ($result);
        
    }
    
}

protected function ReadDataFromSelect ($result)
{
    if ($result==null || $result->num_rows<1)
        return $this->error;
     $values = array();
    while($row = $result->fetch_assoc()) {
     $values[]  = $row ;
    }
    return $values ;
}

function setRequest($Request) {
    $this->Request = $Request;
}



}
