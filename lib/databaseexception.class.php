<?php
/**
* 
*/
class DatabaseException extends Exception
{
	function __construct($e)
	{
		parent::__construct($e->getMessage());
	}
}
