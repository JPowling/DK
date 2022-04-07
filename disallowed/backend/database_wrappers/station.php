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

        $vals = [
            "Name" => $this->name,
            "Platforms" => $this->platforms,
            "Short" => $this->short
        ];

        $sql->request("UPDATE Bahnhofe SET Name=:Name, Gleise=:Platforms WHERE Kennzeichnung=:Short", $vals);
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->request("DELETE FROM Bahnhofe WHERE Kennzeichnung=:Short", ["Short" => $this->short]);
    }

    public function get_connections() {
        $sql = new SQL();

        $result = $sql->request("SELECT BahnhofB, Dauer FROM Verbindungen WHERE BahnhofA=:Short", ["Short" => $this->short])->result;

        $return = array();
        foreach ($result as $index => $row) {
            $other = $row["BahnhofB"];
            $duration = $row["Dauer"];
            $duration_rev = $sql->request("SELECT Dauer FROM Verbindungen WHERE BahnhofA=:Other
                AND BahnhofB=:Short", ["Other" => $other, "Short" => $this->short])->get_from_column("Dauer");

            array_push($return, new Connection($this->short, $other, $duration, $duration_rev));
        }

        return $return;
    }

    public static function get_stations() {
        $sql = new SQL();

        $result = $sql->request("SELECT * FROM Bahnhofe")->result;

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

        $vals = [
            "Short" => $short,
            "Name" => $name,
            "Platforms" => $platforms
        ];

        $sql->request("INSERT INTO Bahnhofe VALUES (:Short, :Name, :Platforms)", $vals);
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
