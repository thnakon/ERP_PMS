<?php

namespace App\Http\Controllers;

use App\Models\DrugInteraction;
use App\Models\Product;
use Illuminate\Http\Request;

class DrugInteractionController extends Controller
{
    /**
     * Display the drug interaction checker.
     */
    public function index()
    {
        return view('tools.drug-interactions');
    }

    /**
     * Search for interactions between drugs.
     */
    public function search(Request $request)
    {
        $drugA = $request->input('drug_a');
        $drugB = $request->input('drug_b');

        if (!$drugA || !$drugB) {
            return response()->json([]);
        }

        $interactions = DrugInteraction::where('is_active', true)
            ->where(function ($query) use ($drugA, $drugB) {
                $query->where(function ($q) use ($drugA, $drugB) {
                    $q->where('drug_a_name', 'like', "%{$drugA}%")
                        ->where('drug_b_name', 'like', "%{$drugB}%");
                })->orWhere(function ($q) use ($drugA, $drugB) {
                    $q->where('drug_a_name', 'like', "%{$drugB}%")
                        ->where('drug_b_name', 'like', "%{$drugA}%");
                });
            })->get();

        return response()->json($interactions);
    }

    /**
     * Suggest drug names for autocomplete.
     */
    public function suggest(Request $request)
    {
        $term = $request->input('term');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        // Search in products (controlled drugs mostly) and existing interactions
        $productDrugs = Product::where('name', 'like', "%{$term}%")
            ->limit(5)
            ->pluck('name');

        $interactionDrugsA = DrugInteraction::where('drug_a_name', 'like', "%{$term}%")
            ->limit(5)
            ->pluck('drug_a_name');

        $interactionDrugsB = DrugInteraction::where('drug_b_name', 'like', "%{$term}%")
            ->limit(5)
            ->pluck('drug_b_name');

        $suggestions = $productDrugs->concat($interactionDrugsA)
            ->concat($interactionDrugsB)
            ->unique()
            ->values();

        return response()->json($suggestions);
    }
}
