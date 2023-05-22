<?php

namespace App\Http\Controllers;

use App\Enums\IssueStatus;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    public function index()
    {
        return Laboratory::all()->map(fn (Laboratory $lab) => $lab->transform());
    }

    public function show(Laboratory $laboratory)
    {
        return $laboratory->transform();
    }

    public function getIssues(Request $request, Laboratory $laboratory)
    {
        $status = $request->query('Status');
        $validatedOnly = $request->query('Validated');

        return $laboratory->issues()
            ->ofStatus(IssueStatus::tryFrom($status), boolval($validatedOnly))
            ->get();
    }

    public function getSeatIssues(Request $request, Laboratory $laboratory, string $seat)
    {
        $status = $request->query('Status');
        $validatedOnly = $request->query('Validated');

        return $laboratory->issues()
            ->ofSeat($seat)
            ->ofStatus(IssueStatus::tryFrom($status), boolval($validatedOnly))
            ->get();
    }
}
