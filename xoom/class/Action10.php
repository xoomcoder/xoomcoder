<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-17 11:12:27
 * licence: MIT
 */

class Action10
{
    static function geocms ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user);

            $title = Form::filterText("title");
            Form::filterText("category");
            Form::filterText("template");
            Form::filterText("code");

            if (Form::isOK()) {
                // complete columns
                $now = date("Y-m-d H:i:s");
                Form::add("priority", 10);
                Form::add("id_user", $id);
                Form::add("username", $login);
                Form::add("datePublication", $now);
                Form::add("uri", File::filterName($title));

                Model::insert("geocms", Form::$formdatas);
                $geocms = Model::read("geocms", "id_user", $id);
                Form::mergeJson("data", [ "geocms" => $geocms]);

                Form::setFeedback("publication OK ($now): $title");
            }

        }
    }
    static function geocmsUpdate ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user, EXTR_PREFIX_ALL, "user");

            $now = date("Y-m-d H:i:s");

            $titleInput = Form::filterText("title");
            Form::filterText("category");
            Form::filterDatetime("datePublication");
            Form::filterText("template");
            Form::filterText("code");
            
            if (Form::isOK()) {
                $id = intval(Form::filterInput("id"));
                $found = Model::read("geocms", "id", $id);
                foreach($found as $line) {
                    extract($line);
                    if ($id_user == $user_id) {
                        // update colmuns
                        Form::add("uri", File::filterName($titleInput));
                        Form::add("priority", 10);
                        
                        Model::update("geocms", Form::$formdatas, $id);

                        Form::setFeedback("modification OK ($now) $title");

                        break; // security: should update only one line
                    }
                }

                $geocms = Model::read("geocms", "id_user", $user_id);
                Form::mergeJson("data", [ "geocms" => $geocms]);

            }

        }
    }

    static function geocmsDelete ($paramas)
    {
        extract($paramas);
        // $id
        if (($table ?? false) && ($id ?? false)) {

            $geocms = Model::read($table, "id", $id);
            foreach($geocms as $note) {
                extract($note);
                $iduser = intval($id_user ?? 0);

                // check if line belongs to user
                // FIXME: better extract to avoid variables collisions...
                extract(Controller::$user, EXTR_PREFIX_ALL, "user");
                $iduser0 = intval($user_id ?? 0);
                if (($iduser > 0) && ($iduser == $iduser0)) {
                    // ok this is his notes
                    Model::delete($table, $id);
                    File::deleteMedia($id);
                }
            }    
        }

    }

    //@end
}