<?php
    require_once 'workflowy-php/src/autoload.php';
    error_reporting(E_ALL & ~E_NOTICE);
    // header('Content-type: text/calendar');

    use WorkFlowyPHP\WorkFlowy;
    use WorkFlowyPHP\WorkFlowyList;
    use WorkFlowyPHP\WorkFlowyException;

    $session_id = WorkFlowy::login($_ENV['USERNAME'], $_ENV['PASSWORD']);
    $list_request = new WorkFlowyList($session_id);
    $list = $list_request->getList();
    $cal = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:wfcal\r\n";

    function parse($node) {
        if (preg_match('/(.*?)<time startYear="(\d+)" startMonth="(\d+)" startDay="(\d+)">.*?<\/time>(.*?)/', $node->getName(), $m)) {
            $date = $m[2].$m[3].$m[4];
            $GLOBALS['cal'] .= "BEGIN:VEVENT\r\nUID:{$node->getID()}\r\nDTSTART;VALUE=DATE:$date\r\nDTEND;VALUE=DATE:$date\r\nSUMMARY:{$m[1]}{$m[5]}\r\nDESCRIPTION:{$node->getDescription()}\r\nEND:VEVENT\r\n";
        }
        foreach ($node->getSublists() as $subnode)
            parse($subnode);
    }

    parse($list);
    echo($cal . "END:VCALENDAR\r\n")
?>
