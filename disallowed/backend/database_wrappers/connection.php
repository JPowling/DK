<?php
# Paul

class Connection {

    public array $connections;
    public string $station_a;
    public string $station_b;
    public int $duration;

    public function __construct(string $station_a, string $station_b, int $duration) {
        $this->connections = ["a" => $station_a, "b" => $station_b];
        $this->duration = $duration;
    }

    public function save() {
        $sql = new SQL(true);

        $sql->sql_request("UPDATE Verbindungen SET Dauer='$this->duration' WHERE BahnhofA='" . $this->connections["a"] . "' AND BahnhofB='" . $this->connections["b"] . "'");
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->sql_request("DELETE FROM Verbindungen WHERE WHERE BahnhofA='" . $this->connections["a"] . "' AND BahnhofB='" . $this->connections["b"] . "'");
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
        $connections = Connection::get_connections();

        $connections = array_filter($connections, function ($s) use ($station_a, $station_b) {
            return $s->station_a == $station_a and $s->station_b == $station_b;
        });

        if (empty($connections)) {
            return null;
        }

        return $connections[array_key_first($connections)];
    }

    public static function create(string $station_a, string $station_b, int $duration) {
        $sql = new SQL(true);

        $sql->sql_request("INSERT INTO Verbindungen VALUES ('$station_a', '$station_b', $duration)");
    }
}
