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
            // Test::log($dsn);
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
            "blocnote.read" => "SELECT * FROM blocnote ORDER BY id DESC;",
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

    static function read ($table, $column="", $search="", $sort="id DESC", $condition="=")
    {
        $tokenas    = [];
        $whereline  = "";
        $sortline   = "";
        if ($sort != "") $sortline = "ORDER BY $sort";
        if($column != "") {
            $whereline = "WHERE $column $condition :$column";
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
        if ($pdoStatement) {
            return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            // FIXME: maybe should raise en error ?!
            return [];
        }
    }

    static function delete ($table, $search, $column="id", $condition="=")
    {
        $pdoStatement = null;
        if ($column != "") {
            $sql = 
            <<<x
            DELETE FROM `$table`
            where $column $condition :$column
    
            x;
    
            $pdoStatement = Model::sendSql($sql, [ $column => $search ]);
        }
        return $pdoStatement;
    }

    static function update ($table, $tokenas=[], $search="", $colsearch="id", $condition="=")
    {
        if ($condition == "") $condition = "=";

        $cols = [];
        foreach($tokenas as $k => $v) {
            $cols[] = "$k = :$k"; 
        }
        $linecols = implode(", ", $cols);
        $selector = "$colsearch $condition :xxx$colsearch";
        $tokenas["xxx$colsearch"] = $search;

        $sql = 
        <<<x
        UPDATE `$table`
        SET
        $linecols
        WHERE
        $selector;

        x;

        $pdoStatement = Model::sendSql($sql, $tokenas);
        return $pdoStatement;
    }

    static function lastInsertId ()
    {
        $res = 0;
        if (Model::$pdo != null) {
            // https://www.php.net/manual/fr/pdo.lastinsertid.php
            $res = Model::$pdo->lastInsertId();
        }
        return $res;
    }
    //@end
}