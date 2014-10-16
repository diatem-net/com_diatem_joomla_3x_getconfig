<?php

class JoomlaConfig {

    static $loaded = false;

    public static function getJSon() {
	if (!self::$loaded) {
	    self::initJoomla();
	}
	return json_encode(self::getDataArray());
    }

    public static function getSecuredKeys() {
	if (!self::$loaded) {
	    self::initJoomla();
	}

	$params = JComponentHelper::getParams('com_diatem_joomla_3x_getconfig');
	return array($params->get('clepublique') => $params->get('cleprivee'));
    }

    private static function initJoomla() {

	if (version_compare(PHP_VERSION, '5.3.1', '<')) {
	    die('Your host needs to use PHP 5.3.1 or higher to run this version of Joomla!');
	}
	define('_JEXEC', 1);

	if (file_exists('../../defines.php')) {
	    include_once '../../defines.php';
	}

	if (!defined('_JDEFINES')) {
	    define('JPATH_BASE', '../../');
	    require_once JPATH_BASE . '/includes/defines.php';
	}

	require_once JPATH_BASE . '/includes/framework.php';
	JDEBUG ? $_PROFILER->mark('afterLoad') : null;

	jimport('joomla.application.module.helper');

	$app = &JFactory::getApplication('site');

	self::$loaded = true;
    }

    private static function getDataArray() {
	$output = array();

	$output['cms'] = self::getCms();
	$output['plugins'] = self::getPlugins();

	return $output;
    }

    private static function getCms() {
	$output = array();

	$jversion = new JVersion();

	$output['name'] = 'joomla';
	$output['version'] = $jversion->getShortVersion();
	return $output;
    }

    private static function getPlugins() {
	$output = array();

	// Get a db connection.
	$db = JFactory::getDbo();

	//Create a new query object .
	$query = $db->getQuery(true);

	// Select all records from the user profile table where key begins with "custom.".
	// Order it by the ordering field.
	$query->select($db->quoteName(array('extension_id', 'name', 'type', 'manifest_cache', 'enabled')));
	$query->from($db->quoteName('tb_extensions'));
	
	// Reset the query using our newly populated query object.
	$db->setQuery($query);

	// Load the results as a list of stdClass objects (see later for more options on retrieving data).
	$results = $db->loadObjectList();

	foreach ($results as $r) {
	    $mInfo = json_decode($r->manifest_cache);
	    if ($mInfo->author != 'Joomla! Project' 
		&& ($r->type == 'module' || $r->type == 'component' || $r->type == 'plugin')
		&& $r->name != 'plg_editors_codemirror'
		&& $r->name != 'plg_editors_none'
		&& $r->name != 'plg_editors_tinymce') {
		$line = array();
		$line['type'] = $r->type;
		$line['name'] = $r->name;
		$line['version'] = $mInfo->version;
		$line['editeur'] = $mInfo->author;
		$line['pluginUrl'] = $mInfo->authorUrl;
		$line['info'] = $mInfo->description;
		$line['enabled'] = ($r->enabled == 1) ? true : false;
		$output[] = $line;
		
	    }
	}

	return $output;
    }

}
