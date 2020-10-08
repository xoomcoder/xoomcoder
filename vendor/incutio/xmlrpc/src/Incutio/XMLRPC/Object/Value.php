<?php

namespace Incutio\XMLRPC\Object;

use Incutio\XMLRPC\Object;

/**
 * @package IXR
 * @since 1.5
 *
 * @copyright  Incutio Ltd 2010 (http://www.incutio.com)
 * @version    1.7.4 7th September 2010
 * @author     Simon Willison
 * @link       http://scripts.incutio.com/xmlrpc/ Site/manual
 */
class Value implements Object
{

    protected $data;
    protected $type;

    public function __construct($data, $type = false)
    {
        $this->data = $data;
        if (!$type) {
            $type = $this->calculateType();
        }
        $this->type = $type;
        if ($type == static::STRUCT) {
            // Turn all the values in the array in to new IXR_Value objects
            foreach ($this->data as $key => $value) {
                $this->data[$key] = new Value($value);
            }
        }
        if ($type == static::ARR) {
            for ($i = 0, $j = count($this->data); $i < $j; $i++) {
                $this->data[$i] = new Value($this->data[$i]);
            }
        }
    }

    function calculateType()
    {
        if ($this->data === true || $this->data === false) {
            return static::BOOL;
        }
        if (is_integer($this->data)) {
            return static::INT;
        }
        if (is_double($this->data)) {
            return static::DOUBLE;
        }

        // Deal with IXR object types base64 and date
        if (is_object($this->data) && is_a($this->data, '\\Incutio\\XMLRPC\\Object\\Date')) {
            return static::DATE;
        }
        if (is_object($this->data) && is_a($this->data, '\\Incutio\\XMLRPC\\Object\\Base64')) {
            return static::BASE64;
        }

        // If it is a normal PHP object convert it in to a struct
        if (is_object($this->data)) {
            $this->data = get_object_vars($this->data);
            return static::STRUCT;
        }
        if (!is_array($this->data)) {
            return static::STR;
        }

        // We have an array - is it an array or a struct?
        if ($this->isStruct($this->data)) {
            return static::STRUCT;
        } else {
            return static::ARR;
        }
    }

    public function getXml()
    {
        // Return XML for this value
        switch ($this->type) {
            case static::BOOL:
                return '<boolean>' . (($this->data) ? '1' : '0') . '</boolean>';
                break;
            case static::INT:
                return '<int>' . $this->data . '</int>';
                break;
            case static::DOUBLE:
                return '<double>' . $this->data . '</double>';
                break;
            case static::STR:
                return '<string>' . htmlspecialchars($this->data) . '</string>';
                break;
            case static::ARR:
                $return = '<array><data>' . "\n";
                foreach ($this->data as $item) {
                    $return .= '  <value>' . $item->getXml() . "</value>\n";
                }
                $return .= '</data></array>';
                return $return;
                break;
            case static::STRUCT:
                $return = '<struct>' . "\n";
                foreach ($this->data as $name => $value) {
                    $return .= "  <member><name>$name</name><value>";
                    $return .= $value->getXml() . "</value></member>\n";
                }
                $return .= '</struct>';
                return $return;
                break;
            case static::DATE:
            case static::BASE64:
                return $this->data->getXml();
                break;
        }
        return false;
    }

    /**
     * Checks whether or not the supplied array is a struct or not
     *
     * @param unknown_type $array
     * @return boolean
     */
    function isStruct($array)
    {
        $expected = 0;
        foreach ($array as $key => $value) {
            if ((string) $key != (string) $expected) {
                return true;
            }
            $expected++;
        }
        return false;
    }

}
