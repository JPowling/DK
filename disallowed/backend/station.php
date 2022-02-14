<?php
# Paul

class Station {

    public int $short;
    public int $name;
    public int $platforms;

    public function __construct(int $short, int $name, $platforms) {
        $this->short = $short;
        $this->name = $name;
        $this->platforms = $platforms;
    }

    public function save() {
        $sql = new SQL(true);

        $sql->sql_request("UPDATE Bahnhofe SET Name=$this->name, Gleise=$this->platforms WHERE Kennzeichnung=$this->short");
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->sql_request("DELETE FROM Bahnhofe WHERE Kennzeichnung=$this->short");
    }

    public static function get_stations() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT * FROM Bahnhofe")->result;

        $stations = array();

        foreach ($result as $index => $row) {
            array_push($trains, new Station($row["Kennzeichnung"], $row["Name"], $row["Gleise"]));
        }

        return $stations;
    }

    public static function by_id(string $short) {
        $stations = Station::get_stations();

        $stations = array_filter($stations, function($s) use ($short) {
            return $s->short == $short;
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
}
