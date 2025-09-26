<?php

namespace App\Http\Controllers\API;

use App\Models\Lab;
use App\Models\Event;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Event\EventRepositoryInterface;

class EventController extends Controller
{
    private EventRepositoryInterface $eventRepository;
    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }
    public function getEvents(){
        $events = $this->eventRepository->getEvents();
        ResponseData($events);
    }
    public function getEventById($id){
        $event = $this->eventRepository->getEventById($id);
        ResponseData($event);
    }

    public function storeEvent(Request $request){
        $event = $this->eventRepository->storeEvent($request->all());
        ResponseData($event);
    }

    public function updateEvent(Request $request, $id)
    {
        $data = $request->all();
        $event = $this->eventRepository->updateEvent($id, $data);
        ResponseData($event);
    }

    public function deleteEventById($id){
        $event = $this->eventRepository->deleteEventById($id);
        ResponseData($event);
    }

    public function updateOrCreateLab(Request $request){
        $lab = $this->eventRepository->updateOrCreateLab($request->all());
        ResponseData($lab);
    }

    public function updateLab(Request $request , $id){
        $lab = $this->eventRepository->updateLab($id ,$request->all());
        ResponseData($lab);
    }

    public function getLabs(){
        $labs = $this->eventRepository->getLabs();
        ResponseData($labs);
    }

    public function getLabById($id){
        $lab = $this->eventRepository->getLabById($id);
        ResponseData($lab);
    }

    public function deleteLabById($id){
        $lab = $this->eventRepository->deleteLabById($id);
        ResponseData($lab);
    }

    public function getTimetables()
    {
        $timetables = Timetable::with(['year', 'media'])->get();

        return response()->json($timetables);
    }

    /**
     * Store a newly created timetable in storage.
     */
    public function storeTimetable(Request $request)
    {
        $validated = $request->validate([
            'year_id' => 'required|exists:years,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $timetable = Timetable::create($validated);

        if ($request->hasFile('file')) {
            $timetable->addMediaFromRequest('file')->toMediaCollection('timetable_files');
        }

        return response()->json([
            'message' => 'Timetable created successfully.',
            'data' => $timetable->load(['year', 'media'])
        ], 201);
    }

    /**
     * Display the specified timetable.
     */
    public function getTimetable($id)
    {
        $timetable = Timetable::with(['year', 'media'])->findOrFail($id);

        return response()->json($timetable);
    }

    /**
     * Update the specified timetable in storage.
     */
    public function updateTimetable(Request $request, $id)
    {
        $timetable = Timetable::findOrFail($id);

        $validated = $request->validate([
            'year_id' => 'sometimes|exists:years,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $timetable->update($validated);

        if ($request->hasFile('file')) {
            // Replace old file with the new one
            $timetable->clearMediaCollection('timetable_files');
            $timetable->addMediaFromRequest('file')->toMediaCollection('timetable_files');
        }

        return response()->json([
            'message' => 'Timetable updated successfully.',
            'data' => $timetable->load(['year', 'media'])
        ]);
    }

    /**
     * Remove the specified timetable from storage.
     */
    public function deleteTimetable($id)
    {
        $timetable = Timetable::findOrFail($id);

        $timetable->clearMediaCollection('timetable_files');
        $timetable->delete();

        return response()->json([
            'message' => 'Timetable deleted successfully.'
        ]);
    }
}
