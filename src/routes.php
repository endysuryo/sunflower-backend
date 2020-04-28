<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Ramsey\Uuid\Uuid;

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get("/activities/", function (Request $request, Response $response){
        $sql = "SELECT * FROM activities";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/activities/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM activities WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->post("/activities/", function (Request $request, Response $response){
        $uuid4 = Uuid::uuid4();

        $new_activity = $request->getParsedBody();

        $sql = "INSERT INTO activities (id, name, start_at, end_at) VALUE (:id, :name, :start_at, :end_at)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" =>  $uuid4->toString(),
            ":name" => $new_activity["name"],
            ":start_at" => $new_activity["start_at"],
            ":end_at" => $new_activity["end_at"]
        ];

        if($stmt->execute($data))
        return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });