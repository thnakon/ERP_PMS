<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicalCalculatorController extends Controller
{
    /**
     * Display the medical calculators index page.
     */
    public function index()
    {
        return view('calculators.index');
    }

    /**
     * Pediatric Dosage Calculation (Simple mg/kg)
     */
    public function pediatric(Request $request)
    {
        $weight = $request->input('weight');
        $dose_mg_kg = $request->input('dose_mg_kg');
        $frequency = $request->input('frequency', 1);

        $total_daily_dose = $weight * $dose_mg_kg;
        $dose_per_time = $total_daily_dose / $frequency;

        return response()->json([
            'total_daily_dose' => round($total_daily_dose, 2),
            'dose_per_time' => round($dose_per_time, 2),
        ]);
    }

    /**
     * BMI Calculation
     */
    public function bmi(Request $request)
    {
        $weight = $request->input('weight');
        $height = $request->input('height'); // in cm

        $height_m = $height / 100;
        $bmi = $weight / ($height_m * $height_m);

        $category = '';
        if ($bmi < 18.5) $category = 'underweight';
        elseif ($bmi < 25) $category = 'normal';
        elseif ($bmi < 30) $category = 'overweight';
        else $category = 'obese';

        return response()->json([
            'bmi' => round($bmi, 2),
            'category' => $category
        ]);
    }

    /**
     * CrCl (Cockcroft-Gault) for drug adjustment
     */
    public function egfr(Request $request)
    {
        $age = $request->input('age');
        $weight = $request->input('weight');
        $gender = $request->input('gender'); // male/female
        $scr = $request->input('scr'); // Serum Creatinine mg/dL

        $crcl = ((140 - $age) * $weight) / (72 * $scr);

        if ($gender === 'female') {
            $crcl *= 0.85;
        }

        return response()->json([
            'crcl' => round($crcl, 2)
        ]);
    }
}
