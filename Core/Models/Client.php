<?php

namespace Core\Models;

use Core\Enums\AccountType;
use Core\Enums\FlashType;

class User
{

    public int $id;
    public string $firstName;
    public string $lastName;
    public string $username;
    public string $email;
    public string $password;
    public AccountType $accountType;
    public string $avatar = "default.webp";
    public string $bio = "Tenhle uživatel ještě nemá žádný popis.";

    public ?float $weight;
    public ?float $height;
    public ?int $pressure;
    public ?int $pulse;
    public ?float $bmi;
    public ?float $fat;

    private array $changedProperties = [];

    private ?User $trainer;
    private ?User $requestedTrainer;


    public function __construct($id) {
        $this->accountType = AccountType::USER;
        $this->id = $id;
    }

    function load(): bool {
        $medoo = getMedoo();
        $user = $medoo->select("users", "*", [
            "id" => $this->id
        ]);
        if (count($user) === 0) {
            return false;
        }

        $user = $user[0];
        $this->assignBasicValues($user, $this);
        $this->assignOtherValues($user, $this);

        return true;
    }


    function save(): void {
        if(count($this->changedProperties) === 0) {
            return;
        }

        $medoo = getMedoo();
        $medoo->update("users", $this->changedProperties, ["id" => $this->id]);
        $this->changedProperties = [];
    }

    function update(string $name, mixed $value): void {
        if($this->$name === $value) {
            return;
        }

        $this->$name = $value;
        if($value instanceof AccountType) {
            $value = $value->name;
        }

        $name = str_replace("accountType", "account_type", $name);
        $this->changedProperties[$name] = $value;
    }

    function loadTrainer(): ?User {
        if (isset($this->trainer)) {
            return $this->trainer;
        }

        $medoo = getMedoo();
        $user = $medoo->select("clients", [
            "[>]users" => ["trainer" => "id"]
        ], columns: "*", where: ["client" => $this->id, "approved" => true]);

        if (count($user) === 0) {
            $this->trainer = null;
            return null;
        }

        $user = $user[0];
        $trainer = new User($user["id"]);

        $this->assignBasicValues($user, $trainer);
        $this->assignOtherValues($user, $trainer);
        $this->trainer = $trainer;
        return $trainer;
    }

    public function getRequestedTrainer(): ?User {
        if (isset($this->requestedTrainer)) {
            return $this->requestedTrainer;
        }

        $medoo = getMedoo();
        $user = $medoo->select("clients", [
            "[>]users" => ["trainer" => "id"]
        ], columns: "*", where: ["client" => $this->id, "approved" => false]);

        if (count($user) === 0) {
            $this->requestedTrainer = null;
            return null;
        }

        $user = $user[0];
        $trainer = new User($user["id"]);

        $this->assignBasicValues($user, $trainer);
        $this->assignOtherValues($user, $trainer);
        $this->requestedTrainer = $trainer;
        return $trainer;
    }

