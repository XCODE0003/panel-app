<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use App\Models\Domain;

class ActionController extends Controller
{
    public function store(Request $request)
    {
        $ip_info = file_get_contents('http://ip-api.com/json/' . $request->ip);
        $ip_info = json_decode($ip_info, true);
        $country = $ip_info['country'];
        $user_agent = $request->header('User-Agent');
        $user = Domain::where('domain', $request->domain)->first();
        $data = [
            'user_id' => $user?->id,
            'type' => $request->type,
            'domain' => $request->domain,
            'ip' => $request->ip,
            'user_agent' => $user_agent,
            'address' => $request->address,
            'balance' => $request->balance,
            'country' => $country,
        ];
        Action::create($data);
        return response()->json(['message' => 'Action created successfully'], 201);
    }
}