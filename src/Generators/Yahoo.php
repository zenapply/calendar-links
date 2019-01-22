<?php

namespace Zenapply\CalendarLinks\Generators;

use DateTimeZone;
use Zenapply\CalendarLinks\Link;
use Zenapply\CalendarLinks\Generator;

/**
 * @see https://github.com/InteractionDesignFoundation/add-event-to-calendar-docs/blob/master/services/yahoo.md
 */
class Yahoo implements Generator
{
    public function generate(Link $link)
    {
        $url = 'https://calendar.yahoo.com/?v=60&view=d&type=20';

        $url .= '&title='.urlencode($link->title);

        if ($link->allDay) {
            $dateTimeFormat = 'Ymd';
            $url .= '&st='.$link->from->format($dateTimeFormat);
            $url .= '&dur=allday';
        } else {
            $from = (clone $link->from);
            $to = (clone $link->to);
            $utcStartDateTime = $from->setTimezone(new DateTimeZone('UTC'));
            $utcEndDateTime = $to->setTimezone(new DateTimeZone('UTC'));
            $dateTimeFormat = $link->allDay ? 'Ymd' : 'Ymd\THis';
            $url .= '&st='.$utcStartDateTime->format($dateTimeFormat).'Z';
            $url .= '&et='.$utcEndDateTime->format($dateTimeFormat).'Z';
        }

        if ($link->description) {
            $url .= '&desc='.urlencode($link->description);
        }

        if ($link->address) {
            $url .= '&in_loc='.urlencode($link->address);
        }

        return $url;
    }
}
