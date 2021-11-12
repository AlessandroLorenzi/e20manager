<?php

namespace Views;

use Entities\Event;
use Storage\SQL\EventSQLStorage as EventSQLStorage;
use Twig\Environment;
use \Psr\Log\LoggerInterface;

class Backoffice
{
    private EventSQLStorage $event_sql_storage;
    private \Twig\Environment $twig;
    private LoggerInterface $logger;

    public function __construct(EventSQLStorage $event_sql_storage, Environment $twig, LoggerInterface $logger)
    {
        $this->event_sql_storage = $event_sql_storage;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function main_page()
    {
        $events = $this->event_sql_storage->get_future_events();
        $past_events = $this->event_sql_storage->get_past_events();
        print($this->twig->render(
            'backoffice/events-table.html',
            [
                'events' => $events,
                'past_events' => $past_events,
            ]
        ));
    }

    public function edit_event(int $event_id)
    {
        $event = $this->event_sql_storage->get_event($event_id);
        print($this->twig->render('backoffice/edit-event.html', ['event' => $event]));
    }

    public function execute_edit_event(int $event_id)
    {
        $e = new Event();
        $e->event_id = $event_id;
        $e->title = $_POST['title'];
        $e->cover_image = $_POST['cover_image'];
        $e->start_time = \Datetime::createFromFormat('d/m/Y H:i', $_POST['start_time']);
        $e->end_time = \Datetime::createFromFormat('d/m/Y H:i', $_POST['end_time']);
        $e->description = $_POST['description'];
        $this->event_sql_storage->edit_event($e);
        header("Location: /events/manage/event/${event_id}/edit/?saved");
        print('<pre>Event Saved...');
        print_r($e);
    }

    public function new_event()
    {
        print($this->twig->render('backoffice/new-event.html'));
    }

    public function save_new_event()
    {
        $e = new Event();
        $e->title = $_POST['title'];
        $e->cover_image = $_POST['cover_image'];
        $e->start_time = \Datetime::createFromFormat('d/m/Y H:i', $_POST['start_time']);
        $e->end_time = \Datetime::createFromFormat('d/m/Y H:i', $_POST['end_time']);
        $e->description = $_POST['description'];
        $event_id = $this->event_sql_storage->save_new_event($e);
        header("Location: /events/manage/event/${event_id}/edit/?saved");
        print("<pre>Event $event_id Saved...\n");
        print_r($e);
    }

    public function delete_event(int $event_id)
    {
        $this->event_sql_storage->delete_event($event_id);
        header("Location: /events/manage/event/");
        print('<pre>Event deleted...');
    }
}
