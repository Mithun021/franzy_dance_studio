<?php

namespace App\Http\Controllers;
use App\Models\Rules;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    public function index()
    {
        $rules = Rules::first();

        return view(
            'backend.rules.index',
            compact('rules')
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'rules' => 'nullable',

            'terms_condition' => 'nullable',

        ]);

        Rules::updateOrCreate(

            [
                'id' => 1
            ],

            [

                'rules' => $request->rules,

                'terms_condition' => $request->terms_condition,

            ]

        );

        return back()->with(

            'success',

            'Rules & Terms updated successfully.'

        );
    }
}
