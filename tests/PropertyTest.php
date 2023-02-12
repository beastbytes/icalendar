<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests;

use BeastBytes\ICalendar\Component;
use BeastBytes\ICalendar\Daylight;
use BeastBytes\ICalendar\Exception\InvalidPropertyException;
use BeastBytes\ICalendar\Exception\MissingPropertyException;
use BeastBytes\ICalendar\Standard;
use BeastBytes\ICalendar\Valarm;
use BeastBytes\ICalendar\Vcalendar;
use BeastBytes\ICalendar\Vevent;
use BeastBytes\ICalendar\Vfreebusy;
use BeastBytes\ICalendar\Vjournal;
use BeastBytes\ICalendar\Vtimezone;
use BeastBytes\ICalendar\Vtodo;
use PHPUnit\Framework\TestCase;

use function strtr;

class PropertyTest extends TestCase
{
    /**
     * @dataProvider invalidPropertyProvider
     */
    public function test_invalid_property(Component $component, string $property, ?string $action)
    {
        if (is_string($action)) {
            $component = $component->addProperty(Valarm::PROPERTY_ACTION, $action);
            $message = strtr(
                '{property} is not a valid property of {component} when {component}::ACTION is {action}',
                [
                    '{action}' => $action,
                    '{component}' => $component->getName(),
                    '{property}' => $property
                ]
            );
        } else {
            $message = strtr(
                '{property} is not a valid property of {component}',
                [
                    '{component}' => $component->getName(),
                    '{property}' => $property
                ]
            );
        }

        $this->expectException(InvalidPropertyException::class);
        $this->expectExceptionMessage($message);
        $component->addProperty($property, random_int(0, 100));
    }

    /**
     * @dataProvider validPropertyProvider
     */
    public function test_valid_property(Component $component, string $property, ?string $action)
    {
        if (is_string($action)) {
            $component = $component->addProperty(Valarm::PROPERTY_ACTION, $action);
        }

        $this->assertTrue(
            $component
                ->addProperty($property, random_int(0, 100))
                ->hasProperty($property)
        );
    }

    /**
     * @dataProvider validPropertyProvider
     */
    public function test_property_cardinality(Component $component, string $property, ?string $action)
    {
        if (is_string($action)) {
            $component = $component->addProperty(Valarm::PROPERTY_ACTION, $action);
        }

        if (in_array(
            $component->getCardinality($property),
            [Component::CARDINALITY_ONE_MAY, Component::CARDINALITY_ONE_MUST],
            true
        )) {
            if (!$component->hasProperty($property)) {
                $value = $property === Valarm::PROPERTY_ACTION
                    ? array_rand(
                        array_flip([
                            Valarm::ACTION_AUDIO,
                            Valarm::ACTION_DISPLAY,
                            Valarm::ACTION_EMAIL
                        ])
                    )
                    : random_int(0, 100);
                $component = $component->addProperty($property, $value);
            }

            $this->assertTrue($component->hasProperty($property));

            $this->expectException(InvalidPropertyException::class);
            $this->expectExceptionMessage(
                strtr(
                    '{component} may only have one of property {property}',
                    [
                        '{component}' => $component->getName(),
                        '{property}' => $property,
                    ]
                )
            );
            $component->addProperty($property, random_int(0, 100));
        } else {
            $n = random_int(2, 7);

            for ($i = 0; $i < $n; $i++) {
                $component = $component->addProperty($property, random_int(0, 100));
            }

            $this->assertTrue($component->hasProperty($property));
            $this->assertCount($n, $component->getProperty($property));
        }
    }

    /**
     * @dataProvider validPropertyProvider
     */
    public function test_remove_property(Component $component, string $property, ?string $action)
    {
        if (is_string($action)) {
            $component = $component->addProperty(Valarm::PROPERTY_ACTION, $action);
        }

        if (in_array(
            $component->getCardinality($property),
            [Component::CARDINALITY_ONE_OR_MORE_MAY, Component::CARDINALITY_ONE_OR_MORE_MUST],
            true
        )) {
            $component = $component
                ->addProperty($property, random_int(0, 100))
                ->addProperty($property, random_int(0, 100))
            ;

            $this->assertTrue(
                $component
                    ->hasProperty($property)
            );

            $this->assertCount(is_string($action) ? 2 : 1, $component->getProperties());
            $this->assertCount(2, $component->getProperty($property));

            $component = $component
                ->removeProperty($property, 0)
            ;
            $this->assertTrue($component->hasProperty($property));
            $this->assertCount(1, $component->getProperty($property));

            $this->assertNull($component->getProperty($property, 1));

            $component = $component->removeProperty($property, 0);
            $this->assertFalse($component->hasProperty($property));

            $this->assertNull($component->getProperty($property));
        } else {
            if ($property !== Component::PROPERTY_VERSION) {
                $component = $component->addProperty($property, random_int(0, 100));
            }

            $this->assertCount(is_string($action) ? 2 : 1, $component->getProperties());

            $this->assertTrue($component->hasProperty($property));

            $this->assertFalse(
                $component
                    ->removeProperty($property)
                    ->hasProperty($property)
            );
        }
    }

