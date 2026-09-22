<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class CustomerVenuesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('customer/venues/index');
    }
}
