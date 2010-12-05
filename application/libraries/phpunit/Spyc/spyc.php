<?PHP

/**
 * @package Application
 * @subpackage Libraries
 * @category PHPUnit
 */

/**
 * Begin Document
 */
 	
class Spyc
{
	/**#@+
	* @access private
	* @var mixed
	*/
	var $_haveRefs;
	var $_allNodes;
	var $_allParent;
	var $_lastIndent;
	var $_lastNode;
	var $_inBlock;
	var $_isInline;
	var $_dumpIndent;
	var $_dumpWordWrap;
	var $_containsGroupAnchor = FALSE;
	var $_containsGroupAlias = FALSE;
	var $path;
	var $result;
	var $LiteralBlockMarkers = array ('>', '|');
	var $LiteralPlaceHolder = '___YAML_Literal_Block___';
	var $SavedGroups = array();

	/**#@+
	* @access public
	* @var mixed
	*/
	var $_nodeId;

	/**
     * Load YAML into a PHP array statically
     *
     * The load method, when supplied with a YAML stream (string or file),
     * will do its best to convert YAML in a file into a PHP array.  Pretty
     * simple.
     *  Usage:
     *  <code>
     *   $array = Spyc::YAMLLoad('lucky.yaml');
     *   print_r($array);
     *  </code>
     * @access public
     * @return array
     * @param string $input Path of YAML file or string containing YAML
     */
	function YAMLLoad($input)
	{
		$Spyc = new Spyc;
		return $Spyc->load($input);
	}

	/**
     * Dump YAML from PHP array statically
     *
     * The dump method, when supplied with an array, will do its best
     * to convert the array into friendly YAML.  Pretty simple.  Feel free to
     * save the returned string as nothing.yaml and pass it around.
     *
     * Oh, and you can decide how big the indent is and what the wordwrap
     * for folding is.  Pretty cool -- just pass in 'false' for either if
     * you want to use the default.
     *
     * Indent's default is 2 spaces, wordwrap's default is 40 characters.  And
     * you can turn off wordwrap by passing in 0.
     *
     * @access public
     * @return string
     * @param array $array PHP array
     * @param int $indent Pass in false to use the default, which is 2
     * @param int $wordwrap Pass in 0 for no wordwrap, false for default (40)
     */
	function YAMLDump($array,$indent = FALSE,$wordwrap = FALSE)
	{
		$spyc = new Spyc;
		return $spyc->dump($array,$indent,$wordwrap);
	}


  	/**
     * Dump PHP array to YAML
     *
     * The dump method, when supplied with an array, will do its best
     * to convert the array into friendly YAML.  Pretty simple.  Feel free to
     * save the returned string as tasteful.yaml and pass it around.
     *
     * Oh, and you can decide how big the indent is and what the wordwrap
     * for folding is.  Pretty cool -- just pass in 'false' for either if
     * you want to use the default.
     *
     * Indent's default is 2 spaces, wordwrap's default is 40 characters.  And
     * you can turn off wordwrap by passing in 0.
     *
     * @access public
     * @return string
     * @param array $array PHP array
     * @param int $indent Pass in false to use the default, which is 2
     * @param int $wordwrap Pass in 0 for no wordwrap, false for default (40)
     */
	function dump($array,$indent = FALSE,$wordwrap = FALSE)
	{
		if ($indent === FALSE or !is_numeric($indent))
		{
			$this->_dumpIndent = 2;
		}
		else
		{
			$this->_dumpIndent = $indent;
		}

		if ($wordwrap === FALSE or !is_numeric($wordwrap))
		{
			$this->_dumpWordWrap = 40;
		}
		else
		{
			$this->_dumpWordWrap = $wordwrap;
		}

		$string = "---\n";

		foreach ($array as $key => $value)
		{
			$string .= $this->_yamlize($key,$value,0);
		}
		return $string;
	}

	/**
     * Attempts to convert a key / value array item to YAML
     * @access private
     * @return string
     * @param $key The name of the key
     * @param $value The value of the item
     * @param $indent The indent of the current node
     */
	function _yamlize($key,$value,$indent)
	{
		if (is_array($value))
		{
			$string = $this->_dumpNode($key,NULL,$indent);
			$indent += $this->_dumpIndent;
			$string .= $this->_yamlizeArray($value,$indent);
		}
		elseif (!is_array($value))
		{
			$string = $this->_dumpNode($key,$value,$indent);
		}
		return $string;
	}

