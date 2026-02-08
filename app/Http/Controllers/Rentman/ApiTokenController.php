<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rentman\UpdateApiTokenRequest;
use App\Models\Rentman\ApiToken;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tokens = ApiToken::paginate(25);

        return view('admin.rentman.apitokens.index', compact('tokens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiToken $apiToken)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApiToken $apitoken)
    {
        $token = $apitoken;
        return view('admin.rentman.apitokens.edit', compact('token'));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(UpdateApiTokenRequest $request, ApiToken $apitoken)
    {
        // Verify input
        // Because we use the UpdateApiTokenRequest the input data is already validated

        // return $request->validated();

        // update resource
        $apitoken->update($request->validated());

        // redirect to index page
        return redirect()->route('admin.apitoken.index')
            ->with('success', 'Token succesvol bijgewerkt!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiToken $apiToken)
    {
        //
    }
}
