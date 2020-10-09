<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-11 13:09:01
 * licence: MIT
 */

class Terminal
{
    static function runTerminal ()
    {
        $params = getopt("f:s:");
        extract($params);

        if ($f ?? false) {
            // https://www.php.net/manual/fr/function.getcwd.php
            $cwd = getcwd();
            $target = "$cwd/$f";
            if (is_file($target)) {
                $code = file_get_contents($target);
                AdminCommand::run($code, false, true);
            }

            echo <<<x
            $cwd/$f

            x;

        }

        if ($s ?? false) {
            extract(Xoom::getConfig("dbname,dbhost,dbuser,dbpassword"));

            $cmd = 
            <<<x
            mysqldump --no-tablespaces --host=$dbhost --user=$dbuser --password=$dbpassword $dbname > $s.sql
            x;

            echo 
            <<<x
            $cmd

            x;

            passthru($cmd);

        }
    }

    //@end
}