<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar\Tests;

use BeastBytes\ICalendar\Valarm;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ValarmTest extends TestCase
{
    #[DataProvider('valarmProvider')]
    public function test_valarm($valarm, $expected)
    {
        $this->assertSame(implode("\r\n", $expected) . "\r\n", $valarm->render());
    }

    public static function valarmProvider(): \Generator
    {
        foreach ([
            'action_audio' => [
                (new Valarm())
                    ->addProperty(
                        Valarm::PROPERTY_TRIGGER,
                        '19970317T133000Z',
                        [
                            Valarm::PARAMETER_VALUE => Valarm::DATA_TYPE_DATE_TIME
                        ]
                    )
                    ->addProperty(Valarm::PROPERTY_REPEAT, 4)
                    ->addProperty(Valarm::PROPERTY_DURATION, 'PT15M')
                    ->addProperty(Valarm::PROPERTY_ACTION, Valarm::ACTION_AUDIO)
                    ->addProperty(
                        Valarm::PROPERTY_ATTACH,
                        'ftp://example.com/pub/sounds/bell-01.aud',
                        [
                            Valarm::PARAMETER_FORMAT_TYPE => 'audio/basic'
                        ]
                    )
                ,
                [
                    'BEGIN:VALARM',
                    'TRIGGER;VALUE=DATE-TIME:19970317T133000Z',
                    'REPEAT:4',
                    'DURATION:PT15M',
                    'ACTION:AUDIO',
                    'ATTACH;FMTTYPE=audio/basic:ftp://example.com/pub/sounds/bell-01.aud',
                    'END:VALARM'
                ]
            ],
            'action_display' => [
                (new Valarm())
                    ->addProperty(Valarm::PROPERTY_TRIGGER,'-PT30M')
                    ->addProperty(Valarm::PROPERTY_REPEAT, 2)
                    ->addProperty(Valarm::PROPERTY_DURATION, 'PT15M')
                    ->addProperty(Valarm::PROPERTY_ACTION, Valarm::ACTION_DISPLAY)
                    ->addProperty(Valarm::PROPERTY_DESCRIPTION,'Breakfast meeting')
                ,
                [
                    'BEGIN:VALARM',
                    'TRIGGER:-PT30M',
                    'REPEAT:2',
                    'DURATION:PT15M',
                    'ACTION:DISPLAY',
                    'DESCRIPTION:Breakfast meeting',
                    'END:VALARM'
                ]
            ],
            'action_email' => [
                (new Valarm())
                    ->addProperty(
                        Valarm::PROPERTY_TRIGGER,
                        '-P2D',
                        [
                            Valarm::PARAMETER_RELATED => Valarm::END
                        ]
                    )
                    ->addProperty(Valarm::PROPERTY_ACTION, Valarm::ACTION_EMAIL)
                    ->addProperty(Valarm::PROPERTY_ATTENDEE, 'mailto:john_doe@example.com')
                    ->addProperty(
                        Valarm::PROPERTY_SUMMARY,
                        '*** REMINDER: SEND AGENDA FOR WEEKLY STAFF MEETING ***'
                    )
                    ->addProperty(
                        Valarm::PROPERTY_DESCRIPTION,
                        'A draft agenda needs to be sent out to the attendees to the weekly managers meeting (MGR-LIST). Attached is a pointer the document template for the agenda file.'
                    )
                    ->addProperty(
                        Valarm::PROPERTY_ATTACH,
                        'http://example.com/templates/agenda.doc',
                        [
                            Valarm::PARAMETER_FORMAT_TYPE => 'application/msword'
                        ]
                    )
                ,
                [
                    'BEGIN:VALARM',
                    'TRIGGER;RELATED=END:-P2D',
                    'ACTION:EMAIL',
                    'ATTENDEE:mailto:john_doe@example.com',
                    'SUMMARY:*** REMINDER: SEND AGENDA FOR WEEKLY STAFF MEETING ***',
                    "DESCRIPTION:A draft agenda needs to be sent out to the attendees to the wee\r\n kly managers meeting (MGR-LIST). Attached is a pointer the document templa\r\n te for the agenda file.",
                    'ATTACH;FMTTYPE=application/msword:http://example.com/templates/agenda.doc',
                    'END:VALARM'
                ]
            ]
        ] as $name => $value) {
            yield $name => $value;
        }
    }
}
