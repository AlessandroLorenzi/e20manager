<?php
include_once("entities/event.php");
include_once("entities/ticket.php");

require_once("storage/sql/event_sql_storage.php");
require_once("storage/sql/ticket_sql_storage.php");

include_once("platform/mysql.php");

include_once("views/public-site.php");
include_once("views/backoffice.php");
include_once("views/error500.php");
include_once("views/error404.php");

require_once("vendor/autoload.php");



// LOGGER

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('e20');
$handler = new StreamHandler('php://stdout', 'DEBUG');
$handler->setFormatter(new JsonFormatter());
$logger->pushHandler($handler);
// Mysql

use Platform\MySQL;

$conn = MySQL::connect();

// Event Storage
use Storage\SQL\EventSQLStorage;
use Storage\SQL\TicketSQLStorage;

$event_storage = new EventSQLStorage($conn);
$ticket_storage = new TicketSQLStorage($conn);

// Templates

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);


// PAGES
try {

    if (
        $_SERVER["REQUEST_URI"] == "/events/" &&
        $_SERVER["REQUEST_METHOD"] == "GET"
    ) {
        $eventView = new Views\PublicSite($event_storage, $ticket_storage, $twig, $logger);
        $eventView->incoming_events();
        exit(0);
    }

    if (
        preg_match("/\/events\/([0-9]+)\//", $_SERVER["REQUEST_URI"], $matches) &&
        $_SERVER["REQUEST_METHOD"] == "GET"
    ) {
        $eventView = new Views\PublicSite($event_storage, $ticket_storage, $twig, $logger);
        $eventView->event($matches[1]);
        exit(0);
    }

    if (
        $_SERVER["PATH_INFO"] == "/events/manage/" &&
        $_SERVER["REQUEST_METHOD"] == "GET"
    ) {
        $backofficeView = new Views\Backoffice($event_storage, $twig, $logger);
        $backofficeView->main_page();
        exit(0);
    }

    if (
        preg_match("/\/events\/manage\/event\/([0-9]+)\/edit\//", $_SERVER["REQUEST_URI"], $matches) &&
        $_SERVER["REQUEST_METHOD"] == "GET"
    ) {
        $backofficeView = new Views\Backoffice($event_storage, $twig, $logger);
        $backofficeView->edit_event($matches[1]);
        exit(0);
    }

    if (
        preg_match("/\/events\/manage\/event\/([0-9]+)\/edit\//", $_SERVER["REQUEST_URI"], $matches) &&
        $_SERVER["REQUEST_METHOD"] == "POST"
    ) {
        $backofficeView = new Views\Backoffice($event_storage, $twig, $logger);
        $backofficeView->execute_edit_event($matches[1]);
        exit(0);
    }

    if (
        $_SERVER["REQUEST_URI"] == "/events/manage/new/" &&
        $_SERVER["REQUEST_METHOD"] == "GET"
    ) {
        $backofficeView = new Views\Backoffice($event_storage, $twig, $logger);
        $backofficeView->new_event();
        exit(0);
    }

    if (
        $_SERVER["REQUEST_URI"] == "/events/manage/new/" &&
        $_SERVER["REQUEST_METHOD"] == "POST"
    ) {
        $backofficeView = new Views\Backoffice($event_storage, $twig, $logger);
        $backofficeView->save_new_event();
        exit(0);
    }

    if (
        preg_match("/\/events\/manage\/event\/([0-9]+)\/delete\//", $_SERVER["REQUEST_URI"], $matches) &&
        $_SERVER["REQUEST_METHOD"] == "DELETE"
    ) {
        $backofficeView = new Views\Backoffice($event_storage, $twig, $logger);
        $backofficeView->delete_event($matches[1]);
        exit(0);
    }
} catch (Exception $e) {
    new Views\Error500($twig, $logger, $e);
    exit(1);
}

new Views\Error404($twig, $logger);
exit(2);

// TODO 404 page