<?php
namespace App\Traits;

trait ImportTrait{
	public $faileds = [];
    public $success = [];

    public function getSuccess(): array 
    {
        return $this->success;
    }

    public function getFaileds(): array
    {
        return $this->faileds;
    }

    public function limit(): int
    {
        return 100;
    }

    public function startRow(): int
    {
        return 3;
    }
}