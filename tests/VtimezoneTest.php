<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);


use BeastBytes\ICalendar\Daylight;
use BeastBytes\ICalendar\Standard;
use BeastBytes\ICalendar\Vtimezone;
use PHPUnit\Framework\TestCase;

class VtimezoneTest extends TestCase
{
    /**
     * @dataProvider vtimezoneProvider
     */
    public function test_vtimezone($vtimezone, $expected)
    {
        $this->assertSame(implode("\r\n", $expected) . "\r\n", $vtimezone->render());
    }

    public function vtimezoneProvider()
    {
        $daylight = new Daylight();
        $standard = new Standard();
        $vTimezone = new Vtimezone();
        $newYorkTimezone = $vTimezone->addProperty(Vtimezone::PROPERTY_TZID, 'America/New_York');
        $fictitiousTimezone = $vTimezone->addProperty(Vtimezone::PROPERTY_TZID, 'Fictitious');

        return [
            [
                $newYorkTimezone
                    ->addProperty(Vtimezone::PROPERTY_LAST_MODIFIED, '20050809T050000Z')
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '19670430T020000')
                        ->addProperty(
                            Daylight::PROPERTY_RRULE,
                            [
                                Daylight::RRULE_FREQ => Daylight::FREQUENCY_YEARLY,
                                Daylight::RRULE_BY_MONTH => Daylight::APRIL,
                                Daylight::RRULE_BY_DAY => -1 . Daylight::SUNDAY,
                                Daylight::RRULE_UNTIL => '19730429T070000Z'
                            ]
                        )
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                    ->addComponent($standard
                        ->addProperty(Standard::PROPERTY_DTSTART, '19671029T020000')
                        ->addProperty(
                            Standard::PROPERTY_RRULE,
                            [
                                Standard::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Standard::RRULE_BY_MONTH => Standard::OCTOBER,
                                Standard::RRULE_BY_DAY => -1 . Standard::SUNDAY,
                                Standard::RRULE_UNTIL => '20061029T060000Z'
                            ]
                        )
                        ->addProperty(Standard::PROPERTY_TZOFFSETFROM, '-0400')
                        ->addProperty(Standard::PROPERTY_TZOFFSETTO, '-0500')
                        ->addProperty(Standard::PROPERTY_TZNAME, 'EST')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '19740106T020000')
                        ->addProperty(Daylight::PROPERTY_RDATE, '19750223T020000')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '19760425T020000')
                        ->addProperty(
                            Daylight::PROPERTY_RRULE,
                            [
                                Daylight::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Daylight::RRULE_BY_MONTH => Standard::APRIL,
                                Daylight::RRULE_BY_DAY => -1 . Standard::SUNDAY,
                                Daylight::RRULE_UNTIL => '19860427T070000Z'
                            ]
                        )
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '19870405T020000')
                        ->addProperty(
                            Daylight::PROPERTY_RRULE,
                            [
                                Daylight::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Daylight::RRULE_BY_MONTH => Standard::APRIL,
                                Daylight::RRULE_BY_DAY => 1 . Standard::SUNDAY,
                                Daylight::RRULE_UNTIL => '20060402T070000Z'
                            ]
                        )
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '20070311T020000')
                        ->addProperty(
                            Daylight::PROPERTY_RRULE,
                            [
                                Daylight::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Daylight::RRULE_BY_MONTH => Standard::MARCH,
                                Daylight::RRULE_BY_DAY => 2 . Standard::SUNDAY
                            ]
                        )
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                    ->addComponent($standard
                        ->addProperty(Standard::PROPERTY_DTSTART, '20071104T020000')
                        ->addProperty(
                            Standard::PROPERTY_RRULE,
                            [
                                Standard::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Standard::RRULE_BY_MONTH => Standard::NOVEMBER,
                                Standard::RRULE_BY_DAY => 1 . Standard::SUNDAY
                            ]
                        )
                        ->addProperty(Standard::PROPERTY_TZOFFSETFROM, '-0400')
                        ->addProperty(Standard::PROPERTY_TZOFFSETTO, '-0500')
                        ->addProperty(Standard::PROPERTY_TZNAME, 'EST')
                    )
                ,
                [
                    'BEGIN:VTIMEZONE',
                    'TZID:America/New_York',
                    'LAST-MODIFIED:20050809T050000Z',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:19670430T020000',
                    'RRULE:FREQ=YEARLY;BYMONTH=4;BYDAY=-1SU;UNTIL=19730429T070000Z',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'BEGIN:STANDARD',
                    'DTSTART:19671029T020000',
                    'RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU;UNTIL=20061029T060000Z',
                    'TZOFFSETFROM:-0400',
                    'TZOFFSETTO:-0500',
                    'TZNAME:EST',
                    'END:STANDARD',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:19740106T020000',
                    'RDATE:19750223T020000',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:19760425T020000',
                    'RRULE:FREQ=YEARLY;BYMONTH=4;BYDAY=-1SU;UNTIL=19860427T070000Z',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:19870405T020000',
                    'RRULE:FREQ=YEARLY;BYMONTH=4;BYDAY=1SU;UNTIL=20060402T070000Z',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:20070311T020000',
                    'RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'BEGIN:STANDARD',
                    'DTSTART:20071104T020000',
                    'RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU',
                    'TZOFFSETFROM:-0400',
                    'TZOFFSETTO:-0500',
                    'TZNAME:EST',
                    'END:STANDARD',
                    'END:VTIMEZONE'
                ]
            ],
            [
                $newYorkTimezone
                    ->addProperty(Vtimezone::PROPERTY_LAST_MODIFIED, '20050809T050000Z')
                    ->addComponent($standard
                        ->addProperty(Standard::PROPERTY_DTSTART, '20071104T020000')
                        ->addProperty(Standard::PROPERTY_TZOFFSETFROM, '-0400')
                        ->addProperty(Standard::PROPERTY_TZOFFSETTO, '-0500')
                        ->addProperty(Standard::PROPERTY_TZNAME, 'EST')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '20070311T020000')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                ,
                [
                    'BEGIN:VTIMEZONE',
                    'TZID:America/New_York',
                    'LAST-MODIFIED:20050809T050000Z',
                    'BEGIN:STANDARD',
                    'DTSTART:20071104T020000',
                    'TZOFFSETFROM:-0400',
                    'TZOFFSETTO:-0500',
                    'TZNAME:EST',
                    'END:STANDARD',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:20070311T020000',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'END:VTIMEZONE'
                ]
            ],
            [
                $newYorkTimezone
                    ->addProperty(Vtimezone::PROPERTY_LAST_MODIFIED, '20050809T050000Z')
                    ->addProperty(
                        Vtimezone::PROPERTY_TZURL,
                        'http://zones.example.com/tz/America-New_York.ics'
                    )
                    ->addComponent($standard
                        ->addProperty(Standard::PROPERTY_DTSTART, '20071104T020000')
                        ->addProperty(
                            Standard::PROPERTY_RRULE,
                            [
                                Standard::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Standard::RRULE_BY_MONTH => Standard::NOVEMBER,
                                Standard::RRULE_BY_DAY => 1 . Standard::SUNDAY
                            ]
                        )
                        ->addProperty(Standard::PROPERTY_TZOFFSETFROM, '-0400')
                        ->addProperty(Standard::PROPERTY_TZOFFSETTO, '-0500')
                        ->addProperty(Standard::PROPERTY_TZNAME, 'EST')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '20070311T020000')
                        ->addProperty(
                            Standard::PROPERTY_RRULE,
                            [
                                Standard::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Standard::RRULE_BY_MONTH => Standard::MARCH,
                                Standard::RRULE_BY_DAY => 2 . Standard::SUNDAY
                            ]
                        )
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                ,
                [
                    'BEGIN:VTIMEZONE',
                    'TZID:America/New_York',
                    'LAST-MODIFIED:20050809T050000Z',
                    'TZURL:http://zones.example.com/tz/America-New_York.ics',
                    'BEGIN:STANDARD',
                    'DTSTART:20071104T020000',
                    'RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU',
                    'TZOFFSETFROM:-0400',
                    'TZOFFSETTO:-0500',
                    'TZNAME:EST',
                    'END:STANDARD',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:20070311T020000',
                    'RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'END:VTIMEZONE'
                ]
            ],
            [
                $fictitiousTimezone
                    ->addProperty(Vtimezone::PROPERTY_LAST_MODIFIED, '19870101T000000Z')
                    ->addComponent($standard
                        ->addProperty(Standard::PROPERTY_DTSTART, '19671029T020000')
                        ->addProperty(
                           Standard::PROPERTY_RRULE,
                            [
                                Standard::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Standard::RRULE_BY_DAY => -1 . Standard::SUNDAY,
                                Standard::RRULE_BY_MONTH => Standard::OCTOBER
                            ]
                        )
                        ->addProperty(Standard::PROPERTY_TZOFFSETFROM, '-0400')
                        ->addProperty(Standard::PROPERTY_TZOFFSETTO, '-0500')
                        ->addProperty(Standard::PROPERTY_TZNAME, 'EST')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '19870405T020000')
                        ->addProperty(
                            Daylight::PROPERTY_RRULE,
                            [
                                Daylight::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Daylight::RRULE_BY_DAY => 1 . Standard::SUNDAY,
                                Daylight::RRULE_BY_MONTH => Standard::APRIL,
                                Daylight::RRULE_UNTIL => '19980404T070000Z'
                            ]
                        )
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                ,
                [
                    'BEGIN:VTIMEZONE',
                    'TZID:Fictitious',
                    'LAST-MODIFIED:19870101T000000Z',
                    'BEGIN:STANDARD',
                    'DTSTART:19671029T020000',
                    'RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10',
                    'TZOFFSETFROM:-0400',
                    'TZOFFSETTO:-0500',
                    'TZNAME:EST',
                    'END:STANDARD',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:19870405T020000',
                    'RRULE:FREQ=YEARLY;BYDAY=1SU;BYMONTH=4;UNTIL=19980404T070000Z',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'END:VTIMEZONE'
                ]
            ],
            [
                $fictitiousTimezone
                    ->addProperty(Vtimezone::PROPERTY_LAST_MODIFIED, '19870101T000000Z')
                    ->addComponent($standard
                        ->addProperty(Standard::PROPERTY_DTSTART, '19671029T020000')
                        ->addProperty(
                            Standard::PROPERTY_RRULE,
                            [
                                Standard::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Standard::RRULE_BY_DAY => -1 . Standard::SUNDAY,
                                Standard::RRULE_BY_MONTH => Standard::OCTOBER
                            ]
                        )
                        ->addProperty(Standard::PROPERTY_TZOFFSETFROM, '-0400')
                        ->addProperty(Standard::PROPERTY_TZOFFSETTO, '-0500')
                        ->addProperty(Standard::PROPERTY_TZNAME, 'EST')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '19870405T020000')
                        ->addProperty(
                            Standard::PROPERTY_RRULE,
                            [
                                Standard::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Standard::RRULE_BY_DAY => 1 . Standard::SUNDAY,
                                Standard::RRULE_BY_MONTH => Standard::APRIL,
                                Standard::RRULE_UNTIL => '19980404T070000Z'
                            ]
                        )
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                    ->addComponent($daylight
                        ->addProperty(Daylight::PROPERTY_DTSTART, '19990424T020000')
                        ->addProperty(
                            Standard::PROPERTY_RRULE,
                            [
                                Standard::RRULE_FREQ => Standard::FREQUENCY_YEARLY,
                                Standard::RRULE_BY_DAY => -1 . Standard::SUNDAY,
                                Standard::RRULE_BY_MONTH => Standard::APRIL
                            ]
                        )
                        ->addProperty(Daylight::PROPERTY_TZOFFSETFROM, '-0500')
                        ->addProperty(Daylight::PROPERTY_TZOFFSETTO, '-0400')
                        ->addProperty(Daylight::PROPERTY_TZNAME, 'EDT')
                    )
                ,
                [
                    'BEGIN:VTIMEZONE',
                    'TZID:Fictitious',
                    'LAST-MODIFIED:19870101T000000Z',
                    'BEGIN:STANDARD',
                    'DTSTART:19671029T020000',
                    'RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10',
                    'TZOFFSETFROM:-0400',
                    'TZOFFSETTO:-0500',
                    'TZNAME:EST',
                    'END:STANDARD',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:19870405T020000',
                    'RRULE:FREQ=YEARLY;BYDAY=1SU;BYMONTH=4;UNTIL=19980404T070000Z',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'BEGIN:DAYLIGHT',
                    'DTSTART:19990424T020000',
                    'RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=4',
                    'TZOFFSETFROM:-0500',
                    'TZOFFSETTO:-0400',
                    'TZNAME:EDT',
                    'END:DAYLIGHT',
                    'END:VTIMEZONE'
                ]
            ]
        ];
    }
}
