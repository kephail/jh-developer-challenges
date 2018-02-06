<?php

namespace Jh\Shipping;

/**
 * Class ShippingDates
 * @package Jh\Shipping
 */
class ShippingDatesAdvanced implements ShippingDatesInterface
{
    const SATURDAY = 6;
    const SUNDAY = 0;
    const DAYS_TO_DELIVER = 3;
    const CUTOFF_HOUR = 17;
    const CUTOFF_MINUTES = 0;

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
     * Check if the order was made after 5pm, or if the order was made on a weekend.
     *
     * @param \DateTime $orderDate
     * @return \DateTime
     */
    public function calculateDispatchDate(\DateTime $orderDate): \DateTime
    {
        $tempDate = clone $orderDate;
        $cutoff = clone $orderDate;
        $cutoff->setTime(self::CUTOFF_HOUR, self::CUTOFF_MINUTES, 0);

        if ($tempDate >= $cutoff) {
            $tempDate->add(new \DateInterval('P1D'));
        }

        $day = $tempDate->format('w');
        if ($day == self::SATURDAY) {
            $tempDate->add(new \DateInterval('P2D'));
        } elseif ($day == self::SUNDAY) {
            $tempDate->add(new \DateInterval('P1D'));
        }

        $dispatchDate = $tempDate;
        return $dispatchDate;
    }
}
