<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index() {
        $countries = Country::all();

        return view('administrator.country.all', compact('countries'));
    }

    public function add() {
        return view('administrator.country.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $country = new Country();
        $country->name = $request->name;
        $country->country_code = $request->country_code;
        $country->status = $request->status;
        $country->save();

        return redirect()->route('country')->with('message', 'Country add successfully.');
    }

    public function edit(Country $country) {
        return view('administrator.country.edit', compact('country'));
    }

    public function editPost(Country $country, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $country->name = $request->name;
        $country->country_code = $request->country_code;
        $country->status = $request->status;
        $country->save();

        return redirect()->route('country')->with('message', 'Country edit successfully.');
    }
}
