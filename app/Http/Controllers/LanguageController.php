<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function switch($locale): RedirectResponse
    {
        if (array_key_exists($locale, config('app.locales', ['en' => 'English', 'id' => 'Indonesian']))) {
            session()->put('locale', $locale);
            App::setLocale($locale);
        }

        return redirect()->back();
    }
}
