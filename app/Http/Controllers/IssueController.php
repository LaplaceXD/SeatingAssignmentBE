<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueDetailsRequest;
use App\Models\Issue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query();

        return Issue::query()
            ->when(array_key_exists('Status', $q), fn (Builder $query) => $query->where('Status', $q['Status']))
            ->orderByDesc('IssuedAt')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssueDetailsRequest $request)
    {
        // todo add trail
        return Issue::create(array_merge(['IssuerID' => $request->user()->UserID], $request->safe()->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        return $issue;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IssueDetailsRequest $request, Issue $issue)
    {
        // todo add trail
        $issue->update($request->safe()->all());
        return $issue->refresh();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();

        return ['message' => 'Issue deleted successfully.'];
    }
}
