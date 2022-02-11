<?php
# Owner: Paul

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
    public string $creation_date;

    public function __construct(string $email, bool $autofetch = true) {
        $this->email = $email;

        if ($autofetch) {
            $this->fetch();
        }
    }

    public function get_privileges() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT Ber.Bezeichnung FROM Benutzer as Ben "
            . "INNER JOIN Berechtigungen as Ber ON Ben.BerechtigungsID = Ber.BerechtigungsID "
            . "WHERE EMail='$this->email'");

        $result = $result->get_from_column("Bezeichnung");

        return $result;
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
        $this->creation_date = $result->get_from_column("Erstelldatum");
    }

    // Store data in database
    public function store_data() {
        $sql = new SQL(true);

        $sql->sql_request("UPDATE Benutzer SET Name='$this->surname', "
            . "Vorname='$this->forename', Telefon='$this->phone', "
            . "Ort='$this->residence', PLZ='$this->postal', "
            . "Strasse='$this->street', Hausnummer='$this->house', "
            . "Erstelldatum='$this->creation_date' "
            . "WHERE EMail='$this->email'");
    }

    public function set_password(string $clearpassword) {
        $sql = new SQL(true);
        $hash = password_hash($clearpassword, PASSWORD_DEFAULT);

        $sql->sql_request("UPDATE Benutzer SET PasswordHash='$hash' WHERE EMail='$this->email'");
    }

    public static function email_exists(string $email) {
        $sql = new SQL();
        echo $sql->sql_request("SELECT * FROM Benutzer WHERE EMail='$email'")->get_num_rows();
        return $sql->sql_request("SELECT * FROM Benutzer WHERE EMail='$email'")->get_num_rows() == 1;
    }

    public static function create_account(string $email, string $clearpassword) {
        $sql = new SQL(true);
        if ($sql->sql_request("SELECT * FROM Benutzer WHERE EMail='$email'")->get_num_rows() == 1) {
            return false;
        }

        $hash = password_hash($clearpassword, PASSWORD_DEFAULT);
        $sql->sql_request("INSERT INTO Benutzer (BerechtigungsID, EMail, PasswordHash, Erstelldatum) VALUES (1, '$email', '$hash', '".date("Y-m-d H:i:s")."')");
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

    public static function delete_account(string $email) {
        $sql = new SQL(true);
        $sql->sql_request("DELETE FROM Benutzer WHERE EMail='$email'");
    }
}
