<?php

class bdPaygateFreeKassa_bdPaygate_Model_Processor extends XFCP_bdPaygateFreeKassa_bdPaygate_Model_Processor
{
    public function getCurrencies()
    {
        $currencies = parent::getCurrencies();

        $currencies[bdPaygateFreeKassa_Processor::CURRENCY_RUB] = 'RUB';
        return $currencies;
    }

    public function getProcessorNames()
    {
        $names = parent::getProcessorNames();

        $names['freekassa'] = 'bdPaygateFreeKassa_Processor';

        return $names;
    }
}