<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\DeliveryLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessengerController extends Controller
{
    /**
     * Display the messenger interface.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get user's chat rooms
        $chatRooms = ChatRoom::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['latestMessage.sender', 'users'])
            ->withCount(['messages' => function ($query) use ($user) {
                $query->whereHas('chatRoom.participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->whereColumn('messages.created_at', '>', 'chat_room_participants.last_read_at');
                })->where('sender_id', '!=', $user->id);
            }])
            ->orderByDesc('last_message_at')
            ->get();

        // Get all users for starting new chats
        $allUsers = User::where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        // Get delivery logs stats
        $deliveryStats = [
            'total' => DeliveryLog::count(),
            'sent' => DeliveryLog::where('status', 'sent')->count(),
            'delivered' => DeliveryLog::where('status', 'delivered')->count(),
            'failed' => DeliveryLog::where('status', 'failed')->count(),
            'today' => DeliveryLog::whereDate('created_at', today())->count(),
        ];

        // Recent delivery logs
        $recentLogs = DeliveryLog::with(['customer', 'order'])
            ->latest()
            ->limit(20)
            ->get();

        // Active room (if specified)
        $activeRoom = null;
        $messages = collect();

        if ($request->has('room')) {
            $activeRoom = ChatRoom::with(['users', 'participants'])
                ->find($request->room);

            if ($activeRoom && $activeRoom->participants()->where('user_id', $user->id)->exists()) {
                $messages = $activeRoom->messages()
                    ->with(['sender', 'customer', 'replyTo'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Mark as read
                $activeRoom->markAsReadFor($user->id);
            }
        }

        return view('messenger.index', compact(
            'chatRooms',
            'allUsers',
            'deliveryStats',
            'recentLogs',
            'activeRoom',
            'messages'
        ));
    }

    /**
     * Get messages for a chat room (AJAX).
     */
    public function getMessages(ChatRoom $chatRoom)
    {
        $user = auth()->user();

        // Check if user is participant
        if (!$chatRoom->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $chatRoom->messages()
            ->with(['sender', 'customer', 'replyTo'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        $chatRoom->markAsReadFor($user->id);

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send a message.
     */
    public function sendMessage(Request $request, ChatRoom $chatRoom)
    {
        $request->validate([
            'content' => 'required_without:attachment|string|max:5000',
            'attachment' => 'nullable|file|max:10240',
            'type' => 'in:text,image,file,audio',
            'reply_to_id' => 'nullable|exists:messages,id',
        ]);

        $user = auth()->user();

        // Check if user is participant
        if (!$chatRoom->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messageData = [
            'sender_id' => $user->id,
            'sender_type' => 'user',
            'content' => $request->content,
            'type' => $request->type ?? 'text',
            'reply_to_id' => $request->reply_to_id,
        ];

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('messenger/attachments', 'public');

            $messageData['attachment_url'] = $path;
            $messageData['attachment_name'] = $file->getClientOriginalName();
            $messageData['attachment_size'] = $file->getSize();

            // Determine type from file
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'image/')) {
                $messageData['type'] = 'image';
            } elseif (str_starts_with($mimeType, 'audio/')) {
                $messageData['type'] = 'audio';
            } else {
                $messageData['type'] = 'file';
            }
        }

        $message = $chatRoom->messages()->create($messageData);

        // Update room's last message timestamp
        $chatRoom->update(['last_message_at' => now()]);

        // Load relationships
        $message->load(['sender', 'replyTo']);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Start a new direct chat.
     */
    public function startChat(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = auth()->user();
        $room = ChatRoom::getOrCreateDirect($user->id, $request->user_id);

        return response()->json([
            'success' => true,
            'room_id' => $room->id,
        ]);
    }

    /**
     * Create a group chat.
     */
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $user = auth()->user();

        $room = ChatRoom::create([
            'name' => $request->name,
            'type' => 'group',
            'created_by' => $user->id,
        ]);

        // Add creator as admin
        $room->participants()->create([
            'user_id' => $user->id,
            'role' => 'admin',
        ]);

        // Add other members
        foreach ($request->user_ids as $userId) {
            $room->participants()->create([
                'user_id' => $userId,
                'role' => 'member',
            ]);
        }

        return response()->json([
            'success' => true,
            'room_id' => $room->id,
        ]);
    }

    /**
     * Get delivery logs.
     */
    public function deliveryLogs(Request $request)
    {
        $query = DeliveryLog::with(['customer', 'order'])
            ->latest();

        if ($request->channel) {
            $query->where('channel', $request->channel);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('recipient', 'like', "%{$request->search}%")
                    ->orWhere('subject', 'like', "%{$request->search}%");
            });
        }

        $logs = $query->paginate(20);

        return response()->json(['logs' => $logs]);
    }

    /**
     * Resend a failed delivery.
     */
    public function resendDelivery(DeliveryLog $log)
    {
        if ($log->status !== 'failed') {
            return response()->json([
                'success' => false,
                'message' => 'Only failed deliveries can be resent',
            ], 400);
        }

        // Reset status to pending
        $log->update([
            'status' => 'pending',
            'error_message' => null,
        ]);

        // TODO: Dispatch job to actually resend

        return response()->json([
            'success' => true,
            'message' => __('messenger.delivery_queued'),
        ]);
    }

    /**
     * Search messages.
     */
    public function searchMessages(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $user = auth()->user();

        $messages = Message::whereHas('chatRoom.participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('content', 'like', "%{$request->query}%")
            ->with(['sender', 'chatRoom'])
            ->latest()
            ->limit(50)
            ->get();

        return response()->json(['messages' => $messages]);
    }
}
