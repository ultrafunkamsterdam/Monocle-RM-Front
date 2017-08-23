<?php

namespace Config;
session_start();
header('content-type: application/json');
header("Access-Control-Allow-Origin: yourwebsite.tld");
$referer_raw = $_SERVER['HTTP_REFERER'];
$referer_url = parse_url($referer_raw);
$referer_host = $referer_url['host'];
$allowed_referers = array('yourwebsite.tld', 'www.yourwebsite.tld');
$sessionTTL = 45;

$d = array();
if(!in_array($referer_host, $allowed_referers)){
  $d['status'] = "referer-not-allowed: $referer_host";
  http_response_code(400);
  die();
 } else {
  $d['status'] = "ok";
}

if (!isTrue($_SESSION['time_issued']) || !isTrue($_SESSION['time_left']) || $_SESSION['time_left'] <= 0) {
  unset($_SESSION['token']);
  unset($_SESSION['time_issued']);
  unset($_SESSION['time_left']);
  $session_token = $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
  $session_time_issued = $_SESSION['time_issued'] = time();
  $session_time_left = $_SESSION['time_left'] = $sessionTTL;
}
else {
  $session_token = $_SESSION['token'];
  $session_time_issued = $_SESSION['time_issued'];
  $session_time_left = $_SESSION['time_left'] = ($sessionTTL - (time() - $session_time_issued));
}

session_write_close();

include('utils.php');

// Do not touch this!
require 'Medoo.php';
use Medoo\Medoo;

//======================================================================
// PMSF - CONFIG FILE
// https://github.com/Glennmen/PMSF
//======================================================================


$purgeData = 48;

$noRaids = false;                                                   // true/false

$noPokestops = false;                                               // true/false
                                                                    // O: all, 1: lured only
$noPokemon = false;                                                 // true/false

$noGyms = false;                                                    // true/false

//-----------------------------------------------------
// DATABASE CONFIG
//-----------------------------------------------------

$map = "monocle";                                                   // monocle/rm
$fork = "default";                                                  // default/asner/sloppy

$db = new Medoo([// required
    'database_type' => 'pgsql',                                     // mysql/mariadb/pgsql/sybase/oracle/mssql/sqlite
    'database_name' => 'monocle',
    'server' => '127.0.0.1',
    'username' => 'dbuser', //change to your username
    'password' => 'dbpass', //change to your password
    'charset' => 'utf8',

    // [optional]
    //'port' => 5432,                                               // Comment out if not needed, just add // in front!
    //'socket' => /path/to/socket/,
]);
