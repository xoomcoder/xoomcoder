<?php
/**
 * author:  Long Hai LH
 * date:    2020-10-08 21:42:25
 * licence: MIT
 */

class Router
{
    static function build ()
    {
        extract(Xoom::getConfig("rootdir"));

        if (Request::$extension == "html") {
            Framework::add(8200, "Response::findTemplate");
            Framework::add(8400, "Cms::sendResponse");
        }
        elseif (Request::$extension == "vjs") {
            header("Content-Type: application/javascript");

            Framework::add(8200, "Response::findTemplate");
            Framework::add(8400, "Cms::sendResponse");
        }
        elseif (Request::$bid != "") {
            Framework::add(8400, "Cms::showMedia");
        }
        elseif (is_file("$rootdir/public" .Request::$path)) {
            Framework::add(8400, "Response::sendStatic");
        }
        elseif (Request::$extension == "jpg") {
            Framework::add(8400, "Cms::sendPhoto");
        }
        else {
            Framework::add(8400, "Response::send404");
        }

    }
    
    //@end
}