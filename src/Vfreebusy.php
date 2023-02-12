<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

class Vfreebusy extends Component
{
    public const FREEBUSY_SEPARATOR = '/';
    public const NAME = 'VFREEBUSY';
    public const PROPERTY_FREEBUSY = 'FREEBUSY';
    public const TIME_TYPE_BUSY = 'BUSY';
    public const TIME_TYPE_BUSY_TENTATIVE = 'BUSY-TENTATIVE';
    public const TIME_TYPE_BUSY_UNAVAILABLE = 'BUSY-UNAVAILABLE';
    public const TIME_TYPE_FREE = 'FREE';

    public const CARDINALITY = [
        self::PROPERTY_ATTENDEE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_COMMENT => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_CONTACT => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_DATETIME_END => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_DATETIME_STAMP => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_DATETIME_START => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_FREEBUSY => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_ORGANIZER => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_REQUEST_STATUS => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_UID => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_URL => self::CARDINALITY_ONE_MAY,
    ];

    protected const COMPONENTS = [];
}
