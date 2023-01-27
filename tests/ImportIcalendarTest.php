<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

use BeastBytes\ICalendar\Vcalendar;
use PHPUnit\Framework\TestCase;

class ImportIcalendarTest extends TestCase
{
    /**
     * @dataProvider badIcalendarProvider
     */
    public function test_bad_icalendar(string $icalendar)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid iCalendar');
        Vcalendar::import($icalendar);
    }

    /**
     * @dataProvider icalendarProvider
     */
    public function test_icalendar_import($icalendar)
    {
        $imported = Vcalendar::import($icalendar);

        $this->assertInstanceOf(Vcalendar::class, $imported);
        $this->assertSame($icalendar, $imported->render());
    }

    public function badIcalendarProvider(): array
    {
        return [
            ["BEGIN:VEVENT\n"],
            ["END:VEVENT\n"],
            ["END:VCALENDAR\n"],
        ];
    }

    public function icalendarProvider(): array
    {
        return [
            'simple icalendar'  => [
                implode("\r\n", [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'PRODID:-//RDU Software//NONSGML HandCal//EN',
                    'BEGIN:VEVENT',
                    'UID:19970610T172345Z-AF23B2@example.com',
                    'DTSTAMP:19970610T172345Z',
                    'DTSTART:19970714T170000Z',
                    'DTEND:19970715T040000Z',
                    'SUMMARY:Bastille Day Party',
                    'END:VEVENT',
                    'END:VCALENDAR'
                ]) . "\r\n"
            ],
            'many properties with same name' => [
                implode("\r\n", [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'PRODID:-//RDU Software//NONSGML HandCal//EN',
                    'BEGIN:VFREEBUSY',
                    'ORGANIZER;CN="John Smith":mailto:jsmith@example.com',
                    'DTSTART:19980313T141711Z',
                    'DTEND:19980410T141711Z',
                    'FREEBUSY:19980314T233000Z/19980315T003000Z',
                    'FREEBUSY:19980316T153000Z/19980316T163000Z',
                    'FREEBUSY:19980318T030000Z/19980318T040000Z',
                    'URL:http://www.example.com/calendar/busytime/jsmith.ifb',
                    'END:VFREEBUSY',
                    'END:VCALENDAR'
                ]) . "\r\n"
            ],
            'properties with parameters' => [
                implode("\r\n", [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'PRODID:-//ABC Corporation//NONSGML My Product//EN',
                    'BEGIN:VTODO',
                    'DTSTAMP:19980130T134500Z',
                    'SEQUENCE:2',
                    'UID:uid4@example.com',
                    'ORGANIZER:mailto:unclesam@example.com',
                    "ATTENDEE;CUTYPE=GROUP;DELEGATED-FROM=\"mailto:jsmith@example.com\":mailto:jqp\r\n ublic@example.com",
                    'DUE:19980415T000000',
                    'STATUS:NEEDS-ACTION',
                    'SUMMARY:Submit Income Taxes',
                    'BEGIN:VALARM',
                    'ACTION:AUDIO',
                    'TRIGGER:19980403T120000Z',
                    'ATTACH;FMTTYPE=audio/basic:http://example.com/pub/audio-files/ssbanner.aud',
                    'REPEAT:4',
                    'DURATION:PT1H',
                    'END:VALARM',
                    'END:VTODO',
                    'END:VCALENDAR'
                ]) . "\r\n"
            ],
            'folded lines' => [
                implode("\r\n", [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'PRODID:-//ABC Corporation//NONSGML My Product//EN',
                    'BEGIN:VJOURNAL',
                    'DTSTAMP:19970324T120000Z',
                    'UID:uid5@example.com',
                    'ORGANIZER:mailto:jsmith@example.com',
                    'STATUS:DRAFT',
                    'CLASS:PUBLIC',
                    'CATEGORIES:Project Report,XYZ,Weekly Meeting',
                    "DESCRIPTION:Project xyz Review Meeting Minutes\\nAgenda\\n1. Review of projec\r\n t version 1.0 requirements.\\n2. Definition of project processes.\\n3. Revie\r\n w of project schedule.\\nParticipants: John Smith\, Jane Doe\, Jim Dandy\\n-\r\n It was decided that the requirements need to be signed off by product mark\r\n eting.\\n-Project processes were accepted.\\n-Project schedule needs to acco\r\n unt for scheduled holidays and employee vacation time. Check with HR for s\r\n pecific dates.\\n-New schedule will be distributed by Friday.\\n-Next weeks \r\n meeting is cancelled. No meeting until 3/23.",
                    'END:VJOURNAL',
                    'END:VCALENDAR'
                ]) . "\r\n"
            ]
        ];
    }
}
