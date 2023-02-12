<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests;

use BeastBytes\ICalendar\Daylight;
use BeastBytes\ICalendar\Exception\InvalidComponentException;
use BeastBytes\ICalendar\Standard;
use BeastBytes\ICalendar\Valarm;
use BeastBytes\ICalendar\Vcalendar;
use BeastBytes\ICalendar\Vevent;
use BeastBytes\ICalendar\Vfreebusy;
use BeastBytes\ICalendar\Vjournal;
use BeastBytes\ICalendar\Vtimezone;
use BeastBytes\ICalendar\Vtodo;
use PHPUnit\Framework\TestCase;
use Tests\support\NonStandardComponent;

use function strtr;

class ComponentTest extends TestCase
{
    /**
     * @dataProvider invalidComponentProvider
     */
    public function test_invalid_component($parent, $child)
    {
        $this->expectException(InvalidComponentException::class);
        $this->expectExceptionMessage(strtr(
            '{child} is not a valid component of {parent}',
            [
                '{child}' => $child->getName(),
                '{parent}' => $parent->getName()
            ]
        ));
        $parent->addComponent($child);
    }

    /**
     * @dataProvider validComponentProvider
     */
    public function test_valid_component($parent, $child)
    {
        $this->assertTrue(
            $parent
                ->addComponent($child)
                ->hasComponent(
                    $child->getName()
                )
        );
    }

