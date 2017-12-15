<?php
class Curotec_OrderAlert_Model_Config_Unit
{
	public function toOptionArray()
    {
        return array(
            [
                'value' => 'minute',
                'label' => 'Minute',
            ],
            [
                'value' => 'hour',
                'label' => 'Hour',
            ],
            [
                'value' => 'day',
                'label' => 'Day',
            ],
            [
                'value' => 'month',
                'label' => 'Month',
            ],
            [
                'value' => 'year',
                'label' => 'Year',
            ],
        );
    }
}