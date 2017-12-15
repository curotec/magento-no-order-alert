<?php
class Curotec_OrderAlert_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isEnabled()
	{
		return (bool) Mage::getStoreConfig('order_alert_settings/general/enabled');
	}

	public function getConfig($key)
	{
		return Mage::getStoreConfig("order_alert_settings/general/{$key}");
	}
}
	 