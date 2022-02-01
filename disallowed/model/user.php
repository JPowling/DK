<?php

class User {

    public $userid;
    public $forename;
    public $surname;
    public $email;
    public $phone;
    public $residence;
    public $postal;
    public $street;
    public $house;

    public function __construct(string $email, bool $autofetch = true) {
        $this->email = $email;

        if ($autofetch) {
            $this->fetch();
        }
    }

    // fet data from database using email since its unique
    public function fetch() {

    }

    // Store data in database
    // If $passwordhash is null, it won't be stored
    public function store_data(string $passwordhash = null) {

    }

    // TODO
    public static function verify_password(string $email, string $clearpassword) {
        $hash = password_hash("12345", PASSWORD_DEFAULT);
        return password_verify($clearpassword, $hash);
    }

}  

?>
