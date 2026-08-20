<?php

namespace App\Http\Controllers;
use App\Models\Studio;
use App\Models\StudioCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudioController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $studios = Studio::with('category')
            ->latest()
            ->get();

        return view('backend.studio.index',compact('studios'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $categories = StudioCategory::orderBy('name')->get();

        return view('backend.studio.create',compact('categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $request->validate([

            'studio_category_id' => 'required|exists:studio_categories,id',

            'price_per_hour' => 'required|numeric|min:0',
            'price_per_day' => 'required|numeric|min:0',

            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'description' => 'nullable',

            'status' => 'required|in:Active,Inactive',

        ]);

        DB::beginTransaction();

        try{

            $thumbnail = null;

            if($request->hasFile('thumbnail')){

                $thumbnail = $request->file('thumbnail')
                    ->store('studio','public');

            }

            Studio::create([

                'studio_category_id' => $request->studio_category_id,

                'price_per_day' => $request->price_per_day,
                'price_per_hour' => $request->price_per_hour,

                'thumbnail' => $thumbnail,

                'description' => $request->description,

                'status' => $request->status,

            ]);

            DB::commit();

            return redirect()
                ->route('studio.index')
                ->with('success','Studio created successfully.');

        }catch(\Exception $e){

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error',$e->getMessage());

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {

        $studio = Studio::findOrFail($id);

        $categories = StudioCategory::orderBy('name')->get();

        return view('backend.studio.edit',compact(
            'studio',
            'categories'
        ));

    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request,$id)
    {

        $request->validate([

            'studio_category_id' => 'required|exists:studio_categories,id',

            'price_per_day' => 'required|numeric|min:0',
            'price_per_hour' => 'required|numeric|min:0',

            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'description' => 'nullable',

            'status' => 'required|in:Active,Inactive',

        ]);

        DB::beginTransaction();

        try{

            $studio = Studio::findOrFail($id);

            if($request->hasFile('thumbnail')){

                if($studio->thumbnail && Storage::disk('public')->exists($studio->thumbnail)){

                    Storage::disk('public')->delete($studio->thumbnail);

                }

                $studio->thumbnail = $request->file('thumbnail')
                    ->store('studio','public');

            }

            $studio->studio_category_id = $request->studio_category_id;

            $studio->price_per_day = $request->price_per_day;
            $studio->price_per_hour = $request->price_per_hour;

            $studio->description = $request->description;

            $studio->status = $request->status;

            $studio->save();

            DB::commit();

            return redirect()
                ->route('studio.index')
                ->with('success','Studio updated successfully.');

        }catch(\Exception $e){

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error',$e->getMessage());

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {

        try{

            $studio = Studio::findOrFail($id);

            if($studio->thumbnail && Storage::disk('public')->exists($studio->thumbnail)){

                Storage::disk('public')->delete($studio->thumbnail);

            }

            $studio->delete();

            return redirect()
                ->route('studio.index')
                ->with('success','Studio deleted successfully.');

        }catch(\Exception $e){

            return back()
                ->with('error',$e->getMessage());

        }

    }
}
