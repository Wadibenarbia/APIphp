<?php

class Authentification {

    private $success = array("response" => 0);
    private $error = array("response" => 1);
    private $passerror = array("response" => 2);
    private $emailexist = array("response" => -1);

    public function Get_Request($data) {
        $Nom = $data["Nom"];
        $Prenom = $data["Prenom"];
        $Email = $data["Email"];
        $Password = $data["Password"];
        $response = $this->Register($Nom, $Prenom, $Email, $Password);
        return $response;
    }
    
    public function Get_Login($data) {
        $Email = $data["Email"];
        $Password = $data["Password"];
        $response = $this->Login($Email, $Password);
        return $response;
    }
    
    public function Modify($data) {
        $oldEmail = $data["oldEmail"];
        $Email = $data["newEmail"];
        $Nom = $data["Nom"];
        $Prenom = $data["Prenom"];
        $Password = $data["Password"];
        $test = "";
        if ($Password != '') {
            if($this->Check_Password($Password) == 0) {
                $test = " " . $Password;
                if($this->Update("Password", $Password, "Email", $oldEmail) == 1) {
                    return $this->error;
                }
            }
            else {return $this->passerror;}
        }        
        if($Nom != '') {
            if($this->Update("Nom", $Nom, "Email", $oldEmail) == 1) {
                return $this->error;
            }
            else {
              $test = $test . " " . $Nom;  
            }
        }
        
        if($Prenom != '') {
            $test = $test . " " . $Prenom;
            if($this->Update("Prenom", $Prenom, "Email", $oldEmail) == 1) {
                return $this->error;
            }
        }
       if ($Email != '') {
            if(($this->Email_exist($Email)) == 0) {
                $test = $test . " " . $Email;
                if($this->Update("Email", $Email, "Email", $oldEmail) == 1) {
                    return $this->error;
                }
            }
            else {return $this->emailexist;}
        }
        return ($this->success);
        
        
        
    }
    
    private function Update($champsvaleur, $valeur, $champs1, $champs2)  {
                $DB = new MySQL();
                $valeur = array($champsvaleur => "'$valeur'");
                $Whereup[] = array(
                    "champs1" => $champs1,
                    "operations" => "=",
                    "champs2" => "'$champs2'"
                );
                try {
                    if ($DB->Update("user", $valeur, $Whereup)) {
                        return 0;
                    }
                    else {return 1;}
                }
                catch (Exception $e) {}
    }
    
    public function Get_Infos($data) {
        
        $DB = new MySQL();
        $Email = $data["Email"];
        if ($this->Email_exist($Email) == 1) {
            $where[] = [
                "champs1" => "Email",
                "operations" => "=",
                "champs2" => "'$Email'",
                ] ;
            $reponse = $DB->Select("user", "", $where);
            return $reponse[0];
        }
        else 
            return $this->error;
    }
    
    public function Get_Password($data) {
        $Email = $data["Email"];
        $response = $this->Forgot_Password($Email);
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
                return $this->passerror;
            }
                
        }
        else {
            return $this->error;
        }
            
    }
   
    private function Login($Email, $Password) {
        $DB = new MySQL();
        $where[] = [
            "champs1" => "Email",
            "operations" => "=",
            "champs2" => "'$Email'",
            ];
       if($DB->Select("user", "", $where)) {
           $resultat = $DB->Select("user", "", $where);
           $Passwordhash = $resultat[0]["Password"];
           if(password_verify($Password, $Passwordhash)) {
               return $this->success;
           }
           else {
               return $this->error;
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
    
    private function ID_exist($IDuser) {
        $DB = new MySQL();
        $where[] = [
            "champs1" => "id",
            "operations" => "=",
            "champs2" => "'$IDuser'"
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
    
    private function Forgot_Password($Email) {
        if($this->Email_exist($Email) == 1) {
            if($this->Generate_Password()) {
                $Password = $this->Generate_Password();
                $DB = new MySQL;
                $Passwordhash = password_hash($Password, PASSWORD_DEFAULT);
                $valeur = array("Password" => "'$Passwordhash'");
                $Whereup[] = array(
                    "champs1" => "Email",
                    "operations" => "=",
                    "champs2" => "'$Email'"
                );
                if ($DB->Update("user", $valeur, $Whereup)) {
                    return ($Password);
                }
                else {
                    return $this->error;
                }
                    
            }
            return $this->error;
        }
        return $this->error;
    }

        private function Generate_Password() {
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
        return $Password;
    }
    
    public function Get_Interventions($data) {
        $IDuser = $data["iDuser"];
        $DB = new MySQL();
        if($this->ID_exist($IDuser) == 1) {
            $where[] = [
            "champs1" => "idUser",
            "operations" => "=",
            "champs2" => "'$IDuser'"
            ] ;
        try {
           // $resultat = $DB->Select("Interventions", "*", $where );
        }
        catch (Exception $e) {}
        return $this->success;
        }
 }
}
