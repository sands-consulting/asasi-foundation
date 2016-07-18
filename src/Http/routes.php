<?php

Route::get('/set/{$locale}', ['as' => 'locale', function ($locale) {
    return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
}]);
