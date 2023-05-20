<?php

namespace App\Http\Controllers;

use App\Models\IssueType;
use App\Http\Requests\IssueTypeRequest;

class IssueTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return IssueType::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssueTypeRequest $request)
    {
        return IssueType::create($request->safe()->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(IssueType $type)
    {
        return $type;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IssueTypeRequest $request, IssueType $type)
    {
        $type->update($request->safe()->all());
        return $type->refresh();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IssueType $type)
    {
        $type->delete();

        return ['message' => 'Issue Type successfully deleted.'];
    }
}
