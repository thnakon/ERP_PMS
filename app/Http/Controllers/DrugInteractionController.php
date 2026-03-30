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
        $drugA = trim($request->input('drug_a'));
        $drugB = trim($request->input('drug_b'));

        if (!$drugA || !$drugB) {
            return response()->json([]);
        }

        // Search for interactions where:
        // (Interaction.drug_a_name is PART OF search.drug_a AND Interaction.drug_b_name is PART OF search.drug_b)
        // OR vice versa.
        // This allows SEARCH "Simvastatin 20mg" to match INTERACTION "Simvastatin".
        $interactions = DrugInteraction::where('is_active', true)
            ->where(function ($query) use ($drugA, $drugB) {
                $query->where(function ($q) use ($drugA, $drugB) {
                    $q->whereRaw("? LIKE CONCAT('%', drug_a_name, '%')", [$drugA])
                        ->whereRaw("? LIKE CONCAT('%', drug_b_name, '%')", [$drugB]);
                })->orWhere(function ($q) use ($drugA, $drugB) {
                    $q->whereRaw("? LIKE CONCAT('%', drug_a_name, '%')", [$drugB])
                        ->whereRaw("? LIKE CONCAT('%', drug_b_name, '%')", [$drugA]);
                })->orWhere(function ($q) use ($drugA, $drugB) {
                    // Fallback to traditional LIKE for broader matches
                    $q->where(function ($q2) use ($drugA, $drugB) {
                        $q2->where('drug_a_name', 'like', "%{$drugA}%")
                           ->where('drug_b_name', 'like', "%{$drugB}%");
                    })->orWhere(function ($q2) use ($drugA, $drugB) {
                        $q2->where('drug_a_name', 'like', "%{$drugB}%")
                           ->where('drug_b_name', 'like', "%{$drugA}%");
                    });
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
