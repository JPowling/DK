<?php
# Paul

class Connection {

    public string $a;
    public string $b;
    public int $duration;
    public int $duration_rev;

    public static array $cached = array();

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

    public static function refresh() {
        $sql = new SQL();
        Connection::$cached = $sql->request("SELECT * FROM Verbindungen")->result;
    }

    public static function by_id(string $station_a, string $station_b) {
        $cache = Connection::$cached;


        $result = array_filter($cache, function ($row) use ($station_a, $station_b) {
            return $row["BahnhofA"] === $station_a && $row["BahnhofB"] === $station_b;
        });
        $result = array_values($result);

        $result_rev = array_filter($cache, function ($row) use ($station_a, $station_b) {
            return $row["BahnhofB"] === $station_a && $row["BahnhofA"] === $station_b;
        });
        $result_rev = array_values($result_rev);

        if (!isset($result[0]["Dauer"])) {
            return null;
        }

        return new Connection($station_a, $station_b, $result[0]["Dauer"], $result_rev[0]["Dauer"]);
    }

    public static function create(string $station_a, string $station_b, int $duration) {
        $sql = new SQL(true);

        $sql->sql_request("INSERT INTO Verbindungen VALUES ('$station_a', '$station_b', $duration)");
    }
}
