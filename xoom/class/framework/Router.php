<?php
/**
 * author:  Long Hai LH
 * date:    2020-10-08 21:42:25
 * licence: MIT
 */

class Router
{
    static $aExtRoutes = [];
    static $aUriRoutes = [];

    static function addExt($extension, $callback)
    {
        Router::$aExtRoutes["$extension"][] = $callback;
    }

    static function build ()
    {
        extract(Xoom::getConfig("rootdir"));

        $stop = false;
        if (array_key_exists(Request::$extension, Router::$aExtRoutes)) {
            $routes = Router::$aExtRoutes[Request::$extension];
            foreach($routes as $route) {
                if ($route && is_callable($route)) {
                    $stop = $route();
                    if ($stop) break;
                }    
            }
        }   

        if (!$stop) {
            if (Request::$bid != "") {
                Framework::add(8400, "Cms::showMedia");
            }
            elseif (is_file("$rootdir/public" .Request::$path)) {
                Framework::add(8400, "Response::sendStatic");
            }
            else {
                Framework::add(8400, "Response::send404");
            }    
        }

    }
    
    //@end
}