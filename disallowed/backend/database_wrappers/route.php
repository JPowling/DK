<?php
# Paul

class Route {

    public int $id;

    public array $data;

    private function __construct(int $id, bool $fetch) {
        $this->id = $id;

        if ($fetch) {
            // Since Routen is a m:n relation this gets stored weirdly in an array and needs to be built using only the id
            $this->fetch();
        }
    }

    // Store recursive to database
    public function save() {
        $sql = new SQL(true);
        $commands = array();
        array_push($commands, "SET FOREIGN_KEY_CHECKS = 0;");

        $checkexistsresult = $sql->sql_request("SELECT BahnhofA, BahnhofB, VerbindungsIndex FROM Routen WHERE RoutenID=$this->id ORDER BY VerbindungsIndex")->result;

        foreach ($this->data as $local_index => $stored) {
            $exists = false;
            $changed = true;
            $local_a = $stored["a"];
            $local_b = $stored["b"];
            $local_stand = $stored["stand_time"];

            foreach ($checkexistsresult as $remoteindex => $remotekeys) {

                if ($local_a == $remotekeys["BahnhofA"] && $local_b == $remotekeys["BahnhofB"]) {
                    // local key is already in remote, so it will be needed to be updated instead of created

                    $exists = true;

                    if ($local_index == $remotekeys["VerbindungsIndex"]) {
                        $changed = false;
                    }

                    // When unsetting the result, we can check later whether the remote has a key thats not in local and therefore needs to be deleted
                    unset($checkexistsresult[$remoteindex]);
                }
                
            }


            // It might be the case, that a UNIQUE constraint fails, so 'SET UNIQUE_CHECKS = 0;' is a workaround
            if ($changed) {
                if ($exists) {
                    array_push($commands, "UPDATE Routen SET VerbindungsIndex=$local_index, Standzeit=$local_stand WHERE BahnhofA='$local_a' AND BahnhofB='$local_b';");
                } else {
                    array_push($commands, "INSERT INTO Routen VALUES ($this->id, '$local_a', '$local_b', $local_index, $local_stand);");
                }
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

        $sql->sql_request("DELETE FROM Routen WHERE RoutenID=$this->id");
    }

    public function fetch() {
        $sql = new SQL();
        $this->data = array();

        $result = $sql->sql_request("SELECT BahnhofA, BahnhofB, VerbindungsIndex, Standzeit FROM Routen WHERE RoutenID=$this->id")->get_result();

        foreach ($result as $row) {
            $this->data[$row["VerbindungsIndex"]] = ["a" => $row["BahnhofA"], "b" => $row["BahnhofB"], "stand_time" => $row["Standzeit"]];
        }

        ksort($this->data);
    }

    public static function get_routes() {
        $sql = new SQL();

        $result = $sql->sql_request("SELECT DISTINCT RoutenID FROM Routen")->result;

        $routes = array();

        foreach ($result as $index => $row) {
            array_push($routes, new Route($row["RoutenID"], true));
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
}
