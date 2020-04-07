<?php


namespace App\Utils;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class GestionLog
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function addLogInfo($user, $module, $message, $ip = null)
    {
        $this->logger->info($message,['username'=>$user->getUsername(),'module'=>$module, 'ip' => $ip]);

        return true;
    }

}