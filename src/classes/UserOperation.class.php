<?php
use schema\User;

abstract class UserOperation
{
    public function authenticate(string $username, string $password): User|null
    {
        return null;
    }
}