<?php
/**
 * author:  Long Hai LH
 * date:    2020-08-31 11:47:22
 * licence: MIT
 */

class Controller
{
    static $user = [];

    static function checkMemberToken ()
    {
        $result = false;
        $loginToken = Form::filterInput("loginToken");
        $infos = explode(",", $loginToken);
        if (count($infos) == 5) {
            list($login0, $level0, $id0, $time0, $signature0) = $infos;
            $payload    = "$login0,$level0,$id0,$time0";
            $users = Model::read("user", "id", $id0);
            foreach($users as $user) {
                extract($user);
                if (password_verify("$payload$password", $signature0)) {
                    $result = true;
                    Controller::$user = $user;
                }
                break; // should be only one ?
            }

        }
        return $result;
    }
    
    static function filterFilename ($name)
    {
        $nameOK = trim($name);
        $nameOK = rawurldecode($nameOK);    // accents from URLs...
        $nameOK = File::removeAccents($nameOK);
        $nameOK = strtolower(preg_replace("/[^a-zA-Z0-9]/", "-", $nameOK));
        $nameOK = preg_replace("/[-]+/", "-", $nameOK);
        $nameOK = trim($nameOK, "-");

        return $nameOK;
    }

    //@end
}