    public function test_update_property()
    {
        $component = new Vevent();

        $this->assertFalse($component->hasProperty(Vevent::PROPERTY_SEQUENCE));
        $component->addProperty(Vevent::PROPERTY_SEQUENCE, 0);

        for ($i = 1, $j = rand(5, 25); $i < $j; $i++) {
            $component = $component->setProperty(Vevent::PROPERTY_SEQUENCE, 0, $i);

            $this->assertTrue($component->hasProperty(Vevent::PROPERTY_SEQUENCE));
            $this->assertCount(1, $component->getProperty(Vevent::PROPERTY_SEQUENCE));
            $this->assertSame(
                (string) $i,
                $component
                    ->getProperty(Vevent::PROPERTY_SEQUENCE, 0)
                    ->getValue()
            );
        }
    }

    /**
     * @dataProvider missingPropertyProvider
     */
    public function test_missing_property(Component $component, string $property)
    {
        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage(strtr(
            'Required property {property} not set in {component}',
            [
                '{property}' => $property,
                '{component}' => $component->getName(),
            ]
        ));
        $component->render();
    }

    public function test_non_standard_property_not_registered()
    {
        $vCalendar = new Vcalendar();

        $nonStandardProperty = 'NON-STANDARD-PROPERTY';

        $this->expectException(InvalidPropertyException::class);
        $this->expectExceptionMessage(strtr(
            '{property} is not a valid property of {component}',
            [
                '{property}' => $nonStandardProperty,
                '{component}' => $vCalendar->getName()
            ]
        ));

        $vCalendar->addProperty($nonStandardProperty, 1);
    }

    public function test_non_standard_property()
    {
        $nonStandardProperty = 'NON-STANDARD-PROPERTY';
        Vcalendar::registerNonStandardProperty($nonStandardProperty);

        $vCalendar = (new Vcalendar())
            ->addProperty($nonStandardProperty, 1)
        ;
        $this->assertTrue($vCalendar->hasProperty($nonStandardProperty));
    }

    public function invalidPropertyProvider(): \Generator
    {
        $properties = [
            Valarm::PROPERTY_ACTION,
            Component::PROPERTY_ATTACH,
            Component::PROPERTY_ATTENDEE,
            Component::PROPERTY_CALENDAR_SCALE,
            Component::PROPERTY_CATEGORIES,
            Component::PROPERTY_CLASS,
            Component::PROPERTY_COLOR,
            Component::PROPERTY_COMMENT,
            Component::PROPERTY_COMPLETED,
            Component::PROPERTY_CONFERENCE,
            Component::PROPERTY_CONTACT,
            Component::PROPERTY_CREATED,
            Component::PROPERTY_DESCRIPTION,
            Component::PROPERTY_DATETIME_END,
            Component::PROPERTY_DATETIME_STAMP,
            Component::PROPERTY_DATETIME_START,
            Component::PROPERTY_DUE,
            Component::PROPERTY_DURATION,
            Component::PROPERTY_EXCEPTION_DATE,
            Component::PROPERTY_GEOGRAPHIC_POSITION,
            Component::PROPERTY_IMAGE,
            Component::PROPERTY_LAST_MODIFIED,
            Component::PROPERTY_LOCATION,
            Component::PROPERTY_METHOD,
            Vcalendar::PROPERTY_NAME,
            Component::PROPERTY_ORGANIZER,
            Component::PROPERTY_PERCENT_COMPLETE,
            Component::PROPERTY_PRIORITY,
            Component::PROPERTY_PRODUCT_IDENTIFIER,
            Component::PROPERTY_RECURRENCE_DATETIME,
            Component::PROPERTY_RECURRENCE_ID,
            Vcalendar::PROPERTY_REFRESH_INTERVAL,
            Component::PROPERTY_RELATED_TO,
            Component::PROPERTY_REPEAT,
            Component::PROPERTY_REQUEST_STATUS,
            Component::PROPERTY_RESOURCES,
            Component::PROPERTY_RECURRENCE_RULE,
            Component::PROPERTY_SEQUENCE,
            Vcalendar::PROPERTY_SOURCE,
            Component::PROPERTY_STATUS,
            Component::PROPERTY_SUMMARY,
            Component::PROPERTY_TRANSPARENCY,
            Valarm::PROPERTY_TRIGGER,
            Vtimezone::PROPERTY_TZID,
            Standard::PROPERTY_TZNAME,
            Standard::PROPERTY_TZOFFSETFROM,
            Standard::PROPERTY_TZOFFSETTO,
            Vtimezone::PROPERTY_TZURL,
            Component::PROPERTY_UID,
            Component::PROPERTY_URL,
            Component::PROPERTY_VERSION,
        ];

        foreach (
            [
                new Daylight(),
                new Standard(),
                new Valarm(),
                new Vcalendar(),
                new Vevent(),
                new Vfreebusy(),
                new Vjournal(),
                new Vtimezone(),
                new Vtodo(),
            ] as $component
        ) {
            if ($component instanceof Valarm) {
                foreach (
                    [
                        [Valarm::PROPERTY_ATTACH, Valarm::ACTION_DISPLAY],
                        [Valarm::PROPERTY_ATTENDEE, Valarm::ACTION_AUDIO],
                        [Valarm::PROPERTY_ATTENDEE, Valarm::ACTION_DISPLAY],
                        [Valarm::PROPERTY_DESCRIPTION, Valarm::ACTION_AUDIO],
                        [Valarm::PROPERTY_SUMMARY, Valarm::ACTION_AUDIO],
                        [Valarm::PROPERTY_SUMMARY, Valarm::ACTION_DISPLAY],
                    ] as $yield
                ) {
                    array_unshift($yield, $component);
                    yield $yield;
                }
            }
            foreach (array_diff($properties, array_keys($component::CARDINALITY)) as $property) {
                yield [$component, $property, null];
            }
        }
    }

