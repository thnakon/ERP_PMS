<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch application language.
     */
    public function switch(string $locale)
    {
        // Validate locale
        if (!in_array($locale, ['en', 'th'])) {
            $locale = 'en';
        }

        // Store in session
        Session::put('locale', $locale);

        // Set app locale
        App::setLocale($locale);

        // Return for AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
            ]);
        }

        // Redirect back for normal requests
        $message = $locale === 'th' ? 'เปลี่ยนภาษาเป็นไทยสำเร็จ' : 'Language switched to English';
        return redirect()->back()->with('success', $message);
    }
}
