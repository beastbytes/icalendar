<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar\Exception;

use BeastBytes\ICalendar\Component;
use Throwable;

class MissingPropertyException extends \RuntimeException
{
    public const MISSING_PROPERTY_EXCEPTION_MESSAGE = 'Required property "{property}" not set in {component}';

    public function __construct(Component $component, string $property, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            strtr(self::MISSING_PROPERTY_EXCEPTION_MESSAGE, [
                '{component}' => $component->getName(),
                '{property}' => $property
            ]),
            $code,
            $previous
        );
    }
}
