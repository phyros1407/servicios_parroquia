<?php

  if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

  $app->get("/actividades", function() use ($app){

    try {

      $id_evento = $_GET['id_evento'];

      $query =   " SELECT distinct(a.id_evento)id_evento,a.titulo,a.descripcion, "
                ." date_format(a.inicio,'%d %M') as fecha_inicio_actividad,date_format(a.inicio,'%H:%i') as hora_inicio_actividad, "
                ." date_format(a.fin,'%d %M') as fecha_fin_actividad,date_format(a.fin,'%H:%i') as hora_fin_actividad"
                ." FROM t_parroquia p, t_evento e, t_actividad a"
                ." WHERE e.id = a.id_evento and e.id = " . $id_evento;

      $connection = getConnection();

      $recordSet = $connection->query($query)->fetchALL( PDO::FETCH_ASSOC);

      $connection = null;

      $listadoEventos = array();

      foreach ($recordSet as $row ) {
        $listadoEventos[] = $row;
      }

      $app->response->headers->set("Content-type","application/json");
      $app->response->status(200);
      $app->response->body(json_encode($listadoEventos));

    } catch (PDOException $e) {

      echo "Error : -->>" . $e ->getMessage();

    }


  });

  $app->get("/eventos/", function() use ($app){

    try {

      $query =    " SELECT distinct(e.id) id,e.titulo,e.descripcion,e.fondo,e.id_parroquia,e.estado, p.nombre as parroquia,"
                 ." date_format(min(a.inicio),'%d %M') as fecha_inicio,date_format(max(a.fin),'%d %M') as fecha_fin "
                 ." FROM t_evento e, t_parroquia p, t_actividad a"
                 ." WHERE e.estado = 'A' and e.id_parroquia = p.id and a.id_evento = e.id"
                 ." GROUP BY e.id;";

      $connection = getConnection();

      $recordSet = $connection->query($query)->fetchALL( PDO::FETCH_ASSOC);

      $connection = null;

      $listadoEventos = array();

      foreach ($recordSet as $row) {
        $listadoEventos[] = $row;
      }

      $app->response->headers->set("Content-type","application/json");
      $app->response->status(200);
      $app->response->body(json_encode($listadoEventos));

    } catch (PDOException $e) {

      echo "Error : -->>" . $e ->getMessage();

    }


  });


  $app->get("/parroquia", function() use ($app){

    try {

      $query = " SELECT distinct(id) id, nombre, direccion, latitud, longitud "
             . " FROM t_parroquia ; ";

      $connection = getConnection();

      $recordSet = $connection->query($query)->fetchALL(PDO::FETCH_ASSOC);

      $connection = null;

      $listadoEventos = array();

      foreach ($recordSet as $row ) {
        $listadoEventos[] = $row;
      }

      $app->response->headers->set("Content-type","application/json");
      $app->response->status(200);
      $app->response->body(json_encode($listadoEventos));

    } catch (PDOException $e) {

      echo "Error : -->>" . $e ->getMessage();

    }


  });

  $app->post("/user_access", function() use ($app){

    try {

      $data = file_get_contents('php://input');
      $json = json_decode($data);
/*
      $username = $app->request->post("username");
      $password = $app->request->post("password");
*/

      $username = $json->{'username'};
      $password = $json->{'password'};

      $query = " SELECT * ".
					     " FROM t_persona p".
					     " INNER JOIN t_usuario u ON p.ID = u.USU_ID ".
					     " WHERE u.USER = ? AND u.PASS = ? ";

      $connection = getConnection();
      $dbh = $connection->prepare($query);
      $dbh->bindParam(1,$username);
      $dbh->bindParam(2,$password);
      $dbh->execute();
      $users = $dbh->fetchObject();
      $connection = null;

      //ENVIANDO EN FORMATO JSON
      $app->response->headers->set("Content-type","application/json");
      $app->response->status(200);
      $app->response->body(json_encode($users));

    } catch (PDOException $e) {

      echo "Error : -->>" . $e ->getMessage();

    }


  });



 ?>