    public function validPropertyProvider(): \Generator
    {
        foreach (
            [
                new Daylight(),
                new Standard(),
                new Valarm(),
                new Vcalendar(),
                new Vevent(),
                new Vfreebusy(),
                new Vjournal(),
                new Vtimezone(),
                new Vtodo(),
            ] as $component
        ) {
            if ($component instanceof Valarm) {
                foreach (
                    [
                        [Valarm::PROPERTY_ACTION, null],
                        [Valarm::PROPERTY_ATTACH, Valarm::ACTION_AUDIO],
                        [Valarm::PROPERTY_ATTACH, Valarm::ACTION_EMAIL],
                        [Valarm::PROPERTY_ATTENDEE, Valarm::ACTION_EMAIL],
                        [Valarm::PROPERTY_DESCRIPTION, Valarm::ACTION_DISPLAY],
                        [Valarm::PROPERTY_DESCRIPTION, Valarm::ACTION_EMAIL],
                        [Valarm::PROPERTY_DURATION, null],
                        [Valarm::PROPERTY_REPEAT, null],
                        [Valarm::PROPERTY_REQUEST_STATUS, null],
                        [Valarm::PROPERTY_SUMMARY, Valarm::ACTION_EMAIL],
                        [Valarm::PROPERTY_TRIGGER, null],
                    ] as $yield
                ) {
                    array_unshift($yield, $component);
                    yield $yield;
                }
            } else {
                foreach (array_keys($component::CARDINALITY) as $property) {
                    // Vcalendar::VERSION is set in the constructor
                    if (!$component instanceof Vcalendar && $property !== Vcalendar::PROPERTY_VERSION) {
                        yield [$component, $property, null];
                    }
                }
            }
        }
    }

    public function missingPropertyProvider(): \Generator
    {
        foreach (
            [
                [new Daylight(), Daylight::PROPERTY_DATETIME_START],
                [new Standard(), Daylight::PROPERTY_DATETIME_START],
                [new Valarm(), Valarm::PROPERTY_ACTION],
                [new Vcalendar(), Vcalendar::PROPERTY_PRODUCT_IDENTIFIER],
                [new Vevent(), Vevent::PROPERTY_DATETIME_STAMP],
                [new Vfreebusy(), Vfreebusy::PROPERTY_DATETIME_STAMP],
                [new Vjournal(), Vjournal::PROPERTY_DATETIME_STAMP],
                [new Vtimezone(), Vtimezone::PROPERTY_TZID],
                [new Vtodo(), Vtodo::PROPERTY_DATETIME_STAMP],
            ] as $componentProperty
        ) {
            yield [$componentProperty[0], $componentProperty[1]];
        }
    }
}