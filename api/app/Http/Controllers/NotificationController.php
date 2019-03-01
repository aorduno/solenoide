<?php

namespace App\Http\Controllers;

use App\Core\Api\NotificationApi;
use App\Http\Resources\NotificationCollection;

class NotificationController extends Controller
{
    private $notificationApi;

    public function __construct(NotificationApi $notificationApi)
    {
        $this->notificationApi = $notificationApi;
    }

    /**
     * Display a listing of the resource.
     *
     * @return NotificationCollection
     */
    public function index()
    {
        return new NotificationCollection($this->notificationApi->fetchAll());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // @TODO:aorduno - we should use resource for this like other endpoints but meh!
        return response()->json(array('data' => array('id' => $this->notificationApi->delete($id), 'type' => 'deleteNoficiationResponse')));
    }
}
