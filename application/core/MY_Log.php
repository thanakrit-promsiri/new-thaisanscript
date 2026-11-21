<?php defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class MY_Log extends CI_Log {

	/**
	 * Predefined logging levels:
	 * 1 = ERROR (actual PHP / application errors)
	 * 2 = INFO  (application usage & audit logs, e.g. Transliteration requests)
	 * 3 = DEBUG (verbose CodeIgniter core class initialization messages)
	 * 4 = ALL
	 *
	 * With log_threshold = 2 (or array(1, 2)), only ERROR and application INFO
	 * are written, without bloating the log file with framework init / file-load noise.
	 *
	 * @var array
	 */
	protected $_levels = array('ERROR' => 1, 'INFO' => 2, 'DEBUG' => 3, 'ALL' => 4);

	public function write_log($level, $msg)
	{
		$upper = strtoupper($level);
		if ($upper === 'INFO' && $this->_threshold < 3)
		{
			if ($this->_is_core_info_noise($msg))
			{
				return FALSE;
			}
		}

		return parent::write_log($level, $msg);
	}

	/**
	 * Returns TRUE if $msg is an internal CodeIgniter 3 framework 'info' trace
	 * (File loaded, Helper loaded, Language file loaded, * Class Initialized, etc.)
	 *
	 * @param	string	$msg
	 * @return	bool
	 */
	protected function _is_core_info_noise($msg)
	{
		if ($msg === 'Final output sent to browser'
			|| $msg === 'CSRF token verified'
			|| $msg === 'CSRF cookie sent')
		{
			return TRUE;
		}

		if (strpos($msg, 'File loaded: ') === 0
			|| strpos($msg, 'Helper loaded: ') === 0
			|| strpos($msg, 'Language file loaded: ') === 0
			|| strpos($msg, 'Model "') === 0
			|| strpos($msg, 'Session: Class initialized') === 0
			|| strpos($msg, 'Encryption: ') === 0
			|| strpos($msg, 'Javascript Class Initialized') === 0)
		{
			return TRUE;
		}

		if (substr($msg, -18) === ' Class Initialized'
			|| substr($msg, -19) === 'Model class loaded')
		{
			return TRUE;
		}

		return FALSE;
	}
}
