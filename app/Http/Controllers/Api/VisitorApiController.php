<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorApiController extends Controller
{
    /**
     * Store or update a unique visitor UUID.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 200);
        }

        $now = now();
        $visitor = Visitor::where('uuid', $request->uuid)->first();

        if ($visitor) {
            $visitor->update([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'last_visited_at' => $now,
            ]);
            $isNew = false;
        } else {
            $visitor = Visitor::create([
                'uuid' => $request->uuid,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'last_visited_at' => $now,
            ]);
            $isNew = true;
        }

        $totalCount = Visitor::count();

        return response()->json([
            'success' => true,
            'message' => $isNew ? 'Visitor recorded successfully' : 'Visitor visit updated successfully',
            'data' => [
                'uuid' => $visitor->uuid,
                'is_new' => $isNew,
                'first_visited_at' => $visitor->created_at,
                'last_visited_at' => $visitor->last_visited_at,
                'total_unique_visitors' => $totalCount,
            ]
        ], 200);
    }

    /**
     * Get the count of unique visitors.
     */
    public function count()
    {
        $totalCount = Visitor::count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_unique_visitors' => $totalCount,
            ]
        ], 200);
    }
}
