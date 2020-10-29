<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-20 18:00:47
 * licence: MIT
 */

class Cms
{
    static function load ()
    {
        Router::addExt("html",  "Cms::routeHtml");
        Router::addExt("vjs",   "Cms::routeVjs");
        Router::addExt("jpg",   "Cms::routeJpg");

        Router::addUri("Cms::routeStatic");

    }

    static function routeStatic ()
    {
        extract(Xoom::getConfig("rootdir"));
        if (is_file("$rootdir/public" .Request::$path)) {
            Framework::add(8400, "Response::sendStatic");
        }
        else {
            Framework::add(8400, "Response::send404");
        }    
        return false; // continue
    }

    static function routeHtml ()
    {
        Framework::add(6200, "Cms::findTemplate");
        Framework::add(6400, "Cms::sendResponse");
        return true; // stop
    }

    static function routeVjs ()
    {
        header("Content-Type: application/javascript");

        Framework::add(6200, "Cms::findTemplate");
        Framework::add(6400, "Cms::sendResponse");
        return true; // stop
    }

    static function routeJpg ()
    {
        extract(Xoom::getConfig("rootdir"));
        if (Request::$bid != "") {
            Framework::add(6400, "Cms::showMedia");
        }
        elseif (is_file("$rootdir/public" . Request::$path)) {
            Framework::add(6400, "Response::sendStatic");
        }
        elseif (Request::$extension == "jpg") {
            Framework::add(6400, "Cms::sendPhoto");
        }
        return true; // stop
    }

    static function process ()
    {

    }
    
    // 9000: sendResponse
    static function api ()
    {
        Framework::add(7200, "Form::log");
        Framework::add(7400, "Form::process");
        Framework::add(7600, "Form::sendJSON");
    }

