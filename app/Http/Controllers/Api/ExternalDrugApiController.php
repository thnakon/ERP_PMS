<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DrugDatabaseService;

class ExternalDrugApiController extends Controller
{
    protected $drugService;

    public function __construct(DrugDatabaseService $drugService)
    {
        $this->drugService = $drugService;
    }

    /**
     * Search for drugs in external databases (Thai FDA Open Data & OpenFDA)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = $this->drugService->search($query);

        return response()->json($results);
    }
}
