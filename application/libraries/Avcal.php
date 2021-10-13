<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');
/*
 * This Library to add some tools customized for The work
 * Made to extend the core as Version one additional toolset
 * PHP Version 7.3
 * Auther Gebriel Alkhayal
 * Calendar Version 1.0
 */
class Avcal
{
    private $cal;
    private $providerid;
    public $periods;
    private $periodsBaseAvCal;
    public $periodsMonthAvCal;
    public $RefRoomAvDates;
    public $PeriodsMonths; //generated
    private $AllPeriodsIntervals; //generated
    public $rooms;
    public $ToDay;
    public $reservations;

    public function __construct()
    {
        $this->cal = &get_instance();
        $this->cal->load->model('Calav_model', 'av');
        $this->ToDay = date('Y-m-d');
    }

    public function _init($providerid, $target_month = null)
    {
        $this->providerid = $providerid;
        $this->target_month_num = $target_month ?? $this->tdates('m', 'now');
        $this->target_month_name = date("F", mktime(0, 0, 0, $this->target_month_num, 10));
        $this->hotel = $this->cal->av->getHotel($providerid);
        $this->periods = $this->cal->av->getperiods($providerid);
        $this->rooms = $this->cal->av->hotel_rooms($providerid);
        $this->periodsBase = $this->cal->av->BaseRoomAvDates($providerid);
        $this->reservations = $this->cal->av->reservations($providerid);
        $this->AllPeriodsIntervals();
        $this->periodsBaseAvCal = [];
        if ($this->periodsBase) {
            foreach ($this->periodsBase as $base_av) {
                $base_av->SubDates = $this->cal->av->RefRoomtype($this->providerid, $base_av->R_Type_ID);
                $this->periodsBaseAvCal[] = $base_av;
            }
        }
    }

    public function tdates($kind, $date)
    {
        $raw = new DateTime($date);
        return $raw->format($kind);
    }
    public function diff_dates($date1, $date2): bool
    {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        return ($date1 > $date2) ? true : false;
    }

    public function date_jump($date1, $date2)
    {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        return ($date1 > $date2) ? true : false;
    }

    public function month_range($type = 'num')
    {
        $month = date("Y-m-d", mktime(0, 0, 0, $this->target_month_num, 01));
        $current = new DateTime($month);
        return date_range($month, $current->format('Y-m-t'));
    }

    public function next_month($type = 'num')
    {
        $month = date("Y-m-d", mktime(0, 0, 0, $this->target_month_num, 10));
        $current = new DateTime($month);
        $current->modify('next month');
        if ($type == 'num') {
            return $current->format('m');
        } else {
            return $current->format('F');
        }
    }

    public function prev_month($type = 'num')
    {
        $month = date("Y-m-d", mktime(0, 0, 0, $this->target_month_num, 10));
        $current = new DateTime($month);
        $current->modify('-1 month');
        if ($type == 'num') {
            return $current->format('m');
        } else {
            return $current->format('F');
        }
    }

    public function date_iterate($type, $date1, $date2)
    {
        $next = @date('Y-M-01', $current) . "+1 month";
        $iterator = date('Y-m-01', strtotime("$idata->ResDate +" . (int) $rs_timeout . " DAYS"));
        $iterator = date('Y-m-01', strtotime("$idata->ResDate +" . (int) $rs_timeout . " DAYS"));
        $iterator = date('Y-m-01', strtotime("$idata->ResDate +" . (int) $rs_timeout . " DAYS"));
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        echo $date1->format('Y-M-D');
        while ($date1 < $date2) {
            $date1->modify('next month');
            echo "<br>";
            echo $date1->format('F');
        }
    }

    public function filter_dates($dateset)
    {
        return array_filter(
            $dateset,
            function ($date) {
                return ($this->tdates('m', $date) == $this->target_month_num);
            }
        );
    }

