<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        "/image-upload",
        "/admin/update-user-status","/admin/update-cms-status","admin/update-faq-status","admin/update-api-response-status","admin/update-blog-status","admin/update-app-feedback-status","admin/update-main-category-status","admin/update-ticket-status",
    ];
}
