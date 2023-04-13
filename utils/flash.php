<?php

use Core\Enums\FlashType;

function flash(FlashType $type, string $message): void
{
    if (isset($_SESSION["FLASH"])) {
        unset($_SESSION["FLASH"]);
    }

    $_SESSION["FLASH"] = [
        "type" => $type,
        "message" => $message
    ];
}

function flashWithRedirect(FlashType $type, string $message, string $redirect): void
{
    flash($type, $message);
    header("Location: $redirect");
}

function getFlash(): ?array
{
    if (isset($_SESSION["FLASH"])) {
        $flash = $_SESSION["FLASH"];
        unset($_SESSION["FLASH"]);

        return $flash;
    }

    return null;
}

function renderFlashToToastify(): string {
    $flash = getFlash();
    if ($flash) {
        $type = $flash["type"];
        $message = $flash["message"];

        $type = match ($type) {
            FlashType::SUCCESS => "success",
            FlashType::ERROR => "error",
            default => "info"
        };

        $background = match ($type) {
            "success" => "linear-gradient(to right, #34eb8c, #006b34)",
            "error" => "linear-gradient(to right, #e82034, #7a000c)",
            default => "linear-gradient(to right, #00b4db, #0083b0)"
        };

        return <<<HTML
        <script>
            Toastify({
                text: "$message",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "$background",
                stopOnFocus: true,
                onClick: function(){} 
            }).showToast();
        </script>
        HTML;
    }

    return "<!-- No flash -->";
}