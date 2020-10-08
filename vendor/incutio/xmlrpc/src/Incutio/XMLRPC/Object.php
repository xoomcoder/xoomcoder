<?php

namespace Incutio\XMLRPC;

interface Object
{
    
    const STRUCT = 'struct';
    const ARR = 'array';
    const BOOL = 'boolean';
    const INT = 'int';
    const DOUBLE = 'double';
    const DATE = 'date';
    const BASE64 = 'bae64';
    const STR = 'string';
    
    /**
     * @return string xml格式
     */
    public function getXml();
}