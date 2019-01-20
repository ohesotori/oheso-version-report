<?php

if (is_admin()) {
    delete_option('oheso_version_report_saveddata');
    delete_option('oheso_version_report_saveddate');
}
