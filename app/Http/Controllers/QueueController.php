<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class QueueController extends ApiController
{
    public function index()
    {
        Gate::authorize('viewAny', Queue::class);

        $queues = Queue::with('service', 'user')->latest()->get();
        return $this->sendResponse($queues->toArray(), 'Queues retrieved successfully.');
    }

    public function store(Request $request, Service $service)
    {
        $lastQueueNumber = Queue::where('service_id', $service->id)->whereDate('created_at', Carbon::today())->max('number');
        $estTimeAll = Queue::where('service_id', $service->id)->where('status', 'Pending')->whereDate('created_at', Carbon::today())->max('est_time_mins');

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

    public function history()
    {
        $queues = Queue::with('service')->where('user_id', Auth::user()->id)->latest()->get();
        return $this->sendResponse($queues->toArray(), 'Queues retrieved successfully.');
    }

    public function show(Queue $queue)
    {
        Gate::authorize('view', $queue);

        $withRelations = Queue::with('service')->find($queue->id);
        return $this->sendResponse($withRelations->toArray(), 'Queue retrieved successfully.');
    }

    public function changeStatus(Request $request, Queue $queue)
    {
        Gate::authorize('update', $queue);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Served,Done,Canceled'
        ], [
            'status.in' => 'Status harus berupa "Pending", "Served", "Done", atau "Canceled"'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Input validation error', $validator->errors(), 400);
        }

        $queue->status = $request->input('status');
        $queue->save();

        return $this->sendResponse($queue, 'Queue status updated successfully!');
    }

    public function stats(Request $request)
    {
        $dateStart = $request->query('date_start');
        $dateEnd = $request->query('date_end');

        $stats = DB::table('queues')
            ->selectRaw('count(id) as queue_counts, service_id, DATE(created_at) as date')
            ->groupBy('service_id', 'date');

        if ($dateStart) {
            $stats = $stats->whereDate('created_at', '>=', $dateStart);
        }

        if ($dateEnd) {
            $stats = $stats->whereDate('created_at', '<=', $dateEnd);
        }

        $stats = $stats->get();

        return $this->sendResponse($stats, 'Queue statistics retrieved successfully!');
    }
}
