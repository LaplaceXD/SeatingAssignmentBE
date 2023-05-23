<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueDetailsRequest;
use App\Models\Issue;
use App\Enums\IssueStatus;
use App\Enums\UserRole;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('Status');
        $validatedOnly = $request->query('Validated');

        return Issue::ofStatus(IssueStatus::tryFrom($status), boolval($validatedOnly))->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssueDetailsRequest $request)
    {
        // todo add trail
        return Issue::raise($request->safe()->all());
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

        $issue->setDetails($request->safe()->all());
        return $issue->refresh();
    }

    public function validated(Issue $issue)
    {
        // todo add trail
        abort_if($issue->isCompleted, Response::HTTP_BAD_REQUEST, 'Issue is already frozen.');
        abort_if($issue->isValidated, Response::HTTP_BAD_REQUEST, 'Issue is already validated.');

        return $issue->validate()->refresh();
    }

    public function updateStatus(Request $request, Issue $issue)
    {
        abort_unless($issue->isValidated, Response::HTTP_BAD_REQUEST, 'Issue is not yet validated.');

        $fields = $request->validate(['Status' => ['required', new Enum(IssueStatus::class)]]);
        $status = IssueStatus::from($fields['Status']);

        $issue->setStatus($status);
        return $issue->refresh();
    }

    public function assign(Request $request, Issue $issue)
    {
        abort_if($issue->isCompleted, Response::HTTP_BAD_REQUEST, 'Issue is already frozen.');
        abort_unless($issue->isValidated, Response::HTTP_BAD_REQUEST, 'Issue is not yet validated.');

        $fields = Validator::make($request->all(), [
            'AssigneeID' => [
                'required', 'numeric',
                Rule::exists('Users', 'UserID')->where('Role', UserRole::Technician->value)
            ]
        ])->setAttributeNames(['AssigneeID' => 'assignee ID'])->validate();

        $issue->setAssignee(User::where('UserID', $fields['AssigneeID'])->first());
        return $issue->fresh();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        abort_if($issue->isCompleted, Response::HTTP_BAD_REQUEST, 'Issue is already completed.');

        $issue->setStatus(IssueStatus::Dropped);
        return ['message' => 'Issue dropped successfully.'];
    }
}
