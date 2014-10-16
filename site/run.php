<?php


include 'restservice_class.php';
include 'joomlaconfig_class.php';

class DiatemJoomlaGetConfig extends RestService{
    public function __construct() {
	parent::__construct();
    }
    
    public function _get(){
	if($this->get_request_method() != 'GET'){
	    $this->response('', 405);
	}
	
	$retStr = JoomlaConfig::getJSon();
	$this->response($retStr, 200);
    }
}

$api = new DiatemJoomlaGetConfig();
$api->setSecured(JoomlaConfig::getSecuredKeys());
$api->processApi();

exit();


if (version_compare(PHP_VERSION, '5.3.1', '<')) {
    die('Your host needs to use PHP 5.3.1 or higher to run this version of Joomla!');
}

define('_JEXEC', 1);

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/defines.php')) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/defines.php';
}

if (!defined('_JDEFINES')) {
    define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
    require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

jimport('joomla.application.module.helper');

$app = JFactory::getApplication('site');

$app = &JFactory::getApplication();
$params = JComponentHelper::getParams('com_diatem_joomla_3x_getconfig');
var_dump($params);
//$params = $app->getParams();
/*
  jimport('joomla.application.module.helper' );
  jimport( 'joomla.html.parameter' );
  $module = &JModuleHelper::getModule('mod_diatem_joomlagetconfig');
  $moduleParams = new JParameter($module->params);
  $jparams = new JRegistry($module->params);
  $variable = $jparams->get('joomlagetconfig_publicKey');

  var_dump($variable);
 */
//$params = $app->getParams();
//var_dump($params);

echo 'end';
