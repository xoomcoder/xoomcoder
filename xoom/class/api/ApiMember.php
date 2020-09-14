<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-03 21:05:06
 * licence: MIT
 */

class ApiMember
{
    static function run ()
    {
        if (Controller::checkMemberToken())
        {
            // FIXME: filter HTML tags with strip_tags...
            $title   = Form::filterInput("title");
            $note    = Form::filterInput("note");
            $note2   = Form::filterInput("note2");
            if ($note != "") {
                MemberAct::save($title, $note);
                MemberAct::run($note);    
            }
            if ($note2 != "") {
                MemberAct::run($note2, false, true);
            }
            
            // extract(Controller::$user);
            //$blocnotes = Model::read("blocnote", "id_user", $id);
            //Form::mergeJson("data", [ "blocnote" => $blocnotes]);

            $now = date("Y-m-d H:i:s");
            Form::setFeedback("($now)...");
        }
        else
        {
            Form::setFeedback("Sorry...");
        }

    }

    static function runVue ()
    {
        if (Controller::checkMemberToken())
        {
            $compoName = Form::filterText("compoName");
            if (Form::isOK()) {
                $command = "VueComponent::$compoName";
                if (is_callable($command)) {
                    $compoCode = $command();
                    Form::addJson($compoName, $compoCode);  
                }
                if ($compoName == "mypage") {
                    Form::addJson("xcompo", [ 
                        "xform" => "xform.vue",
                        "xmap" => "xmap.vue",
                        "xlist" => "xlist.vue",
                        "xedit" => "xedit.vue",
                        "xfiles" => "xfiles.vue",
                    ]);
                }
            }
        }
    }

    static function geocms ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user);

            Form::filterText("title");
            Form::filterText("category");
            if ($level >= "100") {
                Form::filterNone("code");
            }
            else {
                Form::filterText("code");
            }
            if (Form::isOK()) {
                $now = date("Y-m-d H:i:s");
                Form::add("id_user", $id);
                Form::add("username", $login);
                Form::add("datePublication", $now);

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

            Form::filterText("title");
            Form::filterText("category");
            Form::filterDatetime("datePublication");
            if ($level >= "100") {
                Form::filterNone("code");
            }
            else {
                Form::filterText("code");
            }
            if (Form::isOK()) {
                $id = intval(Form::filterInput("id"));
                $found = Model::read("geocms", "id", $id);
                foreach($found as $line) {
                    extract($line);
                    if ($id_user == $user_id) {
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