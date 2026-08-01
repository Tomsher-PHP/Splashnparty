<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EnforceMaxFileSize
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($request->allFiles() as $key => $file) {
            if ($key === 'rental_items_pdf') {
                continue;
            }
            if (is_array($file)) {
                $this->checkFiles($key, $file);
            } else {
                if ($file->isValid() && $file->getSize() > 500 * 1024) {
                    throw ValidationException::withMessages([
                        $key => ['The uploaded file must not be greater than 500 KB.'],
                    ]);
                }
            }
        }

        return $next($request);
    }

    /**
     * Recursively check files array.
     */
    private function checkFiles(string $key, array $files): void
    {
        foreach ($files as $file) {
            if (is_array($file)) {
                $this->checkFiles($key, $file);
            } elseif ($file instanceof \Illuminate\Http\UploadedFile) {
                if ($file->isValid() && $file->getSize() > 500 * 1024) {
                    throw ValidationException::withMessages([
                        $key => ['One or more uploaded files exceed the 500 KB limit.'],
                    ]);
                }
            }
        }
    }
}
