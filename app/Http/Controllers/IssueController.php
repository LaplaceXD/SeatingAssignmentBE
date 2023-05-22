<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueDetailsRequest;
use App\Models\Issue;
use App\Enums\IssueStatus;
use App\Http\Requests\IssueProgressRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('Status');

        return Issue::ofStatus(IssueStatus::tryFrom($status))->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssueDetailsRequest $request)
    {
        // todo add trail
        return Issue::create(array_merge(['IssuerID' => $request->user()->UserID], $request->safe()->all()))->fresh();
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
        abort_if($issue->isCompleted, Response::HTTP_BAD_REQUEST, 'Issue is already frozen.');

        $issue->update($request->safe()->all());
        return $issue->refresh();
    }

    public function validated(Issue $issue)
    {
        // todo add trail
        abort_if($issue->isCompleted, Response::HTTP_BAD_REQUEST, 'Issue is already frozen.');
        abort_if($issue->isValidated, Response::HTTP_BAD_REQUEST, 'Issue is already validated.');

        return $issue->validated()->refresh();
    }

    public function updateProgress(IssueProgressRequest $request, Issue $issue)
    {
        abort_unless($issue->isValidated, Response::HTTP_BAD_REQUEST, 'Issue is not yet validated.');

        $fields = $request->safe()->all();
        if (
            array_key_exists('Status', $fields) && $fields['Status']
            && in_array(IssueStatus::from($fields['Status']), IssueStatus::completedCases())
            && !in_array($issue->Status, IssueStatus::completedCases())
        ) {
            $fields['CompletedAt'] = Carbon::now();
        }

        $issue->update($fields);
        return $issue->refresh();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        abort_if($issue->isCompleted, Response::HTTP_BAD_REQUEST, 'Issue is already frozen.');

        $issue->update([
            'Status' => IssueStatus::Dropped,
            'CompletedAt' => Carbon::now()
        ]);

        return ['message' => 'Issue dropped successfully.'];
    }
}
