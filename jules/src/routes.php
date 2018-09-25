<?php

use Slim\Http\Request;
use Slim\Http\Response;

require '../vendor/autoload.php';

include ("Modal/MySQL.php");
include_once "Get_Database/Authentification.php";
include_once 'Get_Database/ImagesRobot.php';
    
$app->POST('/', function (Request $data, Response $response, array $args) use ($app) {
    // Render index view
    $response = array("reponse" => 0);
    $response = json_encode($response);
    return $response;
});   

$app->POST('/Register', function (Request $request, Response $response, array $args) use ($app) {
    // Render index view
    $data = $request->getBody();
    $data = json_decode($data, true);
    $Authentification = new Authentification();
    $response = $Authentification->Get_Request($data);
    return json_encode($response);
});

$app->POST('/Login', function (Request $request, Response $response, array $args) use ($app) {
    
    $data = $request->getBody();
    $data = json_decode($data, true);
    $Authentification = new Authentification();
    $response = $Authentification->Get_Login($data);
    return json_encode($response);
});

$app->POST('/ForgotPassword', function (Request $request, Response $response, array $args) use ($app) {
    
    $data = $request->getBody();
    $data = json_decode($data, true);
    $Authentification = new Authentification();
    $response = $Authentification->Get_Password($data);
    return json_encode($response);
});

$app->POST('/Infos', function (Request $request, Response $response, array $args) use ($app) {
    
    $data = $request->getBody();
    $data = json_decode($data, true);
    $Authentification = new Authentification();
    $response = $Authentification->Get_Infos($data);
    return json_encode($response);
});

$app->POST('/Modify', function (Request $request, Response $response, array $args) use ($app) {
    $data = $request->getBody();
    $data = json_decode($data, true);   
    $Authentification = new Authentification();
    $response = $Authentification->Modify($data);
    return json_encode($response);
});
 


$app->GET('/image', function (Request $request, Response $response, array $args) use ($app) {

$code = $request->getParam("code");
$imagesRobot = new ImagesRobot();
$response = $imagesRobot->GetImageWithCode($code) ;
if ($response==null)
{
    return json_encode(["error"=>1]);
}

});
  

