<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

class Vjournal extends Component
{
    public const NAME = 'VJOURNAL';
    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_FINAL = 'FINAL';

    public const CARDINALITY = [
        self::PROPERTY_ATTACH => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_ATTENDEE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_CATEGORIES => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_CLASSIFICATION => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_COLOR => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_COMMENT => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_CONTACT => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_DATETIME_CREATED => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_DATETIME_STAMP => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_DATETIME_START => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_DESCRIPTION => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_EXCEPTION_DATE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_IMAGE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_LAST_MODIFIED => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_ORGANIZER => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_RECURRENCE_DATETIME => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_RECURRENCE_ID => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_RELATED_TO => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_REQUEST_STATUS => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_RECURRENCE_RULE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_SEQUENCE => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_STATUS => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_SUMMARY => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_UID => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_URL => self::CARDINALITY_ONE_MAY,
    ];

    protected const COMPONENTS = [];
}
