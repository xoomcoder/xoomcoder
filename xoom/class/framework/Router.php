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

    static function addUri($callback)
    {
        Router::$aUriRoutes[] = $callback;
    }

    static function build ()
    {
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
            foreach(Router::$aUriRoutes as $route) {
                if ($route && is_callable($route)) {
                    $stop = $route();
                    if ($stop) break;
                }
            }
        }

    }
    
    //@end
}