<?php
# Paul

class Train {

    public int $number;
    public int $seats;

    public function __construct(int $number, int $seats) {
        $this->number = $number;
        $this->seats = $seats;
    }

    public function save() {
        $sql = new SQL(true);

        $sql->sql_request("UPDATE Fahrzeuge SET Sitzplatze=$this->seats WHERE Fahrzeugnummer=$this->number");
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->sql_request("DELETE FROM Fahrzeuge WHERE Fahrzeugnummer=$this->number");
    }

    public static function get_trains() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT * FROM Fahrzeuge")->result;

        $trains = array();

        foreach ($result as $index => $row) {
            array_push($trains, new Train($row["Fahrzeugnummer"], $row["Sitzplatze"]));
        }

        return $trains;
    }

    public static function by_id(int $id) {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT * FROM Fahrzeuge WHERE Fahrzeugnummer=$id");

        if ($result->get_num_rows() !== 1) {
            return null;
        }

        return new Train($result->get_from_column("Fahrzeugnummer"), $result->get_from_column("Sitzplatze"));
    }

    public static function create(int $seats) {
        $sql = new SQL(true);

        $result = $sql->sql_request("SELECT Fahrzeugnummer FROM Fahrzeuge ORDER BY Fahrzeugnummer")->result;
        $numbers = array_merge_recursive(...$result)["Fahrzeugnummer"];
        
        $lowestpossible = 0;
        for ($i = 1; $i <= PHP_INT_MAX; $i++) {
            if (!in_array($i, $numbers)){
                $lowestpossible = $i;
                break;
            }
        }

        $sql->sql_request("INSERT INTO Fahrzeuge VALUES ($lowestpossible, $seats)");
        return $lowestpossible;
    }
}
