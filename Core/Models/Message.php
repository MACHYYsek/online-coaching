<?php

namespace Core\Models;

use Core\Enums\AccountType;
use Core\Enums\MessageType;

class Message
{

    public int $id;
    public User $from;
    public User $to;
    public int $chat;
    public MessageType $type;
    public string $value;
    public string $createdAt;

    public function __construct($id) {
        $this->id = $id;
    }

    function load(): bool {
        $medoo = getMedoo();
        $message = $medoo->select("messages", "*", [
            "id" => $this->id
        ]);
        if (count($message) === 0) {
            return false;
        }

        $message = $message[0];
        $this->from = new User($message["from"]);
        $this->to = new User($message["to"]);
        $this->type = MessageType::fromValue($message["type"]);
        $this->chat = $message["chat"];
        $this->value = $message["value"];
        $this->createdAt = $message["created_at"];

        return true;
    }

    function renderLastMessage(): string {
        $message = $this->value;
        if ($this->type === MessageType::FILE) {
            $message = "File";
        }

        return $message;
    }

    function isFromUser(User $user): bool {
        return $this->from->id === $user->id;
    }

    public static function getLastMessage(User $user, User $otherUser): ?Message {
        $before = date("Y-m-d H:i:s", time() + 1);

        $medoo = getMedoo();
        $messages = $medoo->select("messages", "*", [
            "AND" => [
                "OR" => [
                    "from" => $user->id,
                    "to" => $user->id
                ],
                "OR" => [
                    "from" => $otherUser->id,
                    "to" => $otherUser->id
                ]
            ],
            "ORDER" => [
                "created_at" => "DESC"
            ],
            "LIMIT" => 1,
            "created_at[<]" => $before
        ]);

        if (count($messages) === 0) {
            return null;
        }

        $message = new Message($messages[0]["message_id"]);
        $message->from = new User($messages[0]["from"]);
        $message->to = new User($messages[0]["to"]);
        $message->type = MessageType::fromValue($messages[0]["type"]);
        $message->value = $messages[0]["value"];
        $message->createdAt = $messages[0]["created_at"];

        return $message;
    }

    public static function getMessages(User $user, User $otherUser): array {
        return self::getMessagesById($user->id, $otherUser->id);
    }

    public static function getMessagesById(int $userId, int $anotherUser): array {
        $medoo = getMedoo();
        return $medoo->select("messages", [
            "[>]users(from)" => ["messages.from" => "id"],
        ],
            "*", [
                "AND" => [
                    "OR" => [
                        "from" => $userId,
                        "to" => $userId
                    ],
                    "OR" => [
                        "from" => $anotherUser,
                        "to" => $anotherUser
                    ]
                ],
                "ORDER" => [
                    "created_at" => "ASC"
                ]
            ]);
    }

    public static function getChats(User $auth): array
    {
        $isTrainer = $auth->accountType === AccountType::TRAINER;
        if(!$isTrainer) {
            $trainer = $auth->loadTrainer();
            if(!$trainer) {
                return [];
            }

            $lastMessage = self::getLastMessage($auth, $trainer);
            return [
                [
                    "full_name" => $trainer->getFullName(),
                    "avatar" => $trainer->getAvatarURL(),
                    "last_message" => $lastMessage->value,
                    "last_message_type" => $lastMessage->type->name,
                    "id" => $trainer->id
                ]
            ];
        }

        $medoo = getMedoo();
        $users = $medoo->select("clients", [
            "[>]users" => ["clients.client" => "id"]
        ], [
            "users.id",
            "users.first_name",
            "users.last_name",
            "users.avatar",
        ], [
            "clients.trainer" => $auth->id,
        ]);

        $chats = [];
        foreach ($users as $user) {
            $lastMessage = self::getLastMessage($auth, new User($user["id"]));
            $chats[] = [
                "full_name" => $user["first_name"] . " " . $user["last_name"],
                "avatar" => "/storage/avatars/". ($user["avatar"] ?: "/default.webp"),
                "last_message" => $lastMessage ? $lastMessage->value : "Zatím žádné zprávy",
                "last_message_type" => $lastMessage ? $lastMessage->type->name : "MESSAGE",
                "id" => $user["id"]
            ];
        }

        return $chats;
    }

}
