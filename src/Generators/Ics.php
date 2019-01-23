<?php

namespace Zenapply\CalendarLinks\Generators;

use Zenapply\CalendarLinks\Link;
use Zenapply\CalendarLinks\Generator;

/**
 * @see https://icalendar.org/RFC-Specifications/iCalendar-RFC-5545/
 */
class Ics implements Generator
{
    protected function compile(Link $link)
    {
        $url = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'BEGIN:VEVENT',
            'UID:'.$this->generateEventUid($link),
            'SUMMARY:'.$link->title,
        ];

        if ($link->allDay) {
            $dateTimeFormat = 'Ymd';
            $url[] = 'DTSTART:'.$link->from->format($dateTimeFormat);
            $url[] = 'DTEND:'.$link->to->format($dateTimeFormat);
        } else {
            $dateTimeFormat = "e:Ymd\THis";
            $url[] = 'DTSTART;TZID='.$link->from->format($dateTimeFormat);
            $url[] = 'DTEND;TZID='.$link->to->format($dateTimeFormat);
        }

        if ($link->prodid) {
            $url[] = 'PRODID:'.$link->prodid;
        }

        if ($link->description) {
            $url[] = 'DESCRIPTION:'.$this->escapeString($link->description);
        }
        if ($link->address) {
            $url[] = 'LOCATION:'.$this->escapeString($link->address);
        }

        $url[] = 'END:VEVENT';
        $url[] = 'END:VCALENDAR';
    }

    public function generateRaw(Link $link)
    {
        $components = $this->compile($link);
        return implode("\n", $components);
    }

    public function generate(Link $link)
    {
        $components = $this->compile($link);
        $redirectLink = implode('%0d%0a', $components);
        return 'data:text/calendar;charset=utf8,'.$redirectLink;
    }

    /** @see https://tools.ietf.org/html/rfc5545.html#section-3.3.11 */
    protected function escapeString($field)
    {
        return addcslashes($field, "\n,;");
    }

    /** @see https://tools.ietf.org/html/rfc5545#section-3.8.4.7 */
    protected function generateEventUid(Link $link)
    {
        return md5($link->from->format(\DateTime::ATOM).$link->to->format(\DateTime::ATOM).$link->title.$link->address);
    }
}
