<?php

namespace Entities;

use \Datetime as DateTime;

class Event
{
    public int $event_id;
    public string $title;
    public DateTime $start_time;
    public DateTime $end_time;
    public string $description;
    public string $cover_image;
}
