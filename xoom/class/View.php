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

        <script type="module">
        let annonces = $json;
        </script>

        x;
    }
    //@end
}