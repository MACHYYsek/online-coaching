<?php

use Core\Enums\MessageType;
use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/middlewares/auth.php';

if(!isset($_GET['target']) && !isset($_POST['target'])) {
    echo json_encode([
        "success" => false,
        "message" => "Bad request"
    ]);
    return;
}

if(!isset($_GET["after"]) && !isset($_POST["message"]) && !isset($_FILES['file'])) {
    echo json_encode([
        "success" => false,
        "message" => "Bad request"
    ]);
    return;
}

$target = User::find($_GET['target'] ?? $_POST['target']);
$user = auth();
if(!$user->canSendMessageTo($target)) {
    echo json_encode([
        "success" => false,
        "message" => "You can't send messages to this user"
    ]);
    return;
}

if(isset($_GET["after"])) {
    $after = $_GET["after"];
    $medoo = getMedoo();
    $messages = $medoo->select("messages", [
        "[>]users(from)" => ["messages.from" => "id"],
    ],
        "*", [
            "AND" => [
                "OR" => [
                    "from" => $user->id,
                    "to" => $user->id
                ],
                "OR" => [
                    "from" => $target->id,
                    "to" => $target->id
                ]
            ],
            "message_id[>]" => $after,
            "ORDER" => [
                "created_at" => "ASC"
            ]
        ]);

    echo json_encode([
        "success" => true,
        "messages" => $messages,
        "after" => $after,
        "user_id" => $user->id,
        "target_id" => $target->id
    ]);
    return;
}

if(isset($_POST["message"])) {
    $message = $_POST["message"];
    if(strlen($message) > 512) {
        echo json_encode([
            "success" => false,
            "message" => "Zpráva je příliš dlouhá"
        ]);
        return;
    }

    if(empty($message)) {
        echo json_encode([
            "success" => false,
            "message" => "Zpráva je prázdná"
        ]);
        return;
    }

    $medoo = getMedoo();
    $medoo->insert("messages", [
        "from" => $user->id,
        "to" => $target->id,
        "type" => MessageType::MESSAGE->name,
        "value" => $message,
        "created_at" => date("Y-m-d H:i:s"),
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Zpráva byla odeslána",
        "data" => [
            "from" => $user->id,
            "to" => $target->id,
            "type" => MessageType::MESSAGE->name,
            "value" => $message,
            "created_at" => date("Y-m-d H:i:s"),
            "avatar" => $user->avatar
        ]
    ]);
    return;
}

if(isset($_FILES["file"])) {
    $directory = $_SERVER['DOCUMENT_ROOT'] . "/storage/attachments/". $user->id ."/". $target->id ."/";

    try {
        if(!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Nepodařilo se vytvořit složku pro přílohy"
        ]);
        return;
    }

    $file = $_FILES["file"];
    $filename = $file["name"];
    $tmp_name = $file["tmp_name"];
    $size = $file["size"];

    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $extension;
    $path = $directory . $filename;

    if($size > 1024 * 1024 * 50) {
        echo json_encode([
            "success" => false,
            "message" => "Soubor je příliš velký"
        ]);
        return;
    }

    if(!move_uploaded_file($tmp_name, $path)) {
        echo json_encode([
            "success" => false,
            "message" => "Nepodařilo se nahrát soubor"
        ]);
        return;
    }

    $path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $path);
    $medoo = getMedoo();
    $medoo->insert("messages", [
        "from" => $user->id,
        "to" => $target->id,
        "type" => MessageType::FILE->name,
        "value" => $path,
        "created_at" => date("Y-m-d H:i:s"),
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Soubor byl odeslán",
        "data" => [
            "from" => $user->id,
            "to" => $target->id,
            "type" => MessageType::FILE->name,
            "value" => $path,
            "created_at" => date("Y-m-d H:i:s"),
            "avatar" => $user->avatar
        ]
    ]);
}