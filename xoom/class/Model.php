<?php
/**
 * author:  Long Hai LH
 * date:    2020-08-31 11:45:20
 * licence: MIT
 */

class Model
{
    static $pdo     = null;
    static $logs    = [];

    static function sendSql ($request, $tokenas=[])
    {
        if (Model::$pdo == null) {
            extract(Xoom::getConfig("dbhost,dbport,dbname,dbuser,dbpassword"));
            // FIXME: $dbname must exists
            $dsn = "mysql:host=$dbhost;port=$dbport;dbname=$dbname;charset=utf8";
            Test::log($dsn);
            try {
                Model::$pdo = new PDO($dsn, $dbuser, $dbpassword);
            } catch (PDOException $e) {
                Test::log('error: ' . $e->getMessage());
            }
        }
        
        $pdoStatement = null;
        if (Model::$pdo != null) {
            $pdoStatement = Model::$pdo->prepare($request);
            $pdoStatement->execute($tokenas);
    
            // https://www.php.net/manual/fr/function.ob-start.php
            ob_start();
            // https://www.php.net/manual/fr/pdostatement.debugdumpparams.php
            $pdoStatement->debugDumpParams();
            // https://www.php.net/manual/fr/function.ob-get-clean.php
            $log = ob_get_clean();
    
            Model::$logs[] = $log;    
        }
        return $pdoStatement;
    }

    static function getSql ($key)
    {
        $sqlas = [
            "user read" => "SELECT * FROM user ORDER BY id DESC;",
        ];

        return $sqlas[$key] ?? "";
    }
    //@end
}