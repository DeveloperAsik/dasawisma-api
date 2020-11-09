<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'https://api.dasawisma.local/',
        'https://api.dasawisma.local/transmit/report-incidents',
        'https://api.dasawisma.local/api/fetch/report-incidents',
        'https://api-dasawisma.orenoservices.com/',
        'https://api-dasawisma.orenoservices.com/transmit/report-incidents',
        'https://api-dasawisma.orenoservices.com/fetch/report-incidents',
        'https://api.dasawisma.local/api',
        'https://api-dasawisma.orenoservices.com/api',
    ];
}
