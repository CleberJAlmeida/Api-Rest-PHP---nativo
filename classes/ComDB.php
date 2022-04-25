<?php

namespace classes;

class ComDB
{
    public static function connect()
    {
        $host = host;
        $user = login;
        $pass = senha;
        $base = banco;
        $resp = new \PDO("mysql:host={$host};dbname={$base};charset=UTF8;", $user, $pass);
        try {
            return $resp;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
