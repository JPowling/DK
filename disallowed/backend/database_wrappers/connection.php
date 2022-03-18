<?php
# Paul

class Connection {

    public string $a;
    public string $b;
    public int $duration;
    public int $duration_rev;

    public function __construct(string $station_a, string $station_b, int $duration, int $duration_rev = -1) {
        $this->a = $station_a;
        $this->b = $station_b;
        $this->duration = $duration;
        $this->duration_rev = $duration_rev;
    }

    public function save() {
        $sql = new SQL(true);

        $sql->sql_request("UPDATE Verbindungen SET Dauer='$this->duration' WHERE BahnhofA='$this->a' AND BahnhofB='$this->b'");
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->sql_request("DELETE FROM Verbindungen WHERE WHERE BahnhofA='$this->a' AND BahnhofB='$this->b'");
    }

    public static function get_connections() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT * FROM Verbindungen")->result;

        $connections = array();

        foreach ($result as $index => $row) {
            array_push($connections, new Station($row["BahnhofA"], $row["BahnhofB"], $row["Dauer"]));
        }

        return $connections;
    }

    public static function by_id(string $station_a, string $station_b) {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT Dauer FROM Verbindungen WHERE BahnhofA='$station_a' AND BahnhofB='$station_b'")->get_from_column("Dauer");
        $result_rev = $sql->sql_request("SELECT Dauer FROM Verbindungen WHERE BahnhofA='$station_b' AND BahnhofB='$station_a'")->get_from_column("Dauer");

        return new Connection($station_a, $station_b, $result, $result_rev);
    }

    public static function create(string $station_a, string $station_b, int $duration) {
        $sql = new SQL(true);

        $sql->sql_request("INSERT INTO Verbindungen VALUES ('$station_a', '$station_b', $duration)");
    }
}
