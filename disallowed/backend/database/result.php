<?php
# Jens

class Result {

    public $result;

    public function __construct($result) {
        $this->result = $result;
    }

    public function get_rows(int $first_row = 0, int $num_rows = PHP_INT_MAX) {
        return (new Result(array_slice($this->result, $first_row, $num_rows)));
    }

    public function get_from_column(string $column, int $index = 0) {
        return (new Result(array_column($this->result, $column)[$index]));
    }

    public function get_column(string $column, int $first_row = 0, int $num_rows = PHP_INT_MAX) {
        return (new Result(array_column($this->result, $column)))->get_rows($first_row, $num_rows);
    }

    public function get_num_rows() {
        if (empty($this->result)) {
            return 0;
        }
        return array_key_last($this->result) + 1;
    }

    public function get_result(){
        return $this->result;
    }

    public function __toString() {
        return print_r($this->result, true);
    }
}
