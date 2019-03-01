<?php

namespace App\Http\Controllers;

use App\Core\Api\NotificationApi;
use App\Http\Resources\NotificationCollection;
use Illuminate\Http\Request;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