    /**
     * @param mixed $data
     * @param User $user
     * @return void
     */
    public function assignBasicValues(mixed $data, User $user): void
    {
        $user->id = $data["id"];
        $user->firstName = $data["first_name"];
        $user->lastName = $data["last_name"];
        $user->username = $data["username"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->accountType = AccountType::fromValue($data["account_type"]);

        if (isset($data["avatar"]) && $data["avatar"] !== "") {
            $user->avatar = $data["avatar"];
        }

        if (isset($data["bio"]) && $data["bio"] !== "") {
            $user->bio = $data["bio"];
        }
    }

    public function assignOtherValues(mixed $data, User $user): void
    {
        $user->weight = $data["weight"];
        $user->height = $data["height"];
        $user->pressure = $data["pressure"];
        $user->pulse = $data["pulse"];
        $user->bmi = $data["bmi"];
        $user->fat = $data["fat"];
    }

    public function getFullName(): string {
        return $this->firstName . " " . $this->lastName;
    }

    public function canSendMessageTo(User $target): bool {
        if ($this->accountType === AccountType::TRAINER && $target->accountType === AccountType::USER && $this->isMyClient($target)) {
            return true;
        }

        if ($this->accountType === AccountType::USER && $target->accountType === AccountType::TRAINER && $target->isMyClient($this)) {
            return true;
        }

        return false;
    }

    public function isMyClient(User $user): bool {
        $medoo = getMedoo();
        $client = $medoo->select("clients", "*", [
            "trainer" => $this->id,
            "client" => $user->id,
            "approved" => 1
        ]);

        return count($client) > 0;
    }

    public function getAllClients(): array {
        $medoo = getMedoo();
        $clients = $medoo->select("clients", [
            "[>]users" => ["client" => "id"]
        ], columns: "*", where: ["trainer" => $this->id, "approved" => true]);

        $result = [];
        foreach ($clients as $client) {
            $user = new User($client["id"]);
            $this->assignBasicValues($client, $user);
            $this->assignOtherValues($client, $user);
            $result[] = $user;
        }

        return $result;
    }

    public static function getAll(): array {
        $medoo = getMedoo();
        $users = $medoo->select("users", "*", []);

        $result = [];
        foreach ($users as $data) {
            $user = new User($data["id"]);
            $user->assignBasicValues($data, $user);
            $user->assignOtherValues($data, $user);
            $result[] = $user;
        }

        return $result;
    }

    public function getAllRequestedClients(): array {
        $medoo = getMedoo();
        $clients = $medoo->select("clients", [
            "[>]users" => ["client" => "id"]
        ], columns: "*", where: ["trainer" => $this->id, "approved" => false]);

        $result = [];
        foreach ($clients as $client) {
            $user = new User($client["id"]);
            $this->assignBasicValues($client, $user);
            $this->assignOtherValues($client, $user);
            $result[] = $user;
        }

        return $result;
    }

    public function getTrainerFullName(): string {
        $trainer = $this->loadTrainer();
        if ($trainer) {
            return $trainer->getFullName();
        }

        return "No trainer";
    }

    public function getAvatarURL(): string {
        return "/storage/avatars/" . $this->avatar;
    }

    public function hasTrainer(): bool {
        return $this->loadTrainer() !== null;
    }

    public function hasRequestedTrainer(): bool {
        return $this->getRequestedTrainer() !== null;
    }

    public static function getAllTrainers(): array {
        $medoo = getMedoo();
        $users = $medoo->select("users", "*", [
            "account_type" => AccountType::TRAINER->name
        ]);

        $trainers = [];
        foreach ($users as $user) {
            $trainer = new User($user["id"]);
            $trainer->assignBasicValues($user, $trainer);
            $trainer->assignOtherValues($user, $trainer);
            $trainers[] = $trainer;
        }

        return $trainers;
    }

    public static function find(int $id)
    {
        $medoo = getMedoo();
        $user = $medoo->select("users", "*", [
            "id" => $id
        ]);

        if (count($user) === 0) {
            return null;
        }

        $user = $user[0];
        $trainer = new User($user["id"]);
        $trainer->assignBasicValues($user, $trainer);
        $trainer->assignOtherValues($user, $trainer);
        return $trainer;
    }

    public function checkAccess(string $endpoint) {
        $access = $this->hasAccessTo();

        if (!in_array($endpoint, $access)) {
            if($access[0] === "*") {
                return;
            }

            flashWithRedirect(FlashType::ERROR, "You don't have access to this page", "/");
            exit;
        }
    }

    private function hasAccessTo(): array {
        return match ($this->accountType) {
            AccountType::TRAINER => [
                "/user/chat.php",
                "/user/clients.php",
                "/user/settings.php",
                "/user/index.php",

                "/actions/approve-client.php",
                "/actions/decline-client.php",
                "/actions/remove-client.php",
                "/actions/settings.php",
            ],
            AccountType::USER => [
                "/user/chat.php",
                "/user/settings.php",
                "/user/index.php",
                "/user/find-trainer.php",

                "/actions/request-trainer.php",
                "/actions/settings.php",
            ],
            AccountType::ADMIN => [
                "/user/settings.php",
                "/user/index.php",
                "/user/users.php",

                "/actions/settings.php",
                "/actions/delete-user.php",
                "/actions/change-account-type.php",
            ],
            default => [],
        };
    }

    function isAdministrator(): bool {
        return $this->accountType === AccountType::ADMIN;
    }

    function isTrainer(): bool {
        return $this->accountType === AccountType::TRAINER;
    }

    function renderBadge(bool $user = false): string {
        return match ($this->accountType) {
            AccountType::TRAINER => '<span class="badge text-bg-success"><i class="fa-solid fa-bolt"></i></span>',
            AccountType::ADMIN => '<span class="badge text-bg-danger"><i class="fa-solid fa-star"></i></span>',
            AccountType::USER => $user ? '<span class="badge text-bg-primary"><i class="fa-solid fa-user"></i></span>' : "",
            default => "",
        };
    }

    function getNextAccountType(): AccountType {
        return match ($this->accountType) {
            AccountType::USER => AccountType::TRAINER,
            AccountType::TRAINER => AccountType::ADMIN,
            AccountType::ADMIN => AccountType::USER,
        };
    }

    function delete() {
        $medoo = getMedoo();
        $medoo->delete("users", [
            "id" => $this->id
        ]);
    }
}