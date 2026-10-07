<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notices = Notice::orderBy('notice_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.notice.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.notice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'notice_date' => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'files'       => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status'      => 'required|in:0,1',
        ]);

        $fileName = null;

        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('files')) {

            $file = $request->file('files');

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $uploadPath = public_path('notices');

            // Create directory if it doesn't exist
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $fileName);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Notice
        |--------------------------------------------------------------------------
        */
        Notice::create([
            'notice_date' => $request->notice_date,
            'title'       => $request->title,
            'description' => $request->description,
            'files'       => $fileName,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('notice.index')
            ->with('success', 'Notice added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notice $notice)
    {
        return view('backend.notice.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notice $notice)
    {
        return view('backend.notice.edit', compact('notice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'notice_date' => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'files'       => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status'      => 'required|in:0,1',
        ]);

        $fileName = $notice->files;

        /*
        |--------------------------------------------------------------------------
        | Upload New File
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('files')) {

            // Delete old file
            if ($notice->files) {

                $oldFilePath = public_path('notices/' . $notice->files);

                if (File::exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
            }

            // Upload new file
            $file = $request->file('files');

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $uploadPath = public_path('notices');

            // Create directory if it doesn't exist
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $fileName);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Notice
        |--------------------------------------------------------------------------
        */
        $notice->update([
            'notice_date' => $request->notice_date,
            'title'       => $request->title,
            'description' => $request->description,
            'files'       => $fileName,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('notice.index')
            ->with('success', 'Notice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notice $notice)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Attached File
        |--------------------------------------------------------------------------
        */
        if ($notice->files) {

            $filePath = public_path('notices/' . $notice->files);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Notice
        |--------------------------------------------------------------------------
        */
        $notice->delete();

        return redirect()
            ->route('notice.index')
            ->with('success', 'Notice deleted successfully.');
    }
}
