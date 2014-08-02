<?php

namespace Munisense\Zigbee;

class Buffer
  {
  //                                Id                   Name			Type		Description
  private static $datatypes = array(0x08	=> array("Data8",		"discrete"),	// Data 8bits
                                    0x09	=> array("Data16",		"discrete"),	// Data 16bits
                                    0x0a	=> array("Data24",		"discrete"),	// Data 24bits
                                    0x0b	=> array("Data32",		"discrete"),	// Data 32bits
                                    0x0c	=> array("Data40",		"discrete"),	// Data 40bits
                                    0x0d	=> array("Data48",		"discrete"),	// Data 48bits
                                    0x0e	=> array("Data56",		"discrete"),	// Data 56bits
                                    0x0f	=> array("Data64",		"discrete"),	// Data 64bits
                                    0x10	=> array("Boolean",		"discrete"),	// Boolean
                                    0x18	=> array("Bitmap8",		"discrete"),	// Bitmap 8bits
                                    0x19	=> array("Bitmap16",		"discrete"),	// Bitmap 16bits
                                    0x1a	=> array("Bitmap24",		"discrete"),	// Bitmap 24bits
                                    0x1b	=> array("Bitmap32",		"discrete"),	// Bitmap 32bits
                                    0x1c	=> array("Bitmap40",		"discrete"),	// Bitmap 40bits
                                    0x1d	=> array("Bitmap48",		"discrete"),	// Bitmap 48bits
                                    0x1e	=> array("Bitmap56",		"discrete"),	// Bitmap 56bits
                                    0x1f	=> array("Bitmap64",		"discrete"),	// Bitmap 64bits
                                    0x20	=> array("Int8u",		"analog"),	// Unsigned Integer 8bits
                                    0x21	=> array("Int16u",		"analog"),	// Unsigned Integer 16bits
                                    0x22	=> array("Int24u",		"analog"),	// Unsigned Integer 24bits
                                    0x23	=> array("Int32u",		"analog"),	// Unsigned Integer 32bits
                                    0x24	=> array("Int40u",		"analog"),	// Unsigned Integer 40bits
                                    0x25	=> array("Int48u",		"analog"),	// Unsigned Integer 48bits
                                    0x26	=> array("Int56u",		"analog"),	// Unsigned Integer 56bits
                                    0x27	=> array("Int64u",		"analog"),	// Unsigned Integer 64bits
                                    0x28	=> array("Int8s",		"analog"),	// Signed Integer 8bits
                                    0x29	=> array("Int16s",		"analog"),	// Signed Integer 16bits
                                    0x2a	=> array("Int24s",		"analog"),	// Signed Integer 24bits
                                    0x2b	=> array("Int32s",		"analog"),	// Signed Integer 32bits
                                    0x2c	=> array("Int40s",		"analog"),	// Signed Integer 40bits
                                    0x2d	=> array("Int48s",		"analog"),	// Signed Integer 48bits
                                    0x2e	=> array("Int56s",		"analog"),	// Signed Integer 56bits
                                    0x2f	=> array("Int64s",		"analog"),	// Signed Integer 64bits
                                    0x30	=> array("Enum8",		"discrete"),	// Enumeration 8bits
                                    0x31	=> array("Enum16",		"discrete"),	// Enumeration 16bits
                                    0x38	=> array("Float16",		"analog"),	// Floating Point 16bits
                                    0x39	=> array("Float32",		"analog"),	// Floating Point 32bits
                                    0x3a	=> array("Float64",		"analog"),	// Floating Point 64bits
                                    0x41	=> array("OctetString",		"discrete"),	// Octet String
                                    0x42	=> array("CharString",		"discrete"),	// Character String
                                    0x43	=> array("OctetStringLong",	"discrete"),	// Long Octet String
                                    0x44	=> array("CharStringLong",	"discrete"),	// Long Character String
                                    0x48	=> array("Array",		"discrete"),	// Array
                                    0x4c	=> array("Structure",		"discrete"),	// Structure
                                    0x50	=> array("Set",			"discrete"),	// Set
                                    0x51	=> array("Bag",			"discrete"),	// Bag
                                    0xe0	=> array("TimeOfDay",		"analog"),	// Time of Day
                                    0xe1	=> array("Date",		"analog"),	// Date
                                    0xe2	=> array("UTC",			"analog"),	// UTC
                                    0xe8	=> array("ClusterId",		"discrete"),	// ClusterId
                                    0xe9	=> array("AttributeId",		"discrete"),	// AttributeId
                                    0xea	=> array("BACnetOID",		"discrete"),	// BacNET OID
                                    0xf0	=> array("Ieee64",		"discrete"),	// Ieee64
                                    0xf1	=> array("Key128",		"discrete"),	// Security Key 128bits
                                    0xff	=> array("Unknown",		"discrete"));	// Unknown

  static function isDiscreteDatatype($datatype)
    {
    if(isset(self::$datatypes[$datatype][1]))
      return self::$datatypes[$datatype][1] === "discrete" ? true : false;
    else
      return null;
    }

  static function isAnalogDatatype($datatype)
    {
    if(isset(self::$datatypes[$datatype][1]))
      return self::$datatypes[$datatype][1] === "analog" ? true : false;
    else
      return null;
    }

  static function displayDatatypeDescription($datatype)
    {
    if(isset(self::$datatypes[$datatype][0]))
      return self::$datatypes[$datatype][0];
    else
      return null;
    }

  static function displayDatatype($datatype, $value)
    {
    if(isset(self::$datatypes[$datatype][0]))
      {
      $function = "display".self::$datatypes[$datatype][0];

      if(method_exists("buffer", $function))
        return self::$function($value);
      }

    return $value;
    }

  static function packDatatype(&$buffer, $datatype, $value, &$length = 0, &$raw_value = null)
    {
    if(preg_match("/^0x([0-9a-f]+)/i", $datatype, $match))
      $datatype = self::hexdec($match[1]);

    if(isset(self::$datatypes[$datatype][0]))
      {
      $function = "pack".self::$datatypes[$datatype][0];
      $start_length = strlen($buffer);
      $output = self::$function($buffer, $value);
      $length = strlen($buffer) - $start_length;

      $raw_value = substr($buffer, $start_length, $length);

      return $output;
      }
    else
      return null;
    }

  static function unpackDatatype(&$buffer, $datatype, &$length = 0, &$raw_value = null)
    {
    if(preg_match("/^0x([0-9a-f]+)/i", $datatype, $match))
      $datatype = self::hexdec($match[1]);

    if(isset(self::$datatypes[$datatype][0]))
      {
      $orig_buffer = $buffer;
      $function = "unpack".self::$datatypes[$datatype][0];
      $start_length = strlen($buffer);
      $output = self::$function($buffer);
      $length = $start_length - strlen($buffer);

      $raw_value = substr($orig_buffer, 0, $length);

      return $output;
      }
    else
      return null;
    }

  static function unpackInt8u(&$buffer)
    {
    if(!isset($buffer[0]))
      throw new \Exception("Size out of range");

    $val = substr($buffer, 0, 1);
    $buffer = substr($buffer, 1);

    return ord($val);
    }

  static function unpackInt8s(&$buffer)
    {
    return self::unpackInteger($buffer, 1, null, "s");
    }

  static function unpackInt16u(&$buffer)
    {
    return self::unpackInteger($buffer, 2, "L", "u");
    }

  static function unpackInt16s(&$buffer)
    {
    return self::unpackInteger($buffer, 2, "L", "s");
    }

  static function unpackLInt16u(&$buffer)
    {
    return self::unpackInteger($buffer, 2, "L", "u");
    }

  static function unpackLInt16s(&$buffer)
    {
    return self::unpackInteger($buffer, 2, "L", "s");
    }

  static function unpackBInt16u(&$buffer)
    {
    return self::unpackInteger($buffer, 2, "B", "u");
    }

  static function unpackBInt16s(&$buffer)
    {
    return self::unpackInteger($buffer, 2, "B", "s");
    }

  static function unpackInt24u(&$buffer)
    {
    return self::unpackInteger($buffer, 3, "L", "u");
    }

  static function unpackInt24s(&$buffer)
    {
    return self::unpackInteger($buffer, 3, "L", "s");
    }

  static function unpackLInt24u(&$buffer)
    {
    return self::unpackInteger($buffer, 3, "L", "u");
    }

  static function unpackLInt24s(&$buffer)
    {
    return self::unpackInteger($buffer, 3, "L", "s");
    }

  static function unpackBInt24u(&$buffer)
    {
    return self::unpackInteger($buffer, 3, "B", "u");
    }

  static function unpackBInt24s(&$buffer)
    {
    return self::unpackInteger($buffer, 3, "B", "s");
    }

  static function unpackInt32u(&$buffer)
    {
    return self::unpackInteger($buffer, 4, "L", "u");
    }

  static function unpackInt32s(&$buffer)
    {
    return self::unpackInteger($buffer, 4, "L", "s");
    }

  static function unpackLInt32u(&$buffer)
    {
    return self::unpackInteger($buffer, 4, "L", "u");
    }

  static function unpackLInt32s(&$buffer)
    {
    return self::unpackInteger($buffer, 4, "L", "s");
    }

  static function unpackBInt32u(&$buffer)
    {
    return self::unpackInteger($buffer, 4, "B", "u");
    }

  static function unpackBInt32s(&$buffer)
    {
    return self::unpackInteger($buffer, 4, "B", "s");
    }

  static function unpackInt40u(&$buffer)
    {
    return self::unpackInteger($buffer, 5, "L", "u");
    }

  static function unpackInt40s(&$buffer)
    {
    return self::unpackInteger($buffer, 5, "L", "s");
    }

  static function unpackLInt40u(&$buffer)
    {
    return self::unpackInteger($buffer, 5, "L", "u");
    }

  static function unpackLInt40s(&$buffer)
    {
    return self::unpackInteger($buffer, 5, "L", "s");
    }

  static function unpackBInt40u(&$buffer)
    {
    return self::unpackInteger($buffer, 5, "B", "u");
    }

  static function unpackBInt40s(&$buffer)
    {
    return self::unpackInteger($buffer, 5, "B", "s");
    }

  static function unpackInt48u(&$buffer)
    {
    return self::unpackInteger($buffer, 6, "L", "u");
    }

  static function unpackInt48s(&$buffer)
    {
    return self::unpackInteger($buffer, 6, "L", "s");
    }

  static function unpackLInt48u(&$buffer)
    {
    return self::unpackInteger($buffer, 6, "L", "u");
    }

  static function unpackLInt48s(&$buffer)
    {
    return self::unpackInteger($buffer, 6, "L", "s");
    }

  static function unpackBInt48u(&$buffer)
    {
    return self::unpackInteger($buffer, 6, "B", "u");
    }

  static function unpackBInt48s(&$buffer)
    {
    return self::unpackInteger($buffer, 6, "B", "s");
    }

  static function unpackInt56u(&$buffer)
    {
    return self::unpackInteger($buffer, 7, "L", "u");
    }

  static function unpackInt56s(&$buffer)
    {
    return self::unpackInteger($buffer, 7, "L", "s");
    }

  static function unpackLInt56u(&$buffer)
    {
    return self::unpackInteger($buffer, 7, "L", "u");
    }

  static function unpackLInt56s(&$buffer)
    {
    return self::unpackInteger($buffer, 7, "L", "s");
    }

  static function unpackBInt56u(&$buffer)
    {
    return self::unpackInteger($buffer, 7, "B", "u");
    }

  static function unpackBInt56s(&$buffer)
    {
    return self::unpackInteger($buffer, 7, "B", "s");
    }

  static function unpackInt64u(&$buffer)
    {
    return self::unpackInteger($buffer, 8, "L", "u");
    }

  static function unpackInt64s(&$buffer)
    {
    return self::unpackInteger($buffer, 8, "L", "s");
    }

  static function unpackLInt64u(&$buffer)
    {
    return self::unpackInteger($buffer, 8, "L", "u");
    }

  static function unpackLInt64s(&$buffer)
    {
    return self::unpackInteger($buffer, 8, "L", "s");
    }

  static function unpackBInt64u(&$buffer)
    {
    return self::unpackInteger($buffer, 8, "B", "u");
    }

  static function unpackBInt64s(&$buffer)
    {
    return self::unpackInteger($buffer, 8, "B", "s");
    }

  static function packInt8u(&$buffer, $value)
    {
    return self::packInteger($buffer, 1, null, "u", $value);
    }

  static function packInt8s(&$buffer, $value)
    {
    return self::packInteger($buffer, 1, null, "s", $value);
    }

  static function packInt16u(&$buffer, $value)
    {
    return self::packInteger($buffer, 2, "L", "u", $value);
    }

  static function packInt16s(&$buffer, $value)
    {
    return self::packInteger($buffer, 2, "L", "s", $value);
    }

  static function packLInt16u(&$buffer, $value)
    {
    return self::packInteger($buffer, 2, "L", "u", $value);
    }

  static function packLInt16s(&$buffer, $value)
    {
    return self::packInteger($buffer, 2, "L", "s", $value);
    }

  static function packBInt16u(&$buffer, $value)
    {
    return self::packInteger($buffer, 2, "B", "u", $value);
    }

  static function packBInt16s(&$buffer, $value)
    {
    return self::packInteger($buffer, 2, "B", "s", $value);
    }

  static function packInt24u(&$buffer, $value)
    {
    return self::packInteger($buffer, 3, "L", "u", $value);
    }

  static function packInt24s(&$buffer, $value)
    {
    return self::packInteger($buffer, 3, "L", "s", $value);
    }

  static function packLInt24u(&$buffer, $value)
    {
    return self::packInteger($buffer, 3, "L", "u", $value);
    }

  static function packLInt24s(&$buffer, $value)
    {
    return self::packInteger($buffer, 3, "L", "s", $value);
    }

  static function packBInt24u(&$buffer, $value)
    {
    return self::packInteger($buffer, 3, "B", "u", $value);
    }

  static function packBInt24s(&$buffer, $value)
    {
    return self::packInteger($buffer, 3, "B", "s", $value);
    }

  static function packInt32u(&$buffer, $value)
    {
    return self::packInteger($buffer, 4, "L", "u", $value);
    }

  static function packInt32s(&$buffer, $value)
    {
    return self::packInteger($buffer, 4, "L", "s", $value);
    }

  static function packLInt32u(&$buffer, $value)
    {
    return self::packInteger($buffer, 4, "L", "u", $value);
    }

  static function packLInt32s(&$buffer, $value)
    {
    return self::packInteger($buffer, 4, "L", "s", $value);
    }

  static function packBInt32u(&$buffer, $value)
    {
    return self::packInteger($buffer, 4, "B", "u", $value);
    }

  static function packBInt32s(&$buffer, $value)
    {
    return self::packInteger($buffer, 4, "B", "s", $value);
    }

  static function packInt40u(&$buffer, $value)
    {
    return self::packInteger($buffer, 5, "L", "u", $value);
    }

  static function packInt40s(&$buffer, $value)
    {
    return self::packInteger($buffer, 5, "L", "s", $value);
    }

  static function packLInt40u(&$buffer, $value)
    {
    return self::packInteger($buffer, 5, "L", "u", $value);
    }

  static function packLInt40s(&$buffer, $value)
    {
    return self::packInteger($buffer, 5, "L", "s", $value);
    }

  static function packBInt40u(&$buffer, $value)
    {
    return self::packInteger($buffer, 5, "B", "u", $value);
    }

  static function packBInt40s(&$buffer, $value)
    {
    return self::packInteger($buffer, 5, "B", "s", $value);
    }

  static function packInt48u(&$buffer, $value)
    {
    return self::packInteger($buffer, 6, "L", "u", $value);
    }

  static function packInt48s(&$buffer, $value)
    {
    return self::packInteger($buffer, 6, "L", "s", $value);
    }

  static function packLInt48u(&$buffer, $value)
    {
    return self::packInteger($buffer, 6, "L", "u", $value);
    }

  static function packLInt48s(&$buffer, $value)
    {
    return self::packInteger($buffer, 6, "L", "s", $value);
    }

  static function packBInt48u(&$buffer, $value)
    {
    return self::packInteger($buffer, 6, "B", "u", $value);
    }

  static function packBInt48s(&$buffer, $value)
    {
    return self::packInteger($buffer, 6, "B", "s", $value);
    }

  static function packInt56u(&$buffer, $value)
    {
    return self::packInteger($buffer, 7, "L", "u", $value);
    }

  static function packInt56s(&$buffer, $value)
    {
    return self::packInteger($buffer, 7, "L", "s", $value);
    }

  static function packLInt56u(&$buffer, $value)
    {
    return self::packInteger($buffer, 7, "L", "u", $value);
    }

  static function packLInt56s(&$buffer, $value)
    {
    return self::packInteger($buffer, 7, "L", "s", $value);
    }

  static function packBInt56u(&$buffer, $value)
    {
    return self::packInteger($buffer, 7, "B", "u", $value);
    }

  static function packBInt56s(&$buffer, $value)
    {
    return self::packInteger($buffer, 7, "B", "s", $value);
    }

  static function packInt64u(&$buffer, $value)
    {
    return self::packInteger($buffer, 8, "L", "u", $value);
    }

  static function packInt64s(&$buffer, $value)
    {
    return self::packInteger($buffer, 8, "L", "s", $value);
    }

  static function packLInt64u(&$buffer, $value)
    {
    return self::packInteger($buffer, 8, "L", "u", $value);
    }

  static function packLInt64s(&$buffer, $value)
    {
    return self::packInteger($buffer, 8, "L", "s", $value);
    }

  static function packBInt64u(&$buffer, $value)
    {
    return self::packInteger($buffer, 8, "B", "u", $value);
    }

  static function packBInt64s(&$buffer, $value)
    {
    return self::packInteger($buffer, 8, "B", "s", $value);
    }

  static function displayInt8u($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt8s($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt16u($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt16s($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt16u($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt16s($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt16u($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt16s($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt24u($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt24s($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt24u($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt24s($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt24u($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt24s($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt32u($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt32s($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt32u($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt32s($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt32u($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt32s($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt40u($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt40s($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt40u($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt40s($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt40u($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt40s($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt48u($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt48s($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt48u($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt48s($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt48u($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt48s($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt56u($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt56s($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt56u($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt56s($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt56u($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt56s($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt64u($value)
    {
    return self::displayInteger($value);
    }

  static function displayInt64s($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt64u($value)
    {
    return self::displayInteger($value);
    }

  static function displayLInt64s($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt64u($value)
    {
    return self::displayInteger($value);
    }

  static function displayBInt64s($value)
    {
    return self::displayInteger($value);
    }

  static function unpackData8(&$buffer)
    {
    return self::unpackInt8u($buffer);
    }

  static function packData8(&$buffer, $value)
    {
    return self::packData($buffer, 1, $value);
    }

  static function displayData8($value)
    {
    return self::displayHex($value, 2);
    }

  static function unpackData16(&$buffer)
    {
    return self::unpackInt16u($buffer);
    }

  static function packData16(&$buffer, $value)
    {
    return self::packInt16u($buffer, $value);
    }

  static function displayData16($value)
    {
    return self::displayHex($value, 4);
    }

  static function unpackData24(&$buffer)
    {
    return self::unpackInt24u($buffer);
    }

  static function packData24(&$buffer, $value)
    {
    return self::packInt24u($buffer, $value);
    }

  static function displayData24($value)
    {
    return self::displayHex($value, 6);
    }

  static function unpackData32(&$buffer)
    {
    return self::unpackInt32u($buffer);
    }

  static function packData32(&$buffer, $value)
    {
    return self::packInt32u($buffer, $value);
    }

  static function displayData32($value)
    {
    return self::displayHex($value, 8);
    }

  static function unpackData40(&$buffer)
    {
    return self::unpackInt40u($buffer);
    }

  static function packData40(&$buffer, $value)
    {
    return self::packInt40u($buffer, $value);
    }

  static function displayData40($value)
    {
    return self::displayHex($value, 10);
    }

  static function unpackData48(&$buffer)
    {
    return self::unpackInt48u($buffer);
    }

  static function packData48(&$buffer, $value)
    {
    return self::packInt48u($buffer, $value);
    }

  static function displayData48($value)
    {
    return self::displayHex($value, 12);
    }

  static function unpackData56(&$buffer)
    {
    return self::unpackInt56u($buffer);
    }

  static function packData56(&$buffer, $value)
    {
    return self::packInt56u($buffer, $value);
    }

  static function displayData56($value)
    {
    return self::displayHex($value, 14);
    }

  static function unpackData64(&$buffer)
    {
    return self::unpackInt64u($buffer);
    }

  static function packData64(&$buffer, $value)
    {
    return self::packInt64u($buffer, $value);
    }

  static function displayData64($value)
    {
    return self::displayHex($value, 16);
    }

  static function unpackBitmap8(&$buffer)
    {
    return self::unpackInt8u($buffer);
    }

  static function packBitmap8(&$buffer, $value)
    {
    return self::packInt8u($buffer, $value);
    }

  static function displayBitmap8($value)
    {
    return self::displayBin($value, 8);
    }

  static function unpackBitmap16(&$buffer)
    {
    return self::unpackInt16u($buffer);
    }

  static function packBitmap16(&$buffer, $value)
    {
    return self::packInt16u($buffer, $value);
    }

  static function displayBitmap16($value)
    {
    return self::displayBin($value, 16);
    }

  static function unpackBitmap24(&$buffer)
    {
    return self::unpackInt24u($buffer);
    }

  static function packBitmap24(&$buffer, $value)
    {
    return self::packInt24u($buffer, $value);
    }

  static function displayBitmap24($value)
    {
    return self::displayBin($value, 24);
    }

  static function unpackBitmap32(&$buffer)
    {
    return self::unpackInt32u($buffer);
    }

  static function packBitmap32(&$buffer, $value)
    {
    return self::packInt32u($buffer, $value);
    }

  static function displayBitmap32($value)
    {
    return self::displayBin($value, 32);
    }

  static function unpackBitmap40(&$buffer)
    {
    return self::unpackInt40u($buffer);
    }

  static function packBitmap40(&$buffer, $value)
    {
    return self::packInt40u($buffer, $value);
    }

  static function displayBitmap40($value)
    {
    return self::displayBin($value, 40);
    }

  static function unpackBitmap48(&$buffer)
    {
    return self::unpackInt48u($buffer);
    }

  static function packBitmap48(&$buffer, $value)
    {
    return self::packInt48u($buffer, $value);
    }

  static function displayBitmap48($value)
    {
    return self::displayBin($value, 48);
    }

  static function unpackBitmap56(&$buffer)
    {
    return self::unpackInt56u($buffer);
    }

  static function packBitmap56(&$buffer, $value)
    {
    return self::packInt56u($buffer, $value);
    }

  static function displayBitmap56($value)
    {
    return self::displayBin($value, 56);
    }

  static function unpackBitmap64(&$buffer)
    {
    return self::unpackInt64u($buffer);
    }

  static function packBitmap64(&$buffer, $value)
    {
    return self::packInt64u($buffer, $value);
    }

  static function displayBitmap64($value)
    {
    return self::displayBin($value, 64);
    }

  static function unpackEnum8(&$buffer)
    {
    return self::unpackInt8u($buffer);
    }

  static function packEnum8(&$buffer, $value)
    {
    return self::packInt8u($buffer, $value);
    }

  static function displayEnum8($value)
    {
    return self::displayInt8u($value, 8);
    }

  static function unpackEnum16(&$buffer)
    {
    return self::unpackInt16u($buffer);
    }

  static function packEnum16(&$buffer, $value)
    {
    return self::packInt16u($buffer, $value);
    }

  static function displayEnum16($value)
    {
    return self::displayInt16u($value, 16);
    }

  static function packData(&$buffer, $size, $value)
    {
    if(is_int($value) || preg_match("/^(0x[a-fA-F0-9]+|0b[0-1]+|[0-9]+)$/", $value))
      return self::packInteger($buffer, $size, "L", "u", $value);

    if(is_bool($value) || is_array($value) || is_object($value))
      return self::packInteger($buffer, $size, "L", "u", ($value ? 1 : 0));

    if(is_float($value))
      {
      switch($size)
        {
        case 2:		return self::packFloat16($buffer, $value);
        case 4:		return self::packFloat32($buffer, $value);
        case 8:		return self::packFloat64($buffer, $value);
        default:	return self::packInteger($buffer, $size, "L", "u", 0);
        }
      }
        
    if(is_string($value))
      {
      for($x = 0; $x < $size; $x++)
        self::packChar($buffer, substr($value, 1));
      return true;
      }

    return false;
    }

  static function packInteger(&$buffer, $size, $endian, $sign, $value)
    {
    if($size < 1 || $size > 8)
      throw new \Exception("Size out of range");

    if($sign !== "u" && $sign !== "s")
      throw new \Exception("Signednes should be 'u'nsigned or 's'igned");

    if(!($endian === null && $size === 1) && $endian !== "B" && $endian !== "L")
      throw new \Exception("Endianness should be 'B'ig or 'L'ittle");

    if(preg_match("/^0[xX]([a-fA-F0-9]+)$/", $value, $match))
      $value = self::hexdec($match[1]);
    elseif(preg_match("/^0[bB]([0-1]+)$/", $value, $match))
      $value = self::bindec($match[1]);
    elseif(preg_match("/^(-?[0-9]+)$/", $value, $match))
      $value = $match[1];
    else
      throw new \Exception("Invalid format of size");

    $full = bcpow(2, 8 * $size);
    if($sign === "s")
      {
      $max = bcsub(bcdiv($full, 2), 1);
      $min = bcmul(bcdiv($full, 2), -1);
      }
    else
      {
      $max = bcsub($full, 1);
      $min = 0;
      }

    if(bccomp($value, $max) === 1)
      throw new \Exception("Value is larger than size could fit");
    if(bccomp($value, $min) === -1)
      throw new \Exception("Value is smaller than size could fit");

    if($sign === "s" && bccomp(0, $value) === 1)
      $value = bcsub($full, str_replace("-", "", $value));

    $bytes = [];
    for($x = $size - 1; $x >= 0; $x--)
      {
      $bytes[$x] = bcdiv($value, bcpow(2, 8 * $x), 0);
      $value = bcmod($value, bcpow(2, 8 * $x));
      }

    if($endian === "L")
      $bytes = array_reverse($bytes);

    foreach($bytes as $byte)
      $buffer .= chr($byte);

    return true;
    }

  static function packChar(&$buffer, $value)
    {
    if(is_int($value))
      $buffer .= chr($value);
    else
      $buffer .= chr(ord($value));
    return true;
    }

  static function unpackInteger(&$buffer, $size, $endian, $sign)
    {
    if($size < 1 || $size > 8 || $size > strlen($buffer))
      throw new \Exception("Size out of range");

    if($sign != "u" && $sign != "s")
      throw new \Exception("Signednes should be 'u'nsigned or 's'igned");
      
    if($endian == "L" || $size == 1)
      $range = array("start" => $size, "end" => 0, "step" => -1);
    elseif($endian == "B")
      $range = array("start" => 1, "end" => $size + 1, "step" => 1);
    else
      throw new \Exception("Endian should be 'L'(ittle) or 'B'(ig)");

    $val = substr($buffer, 0, $size);
    $buffer = substr($buffer, $size);

    $factor = $size;
    $value = "0";
    for($x = $range["start"]; $x != $range["end"]; $x += $range["step"])
      $value = bcadd($value, bcmul(ord($val[$x - 1]), bcpow(2, 8 * --$factor)));


    if($sign === "s")
      {
      $limit = bcsub(bcdiv(bcpow(2, 8 * $size), 2), 1);
      if(bccomp($value, $limit) == 1)
        $value = bcsub($value, bcpow(2, 8 * $size));
      }

    if(strpos($value, "."))
      $value = substr($value, 0, strpos($value, "."));

    if(bccomp($value, PHP_INT_MAX) == -1)
      return (int)$value;

    return $value;
    }

  static function displayInteger($value)
    {
    return $value;
    }

  static function displayHex($value, $nibbles = null)
    {
    $hexval = self::dechex($value);
    if($nibbles)
      $hexval = sprintf("%0".$nibbles."s", $hexval);

    return "0x".$hexval;
    }

  static function displayBin($value, $bits)
    {
    $binval = self::decbin($value);
    if($bits)
      $binval = sprintf("0b%0".$bits."s", $binval);

    return $binval;
    }

  static function unpackTimeOfDay(&$buffer)
    {
    $hours = self::unpackInt8u($buffer);
    $minutes = self::unpackInt8u($buffer);
    $seconds = self::unpackInt8u($buffer);
    $hundredths = self::unpackInt8u($buffer);
  
    return ($hours * 360000) + ($minutes * 6000) + ($seconds * 100) + ($hundredths * 1);
    }

  static function displayTimeOfDay($hundredths)
    {
    $timeval = array("100th" => 1,
                     "sec" => 100,
                     "min" => 6000,
                     "hour"  => 360000);

    foreach(array_reverse($timeval) as $timeitem => $timevalue)
      if($x = floor($hundredths / $timevalue))
        {
        $str[] = $x." ".$timeitem.(($x>1 && $timevalue >= 1)?"s":"");
        $hundredths -= ($x * $timevalue);
        }

    if(isset($str))
      return join(", ", $str);
    else
      return "<1 100th";
    }

  static function packTimeOfDay(&$buffer, $hundredths)
    {
    if(is_numeric($hundredths))
      {
      $hours = floor($hundredths / 360000);
      $hundredths -= $hours * 360000;

      $minutes = floor($hundredths / 6000);
      $hundredths -= $minutes * 6000;

      $seconds = floor($hundredths / 100);
      $hundredths -= $seconds * 100;
      }
    elseif($hundredths != "" && preg_match('/(([0-9]+)\s*hours?\s*,\s*)?(([0-9]+)\s*mins?\s*,\s*)?(([0-9]+)\s*secs?\s*,\s*)?(([0-9]+)\s*100ths?)?/', $hundredths, $match))
      {
      $hours = $match[2] ? $match[2] : 0;
      $minutes = $match[4] ? $match[4] : 0;
      $seconds = $match[6] ? $match[6] : 0;
      $hundredths = $match[0] ? $match[8]: 0;
      }
    else
      throw new \Exception("Invalid format for packTimeOfDay");

    self::packInt8u($buffer, $hours);
    self::packInt8u($buffer, $minutes);
    self::packInt8u($buffer, $seconds);
    self::packInt8u($buffer, $hundredths);

    return true;
    }

  static function unpackDate(&$buffer)
    {
    $year = self::unpackInt8u($buffer);
    $month = self::unpackInt8u($buffer);
    $day = self::unpackInt8u($buffer);
    self::unpackInt8u($buffer); // weekday
  
    return mktime(0, 0, 0, $month, $day, $year + 1900);
    }

  static function displayDate($value)
    {
    return date("d/m/Y", $value);
    }

  static function packDate(&$buffer, $value)
    {
    if(preg_match("!^([0-9]{1,2})/([0-9]{1,2})/(19|20)?([0-9]{2})$!", $value, $match))
      {
      $day = $match[1];
      $month = $match[2];
      $year = $match[4] > 1900 ? $match[4] - 1900 : $match[4];

      $time = mktime(0, 0, 0, $month, $day, $year);
      $weekday = (int)date("N", $time);
      }
    elseif(is_numeric($value))
      {
      $day = (int)date("j", $value);
      $month = (int)date("n", $value);
      $year = (int)date("Y", $value) - 1900;
      $weekday = (int)date("N", $value);
      }
    else
      throw new \Exception("Invalid format for packDate");

    self::packInt8u($buffer, $year);
    self::packInt8u($buffer, $month);
    self::packInt8u($buffer, $day);
    self::packInt8u($buffer, $weekday);

    return true;
    }

  static function unpackUTC(&$buffer)
    {
    return self::unpackInt32u($buffer);
    }

  static function displayUTC($value)
    {
    return date("d/m/Y H:i:s", $value + 946684800);
    }

  static function packUTC(&$buffer, $value)
    {
    if(preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$/", $value, $match))
      $value = mktime($match[4], $match[5], $match[6], $match[2], $match[1], $match[3]) - 946684800;

    return self::packInt32u($buffer, $value);
    }

  static function unpackIeee64(&$buffer)
    {
    return self::unpackInt64u($buffer);
    }

  static function displayIeee64($value, $reverse = false)
    {
    $hexval = self::dechex($value);

    if($reverse)
      $hexval = join("", array_reverse(str_split(sprintf("%016s", $hexval), 2)));

    return strtolower(join(":", str_split(sprintf("%016s", $hexval), 4)));
    }

  static function packIeee64(&$buffer, $value)
    {
    if(preg_match("/^([0-9]+)$/", $value, $match))
      $ieee64 = $match[1];
    elseif(preg_match("/^([a-fA-F0-9]{16})$/", preg_replace("/[:\.\-]/", "", $value), $match))
      $ieee64 = self::hexdec($match[1]);
    else
      throw new \Exception("Invalid value for packIeee64");

    if(!preg_match("/^[0-9]*$/", $ieee64) || (bccomp($ieee64, "0") === -1 || bccomp($ieee64, "18446744073709551615") === 1))
      throw new \Exception("Value out of range for packIeee64");

    return self::packInt64u($buffer, $ieee64);
    }

  static function unpackEui64(&$buffer)
    {
    return self::unpackIeee64($buffer);
    }

  static function packEui64(&$buffer, $value)
    {
    return self::packIeee64($buffer, $value);
    }

  static function displayEui64($value, $reverse = false)
    {
    return self::displayIeee64($value, $reverse);
    }

  static function unpackBoolean(&$buffer)
    {
    $value = self::unpackInt8u($buffer);

    if($value == 0x01)
      return true;
    elseif($value == 0x00)
      return false;

    return null;
    }

  static function displayBoolean($value)
    {
    if($value == 0x01)
      return "TRUE";
    elseif($value == 0x00)
      return "FALSE";

    return null;
    }

  static function packBoolean(&$buffer, $value)
    {
    if((!isset($value) || $value === chr(0)) || $value === 0 || $value === null || $value === false || preg_match("/^(disabled|disable|false|not|no|n)$/i", $value))
      return self::packInt8u($buffer, 0x00);
    elseif(preg_match("/^(0[xX])?[0-9]+$/", $value))
      {
      $intval = "";      
      self::packInt8u($intval, $value);

      if($intval === chr(0))
        return self::packInt8u($buffer, 0x00);
      }

    return self::packInt8u($buffer, 0x01);
    }

  static function unpackOctetString(&$buffer)
    {
    $length = self::unpackInt8u($buffer);

    $val = substr($buffer, 0, $length);
    $buffer = substr($buffer, $length);
  
    return $val;
    }

  static function packOctetString(&$buffer, $value)
    {
    if(preg_match("/^0x/", $value) && preg_match_all("/(0x[a-f0-9]{2})/", $value, $match))
      {
      self::packInt8u($buffer, count($match[1]));
      foreach($match[1] as $byte)
        self::packInt8u($buffer, $byte);

      return true;
      }
    elseif(is_string($value) && strlen($value) > 0)
      {
      self::packInt8u($buffer, strlen($value));
      for($x = 0; $x < strlen($value); $x++)
        self::packInt8u($buffer, ord($value[$x]));

      return true;
      }

    self::packInt8u($buffer, 0);
    return true;
    }

  static function displayOctetString($value, $with_size = false)
    {
    $output = [];
    for($x = 0; $x < strlen($value); $x++)
      $output[] = sprintf("0x%02x", ord($value[$x]));

    if($output)
      return join(" ", $output).($with_size ? " (size: ".strlen($value).")" : "");

    return "-";
    }

  static function unpackOctetStringLong(&$buffer)
    {
    $length = self::unpackInt16u($buffer);

    $val = substr($buffer, 0, $length);
    $buffer = substr($buffer, $length);
  
    return $val;
    }

  static function packOctetStringLong(&$buffer, $value)
    {
    if(preg_match("/^0x/", $value) && preg_match_all("/(0x[a-f0-9]{2})/", $value, $match))
      {
      self::packInt16u($buffer, count($match[1]));
      foreach($match[1] as $byte)
        self::packInt8u($buffer, $byte);

      return true;
      }
    elseif(is_string($value) && strlen($value) > 0)
      {
      self::packInt16u($buffer, strlen($value));
      for($x = 0; $x < strlen($value); $x++)
        self::packInt8u($buffer, ord($value[$x]));

      return true;
      }

    self::packInt16u($buffer, 0);
    return true;
    }

  static function displayOctetStringLong($value)
    {
    return self::displayOctetString($value);
    }

  static function unpackCharString(&$buffer)
    {
    return self::unpackOctetString($buffer);
    }

  static function packCharString(&$buffer, $value)
    {
    return self::packOctetString($buffer, $value);
    }

  static function displayCharString($value, $with_size = false)
    {
    return $value.($with_size ? " (length: ".strlen($value).")" : "");
    }

  static function unpackCharStringLong(&$buffer)
    {
    return self::unpackOctetStringLong($buffer);
    }

  static function packCharStringLong(&$buffer, $value)
    {
    return self::packOctetStringLong($buffer, $value);
    }

  static function displayCharStringLong($value)
    {
    return self::displayCharString($value);
    }

  static function packHexString(&$buffer, $str)
    {
    if(!preg_match("/^(0x)?([a-f0-9]+)$/", $str, $match))
      throw new \Exception("Invalid hex string format");

    $str = $match[2];
    $len = strlen($str);
  
    if($len % 2 != 0)
      throw new \Exception("Hex string should have an even number of nibbles");

    for($x = 0; $x < $len; $x += 2)
      Buffer::packInt8u($buffer, "0x".substr($str, $x, 2));

    return true;
    }

  static function unpackHexString(&$buffer, $length = null)
    {
    if($length == null)
      $length = strlen($buffer);

    $str = "";
    for($x = 0; $x < $length; $x++)
      $str .= sprintf("%02x", Buffer::unpackInt8u($buffer));

    return $str;
    }

  static function unpackKey128(&$buffer)
    {
    if(strlen($buffer) < 16)
      throw new \Exception("Buffer to short to unpack a 128bit key");

    $output = substr($buffer, 0, 16);
    $buffer = substr($buffer, 16);

    return $output;
    }

  static function packKey128(&$buffer, $value)
    {
    $input = array();
    if(is_string($value) && strlen($value) == 16)
      for($x = 0; $x < 16; $x++)
        $input[] = ord(substr($value, $x, 1));
    elseif(preg_match("/^(0x)?([a-f0-9]{32})$/", $value, $match))
      for($x = 0; $x < 16; $x++)
        $input[] = hexdec(substr($match[2], $x * 2, 2));

    if(count($input) == 16)
      {
      foreach($input as $in)
        self::packInt8u($buffer, $in);

      return true;
      }

    return false;
    }

  static function displayKey128($value)
    {
    $output = "";
    if(strlen($value) == 16)
      {
      $output .= "0x";
      for($x = 0; $x < 16; $x++)
        $output .= sprintf("%02x", ord(substr($value, $x, 1)));
      }

    return $output;
    }

  static function unpackFloat16(&$buffer)
    {
    $buffer = substr($buffer, 2);

    assert(true, "Unable to unpackFloat16, not implemented.");

    return null;
    }

  static function packFloat16(&$buffer, $value)
    {
    $buffer .= self::packNull($buffer, 2);

    assert(true, "Unable to packFloat16, not implemented. [".$value."]");

    return null;
    }

  static function unpackFloat32(&$buffer)
    {
    $val = substr($buffer, 0, 4);
    $buffer = substr($buffer, 4);

    list(, $val) = unpack("f", $val);

    return (string)$val;
    }

  static function packFloat32(&$buffer, $value)
    {
    $buffer .= pack("f", floatval($value));

    return true;
    }

  static function unpackFloat64(&$buffer)
    {
    $val = substr($buffer, 0, 8);
    $buffer = substr($buffer, 8);

    list(, $val) = unpack("d", $val);

    return (string)$val;
    }

  static function packFloat64(&$buffer, $value)
    {
    $buffer .= pack("d", doubleval($value));

    return true;
    }

  static function unpackClusterId(&$buffer)
    {
    return self::unpackInt16u($buffer);
    }

  static function packClusterId(&$buffer, $value)
    {
    return self::packInt16u($buffer, $value);
    }

  static function displayClusterId($value)
    {
    return self::displayHex($value, 4);
    }

  static function unpackAttributeId(&$buffer)
    {
    return self::unpackInt16u($buffer);
    }

  static function packAttributeId(&$buffer, $value)
    {
    return self::packInt16u($buffer, $value);
    }

  static function displayAttributeId($value)
    {
    return self::displayHex($value, 4);
    }

  static function unpackBACnetOID(&$buffer)
    {
    return self::unpackInt32u($buffer);
    }

  static function packBACnetOID(&$buffer, $value)
    {
    return self::packInt32u($buffer, $value);
    }

  static function displayBACnetOID($value)
    {
    return self::displayHex($value, 8);
    }

  static function unpackNull(&$buffer, $bytes = 1)
    {
    $buffer = substr($buffer, $bytes);

    return null;
    }

  static function packNull(&$buffer, $bytes = 1)
    {
    for($x = 0; $x < $bytes; $x++)
      $buffer .= pack("x");

    return true;
    }

  static function hexdec($hex)
    {
    $len = strlen($hex);
    $dec = 0;
    for($i = 1; $i <= $len; $i++)
      $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
   
    return $dec;
    }

  static function dechex($dec)
    {
    $hex = "";

    while($dec != "0")
      {
      $hex = dechex(bcmod($dec, "16")).$hex;
      $dec = bcdiv($dec, "16", 0);
      }

    return $hex;
    }

  static function decbin($dec)
    {
    $bin = "";

    while($dec)
      {
      $m = bcmod($dec, 2);
      $dec = bcdiv($dec, 2);
      $bin .= abs($m);
      }

    return strrev($bin);
    }

  static function bindec($bin)
    {
    $dec = "0";

    for($i = 0; $i < strlen($bin); $i++)
      $dec = bcadd(bcmul($dec, "2"), $bin{$i});

    return $dec;
    }
  }

?>
