<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar\Exception;

use BeastBytes\ICalendar\Component;
use Throwable;

class InvalidComponentException extends \RuntimeException
{
    public const INVALID_ICALENDAR_MESSAGE = 'Invalid iCalendar';
    public const INVALID_COMPONENT_MESSAGE = '{child} is not a valid component of {parent}';

    public function __construct(
        ?Component $parent = null,
        ?Component $child = null,
        int $code = 0,
        ?Throwable $previous = null
    )
    {
        if ($child === null && $parent === null) {
            $message = self::INVALID_ICALENDAR_MESSAGE;
        } else {
            $message = strtr(self::INVALID_COMPONENT_MESSAGE, [
                /** @psalm-suppress PossiblyNullReference */
                '{child}' => $child->getName(),
                '{parent}' => $parent->getName()
            ]);
        }

        parent::__construct($message, $code, $previous);
    }
}
