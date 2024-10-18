<?php

declare(strict_types=1);

use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Olifanton\Mnemonic\TonMnemonic;
use Olifanton\Ton\Transports\Toncenter\ClientOptions;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Olifanton\Ton\Transports\Toncenter\ToncenterTransport;
use Psr\Log\AbstractLogger;

function initializeTonCommon()
{
    $isMainnet = (bool) getenv('TON_MAINNET');

    $httpClient = new HttpMethodsClient(
        Psr18ClientDiscovery::find(),
        Psr17FactoryDiscovery::findRequestFactory(),
        Psr17FactoryDiscovery::findStreamFactory(),
    );

    $toncenter = new ToncenterHttpV2Client(
        $httpClient,
        new ClientOptions(
            $isMainnet ? ClientOptions::MAIN_BASE_URL : ClientOptions::TEST_BASE_URL,
            $isMainnet ? getenv("TONCENTER_API_KEY_MAINNET") : getenv("TONCENTER_API_KEY_TESTNET"),
        ),
    );
    $transport = new ToncenterTransport($toncenter);

    $words = explode(" ", trim(getenv("TON_WALLET_WORDS")));
    $kp = TonMnemonic::mnemonicToKeyPair($words);

    $logger = new class extends AbstractLogger {
        public function log($level, Stringable | string $message, array $context = []): void
        {
            // ... (код логгера остается без изменений)
        }
    };

    return [
        'transport' => $transport,
        'keyPair' => $kp,
        'logger' => $logger,
    ];
}