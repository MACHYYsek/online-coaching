<?php

namespace Core\Enums;

enum MessageType
{
    case MESSAGE;
    case FILE;

    public static function fromValue(mixed $message_type): MessageType
    {
        return match ($message_type) {
            "MESSAGE" => MessageType::MESSAGE,
            "FILE" => MessageType::FILE
        };
    }
}
