<?php
namespace App\Service;


use App\Entity\Slot;

class SlotsFiller
{
    /**
     * @return Slot[]|\Generator
     */
    public function getToday(\DateTime $now, \DateTime $finishing, int $slotMinnutes)
    {
        $slotTime = $slotMinnutes * 60;
        $toGrid = $now->getTimestamp() % $slotTime;
        for ($i = floor($now->getTimestamp() - $toGrid); $i < $finishing->getTimestamp(); $i += $slotTime) {
            yield new Slot("", new \DateTime('@' . $i));
        }

        return [];
    }

    /**
     * @param Slot[]|\Generator $available
     * @param Slot[]|\Generator $used
     */
    public function merge($available, $used) {
        $byTime = [];
        foreach ($used as $reservation) {
            $byTime[self::keyByTime($reservation)] = $reservation;
        }

        foreach ($available as $slot) {
            $key = self::keyByTime($slot);
            if (array_key_exists($key, $byTime)) {
                yield $byTime[$key];
            } else {
                yield $slot;
            }
        }

        return [];
    }

    private static function keyByTime(Slot $slot): string {
        return $slot->getDate()->format('Y-m-d_H:i');
    }
}