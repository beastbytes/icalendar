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
    public function __construct(
        ?Component $parent = null,
        ?Component $child = null,
        int $code = 0,
        ?Throwable $previous = null
    )
    {
        if ($parent === null) {
            $message = 'Invalid iCalendar';
        } else {
            $message = sprintf('%s is not a valid component of %s', $child->getName(), $parent->getName());
        }

        parent::__construct($message, $code, $previous);
    }
}