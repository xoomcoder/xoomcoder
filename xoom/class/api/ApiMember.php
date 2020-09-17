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
            if (($level < 100) && ($level >= 10))
                $action = "Action10::geocms";
            if ($level >= 100)
                $action = "Action100::geocms";

            if (is_callable($action ?? "")) $action();
        }
    }
    static function geocmsUpdate ()
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user);
            if (($level < 100) && ($level >= 10))
                $action = "Action10::geocmsUpdate";
            if ($level >= 100)
                $action = "Action100::geocmsUpdate";
                
            if (is_callable($action ?? "")) $action();
        }
    }

    //@end
}