<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    /**
     * Switch the active locale and send the user back where they came
     * from. Open to guests too — no reason to gate language choice
     * behind login.
     */
    public function switch(string $locale): RedirectResponse
    {
        if (array_key_exists($locale, config('locales.available', []))) {
            session(['locale' => $locale]);
        }

        return back();
    }
}
