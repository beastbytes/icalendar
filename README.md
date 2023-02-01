# iCalendar
The iCalendar library provides the ability to create [Internet Calendaring and Scheduling (iCalendar)]
(https://datatracker.ietf.org/doc/html/rfc5545) strings.

## Creating iCalendar files
The iCalendar library allows creation of iCalendar files in an object-oriented way.

The library provides class constants to provide code completion and improve code readability.

To create an iCalendar, create a new Vcalendar object then add properties and components to it; 
properties and other components are added to components in a similar way; multiple components of the same type are 
supported, as are multiple properties with the same name in a component.

Provide property parameters as an array where the key is the parameter name (hint: use class constants) and the value 
is the value.  

Finally, call the Vcalendar's render() method.

### Note
The library does **not** do any checking for validity; it is possible to create a string that is not a valid iCalendar. 

### Example
The following example creates a To-Do with an alarm (it is the example on page 146 of RFC5545).

```php
$iCalendar = (new Vcalendar())
    ->addProperty(Vcalendar::PROPERTY_PRODID, '-//ABC Corporation//NONSGML My Product//EN')
    ->addComponent((new Vtodo())
        ->addProperty(Vtodo::PROPERTY_DTSTAMP, '19980130T134500Z')
        ->addProperty(Vtodo::PROPERTY_SEQUENCE, 2)
        ->addProperty(Vtodo::PROPERTY_UID, 'uid4@example.com')
        ->addProperty(Vtodo::PROPERTY_ORGANIZER, 'mailto:unclesam@example.com')
        ->addProperty(
            Vtodo::PROPERTY_ATTENDEE,
            'mailto:jqpublic@example.com',
            [
                Vtodo::PARAMETER_PARTSTAT => Vtodo::PARTICIPANT_ACCEPTED
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
                    Valarm::PARAMETER_FMTTYPE => 'audio/basic'
                ]
            )
            ->addProperty(Valarm::PROPERTY_REPEAT, 4)
            ->addProperty(Valarm::PROPERTY_DURATION, 'PT1H')
        )
    )
    ->render()
;
```

## Import iCalendar
Import an iCalendar file using Vcalendar's static import() method:

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
"beastbytes/icalendar": "^1.0.0"
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
