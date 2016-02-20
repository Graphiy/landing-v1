<?php

class model {

	private $mysql_link = null;
	private $mysql_gone = false;
	private static $instance = null; 
	
	//Singleton is not evil :)
	public static function get_instance() {
		if (is_null(self::$instance)) {
			$className = __CLASS__;
         self::$instance = new $className;
		}
		return self::$instance;
	}

	public function __construct () {
		$this->mysql_link = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		if ($this->mysql_link === false) {
			$this->log_error("Fatal mysql error. Could not connect to DB: " . mysql_error(), true, true);
		}
		$r = mysql_select_db(DB_DBNAME, $this->mysql_link);
		if ($r === false) {
			$this->log_error("Fatal mysql error. Could not select `". DB_DBNAME ."` database: " . mysql_error(), true, true);
		}		
		$r = $this->query("SET NAMES ".DB_CHARSET);
		if ($r) {
			if ($this->mysql_gone)
				$this->log_error("Reconnected successfully...", true);	
			$this->mysql_gone = false;
		}
    }

    public function addRow($data) {
        $r = $this->query("INSERT IGNORE INTO `". DB_TABLE ."` (`session_id`, `ip`, `question_id`, `answer`) VALUES ('{$data['session_id']}', '{$data['ip']}', {$data['question_id']}, '" . $this->escape($data['answer']) . "')");
        return $r;    
    }

	//etc

	public function optimize_tables ($array) {
		if (count($array) > 0) {
			foreach ($array as $value) {
				$this->query("OPTIMIZE TABLE `".DB_PREFIX."$value`");
			}
		}
	}
	
	public function get_insert_id () {
		return mysql_insert_id ($this->mysql_link);	
	}

	public function _q ($q) {
		return $this->query($q);
	}

	public function escape ($string) {
		return mysql_real_escape_string($string, $this->mysql_link);
	}

	private function query ($query) {
		//echo $query."\n";
		$result = mysql_query($query, $this->mysql_link);
		if ($result === false) {
			$m_error = mysql_error();
			$m_number = mysql_errno();
			if ( ($m_number == 2006 || $m_number == 2013) && !$this->mysql_gone) {
				$this->log_error("Mysql connection lost, trying to reconnect...", true);
				$this->mysql_gone = true;
				$this->__destruct();
				$this->__construct();
				$result = $this->query($query);
			} else {
				//echo $query."\n";
				$this->log_error("Fatal mysql error. {$m_number}:{$m_error}. Exiting. Raw query: {$query}", true, true);
			}
		}
	   return $result;
	}
	
	private function log_error ($text, $verbose = false, $fatal = false) {
		date_default_timezone_set(DEFAULT_TIMEZONE);
		file_put_contents(ERROR_LOG_FILE, date('H:i:s d.m.y')." ".$text."\n", FILE_APPEND);
		if ($verbose) echo $text."\n";
		if ($fatal) exit();
	}

	public function __destruct () {
		@mysql_close($this->mysql_link);
	}
}

?>
