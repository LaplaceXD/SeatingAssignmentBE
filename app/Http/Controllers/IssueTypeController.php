<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function show(string $id)
    {
        return IssueType::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IssueTypeRequest $request, string $id)
    {
        $issue = IssueType::find($id);
        $issue->update($request->safe()->all());
        return $issue;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return IssueType::destroy($id);
    }
}
