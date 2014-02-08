<?php
class MobileDetect {
	protected $accept;
	protected $userAgent;
	protected $isMobile     = false;
	public $isAndroid    = null;
	public $isIphone     = null;
	public $isBlackberry = null;
	public $isOpera      = null;
	public $isPalm		= null;
	public $isWindows    = null;
	public $isGeneric    = null;
	protected $devices = array(
		"Android"		=> "android",
		"Blackberry"	=> "blackberry",
		"Iphone"		=> "(iphone|ipod)",
		"Opera"			=> "opera mini",
		"Palm"			=> "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)",
		"Windows"		=> "windows ce; (iemobile|ppc|smartphone)",
		"Generic"		=> "(UCWEB|Nokia|kindle|mobile|mmp|midp|o2|webOS|240×320|400X240|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap)"
	);
	
	public function __construct() {
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];
		$this->accept    = $_SERVER['HTTP_ACCEPT'];
		if (isset($_SERVER['HTTP_X_WAP_PROFILE'])|| isset($_SERVER['HTTP_PROFILE'])) {
			$this->isMobile = true;
		} elseif (strpos($this->accept,'text/vnd.wap.wml') > 0 || strpos($this->accept,'application/vnd.wap.xhtml+xml') > 0) {
			$this->isMobile = true;
		} else {
			foreach ($this->devices as $device => $regexp) {
				if ($this->isDevice($device)) {
					$this->isMobile = true;
				}
			}
		}
	}
	
	public function __call($name, $arguments) {
		$device = substr($name, 2);
		if ($name == "is" . ucfirst($device)) {
			return $this->isDevice($device);
		} else {
			trigger_error("Method $name not defined", E_USER_ERROR);
		}
	}
	
	public function isMobile() {
		return $this->isMobile;
	}
	
	protected function isDevice($device) {
		$var   = "is" . ucfirst($device);
		$return = $this->$var === null ? (bool) preg_match("/" . $this->devices[$device] . "/i", $this->userAgent) : $this->$var;
		$this->$var = $return;
		if ($device != 'Generic' && $return == true) {
			$this->isGeneric = false;
		}
		return $return;
	}
}
?>