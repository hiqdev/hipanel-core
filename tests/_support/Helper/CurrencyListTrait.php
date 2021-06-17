<?php

namespace hipanel\tests\_support\Helper;

trait CurrencyListTrait {
    public function getCurrencyList(): array
    {
        return [
            'usd' => 'USD',
            'eur' => 'EUR',
            'uah' => 'UAH',
            'rub' => 'RUB',
            'btc' => 'BTC',
        ];
    }
}
