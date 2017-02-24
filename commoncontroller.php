<?php

/**
 * Created by PhpStorm.
 * User: Genesis
 * Date: 2/21/2017
 * Time: 5:00 PM
 */
class CommonController
{
    public  $month_array = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");

    function statesOptions($state)
    {
        $state_array = array(
            "AL", "AK", "AZ", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN",
            "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH",
            "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT",
            "VT", "VA", "WA", "WV", "WI", "WY");

        $str = "<option ";
        if (empty($state)) {
            $str .= " selected ";
        }
        $str .= "disabled> State </option>";

        for ($i = 0; $i <= count($state_array)-1; $i++) {
            $str .= "<option ";
            if ($state === $state_array[$i]) {
                $str .= " selected ";
            }
            $str .= " value='" . $state_array[$i] . "'>" . $state_array[$i] . "</option>\n";
        }

        echo $str;

    }

    function getMonthNumeric($month){

        return (array_search($month, $this->month_array));
    }

    function monthsOptions($month)
    {

        $str = "<option ";
        if (empty($month)) {
            $str .= " selected ";
        }
        $str .= "disabled> - Month - </option>";

        for ($i = 0; $i < count($this->month_array); $i++) {
            $str .= "<option ";
            if ($month == $i) {
                $str .= " selected ";
            }
            $str .= " value='" . $this->month_array[$i] . "'>" . $this->month_array[$i] . "</option>\n";
        }

        echo $str;

    }

    function daysOptions($day)
    {
        $str = "<option ";

        if (empty($day)) {
            $str .= " selected ";
        }
        $str .= "disabled> - Day - </option>";

        for ($i = 1; $i <= 30; $i++) {
            $str .= "<option ";
            if ($day == $i) {
                $str .= " selected ";
            }
            $str .= " value='" . $i . "'>" . $i . "</option>\n";
        }

        echo $str;


    }

    function yearsOptions($year)
    {
        $str = "<option ";

        if (empty($year)) {
            $str .= " selected ";
        }
        $str .= "disabled> - Year - </option>";

        for ($i = 2017; $i >= 1900; $i--) {
            $str .= "<option ";
            if ($year == $i) {
                $str .= " selected ";
            }
            $str .= " value='" . $i . "'>" . $i . "</option>\n";
        }

        echo $str;


    }

    function feetOptions($feet)
    {
        $str = "<option ";

        if (empty($feet)) {
            $str .= " selected ";
        }
        $str .= "disabled> - Feet - </option>";

        for ($i = 1; $i <= 8; $i++) {
            $str .= "<option ";
            if ($feet == $i) {
                $str .= " selected ";
            }
            $str .= " value='" . $i . "'>" . $i . "</option>\n";
        }

        echo $str;

    }

    function inchesOptions($inches)
    {

        $str = "<option ";

        if (empty($inches)) {
            $str .= " selected ";
        }
        $str .= "disabled> - Inches - </option>";

        for ($i = 1; $i <= 11; $i++) {
            $str .= "<option ";
            if ($inches == $i) {
                $str .= " selected ";
            }
            $str .= " value='" . $i . "'>" . $i . "</option>\n";
        }

        echo $str;

    }

}