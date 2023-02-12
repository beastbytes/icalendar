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
    public function __construct(Component $component, string $property, int $code = 0, ?Throwable $previous = null)
    {
        $message = match ($code) {
            0 => sprintf('%s is not a valid property of %s', $property, $component->getName()),
            1 => sprintf(
                '%1$s is not a valid property of %2$s when %2$s::ACTION is %3$s',
                $property,
                $component->getName(),
                $component->getProperty(Valarm::PROPERTY_ACTION, 0)->getValue()
            ),
            2 => sprintf('%s may only have one of property %s', $component->getName(), $property),
            3 => sprintf('Badly formatted property while importing %s: %s', $component->getName(), $property),
        };

        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
