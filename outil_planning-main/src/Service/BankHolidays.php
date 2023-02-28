<?php

namespace App\Service;

use DateTime;
use DateInterval;

class BankHolidays {


    public static function isHoliday($day=false, $alsacemoselle=false){
        if(!$day) $day = date('Y-m-d');
        // Validation de la date
        $dt = DateTime::createFromFormat('Y-m-d', $day);
        if(!$dt || $dt->format('Y-m-d')!=$day){
            trigger_error('Date invalide', E_USER_WARNING);
            return false;
        }
        $year = date("Y", strtotime($day));
        $days = self::getDays($year, $alsacemoselle);
        if(in_array($day, $days)) return true;
        return false;
    }
 
    public static function getDaysAndNames($year=false, $alsacemoselle=false){
        if(!$year) $year = date('Y');
        if(!is_numeric($year)){
            trigger_error('Année invalide', E_USER_WARNING);
            return false;
        }
 
        // Etape 1 : religion (écrasé par les date laïques)
        $days = array(
            self::dimanchePaques($year) => "Pâques",
            self::lundiPaques($year) => "Lundi de Pâques",
            self::jeudiAscension($year) => "Ascension",
            self::lundiPentecote($year) => "Pentecôte",
            '15-08-'.$year => "Assomption",
            '01-11-'.$year => "Toussaint",
            '25-12-'.$year => "Noël",
        );
 
        // Etape 1 bis : dates religieuses supplémentaires Alsace-Moselle
        if($alsacemoselle){
            $days = array_merge($days, array(
                $year.'-12-26' => "Saint-Etienne",
                self::vendrediSaint($year) => "Vendredi Saint"
            ));
        }
 
        // Etape 2 : dates laïques
        $days = array_merge($days, array(
            '01-01-'.$year => "Jour de l'an",
            '01-05-'.$year => "Fête du travail",
            '08-05-'.$year => "Armistice",
            '14-07-'.$year => "Fête nationale",
            '11-11-'.$year => "Armistice",
        ));
        
        ksort($days);
        return $days;
    }
 
    public static function getDays($year=false, $alsacemoselle=false){
        $daysnames = self::getDaysAndNames($year, $alsacemoselle);
        return (is_array($daysnames)) ? array_keys($daysnames) : false;
    }
 
    ///////
 
    private static function dimanchePaques($year){
        $base = new DateTime("$year-03-21");
        $days = easter_days($year);
        $date = $base->add(new DateInterval("P{$days}D"));
        return $date->format("d-m-Y");
        // return date("Y-m-d", easter_date($year));
    }
 
    private static function vendrediSaint($year){
        $dimanche_paques = self::dimanchePaques($year);
        return date("d-m-Y", strtotime("$dimanche_paques -2 days"));
    }
 
    private static function lundiPaques($year){
        $dimanche_paques = self::dimanchePaques($year);
        return date("d-m-Y", strtotime("$dimanche_paques +1 day"));
    }
 
    private static function jeudiAscension($year){
        $dimanche_paques = self::dimanchePaques($year);
        return date("d-m-Y", strtotime("$dimanche_paques +39 days"));
    }
 
    private static function lundiPentecote($year){
        $dimanche_paques = self::dimanchePaques($year);
        return date("d-m-Y", strtotime("$dimanche_paques +50 days"));
    }
        
  }

