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
        try {
            $jd = file_get_contents("php://input");
            $d = json_encode($jd, true);
            echo $d;
        } catch (Exception $err) {
        }
    }
}

$req = new Requests();

echo $req->GET();

echo $req->POST();
