<?php
# Paul

class Station {

    public string $short;
    public string $name;
    public int $platforms;

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
        $stations = Station::get_stations();

        $stations = array_filter($stations, function ($s) use ($short) {
            return $s->short == $short;
        });

        if (empty($stations)) {
            return Station::by_name($short);
        }

        return $stations[array_key_first($stations)];
    }

    public static function by_name(string $name) {
        $stations = Station::get_stations();

        $stations = array_filter($stations, function ($s) use ($name) {
            return $s->name == $name;
        });

        if (empty($stations)) {
            return null;
        }

        return $stations[array_key_first($stations)];
    }

    public static function create(string $short, string $name, int $platforms = 1000) {
        $sql = new SQL(true);

        $sql->sql_request("INSERT INTO Bahnhofe VALUES ('$short', '$name', $platforms)");
    }

    public static function ensure_short(string $msg) {
        $s = Station::by_name($msg);

        if (isset($s)) {
            return $s->short;
        }

        return $msg;
    }
}
