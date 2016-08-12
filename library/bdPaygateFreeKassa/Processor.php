<?php

class bdPaygateFreeKassa_Processor extends bdPaygate_Processor_Abstract
{
    const CURRENCY_RUB = 'rub';

    public function getSupportedCurrencies()
    {
        $currencies = array();
        $currencies[] = self::CURRENCY_RUB;

        return $currencies;
    }

    public function isAvailable()
    {
        $options = XenForo_Application::getOptions();
        // FreeKassa не поддерживает тестовый режим,
        // поэтому на всякий случай отключаем её, если включён "Sandbox"
        if (empty($options->bdPaygateFreeKassa_ID) || empty($options->bdPaygateFreeKassa_SecretKey) || empty($options->bdPaygateFreeKassa_SecretKey_2) || $this->_sandboxMode()) {
            return false;
        }

        return true;
    }

    public function isRecurringSupported()
    {
        return false;
    }

    public function validateCallback(Zend_Controller_Request_Http $request, &$transactionId, &$paymentStatus, &$transactionDetails = array(), &$itemId)
    {
        // TODO: Пофиксить алгоритм
        $input = new XenForo_Input($request);
        $transactionDetails = $input->getInput();

        $signature = $transactionDetails['SIGN'];

        $transactionId = (!empty($transactionDetails['intid']) ? ('freekassa_' . $transactionDetails['intid']) : '');
        $paymentStatus = bdPaygate_Processor_Abstract::PAYMENT_STATUS_OTHER;

        $processorModel = $this->getModelFromCache('bdPaygate_Model_Processor');
        $options = XenForo_Application::get('options');
        $freekassa_key = $options->bdPaygateFreeKassa_SecretKey_2;

        // Проверяем, не была ли уже проведена такая операция
        $log = $processorModel->getLogByTransactionId($transactionId);
        if (!empty($log)) {
            $this->_setError("Transaction {$transactionId} has already been processed");
            return false;
        }

        // Генерация MD5 подписи
        $crc = md5($transactionDetails['MERCHANT_ID'].':'.$transactionDetails['AMOUNT'].':'.$freekassa_key.':'.$transactionDetails['MERCHANT_ORDER_ID']);

        // Сверяем нашу подпись с той, которую мы получили
        if ($crc != $signature) {
            $this->_setError('Request not validated + ' . $crc . ' + ' . $signature);
            return false;
        }

        /// https://www.walletone.com/ru/merchant/documentation/#step5
        // Платеж успешно проведен
        $itemId = $transactionDetails['MERCHANT_ORDER_ID'];
        $paymentStatus = bdPaygate_Processor_Abstract::PAYMENT_STATUS_ACCEPTED;
        echo "YES";

        return true;
    }

    public function generateFormData($amount, $currency, $itemName, $itemId, $recurringInterval = false, $recurringUnit = false, array $extraData = array())
    {
        $this->_assertAmount($amount);
        $this->_assertCurrency($currency);
        $this->_assertItem($itemName, $itemId);
        $this->_assertRecurring($recurringInterval, $recurringUnit);

        $formAction = 'https://www.free-kassa.ru/merchant/cash.php';
        $callToAction = new XenForo_Phrase('bdpaygate_freekassa_call_to_action');

        $options = XenForo_Application::get('options');

        $payment = array(
            'm'     => $options->bdPaygateFreeKassa_ID,
            'oa'    => $amount,
            's'     => $options->bdPaygateFreeKassa_SecretKey,
            'o'     => $itemId,
        );

        //
        // Кодирование MD5 хэша в BASE64
        $payment['s'] = md5(implode(':', $payment));

        // Генерация формы
        $form = "<form action='{$formAction}' method='POST'>";
        foreach ($payment as $item => $value){
            $form .= "<input type='hidden' name='$item' value='$value' />";
        }
        $form .= "<input type='submit' value='{$callToAction}' class='button'/></form>";

        return $form;
    }
}