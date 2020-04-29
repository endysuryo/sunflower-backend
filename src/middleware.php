<?php
$middleware = function ($request, $response, $next) {
    
    $token = $request->getHeaderLine("token");

    if(!isset($token)){
        return $response->withJson(["status" => "API token required"], 401);
    }
    
    $sql = "SELECT * FROM users WHERE token=:token";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":token" => $token]);
    
    if($stmt->rowCount() > 0){
        $result = $stmt->fetch();
        if($token == $result["token"]){
        
            // update hit api
            $sql = "UPDATE users SET hit_api=hit_api+1 WHERE token=:token";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":token" => $token]);
            
            return $response = $next($request, $response);
        }
    }

    return $response->withJson(["status" => "Unauthorized"], 401);

};