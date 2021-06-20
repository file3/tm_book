<?php
require('common.inc.php');

class GetDataFrame
{
    protected $db = NULL;
    protected $dbi = NULL;

    public function __construct()
    {
        if (!defined('DB_MYSQL')) {
            $this->db = new DB_PgSQL();
        } else {
            $this->db = new DB_MySQL();
        }
        $this->dbi = $this->db->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->db->set_charset($this->dbi, DB_CHARSET);

        echo  <<<EOD
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Book</title>
<style>
label {
    display: block;
    font: 1rem 'Fira Sans', sans-serif;
}

input, label {
    margin: .4rem 0;
}

.grid-container {
    display: grid;
    grid-template-columns: auto auto;
    width: 50%;
}
.grid-item {
    border: 1px solid rgba(0, 0, 0, 0.8);
    font-size: 30px;
    text-align: left;
    padding-left: 10px;
    padding-right: 10px;
}

.marq {
}
</style>
</head>
<body>
<label for="site-search">Search the site:</label>
<form>
  <input type="search" id="site-search" name="q" aria-label="Search through site content">
  <button>Search</button>
</form>

EOD;
    }

    public function get_db()
    {
        return $this->db;
    }

    public function get_dbi()
    {
        return $this->dbi;
    }

    public function __destruct()
    {
        echo  <<<EOD
</body>
</html>

EOD;
    }
}

class GetData extends GetDataFrame
{
    public function __construct()
    {
        parent::__construct();
    }

    private function load_one($author, $name, $path)
    {
        $res = $this->db->query($this->dbi, "SELECT * FROM ".AUTHORS_TABLE.", ".BOOKS_TABLE." WHERE auid=author AND UPPER(".AUTHORS_TABLE.".name)=UPPER('".addslashes(ostr($author))."') AND UPPER(".BOOKS_TABLE.".name)=UPPER('".addslashes(ostr($name))."')");
        $row = $this->db->fetch_assoc($res);
        if (!$row) {
            $res = $this->db->query($this->dbi, "SELECT * FROM ".AUTHORS_TABLE." WHERE UPPER(".AUTHORS_TABLE.".name)=UPPER('".addslashes(ostr($author))."')");
            $row = $this->db->fetch_assoc($res);
            if (!$row) {
                $auid = $this->db->insert_id($this->dbi, "authors_auid_seq");
                $this->db->query($this->dbi, "INSERT INTO ".AUTHORS_TABLE." (auid, name) VALUES (".$auid.", '".addslashes(ostr($author))."')");
            } else {
                $auid = $row["auid"];
            }
            $this->db->query($this->dbi, "INSERT INTO ".BOOKS_TABLE." (author, name, path) VALUES (".$auid.", '".addslashes(ostr($name))."', '".addslashes(ostr($path))."')");
        } else {
            $this->db->query($this->dbi, "UPDATE ".BOOKS_TABLE." SET path='".addslashes(ostr($path))."' WHERE boid=".$row["boid"]);
        }
    }

    public function load()
    {
        echo "<pre>\n";

        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(INPUT_DATA));
        $files = array();
        foreach($rii as $file) {
            if ($file->isDir()) {
                continue;
            }
            $files[] = $file->getPathname();
        }
        sort($files);

        foreach($files as $file) {
            $xml = simplexml_load_string(file_get_contents($file)) or die("Error: Cannot create object");
            if (empty($xml->author) || empty($xml->name)) {
                echo "Error: Invalid index file ".$file."\n";
                continue;
            }
            echo $xml->author."\n";
            echo $xml->name."\n";
            echo $file."\n";
            $this->load_one($xml->author, $xml->name, $file);
        }
        $res = $this->db->query($this->dbi, "SELECT * FROM ".BOOKS_TABLE);
        while($row = $this->db->fetch_assoc($res)) {
            if (file_exists($row["path"])) {
                continue;
            }
            $this->db->query($this->dbi, "DELETE * FROM ".BOOKS_TABLE." WHERE boid=".$row["boid"]);
            $res2 = $this->db->query($this->dbi, "SELECT COUNT(*) FROM ".AUTHORS_TABLE.", ".BOOKS_TABLE." WHERE auid=author AND auid=".$row["auid"]);
            $row2 = $this->db->fetch_assoc($res2);
            if ($row2["count"]) {
                continue;
            }
            $this->db->query($this->dbi, "DELETE * FROM ".AUTHORS_TABLE." WHERE auid=".$row["auid"]);
        }

        echo "</pre>\n";
        echo "<hr>\n";
    }

    public function view()
    {
        $q = pgstr("q");
        if ($q) {
            echo "<div class=\"grid-container\">\n";
            $res = $this->db->query($this->dbi, "SELECT ".AUTHORS_TABLE.".name as author, ".BOOKS_TABLE.".name as book FROM ".AUTHORS_TABLE.", ".BOOKS_TABLE." WHERE auid=author AND (UPPER(".AUTHORS_TABLE.".name) LIKE UPPER('%".addslashes($q)."%') OR UPPER(".BOOKS_TABLE.".name) LIKE UPPER('%".addslashes($q)."%')) ORDER BY UPPER(".AUTHORS_TABLE.".name), UPPER(".BOOKS_TABLE.".name)");
            while($row = $this->db->fetch_assoc($res)) {
                echo "<div class=\"grid-item\"><marquee class=\"marq\" behavior=\"slide\" direction=\"left\" scrollamount=\"50\" loop=\"1\">".$row["author"]."</marquee></div>\n";
                echo "<div class=\"grid-item\"><marquee class=\"marq\" behavior=\"slide\" direction=\"left\" scrollamount=\"50\" loop=\"1\">".$row["book"]."</marquee></div>\n";
            }
            echo "</div>\n";
        }
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}
?>
