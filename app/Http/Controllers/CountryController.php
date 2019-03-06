<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\CountryRequest;


class CountryController extends Controller
{

    public function __construct()
    {
    }


    public function index()
    {
        $countries = Country::all();

        return response()->json($countries, 200);
    }


    public function create() {
        //
    }


    public function store(CountryRequest $request) {
        $country = Country::create([
            'name' => $request->input('name'),
            'continent' => $request->input('continent'),
            'user_id' => auth()->id()
        ]);

        return response()->json($country, 200);
    }


    public function show(Country $country) {
      return response()->json($country, 200);
    }


    public function edit(Country $country) {
      //
    }


    public function update(CountryRequest $request, Country $country) {
        $this->authorize('update', $country);

        $country->update([
            'name' => $request->input('name'),
        ]);

        return response()->json($country, 200);
    }


    public function destroy(Country $country) {
        $this->authorize('delete', $country);

        $country->delete();

        return response()->json(true, 200);
    }

}
