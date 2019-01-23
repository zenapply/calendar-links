<?php

namespace Zenapply\CalendarLinks;

use DateTime;
use Zenapply\CalendarLinks\Generators\Ics;
use Zenapply\CalendarLinks\Generators\Yahoo;
use Zenapply\CalendarLinks\Generators\Google;
use Zenapply\CalendarLinks\Generators\WebOutlook;
use Zenapply\CalendarLinks\Exceptions\InvalidLink;

/**
 * @property-read string $title
 * @property-read \DateTime $from
 * @property-read \DateTime $to
 * @property-read string $description
 * @property-read string $address
 * @property-read bool $allDay
 */
class Link
{
    /** @var string */
    protected $title;

    /** @var \DateTime */
    protected $from;

    /** @var \DateTime */
    protected $to;

    /** @var string */
    protected $description;

    /** @var bool */
    protected $allDay;

    /** @var string */
    protected $address;

    protected $prodid;

    public function __construct($title, DateTime $from, DateTime $to, $allDay = false)
    {
        $this->title = $title;
        $this->allDay = $allDay;

        if ($to < $from) {
            throw InvalidLink::invalidDateRange($from, $to);
        }

        $this->from = clone $from;
        $this->to = clone $to;

        if ($this->allDay) {
            $this->from = clone $from;
            $this->to = clone $from;
        }
    }

    /**
     * @param string $title
     * @param \DateTime $from
     * @param \DateTime $to
     * @param bool $allDay
     *
     * @return static
     * @throws InvalidLink
     */
    public static function create($title, DateTime $from, DateTime $to, $allDay = false)
    {
        return new static($title, $from, $to, $allDay);
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function address($address)
    {
        $this->address = $address;

        return $this;
    }

    public function prodid($prodid)
    {
        $this->prodid = $prodid;

        return $this;
    }

    public function google()
    {
        return (new Google())->generate($this);
    }

    public function ics()
    {
        return (new Ics())->generate($this);
    }

    public function ics_raw()
    {
        return (new Ics())->generateRaw($this);
    }

    public function yahoo()
    {
        return (new Yahoo())->generate($this);
    }

    public function webOutlook()
    {
        return (new WebOutlook())->generate($this);
    }

    public function __get($property)
    {
        return $this->$property;
    }
}
