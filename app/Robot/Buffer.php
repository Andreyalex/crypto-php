<?php

namespace App\Robot;

use Illuminate\Support\Collection;

class Buffer extends Collection
{
    protected $data;

    protected $length;

    public function __construct($length, $items = [])
    {
        $this->length = $length;

        parent::__construct($items);
    }

    public function fifo($item)
    {
        $this->push($item);
        if ($this->count() > $this->length) {
            $this->shift();
        }
        return $this;
    }
}
