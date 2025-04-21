<?php

namespace App\Http\Controllers;

use App\Models\TaxCalendarTask;
use App\Models\TaskMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskMessageController extends Controller
{
    public function store(Request $request, TaxCalendarTask $task): JsonResponse
    {
        $this->authorize('view', $task);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = $task->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['message'],
        ]);

        $message->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => [
                'id' => $message->id,
                'content' => $message->content,
                'created_at' => $message->created_at->format('M d, H:i'),
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                ],
            ],
        ]);
    }

    public function markAsRead(TaxCalendarTask $task): JsonResponse
    {
        $this->authorize('view', $task);

        $task->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
        ]);
    }

    public function getNewMessages(Request $request, TaxCalendarTask $task)
    {
        $this->authorize('view', $task);

        $afterId = $request->query('after', 0);

        $messages = $task->messages()
            ->with('user')
            ->where('id', '>', $afterId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'created_at' => $message->created_at->toISOString()
                ];
            });

        return response()->json(['messages' => $messages]);
    }
}
