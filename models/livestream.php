<?php
/**
* 
*/
class Livestream extends Model
{
	const table = 'livestream';
	const dbname = 'web';
	
	public $shortlink='';
	
	function getShortlink(){
		if(empty($this->shortlink)){
			$startpos = 0;
			if($pos = strpos($this->url, "live_video", $startpos)){
				$shortlink = substr($this->url, $pos+11, strpos($this->url, '/', $pos + 1) - $pos-11);
			}
		}
		return $this->shortlink;
	}
}
