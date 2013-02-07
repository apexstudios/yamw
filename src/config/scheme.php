<?php
/*
 * Standard values
 *
 * model => None
 * dbtype => mysql
 *
 *
 * Cave-ats
 *
 * prefix_name
 *     Set it to true if you want to use the value from config
 *     Set to empty string if you want none
 */

return array(
    'units_space' => array(
        'prefix_name' => true,
        'model' => 'SpaceUnit'
    ),
    'units_ground' => array(
        'prefix_name' => true,
        // For now we use SpaceUnit
        'model' => 'SpaceUnit'
    ),
    'updates' => array(
        'prefix_name' => true,
        'model' => 'Update'
    ),
    'chat' => array(
        'prefix_name' => true,
        'model' => 'Chat'
    ),
    'staff' => array(
        'prefix_name' => true,
        'model' => 'About'
    ),
    'forum_cache' => array(
        'prefix_name' => true
    ),
    'meta' => array(
        'prefix_name' => true,
        // MMeta is used somewhere else
        'model' => 'None'
    ),
    'menu' => array(
        'prefix_name' => true,
        'model' => 'None'
    ),
    'menu_entries' => array(
        'prefix_name' => true,
        'model' => 'MenuEntry'
    ),

    'users' => array(
        'prefix_name' => 'mybb_',
        'model' => 'MybbUser'
    ),

    // MongoDB stuff
    'gallery' => array(
        'prefix_name' => '',
        'dbtype' => 'mongo'
    ),
    'media' => array(
        'prefix_name' => '',
        'dbtype' => 'mongo'
    ),
);
