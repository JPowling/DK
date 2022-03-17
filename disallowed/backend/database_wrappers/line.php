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

        $sql->sql_request("UPDATE Linien SET RoutenID=$this->routeid, Startzeit='$this->start_time'
                            , Fahrzeugnummer=$this->train_id, ZuggattungsID='$this->category' 
                            WHERE LinienID=$this->id");
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->sql_request("DELETE FROM Linien WHERE LinienID=$this->id");
    }

    public static function get_lines() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT * FROM Linien")->result;

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

        $sql->sql_request("INSERT INTO Linien VALUES ($id, $routeid, '$start_time', $train_id, '$category')");
    }

}