	/**
     * Attempts to convert an array to YAML
     * @access private
     * @return string
     * @param $array The array you want to convert
     * @param $indent The indent of the current level
     */
	function _yamlizeArray($array,$indent)
	{
		if (is_array($array))
		{
			$string = '';
			foreach ($array as $key => $value)
			{
				$string .= $this->_yamlize($key,$value,$indent);
			}
			return $string;
		}
		else
		{
			return FALSE;
		}
	}

	/**
     * Returns YAML from a key and a value
     * @access private
     * @return string
     * @param $key The name of the key
     * @param $value The value of the item
     * @param $indent The indent of the current node
     */
	function _dumpNode($key,$value,$indent)
	{
		if (strpos($value,"\n") !== FALSE || strpos($value,": ") !== FALSE || strpos($value,"- ") !== FALSE)
		{
			$value = $this->_doLiteralBlock($value,$indent);
		}
		else
		{
			$value	= $this->_doFolding($value,$indent);
		}

		if (is_bool($value))
		{
			$value = ($value) ? "true" : "false";
		}

		$spaces = str_repeat(' ',$indent);

		if (is_int($key))
		{
			$string = $spaces.'- '.$value."\n";
		}
		else
		{
			$string = $spaces.$key.': '.$value."\n";
		}
		return $string;
	}

	/**
     * Creates a literal block for dumping
     * @access private
     * @return string
     * @param $value
     * @param $indent int The value of the indent
     */
	function _doLiteralBlock($value,$indent)
	{
		$exploded = explode("\n",$value);
		$newValue = '|';
		$indent	+= $this->_dumpIndent;
		$spaces	 = str_repeat(' ',$indent);
		foreach ($exploded as $line)
		{
			$newValue .= "\n" . $spaces . trim($line);
		}
		return $newValue;
	}

	/**
     * Folds a string of text, if necessary
     * @access private
     * @return string
     * @param $value The string you wish to fold
     */
	function _doFolding($value,$indent)
	{
		if ($this->_dumpWordWrap === 0)
		{
			return $value;
		}

		if (strlen($value) > $this->_dumpWordWrap)
		{
			$indent += $this->_dumpIndent;
			$indent = str_repeat(' ',$indent);
			$wrapped = wordwrap($value,$this->_dumpWordWrap,"\n$indent");
			$value	 = ">\n".$indent.$wrapped;
		}
		return $value;
	}


	function load($input)
	{
		$Source = $this->loadFromSource($input);
		if (empty ($Source))
		{
			return array();
		}
		$this->path = array();
		$this->result = array();

		for ($i = 0; $i < count($Source); $i++)
		{
			$line = $Source[$i];
			$lineIndent = $this->_getIndent($line);
			$this->path = $this->getParentPathByIndent($lineIndent);
			$line = $this->stripIndent($line, $lineIndent);
			if ($this->isComment($line))
			{
				continue;
			}
			if ($literalBlockStyle = $this->startsLiteralBlock($line))
			{
				$line = rtrim ($line, $literalBlockStyle . "\n");
				$literalBlock = '';
				$line .= $this->LiteralPlaceHolder;
				while ($this->literalBlockContinues($Source[++$i], $lineIndent))
				{
					$literalBlock = $this->addLiteralLine($literalBlock, $Source[$i], $literalBlockStyle);
				}
				$i--;
			}
			$lineArray = $this->_parseLine($line);
			if ($literalBlockStyle)
			{
				$lineArray = $this->revertLiteralPlaceHolder ($lineArray, $literalBlock);
			}

			$this->addArray($lineArray, $lineIndent);
		}
		return $this->result;
	}

	function loadFromSource($input)
	{
		if (!empty($input) && strpos($input, "\n") === FALSE && file_exists($input))
		{
			return file($input);
		}

		$foo = explode("\n",$input);
		foreach ($foo as $k => $_)
		{
			$foo[$k] = trim ($_, "\r");
		}
		return $foo;
	}

