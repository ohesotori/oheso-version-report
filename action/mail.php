<?php

namespace oheso\action;

class mail
{
    /**
     * Send version report via email to the site administrator.
     */
    public function __construct()
    {
        $data = get_option(\oheso\OHESO_OPTION_DATA);
        $saveddate = get_option(\oheso\OHESO_OPTION_DATE);

        if ($data === false) {
            return;
        }

        $to = is_multisite() ? get_site_option('admin_email') : get_option('admin_email');
        $subject = sprintf('[%s] Version Report', get_bloginfo('name'));

        $message = $this->build_message($data, $saveddate);

        wp_mail($to, $subject, $message);
    }

    private function build_message($data, $saveddate)
    {
        $timeformat = 'Y-m-d H:i:s';
        $lines = [];
        $lines[] = 'WordPress Version Report';
        $lines[] = 'Site: ' . get_bloginfo('name');
        $lines[] = 'URL: ' . get_bloginfo('url');
        $lines[] = 'Report Created: ' . date($timeformat, $saveddate);
        $lines[] = '';

        $lines[] = 'Core:';
        if ($data['core']['cur'] !== $data['core']['new']) {
            $lines[] = sprintf('  %s -> %s', $data['core']['cur'], $data['core']['new']);
        } else {
            $lines[] = '  up to date';
        }
        $lines[] = '';

        $lines[] = 'Plugins:';
        if (!empty($data['plugins'])) {
            foreach ($data['plugins'] as $v) {
                $lines[] = sprintf('  %s: %s -> %s', $v['name'], $v['cur'], $v['new']);
            }
        } else {
            $lines[] = '  no updates';
        }
        $lines[] = '';

        $lines[] = 'Themes:';
        if (!empty($data['themes'])) {
            foreach ($data['themes'] as $v) {
                $lines[] = sprintf('  %s: %s -> %s', $v['name'], $v['cur'], $v['new']);
            }
        } else {
            $lines[] = '  no updates';
        }

        return implode("\n", $lines);
    }
}
