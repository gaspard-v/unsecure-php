<?php

namespace schema;

class User
{
    public function __construct(public int $id, public string $username, public string $password, public float $balance)
    {
    }
}