<?php

use Slim\Http\Request;
use Slim\Http\Response;

require '../vendor/autoload.php';

include ("Modal/MySQL.php");
include_once "Get_Database/Authentification.php";
    
    
$app->POST('/Register', function (Request $request, Response $response, array $args) use ($app) {
    // Render index view
    $Authentification = new Authentification();
    $Authentification->Get_Request($request);
});

$app->POST('/Login', function (Request $request, Response $response, array $args) use ($app) {
    // Render index view
    $Authentification = new Authentification();
    $Authentification->Get_Login($request);
});
 
    

