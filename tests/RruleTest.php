<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests;

use BeastBytes\ICalendar\Component;
use BeastBytes\ICalendar\Property;
use BeastBytes\ICalendar\Vevent;
use PHPUnit\Framework\TestCase;

class RruleTest extends TestCase
{
    /**
     * @dataProvider rruleProvider
     */
    public function test_rrule($value, $expected)
    {
        $this->assertSame(
            $expected,
            (new Property(new Vevent(), Component::PROPERTY_RECURRENCE_RULE, $value))
                ->render()
        );
    }

    public function rruleProvider()
    {
        return [
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_DAILY,
                    Component::RRULE_COUNT => 10
                ],
                'RRULE:FREQ=DAILY;COUNT=10'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_DAILY,
                    Component::RRULE_UNTIL => '19971224T000000Z'
                ],
                'RRULE:FREQ=DAILY;UNTIL=19971224T000000Z'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_DAILY,
                    Component::RRULE_INTERVAL => 2
                ],
                'RRULE:FREQ=DAILY;INTERVAL=2'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_YEARLY,
                    Component::RRULE_UNTIL => '20000131T140000Z',
                    Component::RRULE_BY_MONTH => 1,
                    Component::RRULE_BY_DAY => [
                        Component::SUNDAY,
                        Component::MONDAY,
                        Component::TUESDAY,
                        Component::WEDNESDAY,
                        Component::THURSDAY,
                        Component::FRIDAY,
                        Component::SATURDAY
                    ]
                ],
                "RRULE:FREQ=YEARLY;UNTIL=20000131T140000Z;BYMONTH=1;BYDAY=SU,MO,TU,WE,TH,FR,\r\n SA"
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_DAILY,
                    Component::RRULE_UNTIL => '20000131T140000Z',
                    Component::RRULE_BY_MONTH => 1
                ],
                'RRULE:FREQ=DAILY;UNTIL=20000131T140000Z;BYMONTH=1'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_WEEKLY,
                    Component::RRULE_INTERVAL => 2,
                    Component::RRULE_WKST => Component::SUNDAY
                ],
                'RRULE:FREQ=WEEKLY;INTERVAL=2;WKST=SU'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_WEEKLY,
                    Component::RRULE_UNTIL => '19971007T000000Z',
                    Component::RRULE_WKST => Component::SUNDAY,
                    Component::RRULE_BY_DAY => [Component::TUESDAY, Component::THURSDAY]
                ],
                'RRULE:FREQ=WEEKLY;UNTIL=19971007T000000Z;WKST=SU;BYDAY=TU,TH'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_WEEKLY,
                    Component::RRULE_COUNT => 10,
                    Component::RRULE_WKST => Component::SUNDAY,
                    Component::RRULE_BY_DAY => [Component::TUESDAY, Component::THURSDAY]
                ],
                'RRULE:FREQ=WEEKLY;COUNT=10;WKST=SU;BYDAY=TU,TH'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_WEEKLY,
                    Component::RRULE_INTERVAL => 2,
                    Component::RRULE_UNTIL => '19971224T000000Z',
                    Component::RRULE_WKST => Component::SUNDAY,
                    Component::RRULE_BY_DAY => [Component::MONDAY, Component::WEDNESDAY, Component::FRIDAY]
                ],
                'RRULE:FREQ=WEEKLY;INTERVAL=2;UNTIL=19971224T000000Z;WKST=SU;BYDAY=MO,WE,FR'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_WEEKLY,
                    Component::RRULE_INTERVAL => 2,
                    Component::RRULE_COUNT => 8,
                    Component::RRULE_WKST => Component::SUNDAY,
                    Component::RRULE_BY_DAY => [Component::TUESDAY, Component::THURSDAY]
                ],
                'RRULE:FREQ=WEEKLY;INTERVAL=2;COUNT=8;WKST=SU;BYDAY=TU,TH'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_MONTHLY,
                    Component::RRULE_COUNT => 6,
                    Component::RRULE_BY_DAY => '-2MO'
                ],
                'RRULE:FREQ=MONTHLY;COUNT=6;BYDAY=-2MO'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_MONTHLY,
                    Component::RRULE_COUNT => 10,
                    Component::RRULE_BY_MONTH_DAY => [2, 15]
                ],
                'RRULE:FREQ=MONTHLY;COUNT=10;BYMONTHDAY=2,15'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_MONTHLY,
                    Component::RRULE_COUNT => 10,
                    Component::RRULE_BY_MONTH_DAY => [1, -1]
                ],
                'RRULE:FREQ=MONTHLY;COUNT=10;BYMONTHDAY=1,-1'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_MONTHLY,
                    Component::RRULE_INTERVAL => 18,
                    Component::RRULE_COUNT => 10,
                    Component::RRULE_BY_MONTH_DAY => [10, 11, 12, 13, 14, 15]
                ],
                'RRULE:FREQ=MONTHLY;INTERVAL=18;COUNT=10;BYMONTHDAY=10,11,12,13,14,15'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_YEARLY,
                    Component::RRULE_INTERVAL => 3,
                    Component::RRULE_COUNT => 10,
                    Component::RRULE_BY_YEAR_DAY => [1, 100, 200]
                ],
                'RRULE:FREQ=YEARLY;INTERVAL=3;COUNT=10;BYYEARDAY=1,100,200'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_YEARLY,
                    Component::RRULE_BY_MONTH => 3,
                    Component::RRULE_BY_DAY => Component::THURSDAY
                ],
                'RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=TH'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_YEARLY,
                    Component::RRULE_BY_DAY => Component::THURSDAY,
                    Component::RRULE_BY_MONTH => [6, 7, 8]
                ],
                'RRULE:FREQ=YEARLY;BYDAY=TH;BYMONTH=6,7,8'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_MONTHLY,
                    Component::RRULE_BY_DAY => Component::SATURDAY,
                    Component::RRULE_BY_MONTH_DAY => [7, 8, 9, 10, 11, 12, 13]
                ],
                'RRULE:FREQ=MONTHLY;BYDAY=SA;BYMONTHDAY=7,8,9,10,11,12,13'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_MONTHLY,
                    Component::RRULE_COUNT => 3,
                    Component::RRULE_BY_DAY => [Component::TUESDAY, Component::WEDNESDAY, Component::THURSDAY],
                    Component::RRULE_BY_SETPOS => 3
                ],
                'RRULE:FREQ=MONTHLY;COUNT=3;BYDAY=TU,WE,TH;BYSETPOS=3'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_MONTHLY,
                    Component::RRULE_BY_DAY => [
                        Component::MONDAY,
                        Component::TUESDAY,
                        Component::WEDNESDAY,
                        Component::THURSDAY,
                        Component::FRIDAY
                    ],
                    Component::RRULE_BY_SETPOS => -2
                ],
                'RRULE:FREQ=MONTHLY;BYDAY=MO,TU,WE,TH,FR;BYSETPOS=-2'
            ],
            [
                [
                    Component::RRULE_FREQ => Component::FREQUENCY_DAILY,
                    Component::RRULE_BY_HOUR => [9, 10, 11, 12, 13, 14, 15, 16],
                    Component::RRULE_BY_MINUTE => [0, 20, 40]
                ],
                'RRULE:FREQ=DAILY;BYHOUR=9,10,11,12,13,14,15,16;BYMINUTE=0,20,40'
            ],
        ];
    }
}
