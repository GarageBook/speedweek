<?php

namespace App\Http\Controllers\Dashboard\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait RedirectsAfterSave
{
    protected function redirectAfterSave(Request $request, string $message): RedirectResponse
    {
        $fallback = route('dashboard');
        $returnTo = $request->input('return_to', $fallback);

        if (! is_string($returnTo) || ! str_starts_with($returnTo, url('/'))) {
            $returnTo = $fallback;
        }

        return redirect()->to($returnTo)->with('status', $message);
    }
}
