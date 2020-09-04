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
            
            extract(Controller::$user);
            $blocnotes = Model::read("blocnote", "id_user", $id);
            Form::mergeJson("data", [ "blocnote" => $blocnotes]);

            $now = date("Y-m-d H:i:s");
            Form::setFeedback("($now)...");
        }
        else
        {
            Form::setFeedback("Sorry...");
        }

    }
    //@end
}