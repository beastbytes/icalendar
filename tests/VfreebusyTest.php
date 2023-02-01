<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);


use BeastBytes\ICalendar\Vfreebusy;
use PHPUnit\Framework\TestCase;

class VfreebusyTest extends TestCase
{
    /**
     * @dataProvider vfreebusyProvider
     */
    public function test_vfreebusy($vfreebusy, $expected)
    {
        $this->assertSame(implode("\r\n", $expected) . "\r\n", $vfreebusy->render());
    }

    public function vfreebusyProvider()
    {
        return [
            [
                (new Vfreebusy())
                    ->addProperty(Vfreebusy::PROPERTY_UID, '19970901T082949Z-FA43EF@example.com')
                    ->addProperty(Vfreebusy::PROPERTY_ORGANIZER, 'mailto:jane_doe@example.com')
                    ->addProperty(Vfreebusy::PROPERTY_ATTENDEE, 'mailto:john_public@example.com')
                    ->addProperty(Vfreebusy::PROPERTY_DTSTART, '19971015T050000Z')
                    ->addProperty(Vfreebusy::PROPERTY_DTEND, '19971016T050000Z')
                    ->addProperty(Vfreebusy::PROPERTY_DTSTAMP, '19970901T083000Z')
                ,
                [
                    'BEGIN:VFREEBUSY',
                    'UID:19970901T082949Z-FA43EF@example.com',
                    'ORGANIZER:mailto:jane_doe@example.com',
                    'ATTENDEE:mailto:john_public@example.com',
                    'DTSTART:19971015T050000Z',
                    'DTEND:19971016T050000Z',
                    'DTSTAMP:19970901T083000Z',
                    'END:VFREEBUSY'
                ]
            ],
            [
                (new Vfreebusy())
                    ->addProperty(Vfreebusy::PROPERTY_UID, '19970901T095957Z-76A912@example.com')
                    ->addProperty(Vfreebusy::PROPERTY_ORGANIZER, 'mailto:jane_doe@example.com')
                    ->addProperty(Vfreebusy::PROPERTY_ATTENDEE, 'mailto:john_public@example.com')
                    ->addProperty(Vfreebusy::PROPERTY_DTSTAMP, '19970901T100000Z')
                    ->addProperty(
                        Vfreebusy::PROPERTY_FREEBUSY,
                        [
                            '19971015T050000Z' => 'PT8H30M',
                            '19971015T160000Z/PT5H30M',
                            '19971015T223000Z' => 'PT6H30M'
                        ]
                    )
                    ->addProperty(Vfreebusy::PROPERTY_URL, 'http://example.com/pub/busy/jpublic-01.ifb')
                    ->addProperty(
                        Vfreebusy::PROPERTY_COMMENT,
                        'This iCalendar file contains busy time information for the next three months.'
                    )
                ,
                [
                    'BEGIN:VFREEBUSY',
                    'UID:19970901T095957Z-76A912@example.com',
                    'ORGANIZER:mailto:jane_doe@example.com',
                    'ATTENDEE:mailto:john_public@example.com',
                    'DTSTAMP:19970901T100000Z',
                    "FREEBUSY:19971015T050000Z/PT8H30M,19971015T160000Z/PT5H30M,19971015T223000Z\r\n /PT6H30M",
                    'URL:http://example.com/pub/busy/jpublic-01.ifb',
                    "COMMENT:This iCalendar file contains busy time information for the next thr\r\n ee months.",
                    'END:VFREEBUSY'
                ]
            ],
            [
                (new Vfreebusy())
                    ->addProperty(Vfreebusy::PROPERTY_UID, '19970901T115957Z-76A912@example.com')
                    ->addProperty(Vfreebusy::PROPERTY_DTSTAMP, '19970901T120000Z')
                    ->addProperty(Vfreebusy::PROPERTY_ORGANIZER, 'jsmith@example.com')
                    ->addProperty(Vfreebusy::PROPERTY_DTSTART, '19980313T141711Z')
                    ->addProperty(Vfreebusy::PROPERTY_DTEND, '19980410T141711Z')
                    ->addProperty(Vfreebusy::PROPERTY_FREEBUSY, ['19980314T233000Z' => '19980315T003000Z'])
                    ->addProperty(Vfreebusy::PROPERTY_FREEBUSY, '19980316T153000Z/19980316T163000Z')
                    ->addProperty(Vfreebusy::PROPERTY_FREEBUSY, ['19980318T030000Z' => '19980318T040000Z'])
                    ->addProperty(Vfreebusy::PROPERTY_URL, 'http://www.example.com/calendar/busytime/jsmith.ifb')
                ,
                [
                    'BEGIN:VFREEBUSY',
                    'UID:19970901T115957Z-76A912@example.com',
                    'DTSTAMP:19970901T120000Z',
                    'ORGANIZER:jsmith@example.com',
                    'DTSTART:19980313T141711Z',
                    'DTEND:19980410T141711Z',
                    'FREEBUSY:19980314T233000Z/19980315T003000Z',
                    'FREEBUSY:19980316T153000Z/19980316T163000Z',
                    'FREEBUSY:19980318T030000Z/19980318T040000Z',
                    'URL:http://www.example.com/calendar/busytime/jsmith.ifb',
                    'END:VFREEBUSY'
                ]
            ]
        ];
    }
}
