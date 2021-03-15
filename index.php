<?php
    require_once 'workflowy-php/src/autoload.php';
    error_reporting(E_ALL & ~E_NOTICE);
    header('Content-Type: text/calendar');

    use WorkFlowyPHP\WorkFlowy;
    use WorkFlowyPHP\WorkFlowyList;
    use WorkFlowyPHP\WorkFlowyException;

    $session_id = WorkFlowy::login($_ENV['USERNAME'], $_ENV['PASSWORD']);
    $list = (new WorkFlowyList($session_id))->getList();
    $cal = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:wfcal
";

    function parse($node) {
        if (preg_match('/(.*?)<time startYear="(\d+)" startMonth="(\d+)" startDay="(\d+)">.*?<\/time>(.*?)/', $node->getName(), $m)) {
            $date = $m[2]
                .str_pad($m[3],2,'0',STR_PAD_LEFT)
                .str_pad($m[4],2,'0',STR_PAD_LEFT);
            $timestamp = (new DateTime("$m[2]-$m[3]-$m[4]"))->getTimestamp();
            $GLOBALS['cal'] .= "BEGIN:VEVENT
UID:{$node->getID()}
DTSTAMP:$timestamp
DTSTART;VALUE=DATE:$date
DTEND;VALUE=DATE:$date
SUMMARY:{$m[1]}{$m[5]}
DESCRIPTION:{$node->getDescription()}
END:VEVENT
";
        }
        foreach ($node->getSublists() as $subnode)
            parse($subnode);
    }

    parse($list);
    echo($cal . "END:VCALENDAR\r\n")
?>
