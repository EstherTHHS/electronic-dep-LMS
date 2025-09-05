<?php

namespace App\Repositories\Event;

use Illuminate\Http\Request;

interface EventRepositoryInterface
{
    public function getEvents();
    public function storeEvent($data);
    public function getEventById($id);
    public function deleteEventById($id);
    public function updateOrCreateLab($data);
    public function getLabs();
    public function getLabById($id);
    public function deleteLabById($id);

    public function updateOrCreateTimetable($data);
    public function getTimetables(Request $request);
    public function getTimetablesByYearId($yearId);
    public function getTimetableById($id);
    public function deleteTimetableById($id);
}
