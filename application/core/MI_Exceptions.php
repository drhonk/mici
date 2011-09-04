<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Core
 * @category MI_Exceptions
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class MI_Exceptions extends CI_Exceptions
{

    var $action;
    var $severity;
    var $message;
    var $filename;
    var $line;
    var $ob_level;
    var $framework_ini;
    var $log_threshold;
    var $levels = array(
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parsing Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice'
    );

	/**
	 * Some nice names for the error types
	 */
	public static $php_errors = array(
		E_ERROR				 => 'Fatal Error',
		E_USER_ERROR		 => 'User Error',
		E_PARSE				 => 'Parse Error',
		E_WARNING			 => 'Warning',
		E_USER_WARNING		 => 'User Warning',
		E_STRICT			 => 'Strict',
		E_NOTICE			 => 'Notice',
		E_RECOVERABLE_ERROR	 => 'Recoverable Error',
	);
	
	/**
	 * The Shutdown errors to show (all others will be ignored).
	 */
	public static $shutdown_errors = array(E_PARSE, E_ERROR, E_USER_ERROR, E_COMPILE_ERROR);

    /**
     * Constructor
     *
     */
    function __construct()
    {
        $this->ob_level = ob_get_level();

        /**
         * Load INI File
         *
         * Fetch Configuration for Framework INI File
         */
        $this->framework_ini = (array) unserialize(FRAMEWORK_INI);
        $this->log_threshold = $this->framework_ini['config']['log_threshold'];

		parent::__construct();
		
		// If we are in production, then lets dump out now.
		if (IN_PRODUCTION)
		{
			return;
		}
		
		//Set the Exception Handler
		set_exception_handler(array('MI_Exceptions', 'exception_handler'));

		// Set the Error Handler
		set_error_handler(array('MI_Exceptions', 'error_handler'));

		// Set the handler for shutdown to catch Parse errors
		register_shutdown_function(array('MI_Exceptions', 'shutdown_handler'));

		// This is a hack to set the default timezone if it isn't set. Not setting it causes issues.
		date_default_timezone_set(date_default_timezone_get());
    }

    /**
     * Exception Logger
     *
     * This function logs PHP generated error messages
     *
     * @access	private
     * @param	string	the error severity
     * @param	string	the error string
     * @param	string	the error filepath
     * @param	string	the error line number
     * @return	string
     */
    function log_exception($severity, $message, $filepath, $line)
    {
        if($this->log_threshold > 0)
        {
            $severity = (!isset($this->levels[$severity]))
                    ? $severity
                    : $this->levels[$severity];

            if($this->log_threshold == 4)
            {
                log_message('error', 'Severity: ' . $severity . '  --> ' . $message . ' ' . $filepath . ' ' . $line, TRUE);
            }
            else if($severity != 'Notice' && $severity != 'Warning' && $severity != 'User Notice' && $severity != 'User Warning' && $severity != 'Runtime Notice')
            {
                log_message('error', 'Severity: ' . $severity . '  --> ' . $message . ' ' . $filepath . ' ' . $line, TRUE);
            }
        }
    }

    /**
     * 404 Page Not Found Handler
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function show_404($page = '', $log_error = TRUE)
    {
        $heading = "404 Page Not Found";
        $message = "The page you requested was not found.";

        // By default we log this, but allow a dev to skip it
        if ($log_error)
        {
            log_message('error', '404 Page Not Found --> ' . $page);
        }

        echo $this->show_error($heading, $message, 'error_404', 404);
        exit;
    }

    /**
     * General Error Page
     *
     * This function takes an error message as input
     * (either as a string or an array) and displays
     * it using the specified template.
     *
     * @access	private
     * @param	string	the heading
     * @param	string	the message
     * @param	string	the template name
     * @return	string
     */
    function show_error($heading, $message, $template = 'error_php_custom', $status_code = 500)
    {
        if(!headers_sent())
		{
			set_status_header($status_code);
		}
		
		$trace = debug_backtrace();
		$file = NULL;
		$line = NULL;
		
		// If the application called show_error, don't output a backtrace, just the error
		if(isset($trace[0]['file']) AND strpos($trace[1]['file'], ABS_APPPATH) === 0)
		{
			$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

			if (ob_get_level() > $this->ob_level + 1)
			{
				ob_end_flush();	
			}
			
			ob_start();
			include(APPPATH.'errors/'.$template.EXT);
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		}
		
		// If the system called show_error, so lets find the actual file and line in application/ that caused it.
		foreach($trace as $call)
		{
			if(isset($call['file']) AND strpos($call['file'], ABS_APPPATH) === 0)
			{
				$file = $call['file'];
				$line = $call['line'];
				break;
			}
		}
		unset($trace);
		
		if (error_reporting())
        {
			$exception = new ErrorException('<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>', null, E_ERROR, $file, $line);
		}
		
		self::exception_handler($exception);
		return;
    }

    /**
     * Native PHP error handler
     *
     * @access	private
     * @param	string	the error severity
     * @param	string	the error string
     * @param	string	the error filepath
     * @param	string	the error line number
     * @return	string
     */
    function show_php_error($severity, $message, $filepath, $line)
    {
        $severity = (!isset($this->levels[$severity]))
                ? $severity
                : $this->levels[$severity];

        $filepath = str_replace("\\", "/", $filepath);

        // For safety reasons we do not show the full file path
        if (FALSE !== strpos($filepath, '/'))
        {
            $x = explode('/', $filepath);
            $filepath = $x[count($x) - 2] . '/' . end($x);
        }

        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }

        ob_start();
        include(APPPATH . 'errors/error_php_custom' . EXT);
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }

	/**
	 * Debug Path
	 *
	 * This makes nicer looking paths for the error output.
	 *
	 * @access	public
	 * @param	string	$file
	 * @return	string
	 */
	public static function debug_path($file)
	{
		if (strpos($file, ABS_APPPATH) === 0)
		{
			$file = 'APPPATH/'.substr($file, strlen(ABS_APPPATH));
		}
		elseif (strpos($file, ABS_SYSDIR) === 0)
		{
			$file = 'SYSDIR/'.substr($file, strlen(ABS_SYSDIR));
		}
		elseif (strpos($file, FCPATH) === 0)
		{
			$file = 'FCPATH/'.substr($file, strlen(FCPATH));
		}

		return $file;
	}
	
	/**
	 * Error Handler
	 *
	 * Converts all errors into ErrorExceptions. This handler
	 * respects error_reporting settings.
	 *
	 * @access	public
	 * @throws	ErrorException
	 * @return	bool
	 */
	public static function error_handler($code, $error, $file = NULL, $line = NULL)
	{
		if (error_reporting() & $code)
		{
			// This error is not suppressed by current error reporting settings
			// Convert the error into an ErrorException
			self::exception_handler(new ErrorException($error, $code, 0, $file, $line));
		}

		// Do not execute the PHP error handler
		return TRUE;
	}

	/**
	 * Exception Handler
	 *
	 * Displays the error message, source of the exception, and the stack trace of the error.
	 *
	 * @access	public
	 * @param	object	 exception object
	 * @return	boolean
	 */
	public static function exception_handler(Exception $e)
	{
		try
		{
			// Get the exception information
			$type	 = get_class($e);
			$code	= $e->getCode();
			$message = $e->getMessage();
			$file	 = $e->getFile();
			$line	 = $e->getLine();

			// Create a text version of the exception
			$error = self::exception_text($e);

			// Log the error message
			log_message('error', $error, TRUE);

			// Get the exception backtrace
			$trace = $e->getTrace();

			if ($e instanceof ErrorException)
			{
				if (isset(self::$php_errors[$code]))
				{
					// Use the human-readable error name
					$code = self::$php_errors[$code];
				}

				if (version_compare(PHP_VERSION, '5.3', '<'))
				{
					// Workaround for a bug in ErrorException::getTrace() that exists in
					// all PHP 5.2 versions. @see http://bugs.php.net/bug.php?id=45895
					for ($i = count($trace) - 1; $i > 0; --$i)
					{
						if (isset($trace[$i - 1]['args']))
						{
							// Re-position the args
							$trace[$i]['args'] = $trace[$i - 1]['args'];

							// Remove the args
							unset($trace[$i - 1]['args']);
						}
					}
				}
			}
			// Start an output buffer
			ob_start();

			// This will include the custom error file.
			require ABS_APPPATH . 'errors/error_php_custom.php';

			// Display the contents of the output buffer
			echo ob_get_clean();

			return TRUE;
		}
		catch (Exception $e)
		{
			// Clean the output buffer if one exists
			ob_get_level() and ob_clean();

			// Display the exception text
			echo self::exception_text($e), "\n";

			// Exit with an error status
			exit(1);
		}
	}
	
	/**
	 * Shutdown Handler
	 *
	 * Catches errors that are not caught by the error handler, such as E_PARSE.
	 *
	 * @access	public
	 * @return	void
	 */
	public static function shutdown_handler()
	{
		$error = error_get_last();
		if ($error = error_get_last() AND in_array($error['type'], self::$shutdown_errors))
		{
			// Clean the output buffer
			ob_get_level() and ob_clean();

			// Fake an exception for nice debugging
			self::exception_handler(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));

			// Shutdown now to avoid a "death loop"
			exit(1);
		}
	}
	
	/**
	 * Exception Text
	 *
	 * Makes a nicer looking, 1 line extension.
	 *
	 * @access	public
	 * @param	object	Exception
	 * @return	string
	 */
	public static function exception_text(Exception $e)
	{
		return sprintf('%s [ %s ]: %s ~ %s [ %d ]',
			get_class($e), $e->getCode(), strip_tags($e->getMessage()), $e->getFile(), $e->getLine());
	}
	
	/**
	 * Debug Source
	 *
	 * Returns an HTML string, highlighting a specific line of a file, with some
	 * number of lines padded above and below.
	 *
	 * @access	public
	 * @param	string	 file to open
	 * @param	integer	 line number to highlight
	 * @param	integer	 number of padding lines
	 * @return	string	 source of file
	 * @return	FALSE	 file is unreadable
	 */
	public static function debug_source($file, $line_number, $padding = 5)
	{
		if ( ! $file OR ! is_readable($file))
		{
			// Continuing will cause errors
			return FALSE;
		}

		// Open the file and set the line position
		$file = fopen($file, 'r');
		$line = 0;

		// Set the reading range
		$range = array('start' => $line_number - $padding, 'end' => $line_number + $padding);

		// Set the zero-padding amount for line numbers
		$format = '% '.strlen($range['end']).'d';

		$source = '';
		while (($row = fgets($file)) !== FALSE)
		{
			// Increment the line number
			if (++$line > $range['end'])
				break;

			if ($line >= $range['start'])
			{
				// Make the row safe for output
				$row = htmlspecialchars($row, ENT_NOQUOTES);

				// Trim whitespace and sanitize the row
				$row = '<span class="number">'.sprintf($format, $line).'</span> '.$row;

				if ($line === $line_number)
				{
					// Apply highlighting to this row
					$row = '<span class="line highlight">'.$row.'</span>';
				}
				else
				{
					$row = '<span class="line">'.$row.'</span>';
				}

				// Add to the captured source
				$source .= $row;
			}
		}

		// Close the file
		fclose($file);

		return '<pre class="source"><code>'.$source.'</code></pre>';
	}
	
	/**
	 * Trace
	 *
	 * Returns an array of HTML strings that represent each step in the backtrace.
	 *
	 * @access	public
	 * @param	string	path to debug
	 * @return	string
	 */
	public static function trace(array $trace = NULL)
	{
		if ($trace === NULL)
		{
			// Start a new trace
			$trace = debug_backtrace();
		}

		// Non-standard function calls
		$statements = array('include', 'include_once', 'require', 'require_once');

		$output = array();
		foreach ($trace as $step)
		{
			if ( ! isset($step['function']))
			{
				// Invalid trace step
				continue;
			}

			if (isset($step['file']) AND isset($step['line']))
			{
				// Include the source of this step
				$source = self::debug_source($step['file'], $step['line']);
			}

			if (isset($step['file']))
			{
				$file = $step['file'];

				if (isset($step['line']))
				{
					$line = $step['line'];
				}
			}

			// function()
			$function = $step['function'];

			if (in_array($step['function'], $statements))
			{
				if (empty($step['args']))
				{
					// No arguments
					$args = array();
				}
				else
				{
					// Sanitize the file path
					$args = array($step['args'][0]);
				}
			}
			elseif (isset($step['args']))
			{
				if (strpos($step['function'], '{closure}') !== FALSE)
				{
					// Introspection on closures in a stack trace is impossible
					$params = NULL;
				}
				else
				{
					if (isset($step['class']))
					{
						if (method_exists($step['class'], $step['function']))
						{
							$reflection = new ReflectionMethod($step['class'], $step['function']);
						}
						else
						{
							$reflection = new ReflectionMethod($step['class'], '__call');
						}
					}
					else
					{
						$reflection = new ReflectionFunction($step['function']);
					}

					// Get the function parameters
					$params = $reflection->getParameters();
				}

				$args = array();

				foreach ($step['args'] as $i => $arg)
				{
					if (isset($params[$i]))
					{
						// Assign the argument by the parameter name
						$args[$params[$i]->name] = $arg;
					}
					else
					{
						// Assign the argument by number
						$args[$i] = $arg;
					}
				}
			}

			if (isset($step['class']))
			{
				// Class->method() or Class::method()
				$function = $step['class'].$step['type'].$step['function'];
			}

			$output[] = array(
				'function' => $function,
				'args'	   => isset($args)	 ? $args : NULL,
				'file'	   => isset($file)	 ? $file : NULL,
				'line'	   => isset($line)	 ? $line : NULL,
				'source'   => isset($source) ? $source : NULL,
			);

			unset($function, $args, $file, $line, $source);
		}

		return $output;
	}
}

/* End of file MI_Exceptions.php */
/* Location: ./application/core/MI_Exceptions.php */