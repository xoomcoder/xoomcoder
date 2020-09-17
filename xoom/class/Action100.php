<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-17 11:12:27
 * licence: MIT
 */

class Action100
{
    static function geocms ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user);

            $title = Form::filterText("title");
            Form::filterText("category");
            Form::filterText("template", "", "optional=true");
            if ($level >= "100") {
                Form::filterNone("code");
            }
            else {
                Form::filterText("code");
            }
            if (Form::isOK()) {
                // complete columns
                $now = date("Y-m-d H:i:s");
                Form::add("id_user", $id);
                Form::add("username", $login);
                Form::add("datePublication", $now);
                Form::add("uri", File::filterName($title));

                Model::insert("geocms", Form::$formdatas);
                $geocms = Model::read("geocms", "id_user", $id);
                Form::mergeJson("data", [ "geocms" => $geocms]);

                Form::setFeedback("publication OK...");
            }

        }
    }
    static function geocmsUpdate ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user, EXTR_PREFIX_ALL, "user");

            $titleInput = Form::filterText("title");
            Form::filterText("category");
            Form::filterDatetime("datePublication");
            Form::filterText("template", "", "optional=true");
            Form::filterNone("code");

            if (Form::isOK()) {
                $id = intval(Form::filterInput("id"));
                $found = Model::read("geocms", "id", $id);
                foreach($found as $line) {
                    extract($line);
                    if ($id_user == $user_id) {
                        // update colmuns
                        Form::add("uri", File::filterName($titleInput));
                        
                        Model::update("geocms", Form::$formdatas, $id);
                    }
                }

                $geocms = Model::read("geocms", "id_user", $user_id);
                Form::mergeJson("data", [ "geocms" => $geocms]);

                Form::setFeedback("modification OK...");
            }

        }
    }

    //@end
}