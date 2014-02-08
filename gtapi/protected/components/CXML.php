<?php
/**
 * CXML class file.
 */

/**
 * Tools for XML.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: CXML $
 * @package com.server.components
 * @since 0.1.0
 */
class CXML{

	/**
	 * decodes a XML string to array
	 *
	 * @return string Array string representation of input var
	 */
	public static function decode($var)
	{
		return json_decode(json_encode((array) simplexml_load_string($var)),1);
	}

	/**
	 * Encodes array to XML string
	 * @return string XML string representation of input var
	 */
	public static function encode($var)
	{
		return self::toXml($var);
	}

	/**
	 * copy by http://snipplr.com/view.php?codeview&id=3491;
	 *
	 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
	 *
	 * @param array $data
	 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
	 * @param SimpleXMLElement $xml - should only be used recursively
	 * @return string XML
	 */
	protected static function toXml($data, $rootNodeName = 'data', $xml=null)
	{
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set ('zend.ze1_compatibility_mode', 0);
		}

		if ($xml == null)
		{
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}

		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				// make string key...
				$key = "list". (string) $key;
			}

			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z_]/i', '', $key);

			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				$node = $xml->addChild($key);
				// recrusive call.
				self::toXml($value, $rootNodeName, $node);
			}
			else
			{
				// add single node.
				$value = htmlentities($value);
				$xml->addChild($key,$value);
			}

		}
		// pass back as string. or simple xml object if you want!
		return $xml->asXML();
	}
}