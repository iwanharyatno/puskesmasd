<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QueueController extends ApiController
{
    public function index() {
        $queues = Queue::all();
        return $this->sendResponse($queues->toArray(), 'Queues retrieved successfully.');
    }

    public function store(Request $request, Service $service) {
        $lastQueueNumber = Queue::where('service_id', $service->id)->whereDate('created_at', Carbon::today())->max('number');
        $estTimeAll = Queue::where('service_id', $service->id)->where('status', 'Pending')->whereDate('created_at', Carbon::today())->sum('est_time_mins');

        if ($estTimeAll == 0) {
            $estTimeAll = $service->est_duration_mins;
        } else {
            $estTimeAll += $service->est_duration_mins;
        }

        $queue = Queue::create([
            'service_id' => $service->id,
            'user_id' => $request->user()->id,
            'number' => $lastQueueNumber + 1,
            'est_time_mins' => $estTimeAll
        ]);

        return $this->sendResponse($queue->toArray(), 'Queue created successfully.', 201);
    }
}
