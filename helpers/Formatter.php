<?php
namespace app\helpers;

class Formatter
{
    public static function asIndonesianDate($date)
    {
        $datePart = explode('-', $date);

        return $datePart[2] . ' ' . self::bulanIndonesia($datePart[1]) . ' ' . $datePart[0];
    }

    public static function asIndonesianDateWithDay($date)
    {
        $day = date('l', strtotime($date));
        $datePart = explode('-', $date);

        return self::hariIndonesia($day) . ', ' . $datePart[2] . ' ' . self::bulanIndonesia($datePart[1]) . ' ' . $datePart[0];
    }

    public static function asIndonesianMonth($date)
    {
        $datePart = explode('-', $date);

        return self::bulanIndonesia($datePart[1]) . ' ' . $datePart[0];
    }

    protected static function hariIndonesia($hariInggris)
    {
        switch ($hariInggris) {
            case 'Sunday':
                return 'Minggu';
            case 'Monday':
                return 'Senin';
            case 'Tuesday':
                return 'Selasa';
            case 'Wednesday':
                return 'Rabu';
            case 'Thursday':
                return 'Kamis';
            case 'Friday':
                return 'Jum\'at';
            case 'Saturday':
                return 'Sabtu';
            default:
                return 'Tidak Valid';
        }
    }

    protected static function bulanIndonesia($month) 
    {
        switch ($month) {
            case '01':
                return 'Januari';
            case '02':
                return 'Februari';
            case '03':
                return 'Maret';
            case '04':
                return 'April';
            case '05':
                return 'Mei';
            case '06':
                return 'Juni';
            case '07':
                return 'Juli';
            case '08':
                return 'Agustus';
            case '09':
                return 'September';
            case '10':
                return 'Oktober';
            case '11':
                return 'November';
            case '12':
                return 'Desember';
            default:
                return 'Tidak Valid';
        }
    }
}