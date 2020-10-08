<?php

namespace Incutio\XMLRPC;

use Incutio\XMLRPC\Object\Date;

/**
 * IXR_MESSAGE
 *
 * @package IXR
 * @since 1.5
 *
 */
class Message
{
    public $message;
    public $messageType;  // methodCall / methodResponse / fault
    public $faultCode;
    public $faultString;
    public $methodName;
    public $params;

    // Current variable stacks
    private $arraystructs = array();   // The stack used to keep track of the current array/struct
    private $arraystructstypes = array(); // Stack keeping track of if things are structs or array
    private $currentStructName = array();  // A stack as well
    private $param;
    private $value;
    private $currentTag;
    private $currentTagContents;
    // The XML parser
    private $parser;

    public function __construct($message)
    {
        $this->message =& $message;
    }

    public function parse()
    {
        // first remove the XML declaration
        // merged from WP #10698 - this method avoids the RAM usage of preg_replace on very large messages
        $header = preg_replace( '/<\?xml.*?\?'.'>/', '', substr($this->message, 0, 100), 1);
        $this->message = substr_replace($this->message, $header, 0, 100);
        if (trim($this->message) == '') {
            return false;
        }
        $this->parser = xml_parser_create();
        // Set XML parser to take the case of tags in to account
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
        // Set XML parser callback functions
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'tag_open', 'tag_close');
        xml_set_character_data_handler($this->parser, 'cdata');
        $chunk_size = 262144; // 256Kb, parse in chunks to avoid the RAM usage on very large messages
        do {
            if (strlen($this->message) <= $chunk_size) {
                $final = true;
            }
            $part = substr($this->message, 0, $chunk_size);
            $this->message = substr($this->message, $chunk_size);
            if (!xml_parse($this->parser, $part, $final)) {
                return false;
            }
            if ($final) {
                break;
            }
        } while (true);
        xml_parser_free($this->parser);

        // Grab the error messages, if any
        if ($this->messageType == 'fault') {
            $this->faultCode = $this->params[0]['faultCode'];
            $this->faultString = $this->params[0]['faultString'];
        }
        return true;
    }

    function tag_open($parser, $tag, $attr)
    {
        $this->currentTagContents = '';
        $this->currentTag = $tag;
        switch($tag) {
            case 'methodCall':
            case 'methodResponse':
            case 'fault':
                $this->messageType = $tag;
                break;
                /* Deal with stacks of arrays and structs */
            case 'data':    // data is to all intents and puposes more interesting than array
                $this->arraystructstypes[] = 'array';
                $this->arraystructs[] = array();
                break;
            case 'struct':
                $this->arraystructstypes[] = 'struct';
                $this->arraystructs[] = array();
                break;
        }
    }

    function cdata($parser, $cdata)
    {
        $this->currentTagContents .= $cdata;
    }

    function tag_close($parser, $tag)
    {
        $valueFlag = false;
        switch($tag) {
            case 'int':
            case 'i4':
                $value = (int)trim($this->currentTagContents);
                $valueFlag = true;
                break;
            case 'double':
                $value = (double)trim($this->currentTagContents);
                $valueFlag = true;
                break;
            case 'string':
                $value = (string)trim($this->currentTagContents);
                $valueFlag = true;
                break;
            case 'dateTime.iso8601':
                $value = new Date(trim($this->currentTagContents));
                $valueFlag = true;
                break;
            case 'value':
                // "If no type is indicated, the type is string."
                if (trim($this->currentTagContents) != '') {
                    $value = (string)$this->currentTagContents;
                    $valueFlag = true;
                }
                break;
            case 'boolean':
                $value = (boolean)trim($this->currentTagContents);
                $valueFlag = true;
                break;
            case 'base64':
                $value = base64_decode($this->currentTagContents);
                $valueFlag = true;
                break;
                /* Deal with stacks of arrays and structs */
            case 'data':
            case 'struct':
                $value = array_pop($this->arraystructs);
                array_pop($this->arraystructstypes);
                $valueFlag = true;
                break;
            case 'member':
                array_pop($this->currentStructName);
                break;
            case 'name':
                $this->currentStructName[] = trim($this->currentTagContents);
                break;
            case 'methodName':
                $this->methodName = trim($this->currentTagContents);
                break;
        }

        if ($valueFlag) {
            if (count($this->arraystructs) > 0) {
                // Add value to struct or array
                if ($this->arraystructstypes[count($this->arraystructstypes)-1] == 'struct') {
                    // Add to struct
                    $this->arraystructs[count($this->arraystructs)-1][$this->currentStructName[count($this->currentStructName)-1]] = $value;
                } else {
                    // Add to array
                    $this->arraystructs[count($this->arraystructs)-1][] = $value;
                }
            } else {
                // Just add as a paramater
                $this->params[] = $value;
            }
        }
        $this->currentTagContents = '';
    }
}

