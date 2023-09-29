<?php

namespace DevCoder;

class DotEnv
{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    protected $path;


    public function __construct(string $path)
    {
        if(!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        $this->path = $path;
    }

    public function load() :void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

use DevCoder\DotEnv;
(new DotEnv(__DIR__ . '/.env'))->load();

$conn = pg_connect("dbname=".getenv("SQL_DB")." "."user=".getenv("SQL_USER")." "."password=".getenv("SQL_PASSWORD")." "."host=".getenv("SQL_HOST"));

if ($conn) {

echo 'Connection attempt to database succeeded.'. '<br>';
echo 'DBNAME: ' . getenv("SQL_DB") . '<br>';
echo 'DBTABLE: ' . getenv("SQL_TABLE") . '<br>';
echo 'DBPASSWORD: ' . getenv("SQL_PASSWORD") . '<br>';
echo 'DBUSER: ' . getenv("SQL_USER") . '<br>';
echo 'DBHOST: ' . getenv("SQL_HOST") . '<br>';

// estas sentencias se ejecutarán como una sola transacción

$query = "SELECT * FROM " .getenv("SQL_TABLE") . ";";
$res = pg_query($conn, $query);
if ($res) {
    echo 'Connection attempt to table ' . getenv("SQL_TABLE") . ' succeeded.'. '<br>';
    
} else {

echo 'Connection attempt to table ' . getenv("SQL_TABLE") . ' failed.'. '<br>';

}

} else {
    
echo 'Connection attempt to database  failed.'. '<br>';
echo 'DBNAME: ' . getenv("SQL_DB") . '<br>';
echo 'DBTABLE: ' . getenv("SQL_TABLE") . '<br>';
echo 'DBPASSWORD: ' . getenv("SQL_PASSWORD") . '<br>';
echo 'DBUSER: ' . getenv("SQL_USER") . '<br>';
echo 'DBHOST: ' . getenv("SQL_HOST") . '<br>';

}

pg_close($conn);

?>