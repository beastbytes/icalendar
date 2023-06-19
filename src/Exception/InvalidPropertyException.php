<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar\Exception;

use BeastBytes\ICalendar\Component;
use BeastBytes\ICalendar\Valarm;
use Throwable;

class InvalidPropertyException extends \RuntimeException
{
    public const BADLY_FORMATTED_PROPERTY_MESSAGE = 'Badly formatted property while importing {component}: "{property}"';
    public const INVALID_PROPERTY_MESSAGE = '"{property}" is not a valid property of {component}';
    public const INVALID_PROPERTY_WHEN_ACTION_MESSAGE =
        '"{property}" is not a valid property of {component} when {component}::ACTION is "{action}"';
    public const INVALID_PROPERTY_VALUE =
        'Invalid value ({value}) for property "{property}" in {component}';
    public const ONLY_ONE_PROPERTY_MESSAGE = '{component} may only have one of property "{property}"';

    public function __construct(
        Component $component,
        string $property,
        ?string $value = null,
        int $code = 0,
        ?Throwable $previous = null
    )
    {
        $message = match ($code) {
            0 => strtr(self::INVALID_PROPERTY_MESSAGE, [
                '{component}' => $component->getName(),
                '{property}' => $property
            ]),
            1 => strtr(self::INVALID_PROPERTY_WHEN_ACTION_MESSAGE, [
                '{action}' => $component->getProperty(Valarm::PROPERTY_ACTION, 0)->getValue(),
                '{component}' => $component->getName(),
                '{property}' => $property
            ]),
            2 => strtr(self::ONLY_ONE_PROPERTY_MESSAGE, [
                '{component}' => $component->getName(),
                '{property}' => $property
            ]),
            3 => strtr(self::BADLY_FORMATTED_PROPERTY_MESSAGE, [
                '{component}' => $component->getName(),
                '{property}' => $property
            ]),
            4 => strtr(self::INVALID_PROPERTY_VALUE, [
                '{component}' => $component->getName(),
                '{property}' => $property,
                '{value}' => $value
            ]),
            default => 'Invalid Exception code'
        };

        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
