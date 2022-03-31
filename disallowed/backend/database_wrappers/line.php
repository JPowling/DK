<?php
# Paul

class Line {
    
    public int $id;
    public int $routeid;
    public string $start_time;
    public int $train_id;
    public string $category;

    public function __construct(int $id, int $routeid, string $start_time, int $train_id, string $category) {
        $this->id = $id;
        $this->routeid = $routeid;
        $this->start_time = $start_time;
        $this->train_id = $train_id;
        $this->category = $category;
    }

    public function save() {
        $sql = new SQL(true);

        $vals = [
            "Route" => $this->routeid, 
            "Start" => $this->start_time, 
            "Train" => $this->train_id, 
            "Category" => $this->category, 
            "Line" => $this->id];

        $sql->request("UPDATE Linien SET RoutenID=:Route, Startzeit=':Start', Fahrzeugnummer=:Train, ZuggattungsID=':Category' 
                            WHERE LinienID=:Line", $vals);
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->request("DELETE FROM Linien WHERE LinienID=:Line", ["Line" => $this->id]);
    }

    public static function get_lines() {
        $sql = new SQL();

        $result = $sql->request("SELECT * FROM Linien")->result;

        $lines = array();

        foreach ($result as $index => $row) {
            array_push($lines, new Line($row["LinienID"], $row["RoutenID"], $row["Startzeit"], $row["Fahrzeugnummer"], $row["ZuggattungsID"]));
        }

        return $lines;
    }

    public static function by_id(string $id) {
        $lines = Line::get_lines();

        $lines = array_filter($lines, function($s) use ($id) {
            return $s->id == $id;
        });

        if (empty($lines)) {
            return null;
        }

        return $lines[array_key_first($lines)];
    }

    public static function create(int $id, int $routeid, string $start_time, int $train_id, string $category) {
        $sql = new SQL(true);

        $vals = [
            "Route" => $routeid, 
            "Start" => $start_time, 
            "Train" => $train_id, 
            "Category" => $category, 
            "Line" => $id];

        $sql->request("INSERT INTO Linien VALUES (:Line, :Route, ':Start', :Train, ':Category')", $vals);
    }

    public static function create_inc() {
        $sql = new SQL();

        $result = $sql->request("SELECT LinienID FROM Linien ORDER BY LinienID")->result;
        $numbers = array_merge_recursive(...$result)["LinienID"];
        
        $lowestpossible = 100;
        for ($i = $lowestpossible; $i <= PHP_INT_MAX; $i++) {
            if (!in_array($i, $numbers)){
                $lowestpossible = $i;
                break;
            }
        }

        Line::create($lowestpossible, 1, "00:00:00", 1, "ICE");
        return $lowestpossible;
    }

}