	/**
     * Finds and returns the indentation of a YAML line
     * @access private
     * @return int
     * @param string $line A line from the YAML file
     */
	function _getIndent($line)
	{
		if (!preg_match('/^ +/',$line,$match))
		{
			return 0;
		}
		if (!empty($match[0]))
		{
			return strlen ($match[0]);
		}
		return 0;
	}

	/**
     * Parses YAML code and returns an array for a node
     * @access private
     * @return array
     * @param string $line A line from the YAML file
     */
	function _parseLine($line)
	{
		if (!$line)
		{
			return array();
		}
		$line = trim($line);
		if (!$line)
		{
			return array();
		}
		$array = array();

		if ($group = $this->nodeContainsGroup($line))
		{
			$this->addGroup($line, $group);
			$line = $this->stripGroup ($line, $group);
		}
		if ($this->startsMappedSequence($line))
		{
			return $this->returnMappedSequence($line);
		}
		if ($this->startsMappedValue($line))
		{
			return $this->returnMappedValue($line);
		}
		if ($this->isArrayElement($line))
		{
		 	return $this->returnArrayElement($line);
		}

		return $this->returnKeyValuePair($line);

	}



	/**
     * Finds the type of the passed value, returns the value as the new type.
     * @access private
     * @param string $value
     * @return mixed
     */
	function _toType($value)
	{
		if (strpos($value, '#') !== FALSE)
		{
			$value = trim(preg_replace('/#(.+)$/','',$value));
		}

		if (preg_match('/^("(.*)"|\'(.*)\')/',$value,$matches))
		{
			$value = (string)preg_replace('/(\'\'|\\\\\')/',"'",end($matches));
			$value = preg_replace('/\\\\"/','"',$value);
		}
		elseif (preg_match('/^\\[(.+)\\]$/',$value,$matches))
		{
			$explode = $this->_inlineEscape($matches[1]);

			$value	= array();
			foreach ($explode as $v)
			{
				$value[] = $this->_toType($v);
			}
		}
		elseif (strpos($value,': ')!==FALSE && !preg_match('/^{(.+)/',$value))
		{
			$array = explode(': ',$value);
			$key	 = trim($array[0]);
			array_shift($array);
			$value = trim(implode(': ',$array));
			$value = $this->_toType($value);
			$value = array($key => $value);
		}
		elseif (preg_match("/{(.+)}$/",$value,$matches))
		{
			$explode = $this->_inlineEscape($matches[1]);

			$array = array();
			foreach ($explode as $v)
			{
				$array = $array + $this->_toType($v);
			}
			$value = $array;
		}
		elseif (strtolower($value) == 'null' or $value == '' or $value == '~')
		{
			$value = null;
		}
		elseif (preg_match ('/^[0-9]+$/', $value))
		{
			$value = (int)$value;
		}
		elseif (in_array(strtolower($value), array('true')))
		{
			$value = TRUE;
		}
		elseif (in_array(strtolower($value), array('false')))
		{
			$value = FALSE;
		}
		elseif (is_numeric($value))
		{
			$value = (float)$value;
		}
		else {

		}
		return $value;
	}

	/**
     * Used in inlines to check for more inlines or quoted strings
     * @access private
     * @return array
     */
	function _inlineEscape($inline)
	{
		$saved_strings = array();

		$regex = '/(?:(")|(?:\'))((?(1)[^"]+|[^\']+))(?(1)"|\')/';
		if (preg_match_all($regex,$inline,$strings))
		{
			$saved_strings = $strings[0];
			$inline	= preg_replace($regex,'YAMLString',$inline);
		}
		unset($regex);

		if (preg_match_all('/\[(.+)\]/U',$inline,$seqs))
		{
			$inline = preg_replace('/\[(.+)\]/U','YAMLSeq',$inline);
			$seqs	 = $seqs[0];
		}

		if (preg_match_all('/{(.+)}/U',$inline,$maps))
		{
			$inline = preg_replace('/{(.+)}/U','YAMLMap',$inline);
			$maps	 = $maps[0];
		}

		$explode = explode(', ',$inline);

		if (!empty($seqs))
		{
			$i = 0;
			foreach ($explode as $key => $value)
			{
				if (strpos($value,'YAMLSeq') !== FALSE)
				{
					$explode[$key] = str_replace('YAMLSeq',$seqs[$i],$value);
					++$i;
				}
			}
		}
		if (!empty($maps))
		{
			$i = 0;
			foreach ($explode as $key => $value)
			{
				if (strpos($value,'YAMLMap') !== FALSE)
				{
					$explode[$key] = str_replace('YAMLMap',$maps[$i],$value);
					++$i;
				}
			}
		}
		if (!empty($saved_strings))
		{
			$i = 0;
			foreach ($explode as $key => $value)
			{
				while (strpos($value,'YAMLString') !== FALSE)
				{
					$explode[$key] = preg_replace('/YAMLString/',$saved_strings[$i],$value, 1);
					++$i;
					$value = $explode[$key];
				}
			}
		}
		return $explode;
	}