    static function read ($category="news", $orderby="")
    {
        extract(Controller::$user);

        $orderbyline = "";
        $orderby = trim($orderby);
        if ($orderby) $orderbyline = "ORDER BY $orderby";

        $sql =
        <<<x
        SELECT * FROM geocms
        WHERE 
        category = :category
        AND 
        priority >= 100 
        AND 
        id_user = :id_user
        $orderbyline

        x;

        $tokens = [
            "category"  => $category,
            "id_user"   => $id,
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

    static function showNews2 ()
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
                $html .= News::buildHtml2($note);       
            }
            echo $html;

        }
            
    }

    static function findTemplate()
    {
        // if there's a page
        extract(Xoom::getConfig("rootdir,contentdir,filename,bid"));
        $pages = [
            "$contentdir/templates/pages/$filename.php",
            "$rootdir/xoom-pages/$filename.php",
        ];
        $foundpage = false;
        foreach($pages as $pagefile) {
            if (is_file($pagefile)) {
                // special template
                Response::$template = [ 
                    "$pagefile", 
                ];
                $foundpage = true;
                break; // only the first
            }            
        }
        if (!$foundpage) {
            extract(Xoom::getConfig("rootdir,contentdir"));
            // FIXME: add folders in config files
            $contents = [
                "$contentdir/templates/content/$filename.php",  
                "$rootdir/xoom-templates/contenu-$filename.php",
            ];
            foreach($contents as $contentfile) {
                if (is_file($contentfile)) {
                    Response::$template = [ 
                        "$rootdir/xoom-templates/header.php", 
                        $contentfile, 
                        "$rootdir/xoom-templates/footer.php", 
                    ];
                    $foundpage = true;
                    break; // only the first
                }
            }
        }
        if (!$foundpage) {
            // WARNING: SHOULD FILTER MEMBER CONTENT
            if ($bid != "") {
                $geocms_id = Response::name2id($bid);
                $lines = Model::read("geocms", "id", $geocms_id, "priority DESC");
            }
            else {
                $lines = Model::read("geocms", "uri", $filename, "priority DESC");
            }
            foreach($lines as $line) {
                Response::$contents["dbline"] = $line;

                extract($line);
                // $template
                if ($template ?? false) {
                    // add some security
                    $template = pathinfo($template, PATHINFO_FILENAME);
                }
                else {
                    $template = "default";
                }

                Response::$template = [ 
                    "$rootdir/xoom-templates/template-$template.php", 
                ];
                break;  // only one
            }
        }
    }

    static function sendResponse()
    {
        // https://www.php.net/manual/fr/control-structures.foreach.php
        foreach (Response::$template as $file) {
            if (is_file($file)) {
                // https://www.php.net/manual/fr/function.require-once.php
                include $file;
            }
            else {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                // look in geocms if there's a template
                // $lines = Model::read("geocms", "template", $filename, "priority DESC");
                $sql =
                <<<x
                SELECT * FROM geocms
                WHERE
                template LIKE :template
                AND priority >= :priority
                ORDER BY priority DESC
                x;
                $tag = str_replace("template-", "", $filename);
                $tokens = [
                    "template" => "template%-$tag%",
                    "priority" => 100,
                ];

                $lines = [];
                $pdoStatement = Model::sendSql($sql, $tokens);
                if ($pdoStatement) $lines = $pdoStatement->fetchAll();
                //$pdoStatement->debugDumpParams();

                foreach($lines as $line) {
                    extract($line);
                    $priority = intval($priority ?? 0);
                    if ($priority >= 100) {             // security: template are built by webmasters
                        // warning: this is running PHP code stored in database...
                        AdminCommand::runLocal($code);
                    }
                }

                if(empty($line)) {
                    // no template found...
                }
            }
        }
    }

    static function htmlHeader ()
    {
        $canonical = Xoom::$canonical;
        $uri = "https://xoomcoder.com/$canonical";        

        $geocms = Response::$contents["dbline"] ?? []; 
        if (!empty($geocms)) {
            extract($geocms);
            $bid    = Response::id2name($id);

            $noindex = '<meta name="robots" content="index">';
            $codelength = mb_strlen($code);
            if ($codelength > 2000) {
                // $noindex = "";  // index content as big enough
            }

            $ogtitle = str_replace('"', '', $title);

            $res = 
            <<<x
            $noindex

            <meta property="og:title" content="$ogtitle">
            <meta property="og:description" content="$ogtitle - XoomCoder - Formation Développeur Fullstack à Distance">
            <meta property="og:url" content="https://xoomcoder.com/$uri--$bid">
            <meta property="og:image" content="https://xoomcoder.com/$uri--$bid.jpg">
            <meta property="og:image:alt" content="$ogtitle">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="XoomCoder">

            <meta name="description" content="$ogtitle - XoomCoder - Formation Développeur Fullstack à Distance">
            <title>$title - XoomCoder Formation</title>
            <link rel="canonical" href="https://xoomcoder.com/$uri--$bid">
            x;
    
        }
        else {
            if ($canonical == "") $canonical = "Accueil";

            $title = $canonical;

            $ogtitle = str_replace('"', '', $title);

            $res = 
            <<<x

            <meta property="og:title" content="$ogtitle">
            <meta property="og:description" content="$ogtitle - XoomCoder - Formation Développeur Fullstack à Distance">
            <meta property="og:url" content="$uri">
            <meta property="og:image" content="https://xoomcoder.com/$uri.jpg">
            <meta property="og:image:alt" content="$ogtitle">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="XoomCoder">

            <meta name="description" content="$canonical - XoomCoder - Formation Développeur Fullstack à Distance">
            <title>$canonical - XoomCoder * Formation Développeur Fullstack à Distance</title>
            <link rel="canonical" href="$uri">
            x;
        }

        echo $res;
    }

    static function showBodyClass ()
    {
        echo Xoom::$filename;
    }

    static function showMedia ()
    {
        extract(Xoom::getConfig("rootdir"));

        $searchBid      = Request::$bid;
        $searchMedia    = "$rootdir/xoom-data/media/*/my-*-$searchBid.*";
        $fileMedia      = glob($searchMedia);
        $status         = false;
        if (count($fileMedia) > 0) {
            $searchId = Response::name2id($searchBid);
            $lines = Model::read("geocms", "id", $searchId);
            foreach($lines as $line) {
                extract($line);
                // $status

                // get b64
                $b64 = Form::filterText("b64");
                if ($datePublication == trim(base64_decode($b64))) $status = "public";
            }
        }
        if ($status == "public") {
            foreach($fileMedia as $file) {
                $mimetype = Response::getMime($file);
                extract(pathinfo($file));

                header("X-Robots-Tag: noindex");
                // $ext = ($extension ?? false) ? ".$extension" : "";
                // header("Link: <https://xoomcoder.com/--$searchBid$ext" . '>; rel="canonical"');
                header("Content-Type: $mimetype");
                readfile($file);
            }    
        }

    }

    static function sendPhoto ()
    {
        if (Request::$bid == "") {
            $file = News::getPhotos(Request::$filename);
            if (is_file($file)) {
                header("Content-Type: image/jpeg");
                readfile($file);
            }    
        }
    }
    //@end
}