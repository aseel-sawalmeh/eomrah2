<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = true;

$db['default'] = array(
    'dsn' => 'mysql:host=localhost;dbname=eomrah_h',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'mysql',
    'dbdriver' => 'pdo',
    'dbprefix' => '',
    'pconnect' => true,
    'db_debug' => (ENVIRONMENT !== 'development'),
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => array(),
    'save_queries' => true,
);

$db['translatedb'] = array(
    'database' => APPPATH.'filedb/translations.db',
    'dbdriver' => 'sqlite3',
    'pconnect' => true,
    'db_debug' => (ENVIRONMENT !== 'development'),
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => array(),
    'save_queries' => true
);
