<?php

namespace Core\Enums;

enum AccountType
{
    case USER;
    case TRAINER;
    case ADMIN;

    public static function fromValue(mixed $account_type): AccountType
    {
        return match ($account_type) {
            "USER" => AccountType::USER,
            "TRAINER" => AccountType::TRAINER,
            "ADMIN" => AccountType::ADMIN
        };
    }

    public static function all(): array {
        return [
            "USER",
            "TRAINER",
            "ADMIN"
        ];
    }

    function displayName(): string {
        return match ($this) {
            AccountType::USER => "Uživatel",
            AccountType::TRAINER => "Tréner",
            AccountType::ADMIN => "Admin"
        };
    }

}
