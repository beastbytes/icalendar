<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);


use BeastBytes\ICalendar\Vjournal;
use PHPUnit\Framework\TestCase;

class VjournalTest extends TestCase
{
    /**
     * @dataProvider vjournalProvider
     */
    public function test_vjournal($vjournal, $expected)
    {
        $this->assertSame(implode("\r\n", $expected) . "\r\n", $vjournal->render());
    }

    public function vjournalProvider()
    {
        return [
            [
                (new Vjournal())
                    ->addProperty(Vjournal::PROPERTY_UID, '19970901T130000Z-123405@example.com')
                    ->addProperty(Vjournal::PROPERTY_DTSTAMP, '19970901T130000Z')
                    ->addProperty(
                        Vjournal::PROPERTY_DTSTART,
                        '19970317',
                        [
                            Vjournal::PARAMETER_VALUE => Vjournal::VALUE_DATA_TYPE_DATE
                        ]
                    )
                    ->addProperty(Vjournal::PROPERTY_SUMMARY, 'Staff meeting minutes')
                    ->addProperty(
                        Vjournal::PROPERTY_DESCRIPTION,
                        "1. Staff meeting: Participants include Joe\, Lisa\, and Bob. Aurora project plans were reviewed. There is currently no budget reserves for this project. Lisa will escalate to management. Next meeting on Tuesday.\n2. Telephone Conference: ABC Corp. sales representative called to discuss new printer. Promised to get us a demo by Friday.\n3. Henry Miller (Handsoff Insurance): Car was totaled by tree. Is looking into a loaner car. 555-2323 (tel).",
                    )
                ,
                [
                    'BEGIN:VJOURNAL',
                    'UID:19970901T130000Z-123405@example.com',
                    'DTSTAMP:19970901T130000Z',
                    'DTSTART;VALUE=DATE:19970317',
                    'SUMMARY:Staff meeting minutes',
                    "DESCRIPTION:1. Staff meeting: Participants include Joe\, Lisa\, and Bob. Au\r\n rora project plans were reviewed. There is currently no budget reserves fo\r\n r this project. Lisa will escalate to management. Next meeting on Tuesday.\r\n \n2. Telephone Conference: ABC Corp. sales representative called to discuss\r\n  new printer. Promised to get us a demo by Friday.\n3. Henry Miller (Handso\r\n ff Insurance): Car was totaled by tree. Is looking into a loaner car. 555-\r\n 2323 (tel).",
                    'END:VJOURNAL'
                ]
            ],
        ];
    }
}
