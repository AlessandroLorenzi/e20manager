<?php

namespace Storage\SQL;

use Entities\Event as Event;
use \Datetime as DateTime;


class EventSQLStorage
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function get_future_events(): iterable
    {
        return $this->get_events(false);
    }

    public function get_past_events(): iterable
    {
        return $this->get_events(true);
    }
    
    function get_all_events(): iterable
    {
        $rows = $this->db->query(
            "SELECT 
                event_id, title, start_time, end_time, description, cover_image 
            FROM events 
            ORDER BY start_time DESC;"
        );
        $events = array();
        foreach ($rows as $row) {
            array_push($events, $this->sql_to_event($row));
        }

        return $events;
    }

    private function get_events(bool $past): iterable
    {
        $past_operator = "<";
        if (!$past) {
            $past_operator = ">";
        }

        $order = "ASC";
        if ($past) {
            $order = "DESC";
        }

        $now = date("U");
        $rows = $this->db->query(
            "SELECT 
                event_id, title, start_time, end_time, description, cover_image 
            FROM events 
            WHERE
                start_time $past_operator $now
            ORDER BY start_time $order;"
        );
        $events = array();
        foreach ($rows as $row) {
            array_push($events, $this->sql_to_event($row));
        }

        return $events;
    }

    public function get_event(int $eventId): Event
    {
        $sth = $this->db->prepare(
            "SELECT event_id, title, start_time, end_time, description, cover_image from events where event_id = :event_id;",
        );
        $sth->execute(array("event_id" => $eventId));
        $event_fetched = $sth->fetch();
        if (!$event_fetched)  {
            throw new \Exception("NotFound");
        }
        return $this->sql_to_event($event_fetched);
    }

    public function save_new_event(Event $event): int
    {
        $sth = $this->db->prepare(
            "INSERT INTO 
                events (title, start_time, end_time, description, cover_image)
            VALUES
                (:title, :start_time, :end_time, :description, :cover_image);"
        );
        $sth->execute(
            array(
                "title" => $event->title,
                "start_time" => $event->start_time->getTimestamp(),
                "end_time" => $event->end_time->getTimestamp(),
                "description" => $event->description,
                "cover_image" => $event->cover_image
            )
        );
        return $this->db->lastInsertId();
    }

    public function edit_event(Event $event)
    {
        $sth = $this->db->prepare(
            "UPDATE events
            SET
                title = :title,
                start_time = :start_time, 
                end_time = :end_time, 
                description = :description, 
                cover_image = :cover_image 
            WHERE event_id = :event_id;"
        );
        $sth->execute(
            array(
                "event_id" => $event->event_id,
                "title" => $event->title,
                "start_time" => $event->start_time->getTimestamp(),
                "end_time" => $event->end_time->getTimestamp(),
                "description" => $event->description,
                "cover_image" => $event->cover_image
            )
        );
    }

    public function delete_event(int $event_id)
    {
        $sth = $this->db->prepare(
            "DELETE FROM events
            WHERE event_id = :event_id;"
        );
        $sth->execute(
            array(
                "event_id" => $event_id,
            )
        );
    }

    private function sql_to_event(array $event_fetched): Event
    {
        $event = new Event;
        $event->event_id = $event_fetched["event_id"];
        $event->title = $event_fetched["title"];
        $event->start_time = DateTime::createFromFormat('U', $event_fetched["start_time"]);
        $event->end_time = DateTime::createFromFormat('U', $event_fetched["end_time"]);
        $event->description = $event_fetched["description"];
        $event->cover_image = $event_fetched["cover_image"];
        return $event;
    }
}
