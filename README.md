# iCalendar
The iCalendar library provides the ability to create, edit, and import [RFC 5545 - Internet Calendaring and Scheduling (iCalendar)](https://datatracker.ietf.org/doc/html/rfc5545)
including properties defined in [RFC 7986 - New Properties for iCalendar](https://datatracker.ietf.org/doc/html/rfc7986).

## Creating iCalendars
The iCalendar library allows creation of iCalendars in an object-oriented way.

To create an iCalendar, create a new Vcalendar object then add properties and components to it; properties and other components are added to child components in a similar way; multiple components of the same type are supported, as are multiple properties with the same name in a component.

Finally, call the Vcalendar's render() method.

All iCalendar components are immutable.

### UIDs
VEVENT, VFREEBUSY, VJOURNAL, and VTODO components require the UID property;since RFC 7985 it can also be set in VCALENDAR. RFC 7985 udates the recommendation on how to construct the value; it deprecates the use of IP addresses and host and domain names due particularly due privacy and security concerns, and recommends use of Universally Unique Identifier (UUID) values as defined in Sections [4.4](https://datatracker.ietf.org/doc/html/rfc4122#section-4.4) and [4.5](https://datatracker.ietf.org/doc/html/rfc4122#section-4.5) of [RFC4122](https://datatracker.ietf.org/doc/html/rfc4122). 

The library provides a helper method that generates V4 UUIDs.

```php
$vCalendar = (new Vcalendar())
    ->addProperty(Vcalendar::PROPERTY_UID, Vcalendar::uuidv4())
    ->addComponent((new Vevent())
        ->addProperty(Vevent::PROPERTY_UID, Vevent::uuidv4())
    )
;
```

### Non-standard Components
IANA and X- components can be added to the iCalender object (Vcalendar).

Non-standard components must extend Component and define the NAME constant; they must be registered in Vcalendar before use.

```php
use BeastBytes\ICalendar\Component;

class NonStandardComponent extends Component
{
    public const NAME = 'NON-STANDARD-COMPONENT';

    protected const CARDINALITY = [
        // declare cardinality of the component's properties here
    ];
}
---
Vcalendar::registerNonStandardComponent(NonStandardComponent::NAME);

$nonStandardComponent = new NonStandardComponent();

$vCalendar = (new Vcalendar())->addComponent($nonStandardComponent);
// $vCalendar->hasComponent(NonStandardComponent::NAME) === true;
```

### Non-standard Properties
IANA and X- properties can be added to iCalender components.

Non-standard properties must be registered with the component before use; the default cardinality is one or many may be present.

```php
public const NON_STANDARD_PROPERTY = 'NON-STANDARD-PROPERTY';

Vevent::registerNonStandardProperty(self::NON_STANDARD_PROPERTY, Vevent::CARDINALITY_ONE_MAY);

$vEvent = (new Vevent())->addProperty(self::NON_STANDARD_PROPERTY, $value);
// $vEvent->hasProperty(self::NON_STANDARD_PROPERTY) === true;
```

### Example
The following example creates a To-Do with an alarm (it is the example on page 146 of RFC5545 modified to use UUID V4 for UID).

```php
$iCalendar = (new Vcalendar())
    ->addProperty(Vcalendar::PROPERTY_PRODUCT_IDENTIFIER, '-//ABC Corporation//NONSGML My Product//EN')
    ->addComponent((new Vtodo())
        ->addProperty(Vtodo::PROPERTY_DATETIME_STAMP, '19980130T134500Z')
        ->addProperty(Vtodo::PROPERTY_SEQUENCE, 2)
        ->addProperty(Vtodo::PROPERTY_UID, Vtodo::uuidv4())
        ->addProperty(Vtodo::PROPERTY_ORGANIZER, 'mailto:unclesam@example.com')
        ->addProperty(
            Vtodo::PROPERTY_ATTENDEE,
            'mailto:jqpublic@example.com',
            [
                Vtodo::PARAMETER_PARTICIPATION_STATUS => Vtodo::PARTICIPANT_ACCEPTED
            ]
        )
        ->addProperty(Vtodo::PROPERTY_DUE, '19980415T000000')
        ->addProperty(Vtodo::PROPERTY_STATUS, Vtodo::STATUS_NEEDS_ACTION)
        ->addProperty(Vtodo::PROPERTY_SUMMARY, 'Submit Income Taxes')
        ->addComponent((new Valarm())
            ->addProperty(Valarm::PROPERTY_ACTION, Valarm::ACTION_AUDIO)
            ->addProperty(Valarm::PROPERTY_TRIGGER, '19980403T120000Z')
            ->addProperty(
                Valarm::PROPERTY_ATTACH,
                'http://example.com/pub/audio-files/ssbanner.aud',
                [
                    Valarm::PARAMETER_FORMAT_TYPE => 'audio/basic'
                ]
            )
            ->addProperty(Valarm::PROPERTY_REPEAT, 4)
            ->addProperty(Valarm::PROPERTY_DURATION, 'PT1H')
        )
    )
    ->render()
;
```

See tests for more examples.

## Edit iCalendar
iCalendar components can be edited, for example when updating a Vevent.

The library has methods for editing components:

* hasComponent($name) - whether a component has one or more components of type $name
* getComponents() - returns all child components
* getComponent($name) - returns all child components of type $name
* getComponent($name, $n) - returns the $nth occurrence of the child component of type $name
* setComponent($component, $n) - set (overwrite) the $nth occurrence of the type of $component with $component
* removeComponent($name) - remove all child components of type $name
* removeComponent($name, $n) - remove the $nth occurrence of the child component of type $name

* hasProperty($name) - whether a component has one or more properties of type $name
* getProperties() - returns all component properties
* getProperty($name) - returns all component properties of type $name
* getProperty($name, $n) - returns the $nth occurrence of the component property of type $name
* setProperty($name, $n, $value, $parameters) - set (overwrite) the $nth occurrence of the property of type $name with new $value and $parameters
* removeProperty($name) - remove all component properties of type $name
* removeProperty($name, $n) - remove the $nth occurrence of the component property of type $name

## Import iCalendar
Import an iCalendar string using Vcalendar::import():

```php
$icalendar = Vcalendar::import($string);
```

## Installation
The preferred way to install the library is with [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist beastbytes/icalendar
```

or add

```json
"beastbytes/icalendar": "*"
```

to the 'require' section of your composer.json.

## Testing
### Unit testing
The package is tested with PHPUnit. To run the tests:

```
./vendor/bin/phpunit
```

### Static analysis
The code is statically analyzed with Psalm. To run static analysis:

```
./vendor/bin/psalm
```

## License
The iCalendar Library is free software. It is released under the terms of the BSD License. For license information see the [LICENSE](LICENSE.md) file.
