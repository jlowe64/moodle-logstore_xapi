<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'logstore_emitter\\' => array($baseDir . '/classes'),
    'XREmitter\\' => array($vendorDir . '/learninglocker/xapi-recipe-emitter/src'),
    'TinCan\\' => array($vendorDir . '/rusticisoftware/tincan/src'),
    'Tests\\' => array($baseDir . '/Tests', $vendorDir . '/learninglocker/moodle-log-expander/tests', $vendorDir . '/learninglocker/moodle-xapi-translator/tests', $vendorDir . '/learninglocker/xapi-recipe-emitter/tests'),
    'MXTranslator\\' => array($vendorDir . '/learninglocker/moodle-xapi-translator/src'),
    'LogExpander\\' => array($vendorDir . '/learninglocker/moodle-log-expander/src'),
);
