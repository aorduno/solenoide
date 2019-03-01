<?php

namespace App\Core\Services;


interface NotificationService
{
    public function fetchAll();

    public function delete($id);
}
