<?php

namespace Entities;

use \Datetime as DateTime;

class Ticket
{
    public int $ticket_id;
    public int $event_id;
    public string $name;
    public DateTime $start_time;
    public DateTime $end_time;
    public string $description;
    public string $cover_image;
}
