<?php
/*
Plugin Name: oheso-version-report
Plugin URI: https://wordpress.org/plugins/oheso-version-report/
Description: Display update guidance, version of plug-in and date of update collectively.
Author: ohesotori@gmail.com
Text Domain: oheso-version-report
Author URI: https://ohesotori.hateblo.jp/
Version: 0.5
*/

namespace oheso;

// define は namespace に関係なくグローバルで定義されるため const で定義
const OHESO_PLUGIN_NAME = 'oheso-version-report';
const OHESO_OPTION_DATA = 'oheso_version_report_saveddata';
const OHESO_OPTION_DATE = 'oheso_version_report_saveddate';

// only admin page
if (is_admin()) {
    $ovr = new OhesoVersionReport();
}

/**
 * Oheso Version Report.
 */
class OhesoVersionReport
{
    public function __construct()
    {
        // set autoloader
        require_once 'autoload.php';
        new autoload();

        add_action('admin_menu', array($this, 'add_plugin_page'));
    }

    /**
     * Menu.
     */
    public function add_plugin_page()
    {
        add_submenu_page(
            'options-general.php',
            'Version Report',
            'Version Report',
            'update_core',
            'versionreport',
            array($this, 'process')
            );
    }

    /**
     * process.
     */
    public function process()
    {
        $action = isset($_POST['action']) ? $_POST['action'] : null;

        switch ($action) {
            // $action と同名のクラスを呼び出す
            case 'check_versions':
            case 'mail':
                $class = __NAMESPACE__ . '\\action\\' . $action;
                new $class;
                break;

            default:
                break;
        }

        // display template
        new theme\theme_loader();
    }
} // end of class
