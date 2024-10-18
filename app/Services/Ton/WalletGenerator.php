<?php

declare(strict_types=1);

namespace App\Services\Ton;

use Illuminate\Support\Facades\Log;
use Olifanton\Mnemonic\TonMnemonic;
use Olifanton\Ton\Contracts\Wallets\V3\WalletV3R2;
use Olifanton\Ton\Contracts\Wallets\V3\WalletV3Options;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4Options;

require "common.php";

class WalletGenerator
{
    public function generateWallet(): array
    {
        $mnemonic = TonMnemonic::generate();
        $mnemonicPhrase = implode(" ", $mnemonic);

        $kp = TonMnemonic::mnemonicToKeyPair($mnemonic);

        $wallet = new WalletV3R2(new WalletV3Options(
            publicKey: $kp->publicKey,
        ));

        $address = $wallet->getAddress()->toString(true, true, false);

        return [
            'mnemonic' => $mnemonicPhrase,
            'address' => $address,
        ];
    }
    public function getUserBalance(string $address): float
    {
        $apiUrl = "https://tonapi.io/v2/accounts/" . urlencode($address);

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('Ошибка cURL: ' . curl_error($ch));
            return 0.0;
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['balance'])) {
            return round($data['balance'] / 1_000_000_000, 1);
        }

        Log::warning('Не удалось получить баланс для адреса: ' . $address);
        return 0.0;
    }
}