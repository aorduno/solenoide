<?php

namespace App\Core\Api;


interface NotificationApi
{
    public function fetchAll();

    public function delete($id);
}