	function literalBlockContinues($line, $lineIndent)
	{
		if (!trim($line))
		{
			return TRUE;
		}
		if ($this->_getIndent($line) > $lineIndent)
		{
			return TRUE;
		}
		return FALSE;
	}

	function addArray($array, $indent)
	{

		$key = key ($array);
		if (!isset ($array[$key]))
		{
			return FALSE;
		}
		if ($array[$key] === array()) 
		{ 
			$array[$key] = ''; 
		}
		
		$value = $array[$key];
		$tempPath = Spyc::flatten ($this->path);
		eval ('$_arr = $this->result' . $tempPath . ';');

		if ($this->_containsGroupAlias)
		{
			do
			{
				if (!isset($this->SavedGroups[$this->_containsGroupAlias])) 
				{
					echo "Bad group name: $this->_containsGroupAlias."; 
					break;
				}
				$groupPath = $this->SavedGroups[$this->_containsGroupAlias];
				eval ('$value = $this->result' . Spyc::flatten ($groupPath) . ';');
			} 
			while (FALSE);
			$this->_containsGroupAlias = FALSE;
		}

		if ($key)
		{
			$_arr[$key] = $value;
		}
		else
		{
			if (!is_array ($_arr)) 
			{
				$_arr = array ($value);
				$key = 0; 
			}
			else 
			{ 
				$_arr[] = $value; 
				end($_arr); 
				$key = key($_arr);
			}
		}

		$this->path[$indent] = $key;

		eval ('$this->result' . $tempPath . ' = $_arr;');

		if ($this->_containsGroupAnchor)
		{
			$this->SavedGroups[$this->_containsGroupAnchor] = $this->path;
			$this->_containsGroupAnchor = FALSE;
		}
	}

	function flatten($array)
	{
		$tempPath = array();
		if (!empty ($array))
		{
			foreach ($array as $_)
			{
				if (!is_int($_)) 
				{
					$_ = "'$_'";
				}
				$tempPath[] = "[$_]";
			}
		}
		$tempPath = implode ('', $tempPath);
		return $tempPath;
	}

	function startsLiteralBlock($line)
	{
		$lastChar = substr (trim($line), -1);
		if (in_array ($lastChar, $this->LiteralBlockMarkers))
		{
			return $lastChar;
		}
		return FALSE;
	}

	function addLiteralLine($literalBlock, $line, $literalBlockStyle)
	{
		$line = $this->stripIndent($line);
		$line = str_replace ("\r\n", "\n", $line);

		if ($literalBlockStyle == '|')
		{
			return $literalBlock . $line;
		}
		if (strlen($line) == 0) 
		{
			return $literalBlock . "\n";
		}
		if ($line != "\n")
		{
			$line = trim ($line, "\r\n ") . " ";
		}
		return $literalBlock . $line;
	}

	function revertLiteralPlaceHolder($lineArray, $literalBlock)
	{
		foreach ($lineArray as $k => $_)
		{
			if (substr($_, -1 * strlen ($this->LiteralPlaceHolder)) == $this->LiteralPlaceHolder)
			{
				$lineArray[$k] = rtrim ($literalBlock, " \r\n");
			}
		}
		return $lineArray;
	}

	function stripIndent($line, $indent = -1)
	{
		if ($indent == -1)
		{
			$indent = $this->_getIndent($line);
		}
		return substr ($line, $indent);
	}

