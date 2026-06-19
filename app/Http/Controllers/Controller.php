<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Import the trait
use Illuminate\Routing\Controller as BaseController; // Use BaseController alias

abstract class Controller extends BaseController
{
    use AuthorizesRequests; // Add the trait
}
