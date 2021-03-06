<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Core;

/**
 * Some of this code was written by Flinn Mueller.
 *
 * @package		Fuel
 * @category	Core
 * @copyright	Flinn Mueller
 * @link		http://fuelphp.com/docs/classes/inlector.html
 */
class Inflector {

	protected static $uncountable_words = array(
		'equipment', 'information', 'rice', 'money',
		'species', 'series', 'fish', 'meta'
	);

	protected static $plural_rules = array(
		'/^(ox)$/'                 => '\1\2en',     // ox
		'/([m|l])ouse$/'           => '\1ice',      // mouse, louse
		'/(matr|vert|ind)ix|ex$/'  => '\1ices',     // matrix, vertex, index
		'/(x|ch|ss|sh)$/'          => '\1es',       // search, switch, fix, box, process, address
		'/([^aeiouy]|qu)y$/'       => '\1ies',      // query, ability, agency
		'/(hive)$/'                => '\1s',        // archive, hive
		'/(?:([^f])fe|([lr])f)$/'  => '\1\2ves',    // half, safe, wife
		'/sis$/'                   => 'ses',        // basis, diagnosis
		'/([ti])um$/'              => '\1a',        // datum, medium
		'/(p)erson$/'              => '\1eople',    // person, salesperson
		'/(m)an$/'                 => '\1en',       // man, woman, spokesman
		'/(c)hild$/'               => '\1hildren',  // child
		'/(buffal|tomat)o$/'       => '\1\2oes',    // buffalo, tomato
		'/(bu|campu)s$/'           => '\1\2ses',    // bus, campus
		'/(alias|status|virus)$/'  => '\1es',       // alias
		'/(octop)us$/'             => '\1i',        // octopus
		'/(ax|cris|test)is$/'      => '\1es',       // axis, crisis
		'/s$/'                     => 's',          // no change (compatibility)
		'/$/'                      => 's',
	);

	protected static $singular_rules = array(
		'/(matr)ices$/'         => '\1ix',
		'/(vert|ind)ices$/'     => '\1ex',
		'/^(ox)en/'             => '\1',
		'/(alias)es$/'          => '\1',
		'/([octop|vir])i$/'     => '\1us',
		'/(cris|ax|test)es$/'   => '\1is',
		'/(shoe)s$/'            => '\1',
		'/(o)es$/'              => '\1',
		'/(bus|campus)es$/'     => '\1',
		'/([m|l])ice$/'         => '\1ouse',
		'/(x|ch|ss|sh)es$/'     => '\1',
		'/(m)ovies$/'           => '\1\2ovie',
		'/(s)eries$/'           => '\1\2eries',
		'/([^aeiouy]|qu)ies$/'  => '\1y',
		'/([lr])ves$/'          => '\1f',
		'/(tive)s$/'            => '\1',
		'/(hive)s$/'            => '\1',
		'/([^f])ves$/'          => '\1fe',
		'/(^analy)ses$/'        => '\1sis',
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/' => '\1\2sis',
		'/([ti])a$/'            => '\1um',
		'/(p)eople$/'           => '\1\2erson',
		'/(m)en$/'              => '\1an',
		'/(s)tatuses$/'         => '\1\2tatus',
		'/(c)hildren$/'         => '\1\2hild',
		'/(n)ews$/'             => '\1\2ews',
		'/([^us])s$/'           => '\1',
	);


	/**
	 * Add order suffix to numbers ex. 1st 2nd 3rd 4th 5th
	 *
	 * @param   int     the word to singularize
	 * @return  string  the singular version of $word
	 * @link    http://snipplr.com/view/4627/a-function-to-add-a-prefix-to-numbers-ex-1st-2nd-3rd-4th-5th/
	 */
	public static function ordinalize($number)
	{
		if ( ! is_numeric($number))
		{
			return $number;
		}

		if (in_array(($number % 100), range(11, 13)))
		{
			return $number . 'th';
		}
		else
		{
			switch ($number % 10)
			{
				case 1:
					return $number . 'st';
					break;
				case 2:
					return $number . 'nd';
					break;
				case 3:
					return $number . 'rd';
					break;
				default:
					return $number . 'th';
					break;
			}
		}
	}

	/**
	 * Gets the plural version of the given word
	 *
	 * @param   string  the word to pluralize
	 * @return  string  the plural version of $word
	 */
	public static function pluralize($word)
	{
		$result = strval($word);

		if ( ! static::is_countable($result))
		{
			return $result;
		}

		foreach (static::$plural_rules as $rule => $replacement)
		{
			if (preg_match($rule, $result))
			{
				$result = preg_replace($rule, $replacement, $result);
				break;
			}
		}

		return $result;
	}

	/**
	 * Gets the singular version of the given word
	 *
	 * @param   string  the word to singularize
	 * @return  string  the singular version of $word
	 */
	public static function singularize($word)
	{
		$result = strval($word);

		if ( ! static::is_countable($result))
		{
			return $result;
		}

		foreach (static::$singular_rules as $rule => $replacement)
		{
			if (preg_match($rule, $result))
			{
				$result = preg_replace($rule, $replacement, $result);
				break;
			}
		}

		return $result;
	}

	/**
	 * Takes a string that has words seperated by underscores and turns it into
	 * a CamelCased string.
	 *
	 * @param   string  the underscored word
	 * @return  string  the CamelCased version of $underscored_word
	 */
	public static function camelize($underscored_word)
	{
		return preg_replace('/(^|_)(.)/e', "strtoupper('\\2')", strval($underscored_word));
	}

