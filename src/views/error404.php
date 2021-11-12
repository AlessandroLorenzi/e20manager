<?php

namespace Views;


class Error404
{
    public function __construct(\Twig\Environment $twig, \Psr\Log\LoggerInterface $logger)
    {
        $logger->error(
            "not found",
            [
                "REQUEST_URI" => $_SERVER["REQUEST_URI"],
                "REQUEST_METHOD" => $_SERVER["REQUEST_METHOD"],
            ]
        );
        http_response_code(500);
        print($twig->render('error.html', ["error_message" => "not found"]));
    }
}
