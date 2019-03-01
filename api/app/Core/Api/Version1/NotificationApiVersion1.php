<?php

namespace App\Core\Api\Version1;


use App\Core\Api\NotificationApi;
use App\Core\Services\NotificationService;

class NotificationApiVersion1 implements NotificationApi
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function fetchAll()
    {
        return $this->notificationService->fetchAll();
    }
}
