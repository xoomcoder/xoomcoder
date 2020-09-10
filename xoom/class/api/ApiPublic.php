<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-10 19:33:45
 * licence: MIT
 */

class ApiPublic
{
    // warning: can be very dangerous
    static function photoRemove ()
    {
        $tag = Form::filterLetter("tag");
        extract(pathinfo($tag));
        $mediafile = Xoom::$rootdir . "/public/assets/square/$filename.jpg";
        if (is_file($mediafile)) {
            // warning: delete file
            unlink($mediafile);
        }
    } 
    //@end
}