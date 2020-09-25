<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-17 11:12:27
 * licence: MIT
 */

class Action100
{
    static function geocmsMenu ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user);

            $code = Form::filterText("code");
            if (Form::isOK()) {
                Form::setFeedback("working... $code");
                $tabCode = explode("\n", $code);
                foreach($tabCode as $line) {
                    $line = trim($line);
                    if ($line != "") {
                        MemberAct::process($line);
                    }
                }
            }
        }
    }

    static function geocms ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user);

            $title = Form::filterText("title");
            Form::filterText("category");
            Form::filterText("template", "", "optional=true");
            Form::filterInt("priority", $level);
            Form::filterNone("code");
            Form::filterText("status", "", "optional=true");
            if (Form::isOK()) {
                // complete columns
                $now = date("Y-m-d H:i:s");
                Form::add("id_user", $id);
                Form::add("username", $login);
                Form::add("datePublication", $now);
                Form::add("uri", Controller::filterFilename($title));

                Model::insert("geocms", Form::$formdatas);

                // media upload is managed after insert
                $lastId = Model::lastInsertId();
                $imageName = Form::filterMedia("image", $lastId);
                if ($imageName != "") {
                    // update the column
                    Model::update("geocms", [ "image" => $imageName], $lastId);
                }

                Action100::readCms();

                Form::setFeedback("Publication OK ($now) $title");
            }

        }
    }

    static function readCms ()
    {
        // not model
        $category   = Form::filterText("menuContext", "", "optional=true");
        $orderby    = "datePublication DESC, priority DESC, id DESC";
        $geocms     = Cms::read($category ?? "news", $orderby);
        Form::mergeJson("data", [ "geocms" => $geocms]);

    }

    static function geocmsUpdate ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user, EXTR_PREFIX_ALL, "user");

            $titleInput = Form::filterText("title");
            Form::filterText("category");
            Form::filterInt("priority", $user_level);
            Form::filterDatetime("datePublication");
            Form::filterText("template", "", "optional=true");
            Form::filterNone("code");
            Form::filterText("status", "", "optional=true");

            if (Form::isOK()) {
                $id = intval(Form::filterInput("id"));
                $found = Model::read("geocms", "id", $id);
                foreach($found as $line) {
                    extract($line);
                    if ($id_user == $user_id) {
                        // update columns
                        Form::add("uri", Controller::filterFilename($titleInput));
                        
                        // media upload is managed after insert
                        $imageName = Form::filterMedia("image", $id);
                        if ($imageName != "") {
                            // update the column
                            Form::add("image", $imageName);
                        }

                        Model::update("geocms", Form::$formdatas, $id);

                        $now = date("Y-m-d H:i:s");
                        Form::setFeedback("($now) modification OK... $title ($imageName)");
                    }
                }

                Action100::readCms();
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

    static function userDelete ($paramas)
    {
        extract($paramas);
        // $id
        if (($table ?? false) && ($id ?? false)) {

            $users = Model::read($table, "id", $id);
            foreach($users as $user) {
                extract($user);
                $iduser = intval($id ?? 0);

                // check if line belongs to user
                // FIXME: better extract to avoid variables collisions...
                extract(Controller::$user, EXTR_PREFIX_ALL, "user");
                $iduser0 = intval($user_id ?? 0);
                // dont kill your own account
                if (($iduser0 > 0) && ($iduser != $iduser0)) {
                    Model::delete($table, $id);
                }
            }    
        }
    }
    //@end
}