	function getParentPathByIndent($indent)
	{
		if ($indent == 0)
		{
			return array();
		}

		$linePath = $this->path;
		do
		{
			end($linePath); 
			$lastIndentInParentPath = key($linePath);
			if ($indent <= $lastIndentInParentPath)
			{
				array_pop ($linePath);
			}
		} 
		while ($indent <= $lastIndentInParentPath);
		return $linePath;
	}

	function clearBiggerPathValues($indent)
	{
		if ($indent == 0)
		{
			$this->path = array();
		}
		if (empty ($this->path))
		{
			return TRUE;
		}
		foreach ($this->path as $k => $_)
		{
			if ($k > $indent) unset ($this->path[$k]);
		}
		return TRUE;
	}

	function isComment($line)
	{
		if (preg_match('/^#/', $line))
		{
			return TRUE;
		}
		return FALSE;
	}

	function isArrayElement($line)
	{
		if (!$line)
		{
			return FALSE;
		}
		if ($line[0] != '-')
		{
			return FALSE;
		}
		if (strlen ($line) > 3)
		{
			if (substr($line,0,3) == '---')
			{
				return FALSE;
			}
		}
		return TRUE;
	}

	function isHashElement($line)
	{
		if (!preg_match('/^(.+?):/', $line, $matches))
		{
			return FALSE;
		}
		$allegedKey = $matches[1];
		if ($allegedKey)
		{
			return TRUE;
		}
		return FALSE;
	}

	function isLiteral($line)
	{
		if ($this->isArrayElement($line))
		{
			return FALSE;
		}
		if ($this->isHashElement($line))
		{
			return FALSE;
		}
		return TRUE;
	}

	function startsMappedSequence($line)
	{
		if (preg_match('/^-(.*):$/',$line))
		{
			return TRUE;
		}
	}

	function returnMappedSequence($line)
	{
		$array = array();
		$key = trim(substr(substr($line,1),0,-1));
		$array[$key] = '';
		return $array;
	}

	function returnMappedValue($line)
	{
		$array = array();
		$key = trim(substr($line,0,-1));
		$array[$key] = '';
		return $array;
	}

	function startsMappedValue($line)
	{
		if (preg_match('/^(.*):$/',$line))
		{
			return TRUE;
		}
	}

	function returnKeyValuePair($line)
	{
		$array = array();
		if (preg_match('/^(.+):/',$line,$key))
		{
			if (preg_match('/^(["\'](.*)["\'](\s)*:)/',$line,$matches))
			{
				$value = trim(str_replace($matches[1],'',$line));
				$key = $matches[2];
			}
			else
			{
				$explode = explode(':',$line);
				$key = trim($explode[0]);
				array_shift($explode);
				$value = trim(implode(':',$explode));
			}

			$value = $this->_toType($value);
			if (empty($key))
			{
				$array[] = $value;
			}
			else
			{
				$array[$key] = $value;
			}
		}
		return $array;
	}

	function returnArrayElement($line)
	{
		 if (strlen($line) <= 1)
		 {
			 return array(array());
		 }
		 $array = array();
		 $value	 = trim(substr($line,1));
		 $value	 = $this->_toType($value);
		 $array[] = $value;
		 return $array;
	}

	function nodeContainsGroup($line)
	{
		if (strpos($line, '&') === FALSE && strpos($line, '*') === FALSE)
		{
			return FALSE;
		}
		if (preg_match('/^(&[^ ]+)/', $line, $matches))
		{
			return $matches[1];
		}
		if (preg_match('/^(\*[^ ]+)/', $line, $matches))
		{
			return $matches[1];
		}
		if (preg_match('/(&[^" ]+$)/', $line, $matches))
		{
			return $matches[1];
		}
		if (preg_match('/(\*[^" ]+$)/', $line, $matches))
		{
			return $matches[1];
		}
		return FALSE;
	}

	function addGroup($line, $group)
	{
		if (substr ($group, 0, 1) == '&')
		{
			$this->_containsGroupAnchor = substr ($group, 1);
		}
		if (substr ($group, 0, 1) == '*')
		{
			$this->_containsGroupAlias = substr ($group, 1);
		}
	}

	function stripGroup($line, $group)
	{
		$line = trim(str_replace($group, '', $line));
		return $line;
	}
}

/* End of file test.php */
/* Location: ./application/libraries/phpunit/Spyc/test.php */