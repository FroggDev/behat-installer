<?php
namespace froggdev\BehatContexts\Util;

trait DateTrait
{
    /**
     * @param int $numOfMonth
     * @param \DateTime|null $now
     * @return \DateTime
     */
    public function getNextYearSpecificMonth(int $numOfMonth , ?\DateTime $now ) : \DateTime
    {
        // Get current date
        $now = $now ?? new \DateTime('now');
        // Get current month
        $currentMonth = $now->format('m');
        // Get num of month unitil next year specific month
        $modifier = $currentMonth <= $numOfMonth ? 12+$numOfMonth-$currentMonth : 12-$currentMonth+$numOfMonth;

        // Keep same year if <= $numOfMonth (special request)
        $modifier = $currentMonth <= $numOfMonth ? $numOfMonth-$currentMonth : 12-$currentMonth+$numOfMonth;

        // Return current date with added num of month until next specific month of next year
        return $now->modify('+'.$modifier.' month');
    }
}