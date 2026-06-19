<?php

declare(strict_types=1);

final class Mailer
{
    public function send(string $to, string $subject, string $body): bool
    {
        $line = sprintf("[%s] To: %s | %s | %s\n", date('c'), $to, $subject, strip_tags($body));
        file_put_contents(BASE_PATH . '/storage/logs/mail.log', $line, FILE_APPEND);
        return true;
    }
}
