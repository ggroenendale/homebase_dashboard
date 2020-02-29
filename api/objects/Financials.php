<?php
//State the namespace this file is a part of.
namespace Homebase\API\Objects;

//Require vendor autoload file.
require_once __DIR__ . '/../../vendor/autoload.php';

//Include required libraries.
use Homebase\API\Config\Config;


/**
 * This section queries google sheets and amazon alexa for which artist is currently
 * being listened to and favorite artists overall.
 */

/**
 * Step By Step
 * 1) query the google sheet
 * 2) store data in a mysql table
 * 3) Update currently listening to
 * 4) recalculate favorite artist totals.
 */

/**
 * Required google sheet url:
 * https://docs.google.com/spreadsheets/d/1hFvUid-Ui050zwW8ZVShC1zAHz_qRCYjEjALIvMU4B0/edit#gid=0
 * Required google sheet id:
 * 1hFvUid-Ui050zwW8ZVShC1zAHz_qRCYjEjALIvMU4B0
 */

Class Financials{ 

    public function __construct() {
        
    }
}