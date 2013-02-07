<?php
use Yamw\Lib\MySql\AdvMySql;

// Affiliation Codes
/* 1 - Covenant
 * 2 - UNSC
 * 3 - Flood
 * 4 - Forerunners
 * 5 - United Rebel Front
 * 6 - Other
 */

// Classification Codes
/* 0 - Fighter
 * 1 - Bomber
 * 2 - Corvette
 * 3 - Frigate
 * 4 - Destroyer
 * 5 - Cruiser
 * 6 - Heavy Cruiser
 * 7 - Carrier
 */

$this->covenant_list = AdvMySql::getTable('units_space')
    ->where('draft', '0')->addAnd()
    ->where('Affiliation', 1)
    ->orderby('Name', 'ASC')
    ->execute();

$this->unsc_list = AdvMySql::getTable('units_space')
    ->where('draft', '0')->addAnd()
    ->where('Affiliation', 2)
    ->orderby('Name', 'ASC')
    ->execute();

forward404Unless($this->covenant_list);
forward404Unless($this->unsc_list);