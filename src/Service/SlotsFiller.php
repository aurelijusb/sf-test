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
            yield new Slot("Žmogus", new \DateTime('@' . $i));
        }

        return [];
    }
}