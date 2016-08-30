<?php

{
    public function getCurrencies($currency = false)
    {
        $currencies = parent::getCurrencies();

            'name' => 'RUB',
            'suffix' => '₽'
        );

        if ($currency && array_key_exists($currency, $currencies)) {
            return $currencies[$currency];
        }

        return $currencies;
    }
}
