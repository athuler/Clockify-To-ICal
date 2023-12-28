<?php
/**
 * zapcallib.php
 *
 * @package	ZapCalLib
 * @author	Dan Cogliano <http://zcontent.net>
 * @copyright   Copyright (C) 2006 - 2017 by Dan Cogliano
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link	http://icalendar.org/php-library.html
 */

/**
 * used by ZapCalLib
 * @var integer
 */
define('_ZAPCAL',1);

if(!defined('_ZAPCAL_BASE'))
{
	/**
	 * the base folder of the library
	 * @var string
	 */
	define('_ZAPCAL_BASE',__DIR__);
}
//echo("\n" . _ZAPCAL_BASE . '\\includes\\framework.php' . "\n");
//require_once(_ZAPCAL_BASE . '\\includes\\framework.php');
//echo(__DIR__ . "\\includes\\framework.php");
//echo("\n==>" . __DIR__ . '\\includes\\framework.php' . "<==\n");
//echo("\n=>" . realpath('includes/framework.php') . "<=\n");
//require_once(realpath('includes/framework.php'));

$path = realpath(str_replace("\\","/",__DIR__ . "\\includes\\framework.php"));
//echo $path;
require_once($path);

