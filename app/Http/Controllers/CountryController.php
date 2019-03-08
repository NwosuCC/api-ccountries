<?php

namespace App\Http\Controllers;

use App\Continent;
use App\Country;
use App\Http\Requests\CountryRequest;


class CountryController extends Controller
{

    public function __construct()
    {
    }


    public function index(){
      $countries = Country::all();

      return response()->json($countries, 200);
    }


    public function create() {
        //
    }


    public function store(CountryRequest $request) {
      $country = (new Country)->fill([
          'name' => $request->input('name'),
          'user_id' => auth()->id()
      ]);

      $continent_name = $request->input('continent');
      $country = (new Continent)->addCountry($country, $continent_name);

      $country->save();

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

      $continent_name = $request->input('continent');
      $country = (new Continent)->addCountry($country, $continent_name);

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
