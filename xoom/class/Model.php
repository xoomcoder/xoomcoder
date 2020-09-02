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
        // FIXME: dynamic loading from all etc/*.sql files ?! 
        $sqlas = [
            "user.read"     => "SELECT * FROM user ORDER BY id DESC;",
            "content.read"  => "SELECT * FROM content ORDER BY datePublication DESC;",
            "manymany.read" => "SELECT * FROM manymany ORDER BY id DESC;",
            "db.create"     => File::content("xoom/etc/sql-table-create.sql"),
        ];

        return $sqlas[$key] ?? "";
    }

    static function insert ($table, $tokenas=[])
    {
        $cols = implode(",", array_keys($tokenas));
        $tokens = implode(", :", array_keys($tokenas));

        $sql = 
        <<<x
        INSERT INTO `$table`
        ( $cols )
        VALUES
        ( :$tokens );

        x;

        $pdoStatement = Model::sendSql($sql, $tokenas);
        return $pdoStatement;
    }

    static function read ($table, $column="", $search="", $sort="id DESC")
    {
        $tokenas    = [];
        $whereline  = "";
        $sortline   = "";
        if ($sort != "") $sortline = "ORDER BY $sort";
        if($column != "") {
            $whereline = "WHERE $column = :$column";
            $tokenas = [ $column => $search ];
        }

        $sql = 
        <<<x
        SELECT * FROM `$table`
        $whereline
        $sortline
        ;
        x;

        $pdoStatement = Model::sendSql($sql, $tokenas);
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    //@end
}