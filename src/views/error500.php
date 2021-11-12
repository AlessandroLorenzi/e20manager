<?php

namespace Views;

use Exception;

class Error500
{
    public function __construct(\Twig\Environment $twig, \Psr\Log\LoggerInterface $logger, Exception $e)
    {
        $logger->error(
            "Unexpected error",
            ["error_message" => $e->getMessage()]
        );
        http_response_code(500);
        print($twig->render('error.html', ["error_message"=>"Unexpected internal error"]));
    }
}
