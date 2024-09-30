<?php

namespace oheso\action;

class check_versions
{
    const OHESO_API_URL = 'https://api.wordpress.org/plugins/info/1.0/';
    const OHESO_WPPLUGIN_URL = 'https://wordpress.org/plugins/';
    
    /**
     * get Plugin Information from official directory;.
     */
    public function __construct()
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
            $apiurl = self::OHESO_API_URL.dirname($path);
            $json = wp_remote_get($apiurl);
            $plugin_info = $lastupdated = $updated = null;
            if ($json && $json['response']['code'] == 200 ) {
                $plugin_info = is_serialized($json["body"]) ? unserialize($json["body"]) : null;
                $lastupdated = $plugin_info->last_updated;
                $updated = isset($plugin_info->last_updated) ? strtotime($plugin_info->last_updated) : null;
            }
            $data['in_plugins'][] = array(
                'path' => $path,
                'name' => $v['Name'],
                'ver' => $v['Version'],
                'apiurl' => $apiurl,
                'url' => self::OHESO_WPPLUGIN_URL.dirname($path),
                'lastupdated' => $lastupdated,
                'updated' => $updated,
            );
        } //endforeach

        update_option(\oheso\OHESO_OPTION_DATA, $data);
        update_option(\oheso\OHESO_OPTION_DATE, time());
    }    
}