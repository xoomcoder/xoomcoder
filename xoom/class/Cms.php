<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-20 18:00:47
 * licence: MIT
 */

class Cms
{
    static function read ($category="news", $orderby="")
    {
        $orderbyline = "";
        $orderby = trim($orderby);
        if ($orderby) $oderbyline = "ORDER BY $orderby";

        $sql =
        <<<x
        SELECT * FROM geocms
        WHERE 
        category = :category
        AND 
        priority >= 100 
        $orderbyline

        x;

        $tokens = [
            "category"  => $category,
        ];
        $notes = Model::sendSql($sql, $tokens);
        return $notes->fetchAll(PDO::FETCH_ASSOC);
    }

    static function showNews ()
    {
        $users = Model::read("user", "level", 100);
        foreach($users as $user) {
            extract($user, EXTR_PREFIX_ALL, "user");
            // $notes = Model::read("geocms", "id_user", $user_id);
            $sql =
            <<<x
            SELECT * FROM geocms
            WHERE 
            id_user = :id_user
            AND 
            category = :category
            AND 
            priority >= 100 
            ORDER BY 
            datePublication DESC

            x;

            $tokens = [
                "id_user"   => $user_id,
                "category"  => "news",
            ];
            $notes = Model::sendSql($sql, $tokens);

            $html = "";
            foreach($notes as $note) {
                $html .= News::buildHtml($note);       
            }
            echo $html;

        }
            
    }

    //@end
}