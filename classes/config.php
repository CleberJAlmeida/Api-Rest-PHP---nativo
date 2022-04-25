<?php
$enderco = $_SERVER['SERVER_NAME'];
if ($enderco == 'localhost') {
    define('login', "root");
    define('senha', "");
    define('banco', "banco");
    define('host', "localhost");
} else if ($enderco == 'sisinvestimentos.atwebpages.com') {
    define('login', "4007745_admin2");
    define('senha', "cle321C*");
    define('banco', "4007745_admin2");
    define('host', "fdb33.awardspace.net");
}
