<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunniController
{
  public function indexAlunno(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $queryParams = $request->getQueryParams();
    $search = "WHERE nome regexp '$queryParams[search]' OR cognome regexp '$queryParams[search]'" ?? "";
    $sortCol =$queryParams['sortCol'] ?? "id";
    $sort =$queryParams['sort'] ?? "ASC";
    $result = $mysqli_connection->query("SELECT * FROM alunni $search order by $sortCol $sort");
    $results = $result->fetch_all(MYSQLI_ASSOC);
    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function viewAlunno(Request $request, Response $response, $args){
    $id = $args['id'];
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $raw_query = "SELECT * FROM alunni WHERE id=?";
    $stmt = $mysqli_connection->prepare($raw_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);

    if($result && $mysqli_connection->affected_rows > 0){
      $response->getBody()->write(json_encode($results));
    } else {
      $response->getBody()->write(json_encode(array("message" => "Not Found")));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    }
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function createAlunno(Request $request, Response $response, $args):response{
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $body = json_decode($request->getBody()->getContents(),true);
    $nome = $body["nome"];
    $cognome = $body["cognome"];
    $raw_query = "INSERT INTO alunni(nome, cognome) VALUES(?,?)";
    $stmt = $mysqli_connection->prepare($raw_query);
    $stmt->bind_param("ss", $nome, $cognome);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result == null){
      $response->getBody()->write(json_encode(array("message" => "Error")));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    } else {
      $response->getBody()->write(json_encode(array("message" => "created")));
    }
    return $response->withHeader("Content-type", "application/json")->withStatus(201);
  }

  public function updateAlunno(Request $request, Response $response, $args):response{
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $id = $args["id"];
    $body = json_decode($request->getBody()->getContents(),true);
    $nome = $body["nome"];
    $cognome = $body["cognome"];
    $raw_query = "UPDATE alunni SET nome = ?, cognome = ? WHERE id = ?";
    $stmt = $mysqli_connection->prepare($raw_query);
    $stmt->bind_param("ssi", $nome, $cognome, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $check_query = "SELECT nome FROM alunni WHERE id = ?";
    $stmt2 = $mysqli_connection->prepare($check_query);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $results = $result2->fetch_all(MYSQLI_ASSOC);

    if($result2 && $mysqli_connection->affected_rows == 0){
      $response->getBody()->write(json_encode(array("message" => "Not Found")));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    } else {
      $response->getBody()->write(json_encode(array("message" => "updated")));
    }
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function deleteAlunno(Request $request, Response $response, $args):response{
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $id = $args["id"];
    $raw_query = "DELETE FROM alunni WHERE id = ?";
    $stmt = $mysqli_connection->prepare($raw_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $check_query = "SELECT nome FROM alunni WHERE id = ?";
    $stmt2 = $mysqli_connection->prepare($check_query);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $results = $result2->fetch_all(MYSQLI_ASSOC);

    if($result2 && $mysqli_connection->affected_rows == 0){
      $response->getBody()->write(json_encode(array("message" => "Not Found")));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    } else {
      $response->getBody()->write(json_encode(array("message" => "deleted")));
    }
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function indexCertificazione(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $queryParams = $request->getQueryParams();
    $search = "WHERE alunno_id regexp '$queryParams[search]'" ?? "";
    $sortCol =$queryParams['sortCol'] ?? "id";
    $sort =$queryParams['sort'] ?? "ASC";
    $result = $mysqli_connection->query("SELECT * FROM certificazioni $search order by $sortCol $sort");
    $results = $result->fetch_all(MYSQLI_ASSOC);
    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function viewCertificazione(Request $request, Response $response, $args){
    $id = $args['id'];
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $raw_query = "SELECT * FROM certificazioni c INNER JOIN alunni a ON c.alunno_id = a.id WHERE c.alunno_id=?";
    $stmt = $mysqli_connection->prepare($raw_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);

    if($result && $mysqli_connection->affected_rows > 0){
      $response->getBody()->write(json_encode($results));
    } else {
      $response->getBody()->write(json_encode(array("message" => "Not Found")));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    }
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function createCertificazione(Request $request, Response $response, $args):response{
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $body = json_decode($request->getBody()->getContents(),true);
    $alunno_id = $body["alunno_id"];
    $titolo = $body["titolo"];
    $votazione = $body["votazione"];
    $ente = $body["ente"];
    $raw_query = "INSERT INTO certificazioni(alunno_id, titolo, votazione, ente) VALUES(?,?,?,?)";
    $stmt = $mysqli_connection->prepare($raw_query);
    $stmt->bind_param("isis", $alunno_id, $titolo, $votazione, $ente);
    $stmt->execute();
    $result = $stmt->get_result();

    if($votazione > 100 || $votazione < 0){
      $response->getBody()->write(json_encode(array("message" => "Votazione_Out_Of_Bound")));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    } 
    else {
      $response->getBody()->write(json_encode(array("message" => "created")));
    }
    return $response->withHeader("Content-type", "application/json")->withStatus(201);
  }

  public function updateCertificazione(Request $request, Response $response, $args):response{
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $id = $args["id"];
    $body = json_decode($request->getBody()->getContents(),true);
    $alunno_id = $body["alunno_id"];
    $titolo = $body["titolo"];
    $votazione = $body["votazione"];
    $ente = $body["ente"];
    $raw_query = "UPDATE certificazioni SET alunno_id = ?, titolo = ?, votazione = ?, ente = ? WHERE id = ?";
    $stmt = $mysqli_connection->prepare($raw_query);
    $stmt->bind_param("isisi", $alunno_id, $titolo, $votazione, $ente, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $check_query = "SELECT * FROM certificazioni c INNER JOIN alunni a ON c.alunno_id = a.id WHERE c.id = ? AND a.id = ?";
    $stmt2 = $mysqli_connection->prepare($check_query);
    $stmt2->bind_param("ii", $id, $alunno_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $results = $result2->fetch_all(MYSQLI_ASSOC);

    if($result2 && $mysqli_connection->affected_rows == 0){
      $response->getBody()->write(json_encode(array("message" => "Not Found")));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    } else {
      $response->getBody()->write(json_encode(array("message" => "updated")));
    }
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function deleteCertificazione(Request $request, Response $response, $args):response{
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $id = $args["id"];
    $body = json_decode($request->getBody()->getContents(),true);
    $check_query = "SELECT * FROM certificazioni WHERE id = ?";
    $stmt2 = $mysqli_connection->prepare($check_query);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $results = $result2->fetch_all(MYSQLI_ASSOC);

    $raw_query = "DELETE FROM certificazioni WHERE id = ?";
    $stmt = $mysqli_connection->prepare($raw_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();


    if($result2 && $mysqli_connection->affected_rows == 0){
      $response->getBody()->write(json_encode(array("message" => "Not Found")));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    } else {
      $response->getBody()->write(json_encode(array("message" => "deleted")));
    }
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }
}
