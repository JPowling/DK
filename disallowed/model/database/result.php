<?php


class Result
{

    public $result;

    public function __construct($result)
    {
        $this->result = $result;
    }


    public function get_rows(int $first_row = 0, int $num_rows = PHP_INT_MAX)
    {
        return (new Result(array_slice($this->result, $first_row, $num_rows)));
    }

    public function get_from_column(string $column, int $index = 0)
    {
        return (new Result(array_column($this->result, $column)[$index]));
    }

    public function get_column(string $column, int $first_row = 0, int $num_rows = PHP_INT_MAX)
    {
        return (new Result(array_column($this->result, $column)))->get_rows($first_row, $num_rows);
    }

    public function get_num_rows($result)
    {
        return (new Result(array_key_last($result)));
    }

    public function echo_all()
    {
        echo print_r($this->result, true);
    }
}
