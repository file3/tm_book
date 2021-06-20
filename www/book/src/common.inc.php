<?php
require('config.inc.php');

/**
 * \brief Detect whether called from CLI
 * \return Is called from CLI
 */
function is_cli()
{
    if (defined('STDIN'))
        return true;
    elseif (php_sapi_name() === 'cli')
        return true;
/*  elseif (array_key_exists('SHELL', $_ENV))
        return true;
    elseif (empty($_SERVER['REMOTE_ADDR']) && (!isset($_SERVER['HTTP_USER_AGENT'])) && (count($_SERVER['argv']) > 0))
        return true;
    elseif (array_key_exists('REQUEST_METHOD', $_SERVER))
        return true;
*/  else
        return false;
}

/**
 * \brief Translation function e.g. with database backend - currently dummy
 * \param word - Word to translate
 * \param lang - Language, default none
 * \return Translated word
 */
function __($word, $lang=false)
{
    return $word;
}


function ostr(&$var, $def='')
{
    return trim(isset($var) ? $var : $def);
}
function oint(&$var, $def=0)
{
    return (int)(isset($var) ? $var : $def);
}

function pgstr($key, $def='')
{
    return trim(isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $def));
}
function pgint($key, $def=0)
{
    return (int)(isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $def));
}

/**
 * \brief Just to demonstrate interface usage
 */
interface DB
{
    public function connect($host, $user, $pass, $name);
    public function set_charset($db, $charset);
    public function query($db, $query);
    public function fetch_assoc($res);
    public function num_fields($res);
    public function num_rows($res);
    public function insert_id($db, $seq);
    public function last_insert_id($db);
    public function error($db);
}

class DB_PgSQL implements DB
{
    public function connect($host, $user, $pass, $name)
    {
        return pg_pconnect('host='.$host.' user='.$user.' password='.$pass.' dbname='.$name);
    }

    public function set_charset($db, $charset)
    {
        return pg_set_client_encoding($db, $charset);
    }

    public function query($db, $query)
    {
        return pg_query($db, $query);
    }

    public function fetch_assoc($res)
    {
        return pg_fetch_array($res, NULL, PGSQL_ASSOC);
    }

    public function num_fields($res)
    {
        return pg_num_fields($res);
    }

    public function num_rows($res)
    {
        return pg_num_rows($res);
    }

    public function insert_id($db, $seq)
    {
        $res = pg_query($db, "SELECT NEXTVAL('".$seq."')");
        $row = pg_fetch_array($res, NULL, PGSQL_ASSOC);
        return $row['nextval'];
    }

    public function last_insert_id($db)
    {
    }

    public function error($db)
    {
        return pg_last_error($db);
    }
}

class DB_MySQL implements DB
{
    public function connect($host, $user, $pass, $name)
    {
        return mysqli_connect($host, $user, $pass, $name);
    }

    public function set_charset($db, $charset)
    {
        return mysqli_set_charset($db, $charset);
    }

    public function query($db, $query)
    {
        return mysqli_query($db, $query);
    }

    public function fetch_assoc($res)
    {
        return mysqli_fetch_assoc($res);
    }

    public function num_fields($res)
    {
        return mysqli_num_fields($res);
    }

    public function num_rows($res)
    {
        return mysqli_num_rows($res);
    }

    public function insert_id($db, $seq)
    {
    }

    public function last_insert_id($db)
    {
        return mysqli_insert_id($db);
    }

    public function error($db)
    {
        return mysqli_error($db);
    }
}

setlocale(LC_ALL, LOCALE.'.'.CHARSET);
?>
