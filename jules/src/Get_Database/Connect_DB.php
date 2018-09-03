<?php

/**

  The Initial Developer of the Original Code is
  Matthieu  - http://www.programmation-facile.com/
  Portions created by the Initial Developer are Copyright (C) 2013
  the Initial Developer. All Rights Reserved.

  Contributor(s) :

 */
 
/**
 * @file Mysql.php
 * 
 * @author Matthieu
 * 
 * @version 0.1

 
@usage

$oSqlConnection = new Mysql();
$s_sqlSelect = "SELECT nbvisiteur FROM compteur_visite WHERE ip = '192.168.0.23'";
$o_sqlResult = $oSqlConnection->query($s_sqlSelect);
while ( $o_result = $oSqlConnection->getObject($o_sqlResult) )
{
	// caractéristiques des messages
	$n_nbVisiteur = $o_result->nbvisiteur;
}




Documentation :
http://dev.mysql.com/doc/refman/5.0/fr/sql-syntax.html

Installation d'un environnement php, MySQL sous Windows (pour vos tests)
WampServer :  http://www.wampserver.com/
EasyPHP :  http://www.easyphp.org/


exemple de requetes MySQL
 
SELECT *
FROM wordpress_useronline
WHERE username = 'Matthieu'



SELECT ID, user_login
FROM wordpress_users
WHERE user_login = 'Matthieu' AND ID = 2


SELECT posts.post_author, users.user_login, posts.post_date, posts.post_content, posts.post_title, posts.post_name
FROM `wordpress_posts` posts
INNER JOIN  wordpress_users users ON users.ID = posts.post_author 
AND  users.user_login = 'Matthieu' 

*/
class Mysql 
{
	
	private $oLinkSql;// la connexion mysql en cours
	private $oResult;// le resultat de la requete executee

	private $aConnect;// la configuration de la base de donnees
	 
	// ---------------------------------- __construct() ----------------------------------------------------------
   /**
	* @brief 
	* @param -
	*/
	public function __construct()
	{
		// initialisation des propriétés de la classe
		$this->aConnect 	= array();
		
		/* delfiweb */
		$this->aConnect['ip']	= "localhost"; // le serveur
		$this->aConnect['login']	= "root"; // le login
		$this->aConnect['password']	= ""; // mot de passe
		$this->aConnect['database']	= "jules"; // nom de la base de donnee
		$this->aConnect['port'] = "3306"; // 
		
		/* local
		$ip 			= "localhost";
		$login			= "root"; // le login
		$password		= ""; // mot de passe
		 */
		
		// adresse id  $_SERVER["REMOTE_ADDR"]; 
		
		if ( $this->oLinkSql = @mysqli_connect( $this->aConnect['ip'], $this->aConnect['login'], $this->aConnect['password'], $this->aConnect['database'], $this->aConnect['port'] ) )
		{
			$is_erreur = false ;
			$sql = 'SET CHARACTER SET \'utf8\''; 
			$this->query($sql); 
			$sql = 'SET collation_connection = \'utf8_general_ci\''; 
			$this->query($sql);
		}
		else // erreur de connexion a la base -> redirection sur une page d'erreur
		{
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=erreurs/erreurBDD.htm\">";// possibilité d'utiliser un  header('Location: erreurs/erreurBDD.htm');   
			exit;
		}
		
	 }
	
	
	
	// -----------------------------------------------------------------------------------------------------------
	// ------------------------------------------- PUBLIC FUNCTIONS  ---------------------------------------------
	// -----------------------------------------------------------------------------------------------------------
	


	/**
	 * Permet de recuperer les parametres de connexion de la base.
	 * Utile pour d'autre scripts php.
	 *
	 * @return : un tableau avec les parametres de connexion de la base
	 */
	public function getConfig()
	{
		return $this->aConnect;
	} 
	
	
	/**
	 * Execute une requete mysql
	 *
	 * @param requete
	 * @return : un message d'erreur ou l'objet avec le resultat de la requete
	 */
	public  function query($sSqlQuery)
	{ 
		if ( $this->oResult = @mysqli_query($this->oLinkSql,$sSqlQuery) ) return $this->oResult;
		else return 'Query : '.$sSqlQuery.' - error : '.@mysqli_error($this->oLinkSql);
	}	
	 
	 
	 
	/*
	 * Donne le resultat de la requete sous forme d'objet
	 *
	 * @param $oResultQuery : le resultat renvoye par une requete pour obtenir un objet
	 */
	public function getObject($oResultQuery = NULL)
	{
		// dans le resultat d'une autre requete
		if($oResultQuery!=NULL)
		{
			return @mysqli_fetch_object($oResultQuery);
			//if ( @mysqli_error($this->oLinkSql) ) return @mysqli_error($this->oLinkSql);
		}
		
		// donne le resultat de la derniere requete executee
		if ($this->oResult != NULL)
		{
			return mysqli_fetch_object($this->oResult);
			//if ( @mysqli_error($this->oLinkSql) ) return @mysqli_error($this->oLinkSql);
		}
		
	}

	
	
	// ---------------------------------- getNumRows() ----------------------------------------------------------
	/**
	* @brief donne le nombre d'elements modifies par la requete
	* Pratique dans le cas des INSERT
	* 
	* @return (integer) le nombre d'elements modifies par la requete
	*/
	public function getNumRows()
	{
		if( $this->oLinkSql != NULL && $this->oLinkSql != false)
		{
			return @mysqli_affected_rows($this->oLinkSql);
			//if (@mysqli_error($this->oLinkSql)) return @mysqli_error($this->oLinkSql);
		}
	}
	
	
	// ---------------------------------- lastInsertId() ----------------------------------------------------------
	/**
	* @brief Retourne l'identifiant automatiquement généré par la dernière requête.
	* Pratique dans le cas d'un INSERT ou UPDATE pour recuperer le dernier identifiant
	* 
	* @return (integer) le dernier identifiant insere 
	*/
	public function lastInsertId()
	{
		return @mysqli_insert_id($this->oLinkSql);
	}
	
	
	
	// -----------------------------------------------------------------------------------------------------------
	// ----------------------------------------- Others ----------------------------------------------------------
	// -----------------------------------------------------------------------------------------------------------


 	/**
	* @brief
	*
	* @return 
	*/
	public function __destruct() 
	{
 		$this->oLinkSql = NULL ;		
		$b_rep = @mysqli_close($this->oLinkSql);
		if( $b_rep != true )
			return 'Erreur closing sql connexion!';
 	}
	
	
	
	// ---------------------------------------- __toString() ----------------------------------------------------	
	/**
	 * @brief Returns the string representation of this instance.
	 * 
	 * @usage   echo(myObject) ou print(myObject)
	 * @return  the string representation of this instance.
	 */	
	public function __toString() 
	{
		$s_classContent = "[Object ".__CLASS__."]<br />";

		foreach($this as $prop => $value) 
		{
		 	$s_classContent .= "$prop => $value <br />";
		}
		 
		return $s_classContent;
   	}
	 
}
?>