	/**
	 * Takes a CamelCased string and returns an underscore separated version.
	 *
	 * @param   string  the CamelCased word
	 * @return  string  an underscore separated version of $camel_cased_word
	 */
	public static function underscore($camel_cased_word)
	{
		return \Str::lower(preg_replace('/([A-Z]+)([A-Z])/', '\1_\2', preg_replace('/([a-z\d])([A-Z])/', '\1_\2', strval($camel_cased_word))));
	}

	/**
	 * Translate string to 7-bit ASCII
	 * Only works with UTF-8.
	 *
	 * @param   string
	 * @return  string
	 */
	public static function ascii($str)
	{
		// Translate unicode characters to their simpler counterparts
		\Config::load('ascii', true);
		$foreign_characters = \Config::get('ascii');

		$str = preg_replace(array_keys($foreign_characters), array_values($foreign_characters), $str);

		// remove any left over non 7bit ASCII
		return preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $str);
	}

	/**
	 * Converts your text to a URL-friendly title so it can be used in the URL.
	 * Only works with UTF8 input and and only outputs 7 bit ASCII characters.
	 *
	 * @param   string  the text
	 * @param   string  the separator (either - or _)
	 * @return  string  the new title
	 */
	public static function friendly_title($str, $sep = '-', $lowercase = false)
	{
		// Allow underscore, otherwise default to dash
		$sep = $sep === '_' ? '_' : '-';

		// Remove tags
		$str = \Security::strip_tags($str);

		// Decode all entities to their simpler forms
		$str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');

		// Only allow 7bit characters
		$str = static::ascii($str);

		// Strip unwanted characters
		$str = preg_replace("#[^a-z0-9]#i", $sep, $str);
		$str = preg_replace("#[/_|+ -]+#", $sep, $str);
		$str = trim($str, $sep);

		if ($lowercase === true)
		{
			$str = \Str::lower($str);
		}

		return $str;
	}

	/**
	 * Turns an underscore or dash separated word and turns it into a human looking string.
	 *
	 * @param   string  the word
	 * @param   string  the separator (either _ or -)
	 * @param   bool    lowercare string and upper case first
	 * @return  string  the human version of given string
	 */
	public static function humanize($str, $sep = '_', $lowercase = true)
	{
		// Allow dash, otherwise default to underscore
		$sep = $sep != '-' ? '_' : $sep;

		if ($lowercase === true)
		{
			$str = \Str::ucfirst($str);
		}

		return str_replace($sep, " ", strval($str));
	}

	/**
	 * Takes the class name out of a modulized string.
	 *
	 * @param   string  the modulized class
	 * @return  string  the string without the class name
	 */
	public static function demodulize($class_name_in_module)
	{
		return preg_replace('/^.*::/', '', strval($class_name_in_module));
	}

	/**
	 * Takes the namespace off the given class name.
	 *
	 * @param   string  the class name
	 * @return  string  the string without the namespace
	 */
	public static function denamespace($class_name)
	{
		$class_name = trim($class_name, '\\');
		if ($last_separator = strrpos($class_name, '\\'))
		{
			$class_name = substr($class_name, $last_separator + 1);
		}
		return $class_name;
	}

	/**
	 * Returns the namespace of the given class name.
	 *
	 * @param   string  $class_name  the class name
	 * @return  string  the string without the namespace
	 */
	public static function get_namespace($class_name)
	{
		$class_name = trim($class_name, '\\');
		if ($last_separator = strrpos($class_name, '\\'))
		{
			return substr($class_name, 0, $last_separator + 1);
		}
		return '';
	}

	/**
	 * Takes a class name and determines the table name.  The table name is a
	 * pluralized version of the class name.
	 *
	 * @param   string  the table name
	 * @return  string  the table name
	 */
	public static function tableize($class_name)
	{
		$class_name = static::denamespace($class_name);
		if (strncasecmp($class_name, 'Model_', 6) === 0)
		{
			$class_name = substr($class_name, 6);
		}
		return \Str::lower(static::pluralize(static::underscore($class_name)));
	}

	/**
	 * Takes an underscored classname and uppercases all letters after the underscores.
	 *
	 * @param   string  classname
	 * @return  string
	 */
	public static function words_to_upper($class)
	{
		return str_replace(' ', '_', ucwords(str_replace('_', ' ', $class)));
	}

	/**
	 * Takes a table name and creates the class name.
	 *
	 * @param   string  the table name
	 * @param   bool    whether to singularize the table name or not
	 * @return  string  the class name
	 */
	public static function classify($name, $force_singular = true)
	{
		$class = ($force_singular) ? static::singularize($name) : $name;
		return static::words_to_upper($class);
	}

	/**
	 * Gets the foreign key for a given class.
	 *
	 * @param   string  the class name
	 * @param   bool    $use_underscore	whether to use an underscore or not
	 * @return  string  the foreign key
	 */
	public static function foreign_key($class_name, $use_underscore = true)
	{
		$class_name = static::denamespace(\Str::lower($class_name));
		if (strncasecmp($class_name, 'Model_', 6) === 0)
		{
			$class_name = substr($class_name, 6);
		}
		return static::underscore(static::demodulize($class_name)).($use_underscore ? "_id" : "id");
	}

	/**
	 * Checks if the given word has a plural version.
	 *
	 * @param   string  the word to check
	 * @return  bool    if the word is countable
	 */
	public static function is_countable($word)
	{
		return ! (\in_array(\Str::lower(\strval($word)), static::$uncountable_words));
	}
}

