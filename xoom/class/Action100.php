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
            Form::filterInt("priority", $level);
            Form::filterNone("code");
            if (Form::isOK()) {
                // complete columns
                $now = date("Y-m-d H:i:s");
                Form::add("id_user", $id);
                Form::add("username", $login);
                Form::add("datePublication", $now);
                Form::add("uri", Controller::filterFilename($title));

                Model::insert("geocms", Form::$formdatas);
                $geocms = Model::read("geocms", "id_user", $id, "category DESC, template DESC, priority DESC");
                Form::mergeJson("data", [ "geocms" => $geocms]);

                Form::setFeedback("Publication OK ($now) $title");
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
            Form::filterInt("priority", $level);
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
                        Form::add("uri", Controller::filterFilename($titleInput));
                        
                        Model::update("geocms", Form::$formdatas, $id);

                        $now = date("Y-m-d H:i:s");
                        Form::setFeedback("($now) modification OK... $title");
                    }
                }

                $geocms = Model::read("geocms", "id_user", $user_id, "category DESC, template DESC, priority DESC");
                Form::mergeJson("data", [ "geocms" => $geocms]);
            }

        }
    }

    //@end
}