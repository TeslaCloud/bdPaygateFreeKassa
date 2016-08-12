<?php

class bdPaygateFreeKassa_XenForo_Model_Option extends XFCP_bdPaygateFreeKassa_XenForo_Model_Option
{
    // this property must be static because XenForo_ControllerAdmin_UserUpgrade::actionIndex
    // for no apparent reason use XenForo_Model::create to create the optionModel
    // (instead of using XenForo_Controller::getModelFromCache)
    private static $_bdPaygateFreeKassa_hijackOptions = false;

    public function getOptionsByIds(array $optionIds, array $fetchOptions = array())
    {
        if (self::$_bdPaygateFreeKassa_hijackOptions === true)
        {
            $optionIds[] = 'bdPaygateFreeKassa_ID';
            $optionIds[] = 'bdPaygateFreeKassa_SecretKey';
            $optionIds[] = 'bdPaygateFreeKassa_SuccessUrl';
            $optionIds[] = 'bdPaygateFreeKassa_FailUrl';
        }

        $options = parent::getOptionsByIds($optionIds, $fetchOptions);

        self::$_bdPaygateFreeKassa_hijackOptions = false;

        return $options;
    }

    public function bdPaygateFreeKassa_hijackOptions()
    {
        self::$_bdPaygateFreeKassa_hijackOptions = true;
    }
}