<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Ramsey\Uuid\Uuid;

// $app->group('api/', function () use ($app) {

//     $app->post("/login", function (Request $request, Response $response, $args){
//         $uuid4 = Uuid::uuid4();
//         date_default_timezone_set('Asia/Jakarta');

//         $new_activity = $request->getParsedBody();

//         $sql = "UPDATE INTO users SET (:id, :name, :location, :account_id, :created_at)";
//         $stmt = $this->db->prepare($sql);

//         $data = [
//             ":id" =>  $uuid4->toString(),
//             ":name" => $new_activity["name"],
//             ":location" => $new_activity["location"],
//             ":account_id" => $new_activity["account_id"],
//             ":created_at" => date("Y-m-d H:i:s")
//         ];

//         if($stmt->execute($data))
//         return $response->withJson(["status" => "success", "data" => "1"], 200);
        
//         return $response->withJson(["status" => "failed", "data" => "0"], 200);
//     });
// });

// API group activities
$app->group('/api/activities', function () use ($app) {

    $app->get("/", function (Request $request, Response $response){
        $sql = "SELECT * FROM activities ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM activities WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->post("/", function (Request $request, Response $response){
        $uuid4 = Uuid::uuid4();
        date_default_timezone_set('Asia/Jakarta');

        $new_activity = $request->getParsedBody();

        $sql = "INSERT INTO activities VALUE (:id, :name, :location, :account_id, :created_at)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" =>  $uuid4->toString(),
            ":name" => $new_activity["name"],
            ":location" => $new_activity["location"],
            ":account_id" => $new_activity["account_id"],
            ":created_at" => date("Y-m-d H:i:s")
        ];

        if($stmt->execute($data))
        return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->delete("/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "DELETE FROM activities WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":id" => $id
        ];

        if($stmt->execute($data))
        return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
})->add($middleware);