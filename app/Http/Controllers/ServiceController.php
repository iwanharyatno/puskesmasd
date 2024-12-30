<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ServiceController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Service::class);
        $services = Service::all();
        return $this->sendResponse($services, 'Services retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Service::class);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'est_duration_mins' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $service = Service::create($input);

        return $this->sendResponse($service, 'Service created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        Gate::authorize('view', $service);
        return $this->sendResponse($service, 'Service retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        Gate::authorize('uodate', $service);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'est_duration_mins' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $service->name = $input['name'];
        $service->est_duration_mins = $input['est_duration_mins'];
        $service->save();

        return $this->sendResponse($service, 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        Gate::authorize('delete', $service);
        $service->delete();
        return $this->sendResponse([], 'Service deleted successfully.');
    }
}
