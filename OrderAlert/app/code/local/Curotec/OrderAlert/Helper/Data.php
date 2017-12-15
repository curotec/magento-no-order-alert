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

	public function formatDiff($diff)
	{
		$str = '';
		if ($diff->y) {
			$str .= $diff->y . ' years, ';
		}
		if ($diff->m) {
			$str .= $diff->m . ' months, ';
		}
		if ($diff->d) {
			$str .= $diff->d . ' days, ';
		}
		if ($diff->h) {
			$str .= $diff->h . ' hours, ';
		}
		if ($diff->h) {
			$str .= $diff->i . ' minutes, ';
		}

		$str = rtrim(trim($str), ',');
		return $str;
	}
}
	 