<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
include("../connect.php");

class Requests
{
    public function GET()
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            try {
                $cmd = $GLOBALS["db"]->prepare("SELECT * FROM `users`");
                $cmd->execute();
                if ($cmd->rowCount() > 0) {
                    $data = $cmd->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode($data);
                } else {
                    echo json_encode(["message" => "No data found."]);
                }
            } catch (Exception $err) {
                echo json_encode(["error" => "Database query error: " . $err->getMessage()]);
            }
        }
    }

    public function POST()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $json_data = file_get_contents("php://input");
                $d = json_decode($json_data, true);
                if (isset($d["username"]) && isset($d["email"]) && isset($d["password"])) {
                    $cmd = $GLOBALS["db"]->prepare("INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES (NULL, :username, :email, :password)");
                    $cmd->bindParam(':username', $d["username"]);
                    $cmd->bindParam(':email', $d["email"]);
                    $cmd->bindParam(':password', $d["password"]);
                    $cmd->execute();
                    http_response_code(201);
                    echo json_encode(["message" => "تم إدراج المستخدم بنجاح"]);
                } else {
                    echo json_encode(["message" => "No Data Found", "data" => $d]);
                }
            } catch (Exception $err) {
                echo json_encode($err);
            }
        }
    }

    public function PATCH()
    {
        if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
            try {
                $json_data = file_get_contents("php://input");
                $d = json_decode($json_data, true);
                if (isset($d["id"]) && isset($d["username"]) && isset($d["email"]) && isset($d["password"])) {
                    $id = $d["id"];
                    foreach ($d as $k => $e) {
                        $column = $k;
                        $value = strtolower($e);
                        $cmd = $GLOBALS["db"]->prepare("UPDATE `users` SET $column = :val WHERE `users`.`id` = :id");
                        $cmd->bindParam(":id", $id);
                        $cmd->bindParam(":val", $value);
                        $cmd->execute();
                    }
                    // RESULT
                    $cmd = $GLOBALS["db"]->prepare("SELECT `id`, `username`, `email`, `password` FROM `users` WHERE id = :id;");
                    $cmd->bindParam(":id", $id);
                    $cmd->execute();
                    echo json_encode($cmd->fetch(PDO::FETCH_ASSOC));
                }
            } catch (Exception $err) {
                echo json_encode(["msg" => $err]);
            }
        }
    }
    public function DELETE()
    {
        if ($_SERVER["REQUEST_METHOD"] === "DELETE" && isset($_REQUEST["id"])) {
            try {
                $cmd = $GLOBALS["db"]->prepare("DELETE FROM `users` WHERE id = :id");
                $cmd->bindParam(":id", $_REQUEST["id"]);
                $cmd->execute();
                http_response_code(200);
                echo json_encode(["msg" => "DELETED SUCCESSFULLY", "id" => $_REQUEST["id"]]);
            } catch (EXCEPTION $err) {
                http_response_code(404);
                echo json_encode(["msg" => $err]);
            }
        }
    }
}

$req = new Requests();

echo $req->GET();

echo $req->POST();

echo $req->PATCH();

echo $req->DELETE();