    public function test_update_component()
    {
        $vcalendar = new Vcalendar();
        $this->assertFalse($vcalendar->hasComponent(Vevent::NAME));

        $vcalendar = $vcalendar
            ->addProperty(Vcalendar::PROPERTY_PRODUCT_IDENTIFIER, 'prodId')
            ->addComponent(
                (new Vevent())
                    ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '20230202T000102Z')
                    ->addProperty(Vevent::PROPERTY_UID, Vevent::uuidv4())
                    ->addProperty(Vevent::PROPERTY_SEQUENCE, 0)
                    ->addProperty(Vevent::PROPERTY_DATETIME_START, '20230506T101010Z')
            )
            ->addComponent(
                (new Vevent())
                    ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '20230202T000102Z')
                    ->addProperty(Vevent::PROPERTY_UID, Vevent::uuidv4())
                    ->addProperty(Vevent::PROPERTY_SEQUENCE, 0)
                    ->addProperty(Vevent::PROPERTY_DATETIME_START, '20230506T202020Z')
            )
        ;

        $this->assertTrue($vcalendar->hasComponent(Vevent::NAME));
        $this->assertCount(2, $vcalendar->getComponent(Vevent::NAME));

        $vevent = $vcalendar->getComponent(Vevent::NAME, 1);

        $vevent1 = $vevent
            ->setProperty(Vevent::PROPERTY_SEQUENCE, 0, 1)
            ->setProperty(Vevent::PROPERTY_DATETIME_START, 0,'20230506T181818Z')
        ;

        $this->assertSame(
            $vevent->getProperty(Vevent::PROPERTY_UID, 0),
            $vevent1->getProperty(Vevent::PROPERTY_UID, 0)
        );
        $this->assertNotSame(
            $vevent->getProperty(Vevent::PROPERTY_SEQUENCE, 0),
            $vevent1->getProperty(Vevent::PROPERTY_SEQUENCE, 0)
        );
        $this->assertNotSame(
            $vevent->getProperty(Vevent::PROPERTY_DATETIME_START, 0),
            $vevent1->getProperty(Vevent::PROPERTY_DATETIME_START, 0)
        );

        $vcalendar = $vcalendar->setComponent($vevent1, 1);

        $this->assertTrue($vcalendar->hasComponent(Vevent::NAME));
        $this->assertCount(2, $vcalendar->getComponent(Vevent::NAME));

        $vevent2 = $vcalendar->getComponent(Vevent::NAME, 1);

        $this->assertSame(
            $vevent1->getProperty(Vevent::PROPERTY_UID, 0),
            $vevent2->getProperty(Vevent::PROPERTY_UID, 0)
        );

        $this->assertSame(
            $vevent1->getProperty(Vevent::PROPERTY_DATETIME_START, 0),
            $vevent2->getProperty(Vevent::PROPERTY_DATETIME_START, 0)
        );

        $this->assertSame(
            $vevent1->getProperty(Vevent::PROPERTY_SEQUENCE, 0),
            $vevent2->getProperty(Vevent::PROPERTY_SEQUENCE, 0)
        );
    }

    public function test_remove_component()
    {
        $vcalendar = new Vcalendar();
        $this->assertFalse($vcalendar->hasComponent(Vevent::NAME));

        $vcalendar = $vcalendar
            ->addProperty(Vcalendar::PROPERTY_PRODUCT_IDENTIFIER, 'prodId')
            ->addComponent(
                (new Vevent())
                    ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '20230202T000102Z')
                    ->addProperty(Vevent::PROPERTY_UID, Vevent::uuidv4())
                    ->addProperty(Vevent::PROPERTY_SEQUENCE, 0)
                    ->addProperty(Vevent::PROPERTY_DATETIME_START, '20230506T101010Z')
            )
            ->addComponent(
                (new Vevent())
                    ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '20230202T000102Z')
                    ->addProperty(Vevent::PROPERTY_UID, Vevent::uuidv4())
                    ->addProperty(Vevent::PROPERTY_SEQUENCE, 0)
                    ->addProperty(Vevent::PROPERTY_DATETIME_START, '20230506T202020Z')
            )
        ;

        $this->assertTrue($vcalendar->hasComponent(Vevent::NAME));
        $this->assertCount(2, $vcalendar->getComponent(Vevent::NAME));

        $vcalendar = $vcalendar->removeComponent(Vevent::NAME, 0);

        $this->assertTrue($vcalendar->hasComponent(Vevent::NAME));
        $this->assertCount(1, $vcalendar->getComponent(Vevent::NAME));
        $this->assertNull($vcalendar->getComponent(Vevent::NAME, 1));

        $vcalendar = $vcalendar->removeComponent(Vevent::NAME, 0);

        $this->assertFalse($vcalendar->hasComponent(Vevent::NAME));
        $this->assertNull($vcalendar->getComponent(Vevent::NAME));
    }

    public function test_non_standard_component_not_registered()
    {
        $vCalendar = new Vcalendar();
        $nonStandardComponent = new NonStandardComponent();

        $this->expectException(InvalidComponentException::class);
        $this->expectExceptionMessage(strtr(
            '{child} is not a valid component of {parent}',
            [
                '{child}' => $nonStandardComponent->getName(),
                '{parent}' => $vCalendar->getName()
            ]
        ));

        $vCalendar->addComponent($nonStandardComponent);
    }

    public function test_non_standard_component()
    {
        $nonStandardComponent = new NonStandardComponent();
        Vcalendar::registerNonStandardComponent(NonStandardComponent::NAME);

        $vCalendar = (new Vcalendar())
            ->addComponent($nonStandardComponent)
        ;
        $this->assertTrue($vCalendar->hasComponent(NonStandardComponent::NAME));
    }

    public function test_uid_generator()
    {
        $uuids = [];

        for ($i = 0; $i < 1000; $i++) {
            $uuid = Vevent::uuidv4();
            $this->assertMatchesRegularExpression(
                '|^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$|',
                $uuid
            );
            $this->assertFalse(in_array($uuid, $uuids));
            $uuids[] = $uuid;
        }
    }

    public function invalidComponentProvider()
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

    public function validComponentProvider()
    {
        return [
            [new Vcalendar(), new Vevent()],
            [new Vcalendar(), new Vfreebusy()],
            [new Vcalendar(), new Vjournal()],
            [new Vcalendar(), new Vtodo()],
            [new Vevent(), new Valarm()],
            [new Vtimezone(), new Daylight()],
            [new Vtimezone(), new Standard()],
            [new Vtodo(), new Valarm()],
        ];
    }
}
