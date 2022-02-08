<?php

require_once("disallowed/model/database/DB.php");


class SQL
{
    private PDO $pdo;

    public function __construct(bool $is_w = true)
    {
        $this->pdo = DB::connect($is_w);
    }


    public function sql_request(String $command = ";")
    {
        $cmd = $this->pdo->prepare($command);
        $cmd->execute();
        $result = $cmd->fetchAll();
        return $result;
    }



    public static function get_rows(array $result, int $first_row = 0, int $num_rows = PHP_INT_MAX)
    {
        return array_slice($result, $first_row, $num_rows);
    }

    public static function get_from_column($result, string $column, int $index = 0)
    {
        return array_column($result, $column)[$index];
    }

    public static function get_column($result, string $column, int $first_row = 0, int $num_rows = PHP_INT_MAX)
    {
        return SQL::get_rows(array_column($result, $column), $first_row, $num_rows);
    }

    public static function get_num_rows($result)
    {
        return array_key_last($result);
    }

    public static function echo_all($result)
    {
        try {
            foreach ($result as $row_index => $row) {
                echo $row_index . ": ";
                foreach ($row as $column => $value) {
                    echo $column . " -> " . $value . "; ";
                }
                echo "</br>";
            }
        } catch (Throwable $x) {
            echo $x->getMessage() . " SQL::echo_all";
        }
    }
}
