<?php
namespace App\Service;

use DateTime;
use DatePeriod;
use DateInterval;
use DateTimeImmutable;

class Hours {


public function getAllSchoolHours (array $daysOff, \DateTimeImmutable $start, \DateTimeImmutable $end, object $stagesDates,object $examensDates,object $congeDates, int $totalSessionHours,int $min = 0,int $max = 0, ) 
{
    $totalDaysinSession = $this->getAllDayBeetweenTwoDates($start, $end);
    $seperatedDaysOff = $this->getSeperatedDaysOff($daysOff,$totalDaysinSession);
    $sessionDaysOff = $seperatedDaysOff[0];
    $sessionDaysWithWeekend = $seperatedDaysOff[1];
    $stageDays = $this->getDaysWithoutCours($stagesDates);
    $stageDaysWithoutWeekEnd = $this->getDaysWithoutWeekEnd($stageDays)[0];
    $examensDays = $this->getDaysWithoutCours($examensDates);
    $congeDays = $this->getDaysWithoutCours($congeDates);   
    $sessionWithOutWeekEnd = $this->getDaysWithoutWeekEnd($sessionDaysWithWeekend)[0];
    $onlySchoolDays = $this->getSchoolDays($sessionWithOutWeekEnd, $stageDaysWithoutWeekEnd, $examensDays, $congeDays );
    $result = $this->getHoursPerSchoolDays($totalSessionHours, $min, $max );
    $addDaysToHours = $this->getDaysWithHours($onlySchoolDays, $result);
    return $addDaysToHours;

}
/**
 * Retourne 2 tableau: [0] avec les dates sans les Samedis et Dimanches et [1] avec les weekends, depuis un tableau de dates.
 *
 * @param array $dates
 * @return array
 */
public function getDaysWithoutWeekEnd (array $dates): array {
    $datesWithoutWeekEnds = [];
    $weekends = [];
    foreach ($dates as $date ) {
        $tempDate = new DateTimeImmutable($date);
        $dateTime = new DateTimeImmutable($tempDate->format('Y-m-d'));
        if ( intval($dateTime->format('N')) === 6 || intval($dateTime->format('N')) === 7) {
            $weekends[] = $date;
        } else
        { 
        $datesWithoutWeekEnds[] = $date;
        }
    }
    return [$datesWithoutWeekEnds, $weekends];
}
/**
 * Récupère tous les jours entre 2 dates
 *
 * @param \DateTimeImmutable $start
 * @param \DateTimeImmutable $end
 * @return array
 */
private function getAllDayBeetweenTwoDates (\DateTimeImmutable $start, \DateTimeImmutable $end): array
{
    $allDays = [];
    $start = $start->format('Y-m-d');
    $tempEnd = $end->add(DateInterval::createFromDateString('1 day'));
    $end = $tempEnd->format('Y-m-d');

    $period = new DatePeriod(
        new DateTime($start),
        new DateInterval('P1D'),
        new DateTime($end),
   );
   foreach ($period as $i => $date) {
    $allDays[] = $date->format('d-m-Y');
   }
   return $allDays;
}
/**
 * Seperate bank Hollidays days and other days of session
 *
 * @param array $daysOff
 * @param array $totalDaysinSession
 * @return array
 */
public function getSeperatedDaysOff(array $daysOff, array $totalDaysinSession): array
{
    $allDaysOff = [];
    $allWorkingDays = [];
    foreach ($totalDaysinSession as $i => $date) {
        foreach ($daysOff as $k => $frenchDate){
            if ($date == $k) {
                $allDaysOff[] = $date;
            }
        }
        if (!in_array($date, $allDaysOff)){ 
            $allWorkingDays[] = $date;
        }
    }
    return [$allDaysOff, $allWorkingDays];
}
/**
 * Récupère les jours de stages, d'examens, de congés grâce aux dates de début et fin d'un évenement même si il comprends plusieurs dates différentes
 *
 * @param Object $dates
 * @return array
 */
public function getDaysWithoutCours (Object $dates): array {

    $days = [];
    foreach ($dates as $date ){
        $start =  $date->getStart();
        $end = $date->getEnd();
        $allDays = $this->getAllDayBeetweenTwoDates($start, $end);
        $days = array_merge($days, $allDays);
    }
 return $days;
}
/**
 * Retourne uniquement tous les jours de cours une fois les examens, les stages, et les congés enlevés.
 *
 * @param array $dates tous les jours sans les week-ends
 * @param array|null $stage tous les jours de stages sans les week-ends
 * @param array|null $examens tous les jours d'examens
 * @param [type] $conge tous les jours de congés
 * @return array
 */
public function getSchoolDays(array $dates, array $stage = null, array $examens = null, array $conge = null ): array {
   $schoolDays = [];
   $otherDays = [];
    for ($i = 0 ; $i < count($dates); $i++) {
        if (in_array($dates[$i], $stage)) {
            $otherDays [] = $dates[$i];
        } else if (in_array($dates[$i], $examens)) {
            $otherDays [] = $dates[$i];

        } else if (in_array($dates[$i], $conge)) {
            $otherDays [] = $dates[$i];
        } else if (in_array($dates[$i], $otherDays)) {
            $otherDays [] = $dates[$i];
        } 
    }
    foreach ($dates as $date) {
        if (!in_array($date, $otherDays)) {
            $schoolDays[]= $date;
        }
    }
    return $schoolDays;
}

public function getHoursPerSchoolDays($totalCoursHours,int $min ,int $max) {
    // TODO à changer pour que ce soit des variables !!
    $maxHoursPerDay = $max;
    $minHoursPerDay = $min;

    $total = $totalCoursHours;
    $minTotal = 0;
    $temp = [];
    $temp3 = [];


while ($total % $maxHoursPerDay  != 0) {
    $temp[] = $minHoursPerDay;
    $minTotal += $minHoursPerDay;
    $total -= $minHoursPerDay ;
 
}
        $temp2 = [];

    while ($total != 0){
        $temp2[] = $maxHoursPerDay ;
        $minTotal += $maxHoursPerDay ;
        $total -= $maxHoursPerDay ;
    }
    $temp3 = array_merge($temp3, $temp2);
    $temp3 = array_merge($temp3, $temp);
    return $temp3;
   ;
}

public function getDaysWithHours($days, $hours) {
    $hoursByDay= [];
    for ($i = 0; $i < count($hours) ; $i++) {
        $day = $days[$i];
        $hour = $hours[$i];
        $hoursByDay[$day] = $hour;
    }
    return $hoursByDay;
}
}