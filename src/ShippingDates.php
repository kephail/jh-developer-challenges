<?php

namespace Jh\Shipping;

/**
 * Class ShippingDates
 * @package Jh\Shipping
 */
class ShippingDates implements ShippingDatesInterface
{

    const SATURDAY = 6;
    const SUNDAY = 0;
    const DAYS_TO_DELIVER = 3;

    /**
     * Calculate Delivery date
     *
     * @param  \DateTime $orderDate
     * @return \DateTime
     */
    public function calculateDeliveryDate(\DateTime $orderDate): \DateTime
    {
        $dispatchDate = self::calculateDispatchDate($orderDate);
        $tempDate = clone $dispatchDate;

        $workdays = 0;
        while ($workdays < self::DAYS_TO_DELIVER) {
            $tempDate->add(new \DateInterval('P1D'));
            $day = $tempDate->format('w');

            if ($day != self::SATURDAY && $day != self::SUNDAY) {
                $workdays++;
            }
        }

        $deliveryDate = $tempDate;
        return $deliveryDate;
    }

    /**
     * Check if the order was made on a weekend.
     *
     * @param \DateTime $orderDate
     * @return \DateTime
     */
    public function calculateDispatchDate(\DateTime $orderDate): \DateTime
    {
        $tempDate = clone $orderDate;

        $day = $tempDate->format('w');
        if ($day == self::SATURDAY) {
            $tempDate->add(new \DateInterval('P2D'));
        } else if ($day == self::SUNDAY) {
            $tempDate->add(new \DateInterval('P1D'));
        }

        $dispatchDate = $tempDate;
        return $dispatchDate;
    }
}
