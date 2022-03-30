<?php
# Paul

class Station {

    public string $short;
    public string $name;
    public int $platforms;

    public static array $cached = array();

    public function __construct(string $short, string $name, int $platforms) {
        $this->short = $short;
        $this->name = $name;
        $this->platforms = $platforms;
    }

    public function save() {
        $sql = new SQL(true);

        $sql->sql_request("UPDATE Bahnhofe SET Name='$this->name', Gleise=$this->platforms WHERE Kennzeichnung='$this->short'");
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->sql_request("DELETE FROM Bahnhofe WHERE Kennzeichnung='$this->short'");
    }

    public function get_connections() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT BahnhofB, Dauer FROM Verbindungen WHERE BahnhofA='$this->short'")->result;

        $return = array();
        foreach ($result as $index => $row) {
            $other = $row["BahnhofB"];
            $duration = $row["Dauer"];
            $duration_rev = $sql->sql_request("SELECT Dauer FROM Verbindungen WHERE BahnhofA='$other' AND BahnhofB='$this->short'")->get_from_column("Dauer");

            array_push($return, new Connection($this->short, $other, $duration, $duration_rev));
        }

        return $return;
    }

    public static function get_stations() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT * FROM Bahnhofe")->result;

        $stations = array();

        foreach ($result as $index => $row) {
            array_push($stations, new Station($row["Kennzeichnung"], $row["Name"], $row["Gleise"]));
        }

        return $stations;
    }

    public static function by_id(string $short) {
        $cache = Station::$cached;

        $result = array_filter($cache, function ($row) use ($short) {
            return $row["Kennzeichnung"] === $short;
        });
        $result = array_values($result);

        if (!isset($result[0]["Name"])) {
            return null;
        }

        return new Station($result[0]["Kennzeichnung"],
                            $result[0]["Name"],
                            $result[0]["Gleise"]);
    }

    public static function by_name(string $name) {
        $cache = Station::$cached;

        $result = array_filter($cache, function ($row) use ($name) {
            return $row["Name"] === $name;
        });
        $result = array_values($result);

        if (!isset($result[0]["Name"])) {
            return null;
        }

        return new Station($result[0]["Kennzeichnung"],
                            $result[0]["Name"],
                            $result[0]["Gleise"]);
    }

    public static function refresh() {
        $sql = new SQL();
        Station::$cached = $sql->request("SELECT * FROM Bahnhofe")->result;
    }

    public static function create(string $short, string $name, int $platforms = 1000) {
        $sql = new SQL(true);

        $sql->sql_request("INSERT INTO Bahnhofe VALUES ('$short', '$name', $platforms)");
        Station::refresh();
    }

    public static function ensure_short(string $msg) {
        $s = Station::by_name($msg);

        if (isset($s)) {
            return $s->short;
        }

        return $msg;
    }

    public static function ensure_long(string $msg) {
        $s = Station::by_id($msg);

        if (isset($s)) {
            return $s->name;
        }

        return $msg;
    }
}
