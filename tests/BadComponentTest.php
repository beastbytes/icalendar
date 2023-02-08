<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests;

use BeastBytes\ICalendar\Daylight;
use BeastBytes\ICalendar\Standard;
use BeastBytes\ICalendar\Valarm;
use BeastBytes\ICalendar\Vcalendar;
use BeastBytes\ICalendar\Vevent;
use BeastBytes\ICalendar\Vfreebusy;
use BeastBytes\ICalendar\Vjournal;
use BeastBytes\ICalendar\Vtimezone;
use BeastBytes\ICalendar\Vtodo;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function strtr;

class BadComponentTest extends TestCase
{

    /**
     * @dataProvider badComponentProvider
     */
    public function test_bad_component($parent, $child)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(strtr(
            '<child> not a valid component of <parent>',
            [
                '<child>' => $child->getName(),
                '<parent>' => $parent->getName()
            ]
        ));
        $parent->addComponent($child);
    }

    public function badComponentProvider()
    {
        return [
            [new Daylight(), new Daylight()],
            [new Daylight(), new Standard()],
            [new Daylight(), new Valarm()],
            [new Daylight(), new Vcalendar()],
            [new Daylight(), new Vevent()],
            [new Daylight(), new Vfreebusy()],
            [new Daylight(), new Vjournal()],
            [new Daylight(), new Vtimezone()],
            [new Daylight(), new Vtodo()],

            [new Standard(), new Daylight()],
            [new Standard(), new Standard()],
            [new Standard(), new Valarm()],
            [new Standard(), new Vcalendar()],
            [new Standard(), new Vevent()],
            [new Standard(), new Vfreebusy()],
            [new Standard(), new Vjournal()],
            [new Standard(), new Vtimezone()],
            [new Standard(), new Vtodo()],

            [new Valarm(), new Daylight()],
            [new Valarm(), new Standard()],
            [new Valarm(), new Valarm()],
            [new Valarm(), new Vcalendar()],
            [new Valarm(), new Vevent()],
            [new Valarm(), new Vfreebusy()],
            [new Valarm(), new Vjournal()],
            [new Valarm(), new Vtimezone()],
            [new Valarm(), new Vtodo()],

            [new Vcalendar(), new Daylight()],
            [new Vcalendar(), new Standard()],
            [new Vcalendar(), new Vcalendar()],

            [new Vevent(), new Daylight()],
            [new Vevent(), new Standard()],
            [new Vevent(), new Vcalendar()],
            [new Vevent(), new Vevent()],
            [new Vevent(), new Vfreebusy()],
            [new Vevent(), new Vjournal()],
            [new Vevent(), new Vtimezone()],
            [new Vevent(), new Vtodo()],

            [new Vfreebusy(), new Daylight()],
            [new Vfreebusy(), new Standard()],
            [new Vfreebusy(), new Valarm()],
            [new Vfreebusy(), new Vcalendar()],
            [new Vfreebusy(), new Vevent()],
            [new Vfreebusy(), new Vfreebusy()],
            [new Vfreebusy(), new Vjournal()],
            [new Vfreebusy(), new Vtimezone()],
            [new Vfreebusy(), new Vtodo()],

            [new Vjournal(), new Daylight()],
            [new Vjournal(), new Standard()],
            [new Vjournal(), new Valarm()],
            [new Vjournal(), new Vcalendar()],
            [new Vjournal(), new Vevent()],
            [new Vjournal(), new Vfreebusy()],
            [new Vjournal(), new Vjournal()],
            [new Vjournal(), new Vtimezone()],
            [new Vjournal(), new Vtodo()],

            [new Vtimezone(), new Valarm()],
            [new Vtimezone(), new Vcalendar()],
            [new Vtimezone(), new Vevent()],
            [new Vtimezone(), new Vfreebusy()],
            [new Vtimezone(), new Vjournal()],
            [new Vtimezone(), new Vtimezone()],
            [new Vtimezone(), new Vtodo()],

            [new Vtodo(), new Daylight()],
            [new Vtodo(), new Standard()],
            [new Vtodo(), new Vcalendar()],
            [new Vtodo(), new Vevent()],
            [new Vtodo(), new Vfreebusy()],
            [new Vtodo(), new Vjournal()],
            [new Vtodo(), new Vtimezone()],
            [new Vtodo(), new Vtodo()],
        ];
    }
}