    public function filter_dates_key($dateset)
    {
        return array_filter(
            $dateset,
            function ($date) {
                return ($this->tdates('m', $date) == $this->target_month_num);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    public function linkMonth($date)
    {
        return base_url("chotel/hotel_manage/room_availability/$this->providerid/") . $this->tdates('m', $date);
    }

    public function avlink()
    {
        return base_url("chotel/hotel_manage/room_availability/$this->providerid/");
    }

    public function lastDay($date)
    {
        return (int) days_in_month($this->tdates('m', $date), $this->tdates('Y', $date));
    }

    public function resfill()
    {
        //print_r($this->reservations);
        $filtered = [];
        foreach ($this->reservations as $res) {

            $resdates = date_range(date('Y-m-d', strtotime($res['start'])), date('Y-m-d', strtotime($res['end'])));
            if (!empty($this->filter_dates($resdates))) {
                $filtered[$res['type']]['refid'] = $res['refid'];
                $filtered[$res['type']]['count'] = $res['count'];
                $filtered[$res['type']]['dates'] = $this->filter_dates($resdates);
            }
            // if(!empty($this->filter_dates($resdates))){
            //     $fil[] = $filtered;
            // }
        }
        //print_r($fil);
        return $filtered;
    }

    public function gen()
    {
        $next = base_url("chotel/hotel_manage/room_availability/$this->providerid/") . $this->next_month('num');
        $prev = base_url("chotel/hotel_manage/room_availability/$this->providerid/") . $this->prev_month('num');
        $lacks = $this->periodsLacks();
        foreach ($this->AllPeriodsIntervals as $period) {
            if ($this->tdates('m', $period) == $this->target_month_num) {

                $table_begin = "<div class=''>";
                $table_begin .= "<a href='$next' class='btn btn-success' style='float:right'>" . $this->next_month('name') . '</a>';
                $table_begin .= "<a href='$prev' class='btn btn-warning' > " . $this->prev_month('name') . '</a>';
                $table_begin .= '</div>' . '<br>';

                $table_begin .= '<table class="table-striped table-bordered p-5">';
                $table_begin .= '<h3 class="text-center">Your Availability For ' . $this->tdates('F-Y', $period) . '  </h3> <thead>';
                $Table_head = ' <tr> <td> days </td> ';
                $theads = (int) days_in_month($this->tdates('m', $period), $this->tdates('Y', $period));
                $startHeads = 1;
                $th = '';
                for ($i = $startHeads; $i <= $theads; $i++) {
                    $th .= '<td>';
                    $th .= (strlen($i) == 1) ? '0' . $i : $i;
                    $th .= '</td>';
                }
                $Table_head .= $th . '</tr></thead><tbody>';
                echo $table_begin . $Table_head;
                $table_body = '';
                $baseRange = $this->month_range();
                foreach ($this->_BaseSets() as $periodsBase) {
                    $Alldates = $this->filter_dates_key($periodsBase->Dates);
                    $r = isset($this->resfill()[$periodsBase->R_Type_Sn]) ? 2 : '';
                    $table_body .= "<tr><td rowspan=$r>" . $periodsBase->R_Type_Sn . "</td>";
                    foreach ($baseRange as $base) {
                        $found = isset($Alldates[$base]);
                        if ($found) {
                            $table_body .= "<td>";
                            $table_body .= ($Alldates[$base] == 0) ? '<span style="color:red;padding:5px">X</span>' : '<span style="color:#098009">' . $Alldates[$base] . '</span>';
                            $table_body .= "</td>";
                        } else {
                            $table_body .= "<td style='background-color:#b53636;padding:15px'> -E- </td>";
                        }
                    }
                    $table_body .= "</tr>";
                    //show_error(var_dump($this->resfill()));
                    //reservations
                    if (isset($this->resfill()[$periodsBase->R_Type_Sn])) {
                        $table_body .= "<tr>";
                        //show_error(print_r($this->resfill()[$periodsBase->R_Type_Sn]['dates']));
                        $pre = (int) $this->tdates('d', $this->resfill()[$periodsBase->R_Type_Sn]['dates'][0]) - 1;
                        $mindate = $Alldates[$this->resfill()[$periodsBase->R_Type_Sn]['dates'][0]];
                        $count = (int) $this->resfill()[$periodsBase->R_Type_Sn]['count'];
                        $countdist = count($this->resfill()[$periodsBase->R_Type_Sn]['dates']);
                        for ($i = 0; $i < $pre; $i++) {
                            $table_body .= "<td> </td>";
                        }
                        $left = (int)$mindate - $count;
                        $table_body .= "<td class='text-center' colspan=$countdist style='background-color:#b53636;'> $left </td>";

                        $table_body .= "</tr>";
                    }
                    // $table_body .= "<tr><td style='background-color:#b53636;padding:15px'> -E- </td></tr>";
                    // $table_body .= "<tr><td style='background-color:#b53636;padding:15px'> -E- </td></tr>";
                }
                $table_body .= '</tbody></table></div>';
                echo $table_body;
            }
        }
    }

    public function _BaseSets()
    {
        $allBaseSets = [];
        foreach ($this->periodsBase as $BaseSet) {
            $base_range = array_fill_keys(date_range($BaseSet->StartDate, $BaseSet->EndDate), $BaseSet->Available_Count);
            foreach ($BaseSet->SubDates as $subSet) {
                $subsetRange = date_range($subSet->StartDate, $subSet->EndDate);
                $sub_range = array_fill_keys($subsetRange, $subSet->Available_Count);
                $base_range = array_replace($base_range, $sub_range);
            }
            $found = isset($allBaseSets[$BaseSet->R_Type_Sn]);
            if (!$found) {
                uksort($base_range, function ($date1, $date2) {
                    if ($date1 == $date2) {
                        return 0;
                    }
                    return ($date1 < $date2) ? -1 : 1;
                });
                $BaseSet->Dates = $base_range;
                $allBaseSets[$BaseSet->R_Type_Sn] = $BaseSet;
            } else {
                uksort($base_range, function ($date1, $date2) {
                    if ($date1 == $date2) {
                        return 0;
                    }
                    return ($date1 < $date2) ? -1 : 1;
                });
                $BaseSet->Dates = array_replace($allBaseSets[$BaseSet->R_Type_Sn]->Dates, $base_range);
                $allBaseSets[$BaseSet->R_Type_Sn] = $BaseSet;
            }
        }
        return $allBaseSets;
    }

    public function PeriodsMonths()
    {
        $pCal = [];
        foreach ($this->periods as $period) {
            $set = [];
            $pdata = $this->HF($period->dateFrom, $period->dateTo);
            $set['periodName'] = $period->periodName;
            $set['namedMonths'] = $pdata->M_Range_Names;
            $set['MonthsNums'] = $pdata->M_Range_Nums;
            $set['FullDate'] = $pdata->M_Range_full;
            $pCal[] = $set;
        }
        $this->PeriodsMonths = $pCal;
        return $pCal;
    }

    public function periodsLacks()
    {
        $set = [];
        foreach ($this->periods as $period) {
            $current = current($this->periods);
            $next = next($this->periods);
            $lack = [];
            if ($current && $next) {
                $lack = date_range($current->dateTo, $next->dateFrom);
            }
            if ($lack) {
                $set = array_merge($set, array_slice($lack, 1, -1));
            };
        }
        return $set;
    }

    public function RoomAvRefs()
    {
        $set = [];
        foreach ($this->periods as $period) {
            $current = current($this->periods);
            $next = next($this->periods);
            $lack = [];
            if ($current && $next) {
                $lack = date_range($current->dateTo, $next->dateFrom);
            }
            if ($lack) {
                $set = array_merge($set, array_slice($lack, 1, -1));
            };
        }
        return $set;
    }

    public function AllPeriodsIntervals()
    {
        $pCal = [];
        foreach ($this->periods as $period) {
            $pdata = $this->HF($period->dateFrom, $period->dateTo);
            $pCal = array_merge($pCal, $pdata->M_Range_full);
        }
        $this->AllPeriodsIntervals = array_unique($pCal);
        return $this->AllPeriodsIntervals;
    }

    public function HF($date1 = null, $date2 = null)
    {
        $Cal = new stdClass();
        $Cal->M_Range_Names = array();
        $Cal->M_Range_Nums = array();
        $Cal->M_Range_full = array();
        if ($date1 != null) {
            $date1 = new DateTime($date1);
        } else {
            $date1 = new DateTime('now');
        }
        if ($date2 != null) {
            $date2 = new DateTime($date2);
        } else {
            $date2 = new DateTime('now');
        }
        while ($date1->format('m') <= $date2->format('m')) {
            $Cal->M_Range_Nums[] = $date1->format('m');
            $Cal->M_Range_Names[] = $date1->format('F');
            $Cal->M_Range_full[] = $date1->format('Y-m');
            $date1->modify('next month');
        }
        return $Cal;
    }
}
