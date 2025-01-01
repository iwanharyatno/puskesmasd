<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PatientController extends ApiController
{
    public function index() {
        Gate::authorize('viewAny', User::class);
        $patients = User::where('role', 'Patient')->latest()->get();
        return $this->sendResponse($patients, 'Patients retrieved successfully');
    }
}
