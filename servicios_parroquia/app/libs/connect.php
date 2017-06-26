<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

function getConnection()
{
  try{

    $db_username = "root";
    $db_password = "RBLlie39135";
    $db_socket = "";
    $db_name="bd_xcomic";
    $host = null;
    $port = 3306;

    $connection = new PDO("mysql:host=node136151-env-4981020.jelasticlw.com.br;dbname=bd_miparroquia", $db_username, $db_password);
  //  $connection = new PDO("mysql:unix_socket=" . $db_socket . ";port=". $port .";dbname=". $db_name, $db_username, $db_password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  }catch(PDOException $e){

    echo "Error : -->>" . $e->getMessage();

  }

  return $connection;

}



 ?>
