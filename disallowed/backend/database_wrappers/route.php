<?php
# Paul

class Route {

    public int $id;

    public array $data;

    public static array $cached = array();

    public function __construct(int $id, bool $fetch) {
        $this->id = $id;

        if ($fetch) {
            // Since Routen is a m:n relation this gets stored weirdly in an array and needs to be built using only the id
            $this->fetch();
        } else {
            $this->data = array();
        }
    }

    // Store recursive to database
    public function save() {
        $sql = new SQL(true);
        $commands = array();
        array_push($commands, "SET FOREIGN_KEY_CHECKS = 0;");

        $checkexistsresult = $sql->request("SELECT BahnhofA, BahnhofB, VerbindungsIndex,
         Standzeit FROM Routen WHERE RoutenID=:Route ORDER BY VerbindungsIndex", ["Route" => $this->id])->result;

        foreach ($this->data as $local_index => $stored) {
            $exists = false;
            $local_a = $stored["a"];
            $local_b = $stored["b"];
            $local_stand = $stored["stand_time"];

            foreach ($checkexistsresult as $remoteindex => $remotekeys) {

                if ($local_a == $remotekeys["BahnhofA"] && $local_b == $remotekeys["BahnhofB"]) {
                    // local key is already in remote, so it will be needed to be updated instead of created

                    $exists = true;

                    // When unsetting the result, we can check later whether the remote has a key thats not in local and therefore needs to be deleted
                    unset($checkexistsresult[$remoteindex]);
                }
            }


            // It might be the case, that a UNIQUE constraint fails, so 'SET UNIQUE_CHECKS = 0;' is a workaround
            if ($exists) {
                array_push($commands, "UPDATE Routen SET VerbindungsIndex=$local_index, Standzeit=$local_stand WHERE BahnhofA='$local_a' AND BahnhofB='$local_b' AND RoutenID=$this->id;");
            } else {
                array_push($commands, "INSERT INTO Routen VALUES ($this->id, '$local_a', '$local_b', $local_index, $local_stand);");
            }
        }

        // Delete leftovers
        foreach ($checkexistsresult as $_ => $vals) {
            array_push($commands, "DELETE FROM Routen WHERE RoutenID=$this->id AND BahnhofA='" . $vals["BahnhofA"] . "' AND BahnhofB='" . $vals["BahnhofB"] . "';");
        }
        array_push($commands, "SET FOREIGN_KEY_CHECKS = 1;");

        $sql->transaction($commands, array());
    }

    public function get_connections() {
        $connections = array();

        foreach ($this->data as $data) {
            $connection = Connection::by_id($data["a"], $data["b"]);

            array_push($connections, $connection);
        }

        return $connections;
    }

    // Array of Index => [[0] => Connection, [1] => Standzeit]
    public function set_connections(array $connections) {
        $newdata = array();

        foreach ($connections as $index => $val) {
            $newdata[$index] = ["a" => $val[0]->station_a, "b" => $val[0]->station_b, "stand_time" => $val[1]];
        }

        $this->data = $newdata;
    }

    public function delete() {
        $sql = new SQL(true);

        $sql->request("DELETE FROM Routen WHERE RoutenID=:Route", ["Route" => $this->id]);
    }

    public function fetch() {
        $sql = new SQL();
        $this->data = array();

        $result = $sql->request("SELECT BahnhofA, BahnhofB, VerbindungsIndex, Standzeit FROM Routen WHERE RoutenID=:Route", ["Route" => $this->id])->get_result();

        foreach ($result as $row) {
            $this->data[$row["VerbindungsIndex"]] = ["a" => $row["BahnhofA"], "b" => $row["BahnhofB"], "stand_time" => $row["Standzeit"]];
        }

        ksort($this->data);
    }

    public function get_start_finish() {
        $r = array();
        $a = $this->data[1]["a"];
        $b = $this->data[array_key_last($this->data)]["b"];

        $a = Station::ensure_long($a);
        $b = Station::ensure_long($b);

        array_push($r, $a, $b);
        return $r;
    }

    public static function get_routes() {
        $result = Route::$cached;

        $routes = array();

        foreach ($result as $row) {
            $id = $row["RoutenID"];

            if (!isset($routes[$id])) {
                $routes[$id] = new Route($id, false);
            }

            $routes[$id]->data[$row["VerbindungsIndex"]] = ["a" => $row["BahnhofA"], "b" => $row["BahnhofB"], "stand_time" => $row["Standzeit"]];
        }
        
        foreach ($routes as $route) {
            ksort($route->data);
        }
        return $routes;
    }

    public static function by_id(string $id) {
        $routes = Route::get_routes();

        $routes = array_filter($routes, function ($s) use ($id) {
            return $s->id == $id;
        });

        if (empty($routes)) {
            return null;
        }

        return $routes[array_key_first($routes)];
    }

    public static function new_route(string $station_a, string $station_b) {
        $sql = new SQL(true);
        $id = Route::next_free();

        $station_a = Station::ensure_short($station_a);
        $station_b = Station::ensure_short($station_b);

        $vals = [
            "Route" => $id,
            "A" => $station_a,
            "B" => $station_b];

        $sql->request("INSERT INTO Routen VALUES (:Route, ':A', ':B', 1, NULL)", $vals);

        return $id;
    }

    public static function next_free() {
        $sql = new SQL();

        return $sql->request("SELECT MAX(RoutenID) as A FROM Routen")->get_from_column("A") + 1;
    }

    public static function refresh() {
        $sql = new SQL();
        Route::$cached = $sql->request("SELECT * FROM Routen")->result;
    }
}
