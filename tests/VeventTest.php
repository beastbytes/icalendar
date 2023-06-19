<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar\Tests;

use BeastBytes\ICalendar\Vevent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class VeventTest extends TestCase
{
    #[DataProvider('veventProvider')]
    public function test_vevent($vevent, $expected)
    {
        $this->assertSame(implode("\r\n", $expected) . "\r\n", $vevent->render());
    }

    public static function veventProvider(): \Generator
    {
        foreach ([
            '19970901T130000Z-123401@example.com' => [
                (new Vevent())
                    ->addProperty(Vevent::PROPERTY_UID, '19970901T130000Z-123401@example.com')
                    ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '19970901T130000Z')
                    ->addProperty(Vevent::PROPERTY_DATETIME_START, '19970903T163000Z')
                    ->addProperty(Vevent::PROPERTY_DATETIME_END, '19970903T190000Z')
                    ->addProperty(Vevent::PROPERTY_SUMMARY, 'Annual Employee Review')
                    ->addProperty(Vevent::PROPERTY_CLASSIFICATION, Vevent::CLASSIFICATION_PRIVATE)
                    ->addProperty(Vevent::PROPERTY_CATEGORIES, ['BUSINESS', 'HUMAN RESOURCES'])
                ,
                [
                    'BEGIN:VEVENT',
                    'UID:19970901T130000Z-123401@example.com',
                    'DTSTAMP:19970901T130000Z',
                    'DTSTART:19970903T163000Z',
                    'DTEND:19970903T190000Z',
                    'SUMMARY:Annual Employee Review',
                    'CLASS:PRIVATE',
                    'CATEGORIES:BUSINESS,HUMAN RESOURCES',
                    'END:VEVENT'
                ]
            ],
            '19970901T130000Z-123402@example.com' => [
                (new Vevent())
                    ->addProperty(Vevent::PROPERTY_UID, '19970901T130000Z-123402@example.com')
                    ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '19970901T130000Z')
                    ->addProperty(Vevent::PROPERTY_DATETIME_START, '19970401T163000Z')
                    ->addProperty(Vevent::PROPERTY_DATETIME_END, '19970402T010000Z')
                    ->addProperty(Vevent::PROPERTY_SUMMARY, 'Laurel is in sensitivity awareness class.')
                    ->addProperty(Vevent::PROPERTY_CLASSIFICATION, Vevent::CLASSIFICATION_PUBLIC)
                    ->addProperty(Vevent::PROPERTY_CATEGORIES, ['BUSINESS', 'HUMAN RESOURCES'])
                    ->addProperty(Vevent::PROPERTY_TIME_TRANSPARENCY, Vevent::TRANSPARENCY_TRANSPARENT)
                ,
                [
                    'BEGIN:VEVENT',
                    'UID:19970901T130000Z-123402@example.com',
                    'DTSTAMP:19970901T130000Z',
                    'DTSTART:19970401T163000Z',
                    'DTEND:19970402T010000Z',
                    'SUMMARY:Laurel is in sensitivity awareness class.',
                    'CLASS:PUBLIC',
                    'CATEGORIES:BUSINESS,HUMAN RESOURCES',
                    'TRANSP:TRANSPARENT',
                    'END:VEVENT'
                ]
            ],
            '19970901T130000Z-123403@example.com' => [
                (new Vevent())
                    ->addProperty(Vevent::PROPERTY_UID, '19970901T130000Z-123403@example.com')
                    ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '19970901T130000Z')
                    ->addProperty(
                        Vevent::PROPERTY_DATETIME_START,
                        '19970901T130000Z',
                        [
                            Vevent::PARAMETER_VALUE => Vevent::DATA_TYPE_DATE
                        ]
                    )
                    ->addProperty(Vevent::PROPERTY_SUMMARY, 'Our Blissful Anniversary')
                    ->addProperty(Vevent::PROPERTY_TIME_TRANSPARENCY, Vevent::TRANSPARENCY_TRANSPARENT)
                    ->addProperty(Vevent::PROPERTY_CLASSIFICATION, Vevent::CLASSIFICATION_CONFIDENTIAL)
                    ->addProperty(Vevent::PROPERTY_CATEGORIES, ['ANNIVERSARY', 'PERSONAL', 'SPECIAL OCCASION'])
                    ->addProperty(Vevent::PROPERTY_RECURRENCE_RULE, [Vevent::RRULE_FREQ => Vevent::FREQUENCY_YEARLY])
                ,
                [
                    'BEGIN:VEVENT',
                    'UID:19970901T130000Z-123403@example.com',
                    'DTSTAMP:19970901T130000Z',
                    'DTSTART;VALUE=DATE:19970901T130000Z',
                    'SUMMARY:Our Blissful Anniversary',
                    'TRANSP:TRANSPARENT',
                    'CLASS:CONFIDENTIAL',
                    'CATEGORIES:ANNIVERSARY,PERSONAL,SPECIAL OCCASION',
                    'RRULE:FREQ=YEARLY',
                    'END:VEVENT'
                ]
            ],
            '20070423T123432Z-541111@example.com' => [
                (new Vevent())
                    ->addProperty(Vevent::PROPERTY_UID, '20070423T123432Z-541111@example.com')
                    ->addProperty(Vevent::PROPERTY_DATETIME_STAMP, '20070423T123432Z')
                    ->addProperty(
                        Vevent::PROPERTY_DATETIME_START,
                        '20070628',
                        [
                            Vevent::PARAMETER_VALUE => Vevent::DATA_TYPE_DATE
                        ]
                    )
                    ->addProperty(
                        Vevent::PROPERTY_DATETIME_END,
                        '20070709',
                        [
                            Vevent::PARAMETER_VALUE => Vevent::DATA_TYPE_DATE
                        ]
                    )
                    ->addProperty(Vevent::PROPERTY_SUMMARY, 'Festival International de Jazz de Montreal')
                    ->addProperty(Vevent::PROPERTY_TIME_TRANSPARENCY, Vevent::TRANSPARENCY_TRANSPARENT)
                ,
                [
                    'BEGIN:VEVENT',
                    'UID:20070423T123432Z-541111@example.com',
                    'DTSTAMP:20070423T123432Z',
                    'DTSTART;VALUE=DATE:20070628',
                    'DTEND;VALUE=DATE:20070709',
                    'SUMMARY:Festival International de Jazz de Montreal',
                    'TRANSP:TRANSPARENT',
                    'END:VEVENT'
                ],
            ],
        ] as $name => $value) {
            yield $name => $value;
        }
    }
}
