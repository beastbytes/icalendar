<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);


namespace BeastBytes\ICalendar\Tests;

use BeastBytes\ICalendar\Component;
use BeastBytes\ICalendar\Daylight;
use BeastBytes\ICalendar\Exception\InvalidPropertyException;
use BeastBytes\ICalendar\Exception\MissingPropertyException;
use BeastBytes\ICalendar\Standard;
use BeastBytes\ICalendar\Tests\support\PropertyValueTrait;
use BeastBytes\ICalendar\Valarm;
use BeastBytes\ICalendar\Vcalendar;
use BeastBytes\ICalendar\Vevent;
use BeastBytes\ICalendar\Vfreebusy;
use BeastBytes\ICalendar\Vjournal;
use BeastBytes\ICalendar\Vtimezone;
use BeastBytes\ICalendar\Vtodo;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function strtr;

class PropertyTest extends TestCase
{
    use PropertyValueTrait;

    #[DataProvider('invalidPropertyProvider')]
    public function test_invalid_property(Component $component, string $property, ?string $action)
    {
        if (is_string($action)) {
            $component = $component->addProperty(Valarm::PROPERTY_ACTION, $action);
            $message = strtr(
                InvalidPropertyException::INVALID_PROPERTY_WHEN_ACTION_MESSAGE,
                [
                    '{action}' => $action,
                    '{component}' => $component->getName(),
                    '{property}' => $property
                ]
            );
        } else {
            $message = strtr(
                InvalidPropertyException::INVALID_PROPERTY_MESSAGE,
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

    #[DataProvider('validPropertyProvider')]
    public function test_valid_property(Component $component, string $property, ?string $action)
    {
        if (is_string($action)) {
            $component = $component->addProperty(Valarm::PROPERTY_ACTION, $action);
        }

        $this->assertTrue(
            $component
                ->addProperty($property, $this->getValidValue($component, $property))
                ->hasProperty($property)
        );
    }

    #[DataProvider('validPropertyProvider')]
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
                $component = $component->addProperty($property, $this->getValidValue($component, $property));
            }

            $this->assertTrue($component->hasProperty($property));

            $this->expectException(InvalidPropertyException::class);
            $this->expectExceptionMessage(
                strtr(
                    InvalidPropertyException::ONLY_ONE_PROPERTY_MESSAGE,
                    [
                        '{component}' => $component->getName(),
                        '{property}' => $property,
                    ]
                )
            );
            $component->addProperty($property, $this->getValidValue($component, $property));
        } else {
            $n = random_int(2, 7);

            for ($i = 0; $i < $n; $i++) {
                $component = $component->addProperty($property, $this->getValidValue($component, $property));
            }

            $this->assertTrue($component->hasProperty($property));
            $this->assertCount($n, $component->getProperty($property));
        }
    }

    #[DataProvider('validPropertyProvider')]
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
                ->addProperty($property, $this->getValidValue($component, $property))
                ->addProperty($property, $this->getValidValue($component, $property))
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
                $component = $component->addProperty($property, $this->getValidValue($component, $property));
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

    #[DataProvider('missingPropertyProvider')]
    public function test_missing_property(Component $component, string $property)
    {
        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage(strtr(MissingPropertyException::MISSING_PROPERTY_EXCEPTION_MESSAGE, [
            '{component}' => $component->getName(),
            '{property}' => $property,
        ]));
        $component->render();
    }

    public function test_non_standard_property_not_registered()
    {
        $vCalendar = new Vcalendar();

        $nonStandardProperty = 'UNREGISTERED-NON-STANDARD-PROPERTY';

        $this->expectException(InvalidPropertyException::class);
        $this->expectExceptionMessage(strtr(
            InvalidPropertyException::INVALID_PROPERTY_MESSAGE,
            [
                '{component}' => $vCalendar->getName(),
                '{property}' => $nonStandardProperty
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

    public static function invalidPropertyProvider(): \Generator
    {
        $properties = [
            Valarm::PROPERTY_ACTION,
            Component::PROPERTY_ATTACH,
            Component::PROPERTY_ATTENDEE,
            Component::PROPERTY_CALENDAR_SCALE,
            Component::PROPERTY_CATEGORIES,
            Component::PROPERTY_CLASSIFICATION,
            Component::PROPERTY_COLOR,
            Component::PROPERTY_COMMENT,
            Component::PROPERTY_DATETIME_COMPLETED,
            Component::PROPERTY_CONFERENCE,
            Component::PROPERTY_CONTACT,
            Component::PROPERTY_DATETIME_CREATED,
            Component::PROPERTY_DESCRIPTION,
            Component::PROPERTY_DATETIME_END,
            Component::PROPERTY_DATETIME_STAMP,
            Component::PROPERTY_DATETIME_START,
            Component::PROPERTY_DATETIME_DUE,
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
            Component::PROPERTY_TIME_TRANSPARENCY,
            Valarm::PROPERTY_TRIGGER,
            Vtimezone::PROPERTY_TZ_ID,
            Standard::PROPERTY_TZ_NAME,
            Standard::PROPERTY_TZ_OFFSET_FROM,
            Standard::PROPERTY_TZ_OFFSET_TO,
            Vtimezone::PROPERTY_TZ_URL,
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
                    $name = implode('::', $yield);
                    yield $name => $yield;
                }
            } else {
                foreach (array_diff($properties, array_keys($component::CARDINALITY)) as $property) {
                    $name = $component->getName() . '::' . $property;
                    yield $name => [$component, $property, null];
                }
            }
        }
    }

    public static function validPropertyProvider(): \Generator
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
                    $name = implode('::', $yield);
                    yield $name => $yield;
                }
            } else {
                foreach (array_keys($component::CARDINALITY) as $property) {
                    // Vcalendar::VERSION is set in the Vcalendar::_construct()
                    if (!$component instanceof Vcalendar && $property !== Vcalendar::PROPERTY_VERSION) {
                        $name = $component->getName() . '::' . $property;
                        yield $name => [$component, $property, null];
                    }
                }
            }
        }
    }

    public static function missingPropertyProvider(): \Generator
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
                [new Vtimezone(), Vtimezone::PROPERTY_TZ_ID],
                [new Vtodo(), Vtodo::PROPERTY_DATETIME_STAMP],
            ] as $yield
        ) {
            $name = implode('::', $yield);
            yield $name => $yield;
        }
    }
}
