<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',

            'event_time' => 'nullable|date_format:H:i',
            'venue_details' => 'nullable|string|max:255',

            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'address' => 'nullable|string',

            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',

            'is_free' => 'nullable|in:0,1',
            'event_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Events Directory
        |--------------------------------------------------------------------------
        */
        $uploadPath = public_path('events');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */
        $slug = Str::slug($request->event_name);

        $originalSlug = $slug;
        $counter = 1;

        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        /*
        |--------------------------------------------------------------------------
        | Document Upload
        |--------------------------------------------------------------------------
        */
        $documentName = null;

        if ($request->hasFile('document')) {
            $file = $request->file('document');

            $documentName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($uploadPath, $documentName);
        }

        /*
        |--------------------------------------------------------------------------
        | Event Data
        |--------------------------------------------------------------------------
        */
        Event::create([
            'event_name' => $request->event_name,
            'slug' => $slug,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'venue_details' => $request->venue_details,

            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'address' => $request->address,

            'document' => $documentName,

            'is_free' => $request->is_free ?? 1,
            'event_amount' => ($request->is_free == 0)
                ? $request->event_amount
                : null,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('backend.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',

            'event_time' => 'nullable|date_format:H:i',
            'venue_details' => 'nullable|string|max:255',

            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'address' => 'nullable|string',

            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',

            'is_free' => 'nullable|in:0,1',
            'event_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Events Directory
        |--------------------------------------------------------------------------
        */
        $uploadPath = public_path('events');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */
        $slug = Str::slug($request->event_name);

        $originalSlug = $slug;
        $counter = 1;

        while (
            Event::where('slug', $slug)
                ->where('id', '!=', $event->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Document
        |--------------------------------------------------------------------------
        */
        $documentName = $event->document;

        /*
        |--------------------------------------------------------------------------
        | New Document Upload
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('document')) {

            // Delete old document
            if (
                $event->document &&
                file_exists($uploadPath . '/' . $event->document)
            ) {
                unlink($uploadPath . '/' . $event->document);
            }

            $file = $request->file('document');

            $documentName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($uploadPath, $documentName);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Event
        |--------------------------------------------------------------------------
        */
        $event->update([
            'event_name' => $request->event_name,
            'slug' => $slug,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'venue_details' => $request->venue_details,

            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'address' => $request->address,

            'document' => $documentName,

            'is_free' => $request->is_free ?? 1,
            'event_amount' => ($request->is_free == 0)
                ? $request->event_amount
                : null,

            'status' => $request->status,
        ]);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Document
        |--------------------------------------------------------------------------
        */
        if ($event->document) {

            $documentPath = public_path('events/' . $event->document);

            if (file_exists($documentPath)) {
                unlink($documentPath);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Event
        |--------------------------------------------------------------------------
        */
        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }


    // Front Website Event Listing
    public function events()
    {
        $events = Event::where('status', 1)
            ->orderBy('event_date', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('pages.events.events', compact('events'));
    }

    public function eventDetails($slug)
    {
        $event = Event::where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.events.event-details', compact('event'));
    }
}
