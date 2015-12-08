<?php
    /**
     * Valid values are dev and production.
     * A configuration file with the same name will be included if it exists.
     */
    if (!empty(get_cfg_var('codecept.access_log'))) {
        return 'codeception';
    }
    return 'dev';
