<?php

namespace Incutio\XMLRPC\Object;

use Incutio\XMLRPC\Object;

/**
 * IXR_Base64
 *
 * @package IXR
 * @since 1.5
 */
class Base64 implements Object
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getXml()
    {
        return '<base64>'.base64_encode($this->data).'</base64>';
    }
}