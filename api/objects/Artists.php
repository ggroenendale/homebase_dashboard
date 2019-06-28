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

Class Artists{
    private $sheetid = "";
    private $sheetclient = 0;
    private $sheetservice = 0;

    public function __construct(){
        $config = new Config();
        $this->sheetid = $config->get_sheet_id();
        $this->sheetclient = $config->get_sheets_client();
        $this->sheetservice = new Google_Service_Sheets($this->sheetclient);
    }

    public function get_data(){
        //This range should capture all of the available song records and the 4 columns
        $range = 'Sheet1!A:D';

        //Get the response from the service
        $response = $this->sheetservice->spreadsheets_values->get($this->sheetid, $range);
        $values = $response->getValues();

        

        //Process the $values
        if (empty($values)){
            return "No Values";
        }
        else {
            return "Values get!";
        }
    }

    //test
}
