<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests;

use BeastBytes\ICalendar\Vtodo;
use PHPUnit\Framework\TestCase;

class VtodoTest extends TestCase
{
    /**
     * @dataProvider vtodoProvider
     */
    public function test_vtodo($vtodo, $expected)
    {
        $this->assertSame(implode("\r\n", $expected) . "\r\n", $vtodo->render());
    }

    public function vtodoProvider()
    {
        return [
            [
                (new Vtodo())
                    ->addProperty(Vtodo::PROPERTY_UID, '20070313T123432Z-456553@example.com')
                    ->addProperty(Vtodo::PROPERTY_DATETIME_STAMP, '20070313T123432Z')
                    ->addProperty(
                        Vtodo::PROPERTY_DATETIME_DUE,
                        '20070501',
                        [
                            Vtodo::PARAMETER_VALUE => Vtodo::DATA_TYPE_DATE
                        ]
                    )
                    ->addProperty(Vtodo::PROPERTY_SUMMARY, 'Submit Quebec Income Tax Return for 2006')
                    ->addProperty(Vtodo::PROPERTY_CLASSIFICATION, Vtodo::CLASSIFICATION_CONFIDENTIAL)
                    ->addProperty(Vtodo::PROPERTY_CATEGORIES, ['FAMILY', 'FINANCE'])
                    ->addProperty(Vtodo::PROPERTY_STATUS, Vtodo::STATUS_NEEDS_ACTION)
                ,
                [
                    'BEGIN:VTODO',
                    'UID:20070313T123432Z-456553@example.com',
                    'DTSTAMP:20070313T123432Z',
                    'DUE;VALUE=DATE:20070501',
                    'SUMMARY:Submit Quebec Income Tax Return for 2006',
                    'CLASS:CONFIDENTIAL',
                    'CATEGORIES:FAMILY,FINANCE',
                    'STATUS:NEEDS-ACTION',
                    'END:VTODO'
                ]
            ],
            [
                (new Vtodo())
                    ->addProperty(Vtodo::PROPERTY_UID,'20070514T103211Z-123404@example.com')
                    ->addProperty(Vtodo::PROPERTY_DATETIME_STAMP, '20070514T103211Z')
                    ->addProperty(Vtodo::PROPERTY_DATETIME_START, '20070514T110000Z')
                    ->addProperty(Vtodo::PROPERTY_DATETIME_DUE, '20070709T130000Z')
                    ->addProperty(Vtodo::PROPERTY_DATETIME_COMPLETED, '20070707T100000Z')
                    ->addProperty(Vtodo::PROPERTY_SUMMARY, 'Submit Revised Internet-Draft')
                    ->addProperty(Vtodo::PROPERTY_PRIORITY, 1)
                    ->addProperty(Vtodo::PROPERTY_STATUS, Vtodo::STATUS_NEEDS_ACTION)
                ,
                [
                    'BEGIN:VTODO',
                    'UID:20070514T103211Z-123404@example.com',
                    'DTSTAMP:20070514T103211Z',
                    'DTSTART:20070514T110000Z',
                    'DUE:20070709T130000Z',
                    'COMPLETED:20070707T100000Z',
                    'SUMMARY:Submit Revised Internet-Draft',
                    'PRIORITY:1',
                    'STATUS:NEEDS-ACTION',
                    'END:VTODO'
                ]
            ],
        ];
    }
}
