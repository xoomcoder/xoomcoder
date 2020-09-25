<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-03 21:05:06
 * licence: MIT
 */

class ApiMember
{
    /**
     * 
     * MAGIC STATIC METHOD (FACADE)
     * automatic forward depending on token level  
     */     
    static function __callStatic($name, $arguments)
    {
        if (Controller::checkMemberToken())
        {
            extract(Controller::$user);
            if (($level < 100) && ($level >= 10))
                $action = "Action10::$name";
            if ($level >= 100)
                $action = "Action100::$name";

            if (is_callable($action ?? "")) 
                return $action(...$arguments);
        }
        
    }

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
                    // FIXME: should not be manual...
                    // add register for each component
                    Form::addJson("xcompo", [ 
                        "xeditoast" => "xeditoast.vue",
                        "xform"     => "xform.vue",
                        "xmap"      => "xmap.vue",
                        "xlist"     => "xlist.vue",
                        "xedit"     => "xedit.vue",
                        "xfiles"    => "xfiles.vue",
                        "xmenu"     => "xmenu.vue",
                    ]);
                }
            }
        }
    }

    //@end
}