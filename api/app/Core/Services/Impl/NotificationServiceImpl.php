<?php

namespace App\Core\Services\Impl;


use App\Core\Models\Notification;
use App\Core\Services\NotificationService;

class NotificationServiceImpl implements NotificationService
{

    public function fetchAll()
    {
        return Notification::all();
    }
}
