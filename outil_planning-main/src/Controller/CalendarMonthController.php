<?php 

namespace App\Controller;



class CalendarMonthController {
    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    public $months = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    public $month;
    public $year;
    /**
     * Month Constructor.
     *
     * @param integer $month le mois compris entre 1 et 12
     * @param integer $year L'année
     * @throws Exception
     */
    public function __construct(?int $month = null, ?int $year=null)
    {
        if ($month === null || $month < 1 || $month > 12) {
            $month = intval(date('m'));
        }
        if ($year === null) {
            $year = intval(date('Y'));
        }
        $this->month = $month;
        $this->year = $year;
    }
    /**
     * Renvoie la date du premier jour du mois
     *
     * @return \DateTimeImmutable
     */
    public function getStartingDay (): \DateTimeImmutable {
        return new \DateTimeImmutable("{$this->year}-{$this->month}-01") ;
    }
    /**
     * Renvoie le mois en toutes lettres (ec:Mars 2023)
     * @return string
     */
    public function toString () : string {
        $monthToString =  $this->months[$this->month -1]. " " . $this->year ;
       return  $monthToString;
    }
    /**
     * Renvoie le nombre de semaines du mois
     *
     * @return integer
     */
    public function getWeeks (): int  {
        $start = $this->getStartingDay();
        // clone la date start mais ne la modifie pas
        $end =  $start->modify('+1 month -1 day');
        // récupération du nombre de semaine du mois
        $startWeek = intval($start->format('W'));
        $endWeek = intval($end->format('W'));
        if ($endWeek === 1) {
            $endWeek = intval( $end->modify('-7 days')->format('W')) + 1;
        }
        $weeks = $endWeek - $startWeek +1;
        // gestion du cas où le début du mois de janvier est un mercredi du coup on renvoi une valeur négative (ex: -47 semaine en 2017)
        if ($weeks < 0 ) {
            $weeks = intval($end->format('W'));
        }
        return $weeks;
    }
    /**
     * Est-ce que le jour est dans le mois en cours
     *
     * @param \DateTimeImmutable $date
     * @return boolean
     */
    public function withinMonth (\DateTimeImmutable $date): bool {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }
    /**
     * Renvoi le mois suivant
     *
     * @return MonthController
     */
    public function nextMonth () : CalendarMonthController
    {
        $month = $this->month +1;
        $year = $this->year;
        if ($month > 12) {
            $month = 1;
            $year += 1;
        }
        return new CalendarMonthController($month, $year);
    }
    /**
     * Renvoie le mois précédent
     *
     * @return CalendarMonthController
     */
    public function previousMonth () : CalendarMonthController
    {
        $month = $this->month -1;
        $year = $this->year;
        if ($month < 1) {
            $month = 12;
            $year -= 1;
        }
        return new CalendarMonthController($month, $year);
    }
    /**
     * Retourne tous les jours dans un mois
     *
     * @param integer $month
     * @param integer $year
     * @return void
     */
    public function getAllDaysInMonth(int $month, int $year) {
         return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }
    public function getDate() {
        $date = '-'. (($this->month < 10 ) ? '0' : ''). $this->month.'-'.$this->year;
        return $date;
    }
}