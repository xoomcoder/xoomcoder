<?php
/**
 * author:  Long Hai LH
 * date:    2020-08-31 11:47:41
 * licence: MIT
 */

class View
{
    static function show($table)
    {
        $users = Model::read("user", "level", 100);
        $allnotes = [];
        foreach($users as $user) {
            extract($user, EXTR_PREFIX_ALL, "user");
            $notes = Model::read("blocnote", "id_user", $user_id);
            foreach($notes as $note) {
                extract($note);
                echo
                <<<x

                <article class="annonce">
                    <h3>$title</h3>
                    <pre>$code</pre> 
                </article>
                x;
                
                $allnotes[] = $note;
            }
        }

        $json = json_encode($allnotes, JSON_PRETTY_PRINT);
        echo 
        <<<x

        <script>
        let annonces = $json;
        </script>

        x;
    }

    static function showMD ($filename)
    {
        // FIXME: add path in config...
        extract(Xoom::getConfig("contentdir"));
        $file = "$contentdir/markdown/$filename.md";
        if (($file!= "") && is_file($file)) {
            $code = file_get_contents($file);
            $Parsedown = new Parsedown();

            echo $Parsedown->text($code); # prints: <p>Hello <em>Parsedown</em>!</p>    
        }
    }

    // run xoom commands
    static function showBlocMD ($filename)
    {
        $out = [];
        extract(Xoom::getConfig("contentdir"));
        $file = "$contentdir/markdown/$filename.md";
        if (($file!= "") && is_file($file)) {
            $cmd = file_get_contents($file);
            $out = View::parseBlocMD($cmd);
        }
        return $out;
    }

    static function parseBlocMD ($cmd)
    {
        AdminCommand::run($cmd, false, true);
        $meta = json_decode(AdminCommand::getBloc("meta", "{}"), true);
        $out["meta"] = $meta;

        $code = AdminCommand::getBloc("markdown");
        $Parsedown = new Parsedown();
        $out["result"] = $Parsedown->text($code); # prints: <p>Hello <em>Parsedown</em>!</p>    
        return $out;
    }
    //@end
}