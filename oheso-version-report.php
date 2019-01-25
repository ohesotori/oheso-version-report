<?php
/*
Plugin Name: oheso-version-report
Plugin URI: https://wordpress.org/plugins/oheso-version-report/
Description: Display update guidance, version of plug-in and date of update collectively.
Author: ohesotori@gmail.com
Text Domain: oheso-version-report
Author URI: https://ohesotori.hateblo.jp/
Version: 0.1
*/

namespace oheso;

define('OHESO_API_URL', 'https://api.wordpress.org/plugins/info/1.0/');
define('OHESO_WPPLUGIN_URL', 'https://wordpress.org/plugins/');

define('OHESO_PLUGIN_NAME', 'oheso-version-report');

/**
 * Oheso Version Report.
 */
class OhesoVersionReport
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));

        add_action('admin_menu', array($this, 'add_plugin_page'));
    }

    /**
     * autoload
     */
    function autoload($class) {
        // クラス名を \ を区切りにして配列化する
        $class_path = explode('\\', $class);
    
        // クラスのネームスペースが一致するかチェック
        if ($class_path[0] === __NAMESPACE__) {
            // ファイルパスを作るために、namespace のルートをこのディレクトリに変更
            $class_path[0] = __DIR__;
    
            // 配列を / でつないでファイル名を作る
            $file_path = implode(DIRECTORY_SEPARATOR, $class_path) . '.php';
    
            // 対象ファイルが実在すれば読み込む
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
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
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        switch ($action) {
        case 'check':
            $this->get_from_official();
            break;
        case 'mail':
            break;
        default:
            break;
        }

        $this->get_saved_data();

        $report = new theme\theme_loader();
        $report->get_report_theme($this->saveddata, $this->saveddate);
    }


    /**
     * get data list.
     */
    private function get_saved_data()
    {
        $this->saveddata = get_option('oheso_version_report_saveddata');
        $this->saveddate = get_option('oheso_version_report_saveddate');
    }

    /**
     * get Plugin Information from official directory;.
     */
    private function get_from_official()
    {
        global $wp_version;

        $core = get_site_transient('update_core');
        $plgn = get_site_transient('update_plugins');
        $thme = get_site_transient('update_themes');
        $in_plugins = get_plugins();
        $in_themes = wp_get_themes();

        $data = array();
        $data['core']['cur'] = preg_replace('/-.*$/', '', $wp_version);
        $data['core']['new'] = $core->updates[0]->current;

        foreach ($plgn->response as $path => $v) {
            $data['plugins'][] = array(
                'name' => $in_plugins[$path]['Name'],
                'cur' => $in_plugins[$path]['Version'],
                'new' => $v->new_version,
            );
        }
        foreach ($thme->response as $path => $v) {
            $data['themes'][] = array(
                'name' => $in_themes[$path]->Name,
                'cur' => $in_themes[$path]->Version,
                'new' => $v['new_version'],
            );
        }
        foreach ($in_plugins as $path => $v) {
            $apiurl = OHESO_API_URL.dirname($path);
            $json = wp_remote_get($apiurl);
            $plugin_info = $lastupdated = $updated = null;
			if ($json && $json['response']['code'] == 200 ) {
				$plugin_info = unserialize($json["body"]);
                if (!isset($plugin_info->error)) {
                    $lastupdated = $plugin_info->last_updated;
                    $updated = strtotime($plugin_info->last_updated);
                }
            }
            $data['in_plugins'][] = array(
                'path' => $path,
                'name' => $v['Name'],
                'ver' => $v['Version'],
                'apiurl' => $apiurl,
                'url' => OHESO_WPPLUGIN_URL.dirname($path),
                'lastupdated' => $lastupdated,
                'updated' => $updated,
            );
        } //endforeach

        update_option('oheso_version_report_saveddata', $data);
        update_option('oheso_version_report_saveddate', time());
    }
} // end of class


if (is_admin()) {
    $ovr = new OhesoVersionReport();
}
