<?php

class bdPaygateFreeKassa_XenForo_ControllerAdmin_UserUpgrade extends XFCP_bdPaygateFreeKassa_XenForo_ControllerAdmin_UserUpgrade
{
    public function actionIndex()
    {
        $optionModel = $this->getModelFromCache('XenForo_Model_Option');
        $optionModel->bdPaygateFreeKassa_hijackOptions();

        return parent::actionIndex();
    }
}