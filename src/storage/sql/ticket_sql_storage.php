<?php

namespace Storage\SQL;

use Entities\Ticket;

class TicketSQLStorage
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    function get_tickets(int $event_id): iterable
    {
        $sth = $this->db->prepare(
            "SELECT ticket_id, event_id, name, price, currency, max_tickets 
                FROM tickets 
                WHERE event_id = :event_id;
            "
        );
        $sth->execute(
            [
                "event_id" => $event_id,
            ]
        );
        $tickets = [];
        while ($row = $sth->fetch()) {
            array_push($tickets, $this->sql_to_ticket($row));
        }
        return $tickets;
    }

    private function sql_to_ticket($row): Ticket
    {
        $ticket = new Ticket();
        $ticket->ticket_id = $row["ticket_id"];
        $ticket->event_id = $row["event_id"];
        $ticket->name = $row["name"];
        $ticket->price = $row["price"];
        $ticket->currency = $row["currency"];
        $ticket->max_tickets = $row["max_tickets"];
        return $ticket;
    }
}
