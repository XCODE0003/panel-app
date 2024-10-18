<?php


namespace App\Services\Domain;

use App\Models\Domain;
use App\Services\Cloudflare\Client;
use Exception;

class CreateDomain
{
    public function create(array $data): Domain
    {
        $CFClient = new Client();
        $domain = $data['domain'];
        $response = $CFClient->addDomain($domain);

        if (!$response['success']) {
            throw new Exception($response['error']);
        }

        $template_id = $data['template_id'];

        $user = auth()->user();
        $countDomain = Domain::where('user_id', $user->id)->count();
        if ($countDomain >= $user->limit_domain) {
            throw new Exception('Вы достигли лимита доменов.');
        }

        $domain = Domain::create([
            'domain' => $domain,
            'user_id' => $user->id,
            'status' => 'pending',
            'template_id' => $template_id,
            'cloudflare_zone_id' => $response['id'],
            'ns_records' => $response['name_servers'],
        ]);

        return $domain;
    }
}