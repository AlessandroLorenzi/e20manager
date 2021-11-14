<?php

namespace Views;

use Storage\SQL\EventSQLStorage;
use Storage\SQL\TicketSQLStorage;
use Twig\Environment;
use \Psr\Log\LoggerInterface;

class PublicSite
{
    private EventSQLStorage $event_sql_storage;
    private TicketSQLStorage $ticket_sql_storage;
    private \Twig\Environment $twig;
    private LoggerInterface $logger;

    public function __construct(
        EventSQLStorage $event_sql_storage,
        TicketSQLStorage $ticket_sql_storage,
        Environment $twig,
        LoggerInterface $logger
    ) {
        $this->event_sql_storage = $event_sql_storage;
        $this->ticket_sql_storage = $ticket_sql_storage;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function incoming_events()
    {
        $events = $this->event_sql_storage->get_future_events();
        $past_events = $this->event_sql_storage->get_past_events();
        print($this->twig->render(
            'home_page.html',
            [
                'events' => $events,
                'past_events' => $past_events,
            ]
        ));
    }

    public function event(int $event_id)
    {
        $event = $this->event_sql_storage->get_event($event_id);
        $tickets = $this->ticket_sql_storage->get_tickets($event_id);
        print($this->twig->render('event_detail.html', [
            'event' => $event,
            'tickets' => $tickets,
        ]));
    }
}
