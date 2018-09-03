<?php

class Authentification {

    private $success = array("response" => 0);
    private $error = array("response" => 1);
    private $passerror = array("response" => 2);

    public function Get_Request($request) {
        $json = $request->getBody();
        $data = json_decode($json, true);
        $Nom = $data["Nom"];
        $Prenom = $data["Prenom"];
        $Email = $data["Email"];
        $Password = $data["Password"];
        $response = $this->Register($Nom, $Prenom, $Email, $Password);
        return $response;
    }
    public function Get_Login($request) {
        
        $json = $request->getBody();
        $data = json_decode($json, true);
        $Email = $data["Email"];
        $Password = $data["Password"];
        $response = $this->Login($Email, $Password);
        return $response;
    }

    private function Register($Nom, $Prenom, $Email, $Password) {
        $DB = new MySQL();
        if ($this->Email_exist($Email) == 0) {
            if($this->Check_Password($Password) == 0) {
                $Password = password_hash($Password, PASSWORD_DEFAULT);
                $valeurs = [
                    "Nom" => "'$Nom'",
                    "Prenom" => "'$Prenom'",
                    "Email" => "'$Email'",
                    "Password" => "'$Password'"
                    ];
                $DB->insert("user", $valeurs);
                return $this->success;
            }
            else {
                echo "Error Password";
                return $this->passerror;
            }
                
        }
        else {
            echo "Email already existing";
            return $this->error;
        }
            
    }
   
    private function Login($Email, $Password) {
        $DB = new MySQL();
        $where[] = [
            "champs1" => "Email",
            "operations" => "=",
            "champs2" => "'$Email'",
            ] ;
       if($DB->Select("user", "", $where)) {
           $resultat = $DB->Select("user", "", $where);
           $Passwordhash = $resultat[0]["Password"];
           if(password_verify($Password, $Passwordhash)) {
               echo "Connected";
               return (0);
           }
           else {
               echo "Email or Password are incorrect";
               return (1);
           }
        }
    }

    private function Email_exist($Email) {
        $DB = new MySQL();
        $where[] = [
            "champs1" => "Email",
            "operations" => "=",
            "champs2" => "'$Email'"
            ] ;
        try {
            $resultat = $DB->Select("user", "", $where );
        }
        catch (Exception $e) {} 
        if (sizeof($resultat[0]) > 0) {
            return (1);
        }
        else {
        }
            return (0);
   
    }

    private function Check_Password($Password) {
        if (strlen($Password) < 8 || (!ctype_alnum($Password)) || (!preg_match('/[A-Z]/m', $Password)) || (!preg_match('/[a-z]/m', $Password)) || (!preg_match('/[0-9]/m', $Password))) {
            return (1);
        }
        else {
            return (0);
        }
            
    }
    
    public function Generate_Password() {
        $all = range("a","z");
        $ALPHABET = range("A", "Z");
        $chiffre = range(0,9);
        $length = rand(15,20);
        $Password = "";
        $i = 0;
        foreach($ALPHABET as $Al) {
            array_push($all, $Al);
        }
        foreach($chiffre as $ch) {
            array_push($all, $ch);
        }
        while ($i < $length) {
            $rand_keys = array_rand($all);
            $Password = $Password . $all[$rand_keys];
            $i++;
        }
        echo $Password;

    }
}


$test = new Authentification();
$test->Generate_Password();
