<?php


namespace App\Services\Messages;

use App\Models\Domain;
use App\Services\Cloudflare\Client;
use App\Models\Chat;
use App\Models\Message;
use Exception;

class SendMessage
{
    public function send(array $data): Message
    {
        $chat = Chat::find($data['chat_id']);
        if (!$chat) {
            throw new Exception('Чат не найден');
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $data['user_id'],
            'content' => $data['message'],
        ]);

        return $message;
    }
}