<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests;

use BeastBytes\ICalendar\Valarm;
use BeastBytes\ICalendar\Vcalendar;
use BeastBytes\ICalendar\Vevent;
use BeastBytes\ICalendar\Vfreebusy;
use BeastBytes\ICalendar\Vjournal;
use BeastBytes\ICalendar\Vtodo;
use PHPUnit\Framework\TestCase;

class CreateIcalendarTest extends TestCase
{
    /**
     * @dataProvider icalendarProvider
     */
    public function test_icalendar($icalendar, $expected)
    {
        $this->assertSame(implode("\r\n", $expected) . "\r\n", $icalendar->render());
    }

    public function icalendarProvider()
    {
        return [
            'simple_icalendar' => [
                (new Vcalendar())
                    ->addProperty(Vcalendar::PROPERTY_PRODID, '-//RDU Software//NONSGML HandCal//EN')
                    ->addComponent((new Vevent())
                        ->addProperty(Vevent::PROPERTY_UID, '19970610T172345Z-AF23B2@example.com')
                        ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '19970610T172345Z')
                        ->addProperty(Vevent::PROPERTY_DATETIME_START, '19970714T170000Z')
                        ->addProperty(Vevent::PROPERTY_DATETIME_END, '19970715T040000Z')
                        ->addProperty(Vevent::PROPERTY_SUMMARY, 'Bastille Day Party')
                    ),
                [
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
                ]
            ],
            'freebusy' => [
                (new Vcalendar())
                    ->addProperty(Vcalendar::PROPERTY_PRODID, '-//RDU Software//NONSGML HandCal//EN')
                    ->addComponent((new Vfreebusy())
                        ->addProperty(Vfreebusy::PROPERTY_UID, '19970901T115957Z-76A912@example.com')
                        ->addProperty(Vfreebusy::PROPERTY_DATETIME_STAMP, '19970901T120000Z')
                        ->addProperty(
                            Vfreebusy::PROPERTY_ORGANIZER,
                            'mailto:jsmith@example.com',
                            [
                                Vfreebusy::PARAMETER_COMMON_NAME => '"John Smith"'
                            ]
                        )
                        ->addProperty(Vfreebusy::PROPERTY_DATETIME_START, '19980313T141711Z')
                        ->addProperty(Vfreebusy::PROPERTY_DATETIME_END, '19980410T141711Z')
                        ->addProperty(Vfreebusy::PROPERTY_FREEBUSY, '19980314T233000Z/19980315T003000Z')
                        ->addProperty(Vfreebusy::PROPERTY_FREEBUSY, '19980316T153000Z/19980316T163000Z')
                        ->addProperty(Vfreebusy::PROPERTY_FREEBUSY, '19980318T030000Z/19980318T040000Z')
                        ->addProperty(
                            Vfreebusy::PROPERTY_URL,
                            'http://www.example.com/calendar/busytime/jsmith.ifb'
                        )
                    ),
                [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'PRODID:-//RDU Software//NONSGML HandCal//EN',
                    'BEGIN:VFREEBUSY',
                    'UID:19970901T115957Z-76A912@example.com',
                    'DTSTAMP:19970901T120000Z',
                    'ORGANIZER;CN="John Smith":mailto:jsmith@example.com',
                    'DTSTART:19980313T141711Z',
                    'DTEND:19980410T141711Z',
                    'FREEBUSY:19980314T233000Z/19980315T003000Z',
                    'FREEBUSY:19980316T153000Z/19980316T163000Z',
                    'FREEBUSY:19980318T030000Z/19980318T040000Z',
                    'URL:http://www.example.com/calendar/busytime/jsmith.ifb',
                    'END:VFREEBUSY',
                    'END:VCALENDAR'
                ]
            ],
            'todo_with_alarm' => [
                (new Vcalendar())
                    ->addProperty(Vcalendar::PROPERTY_PRODID, '-//ABC Corporation//NONSGML My Product//EN')
                    ->addComponent((new Vtodo())
                        ->addProperty(Vtodo::PROPERTY_DATETIME_STAMP, '19980130T134500Z')
                        ->addProperty(Vtodo::PROPERTY_SEQUENCE, 2)
                        ->addProperty(Vtodo::PROPERTY_UID, 'uid4@example.com')
                        ->addProperty(Vtodo::PROPERTY_ORGANIZER, 'mailto:unclesam@example.com')
                        ->addProperty(
                           Vtodo::PROPERTY_ATTENDEE,
                           'mailto:jqpublic@example.com',
                            [
                                Vtodo::PARAMETER_PARTICIPATION_STATUS => Vtodo::PARTICIPANT_ACCEPTED
                            ]
                        )
                        ->addProperty(Vtodo::PROPERTY_DUE, '19980415T000000')
                        ->addProperty(Vtodo::PROPERTY_STATUS, Vtodo::STATUS_NEEDS_ACTION)
                        ->addProperty(Vtodo::PROPERTY_SUMMARY, 'Submit Income Taxes')
                        ->addComponent((new Valarm())
                            ->addProperty(Valarm::PROPERTY_ACTION, Valarm::ACTION_AUDIO)
                            ->addProperty(Valarm::PROPERTY_TRIGGER, '19980403T120000Z')
                            ->addProperty(
                               Valarm::PROPERTY_ATTACH,
                               'http://example.com/pub/audio-files/ssbanner.aud',
                                [
                                    Valarm::PARAMETER_FORMAT_TYPE => 'audio/basic'
                                ]
                            )
                            ->addProperty(Valarm::PROPERTY_REPEAT, 4)
                            ->addProperty(Valarm::PROPERTY_DURATION, 'PT1H')
                        )
                    ),
                [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'PRODID:-//ABC Corporation//NONSGML My Product//EN',
                    'BEGIN:VTODO',
                    'DTSTAMP:19980130T134500Z',
                    'SEQUENCE:2',
                    'UID:uid4@example.com',
                    'ORGANIZER:mailto:unclesam@example.com',
                    'ATTENDEE;PARTSTAT=ACCEPTED:mailto:jqpublic@example.com',
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
                ]
            ],
            'vjournal' => [
                (new Vcalendar())
                    ->addProperty(Vcalendar::PROPERTY_PRODID, '-//ABC Corporation//NONSGML My Product//EN')
                    ->addComponent((new Vjournal())
                        ->addProperty(Vjournal::PROPERTY_DATETIME_STAMP, '19970324T120000Z')
                        ->addProperty(Vjournal::PROPERTY_UID, 'uid5@example.com')
                        ->addProperty(Vjournal::PROPERTY_ORGANIZER, 'mailto:jsmith@example.com')
                        ->addProperty(Vjournal::PROPERTY_STATUS, Vjournal::STATUS_DRAFT)
                        ->addProperty(Vjournal::PROPERTY_CLASS, Vjournal::CLASSIFICATION_PUBLIC)
                        ->addProperty(Vjournal::PROPERTY_CATEGORIES, ['Project Report','XYZ','Weekly Meeting'])
                        ->addProperty(
                           Vjournal::PROPERTY_DESCRIPTION,
                           'Project xyz Review Meeting Minutes\n'
                            . 'Agenda\n'
                            . '1. Review of project version 1.0 requirements.\n'
                            . '2. Definition of project processes.\n3. Review of project schedule.\n'
                            . 'Participants: John Smith\, Jane Doe\, Jim Dandy\n'
                            . '-It was decided that the requirements need to be signed off by product marketing.\n'
                            . '-Project processes were accepted.\n'
                            . '-Project schedule needs to account for scheduled holidays and employee vacation time.'
                            . ' Check with HR for specific dates.\n'
                            . '-New schedule will be distributed by Friday.\n'
                            . '-Next weeks meeting is cancelled. No meeting until 3/23.'
                        )
                    ),
                [
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
                ]
            ]
        ];
    }
}
