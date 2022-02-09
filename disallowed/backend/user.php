<?php

class User {

    public string $userid;
    public string $forename;
    public string $surname;
    public string $email;
    public string $phone;
    public string $residence;
    public string $postal;
    public string $street;
    public string $house;

    public function __construct(string $email, bool $autofetch = true) {
        $this->email = $email;

        if ($autofetch) {
            $this->fetch();
        }
    }

    // fet data from database using email since its unique
    public function fetch() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT * FROM Benutzer WHERE EMail='$this->email'");

        $this->userid = $result->get_from_column("BenutzerID");
        $this->forename = $result->get_from_column("Vorname");
        $this->surname = $result->get_from_column("Name");
        $this->phone = $result->get_from_column("Telefon");
        $this->residence = $result->get_from_column("Ort");
        $this->postal = $result->get_from_column("PLZ");
        $this->street = $result->get_from_column("Strasse");
        $this->house = $result->get_from_column("Hausnummer");
    }

    // Store data in database
    public function store_data() {

    }

    public static function create_account(string $email, string $clearpassword) {
        $sql = new SQL(true);
        if ($sql->sql_request("SELECT * FROM Benutzer WHERE EMail='$email'")->get_num_rows() !== 1) {
            return false;
        }

        $hash = password_hash($clearpassword, PASSWORD_DEFAULT);
        $sql->sql_request("INSERT INTO Benutzer (BerechtigungsID, EMail, PasswordHash) VALUES (1, '$email', '$hash')");
        return true;
    }

    public static function verify_password(string $email, string $clearpassword) {
        $sql = new SQL();
        $result = $sql->sql_request("SELECT PasswordHash FROM Benutzer WHERE EMail='$email'");
        
        if ($result->get_num_rows() !== 1) {
            return false;
        }

        $hash = $result->get_from_column("PasswordHash");
        return password_verify($clearpassword, $hash);
    }

}
