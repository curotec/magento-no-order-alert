<?php
class Curotec_OrderAlert_Model_Cron
{
	public function checkOrders()
	{
	    Mage::log('Cron started', null, 'orderalert.log');
		$helper = Mage::helper('orderalert');
		if (!$helper->isEnabled()) {
			return;
		}

		$unit = $helper->getConfig('unit');
		$interval = $helper->getConfig('interval');
		$emails_to = $helper->getConfig('emails_to');

		if (empty($unit) || empty($interval) || empty($emails_to)) {
			return;
		}
		if (strlen($emails_to)) {
			$emails_to = explode(',', $emails_to);
		}
		if (!count($emails_to)) {
			return;
		}

		$order = Mage::getResourceModel('sales/order_collection')
		    ->addFieldToSelect('*')
		    ->addAttributeToSort('created_at', 'DESC')
		    ->setPageSize(1)
		    ->getFirstItem();

		$created_at = $order->getData('created_at');
		$created_at = Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime($created_at));
		$current = Mage::getModel('core/date')->date('Y-m-d H:i:s');

		$start = date_create($created_at);
		$end = date_create($current);
		$diff = date_diff( $start, $end );
		// echo '<br><br>The difference is ';
		// echo  $diff->y . ' years, ';
		// echo  $diff->m . ' months, ';
		// echo  $diff->d . ' days, ';
		// echo  $diff->h . ' hours, ';
		// echo  $diff->i . ' minutes, ';
		// echo  $diff->s . ' seconds';

		$order_missed = false;
		$missed_interval = 0;
		if ($unit == 'minute') {
			if ($interval <= $diff->i) {
				$order_missed = true;
				$missed_interval = $diff->i;
			}
		} else if ($unit == 'hour') {
			if ($interval <= $diff->h) {
				$order_missed = true;
				$missed_interval = $diff->h;
			}
		} else if ($unit == 'day') {
			if ($interval <= $diff->d) {
				$order_missed = true;
				$missed_interval = $diff->d;
			}
		} else if ($unit == 'month') {
			if ($interval <= $diff->m) {
				$order_missed = true;
				$missed_interval = $diff->m;
			}
		} else if ($unit == 'year') {
			if ($interval <= $diff->format('%y')) {
				$order_missed = true;
				$missed_interval = $diff->format('%y');
			}
		}

		if ($order_missed && $missed_interval > 0) {
			$text = "It has been {$missed_interval} {$unit}s since last order was placed on your Magento store.";
			$text .= "<br>Last order was placed on: {$created_at}";
		    Mage::log($text, null, 'orderalert.log');
			$this->sendMail($text, $emails_to);
		}
	}

	private function sendMail($html, $emails_to)
	{
		$body = 'Dear Admin,<br><br>' . $html;
		$body .= '<br><br>Thanks';

		$senderName = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
		$senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');

		$mail = Mage::getModel('core/email');
		$mail->setToName('Admin');
		$mail->setToEmail($emails_to);
		$mail->setBody($body);
		$mail->setSubject(Mage::helper("orderalert")->__("Low Order Alert"));
		$mail->setFromEmail($senderEmail);
		$mail->setFromName($senderName);
		$mail->setType('html');

		try {
		    if (! $mail->send()) {
		        throw new Exception ();
		    }
		}
		catch(Exception $ex){
		    Mage::log($ex->getMessage(), null, 'orderalert.log');
		}		
	}
}