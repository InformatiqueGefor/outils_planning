<?php

namespace App\Controller;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Service\Hours;
use App\Entity\Session;
use App\Service\BankHolidays;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CalendarMonthController as CalendarMonthController;

class PlanningController extends AbstractController
{
    private $bankHolidays;
    public function __construct(BankHolidays $bankHolidays)
    {
        $this->bankHolidays = $bankHolidays;
    }

    #[Route('/planning/{id}', name: 'app_planning')]
    /**
     * Crée le planning pour une session et l'affiche depuis la date de départ et la date de fin
     *
     * @param Session $session
     * @param SessionRepository $sessionRepository
     * @param BankHolidays $bankHolidays
     * @return Response
     */
    public function yearPlanning(Session $session, SessionRepository $sessionRepository, Hours $hours): Response
    {
        $weekEnd = ['Samedi', 'Dimanche'];
        $session = $sessionRepository->find($session);
        $daysOff = $this->getDaysOff($session->getStart(), $session->getEnd());
        $stages = $session->getStages();
        $stageDays = $hours->getDaysWithoutCours($stages);
        $stagesDays = $hours->getDaysWithoutWeekEnd($stageDays)[0];
        // $stagesDays = $hours->getDaysWithoutWeekEnd($stages);
        $hours = $hours->getAllSchoolHours($daysOff, $session->getStart(), $session->getEnd(), $stages, $session->getExamens(), $session->getConges(), $session->getTheorieHours(), $session->getMinHoursPerDay(), $session->getMaxHoursPerDay());
        $examens = $session->getExamens();
        $examensDays = $this->getRandomDays($examens);
        $conges = $session->getConges();
        $congesDays = $this->getRandomDays($conges);

        $startYear    = new DateTime($session->getStart()->format('Y-m-d'));
        $startYear->modify('first day of this month');
        $endYears = $session->getEnd()->add(new DateInterval('P1D'));
        $endYear = new DateTime($endYears->format('Y-m-d'));
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($startYear, $interval, $endYear);

        $sessionMonths = [];
        foreach ($period as $date) {
            $month = new CalendarMonthController($date->format('m'), $date->format('Y'));
            $sessionMonths[] = $month;
        }
        $sessionMonth = $this->getAllMonthFromSession($sessionMonths);
        return $this->render('planning/planning.html.twig', [
            'session' => $session,
            'sessionMonth' => $sessionMonth,
            'weekend' => $weekEnd,
            'stagesDays' => $stagesDays,
            'daysOff' => $daysOff,
            'hours' => $hours,
            'examensDays' => $examensDays,
            'congesDays' => $congesDays

        ]);
    }
    /**
     * Récupère tous les mois et les jours de la session en demandée
     *
     * @param array $sessionMonths
     * @return array
     */
    private function getAllMonthFromSession(array $sessionMonths): array
    {
        $sessionMonth = [];
        foreach ($sessionMonths as $i => $month) {
            $sessionMonth[$i]['date'] = $month->getDate();
            // Récupération des jours
            $monthDays = $month->days;
            $sessionMonth[$i]['days'] = $monthDays;
            // Récupération du Mois en String
            $monthToString = $month->toString();
            $sessionMonth[$i]['mois'] = $monthToString;
            // Récupération du nombres de semaines pour chaque mois
            $weeks = $month->getWeeks();
            $sessionMonth[$i]['weeks'] = $weeks;
            // récupère le premier jour du mois
            $start = $month->getStartingDay();
            $sessionMonth[$i]['start'] = $start;
            // récupère le dernier jour du mois
            $end = $start->modify('+' . (6 + 7 * ($weeks - 1)) . ' days');
            $sessionMonth[$i]['end'] = $end;
            // je dois récupérer tous les jours de chaque mois
            $allDays = $month->getAllDaysInMonth($month->month, $month->year);
            $sessionMonth[$i]['allDays'] = $allDays;
            // dd($sessionMonth);
        }
        return $sessionMonth;
    }
    /**
     * Récpèrer les jours depuis les collections(stages, examens, congés,...)
     *
     * @param ArrayCollection [] 
     * @return array
     */
    private function getRandomDays($collections): array
    {
        // Récupérer la date de début et de fin de la période de stage.
        $days = [];
        foreach ($collections as $i => $collection) {
            $start = $collection->getStart();
            $end = $collection->getEnd()->add(new DateInterval('P1D'));
            $interval = DateInterval::createFromDateString('1 day');
            $period   = new DatePeriod($start, $interval, $end);
            foreach ($period as $date) {
                $month = new CalendarMonthController($date->format('m'), $date->format('Y'));

                $days[] = $date->format('d') . ' ' . $month->toString();
            }
        }
        return $days;
    }

    public function getDaysOff($start, $end): array
    {
        $daysOff = [];
        $startbankholidays = $this->bankHolidays->getDaysAndNames($start->format('Y'));
        $daysOff = array_merge($daysOff, $startbankholidays);
        $endBankHolidays = $this->bankHolidays->getDaysAndNames($end->format('Y'));
        $daysOff = array_merge($daysOff, $endBankHolidays);

        foreach ($daysOff as $i => $dayOff) {
            $day = new DateTime($i);
            $dayNumber = $day->format('N');
            if ($dayNumber === "2") {
                $off = $day->add(DateInterval::createFromDateString('-1 day'));
                $daysOff = array_merge($daysOff, array(
                    $off->format('d-m-Y') => 'Pont',
                ));
            } else if ($dayNumber === "4") {
                $off = $day->add(DateInterval::createFromDateString('1 day'));
                $daysOff = array_merge($daysOff, array(
                    $off->format('d-m-Y') => 'Pont',
                ));
            }
        }
        // TODO voir pour avoir les dates dans l'ordre
        return $daysOff;
    }
    public function getExamensDays($examens): array
    {
        $examensDays = [];
        foreach ($examens as $i => $examen) {
            $start = $examen->getStart();
            $end = $examen->getEnd()->add(new DateInterval('P1D'));
            $interval = DateInterval::createFromDateString('1 day');
            $period   = new DatePeriod($start, $interval, $end);
            foreach ($period as $date) {
                $month = new CalendarMonthController($date->format('m'), $date->format('Y'));

                $examensDays[] = $date->format('d') . ' ' . $month->toString();
            }
        }
        return $examensDays;
    }
}
