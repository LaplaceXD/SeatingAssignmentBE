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

        return Issue::query()
            ->when($status, fn (Builder $query) => $query->where('Status', $status))
            ->orderByDesc('IssuedAt')
            ->get();
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
        abort_if($issue->isFrozen, Response::HTTP_BAD_REQUEST, 'Issue is already frozen.');

        $issue->update($request->safe()->all());
        return $issue->refresh();
    }

    public function validated(Request $request, Issue $issue)
    {
        // todo add trail
        abort_if($issue->isFrozen, Response::HTTP_BAD_REQUEST, 'Issue is already frozen.');
        abort_if($issue->isValidated, Response::HTTP_BAD_REQUEST, 'Issue is already validated.');

        $issue->update([
            'ValidatorID' => $request->user()->UserID,
            'ValidatedAt' => Carbon::now(),
            'Status' => IssueStatus::Validated
        ]);

        return $issue->refresh();
    }

    public function updateProgress(IssueProgressRequest $request, Issue $issue)
    {
        abort_unless($issue->isValidated, Response::HTTP_BAD_REQUEST, 'Issue is not yet validated.');

        $fields = $request->safe()->all();
        if (
            array_key_exists('Status', $fields) && $fields['Status']
            && $fields['Status'] === IssueStatus::Fixed->value
            && $issue->Status !== IssueStatus::Fixed
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
        abort_if($issue->isFrozen, Response::HTTP_BAD_REQUEST, 'Issue is already frozen.');

        $issue->update(['Status' => IssueStatus::Dropped]);
        return ['message' => 'Issue dropped successfully.'];
    }
}
