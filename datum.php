<?php

class Datum
{
    public $submitted;
    public $negative;
    public $positive;
    public $pending;
    public $deaths;
    public $created_at;

    function __construct($submitted, $negative, $positive, $pending, $deaths, $created_at)
    {
        $this->submitted = $submitted;
        $this->negative = $negative;
        $this->positive = $positive;
        $this->pending = $pending;
        $this->deaths = $deaths;
        $this->created_at = $created_at;
    }
}
