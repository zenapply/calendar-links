<?php

namespace Zenapply\CalendarLinks\Generators;

use DateTimeZone;
use Zenapply\CalendarLinks\Link;
use Zenapply\CalendarLinks\Generator;

/**
 * @see https://github.com/InteractionDesignFoundation/add-event-to-calendar-docs/blob/master/services/outlook-live.md
 */
class WebOutlook implements Generator
{
    /** {@inheritdoc} */
    public function generate(Link $link)
    {
        $url = 'https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent';

        $from = (clone $link->from);
        $to = (clone $link->to);
        $utcStartDateTime = $from->setTimezone(new DateTimeZone('UTC'));
        $utcEndDateTime = $to->setTimezone(new DateTimeZone('UTC'));
        $url .= '&startdt='.$utcStartDateTime->format('Ymd\THis');
        $url .= '&enddt='.$utcEndDateTime->format('Ymd\THis');
        if ($link->allDay) {
            $url .= '&allday=true';
        }

        $url .= '&subject='.urlencode($link->title);

        if ($link->description) {
            $url .= '&body='.urlencode($link->description);
        }

        if ($link->address) {
            $url .= '&location='.urlencode($link->address);
        }

        return $url;
    }
}
