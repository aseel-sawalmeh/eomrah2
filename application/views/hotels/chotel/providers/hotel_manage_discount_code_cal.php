
<div class="card">
    <div class="card-header">
        <h2 class="text-center">Discount Codes Calendar</h2>
    </div>

    <div class="card-body">
        <?php
        function gebdates($kind, $gdate)
        {
            $raw = new DateTime($gdate);
            return $raw->format($kind);
        }
        $cell = '';
        ?>
        <?php if ($discountmonthsava) : foreach ($discountmonthsava as $mava) : ?>

                <table class="table table-striped">
                    <h3 class="text-center">Your Availability to <?= $mava->tmonth . " " . $mava->ryear ?></h3>
                    <thead>
                        <tr>
                            <td>
                                Code / Days
                            </td>
                            <?php for ($i = 1; $i <= days_in_month($mava->nMonth, $mava->ryear); $i++) : ?>

                                <td>
                                    <?php if (strlen($i) == 1) {
                                        $cell = '0' . $i;
                                        echo $cell;
                                    } else {
                                        $cell = $i;
                                        echo $cell;
                                    }
                                    ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($discountdatesava as $dateava) {
                            echo "<tr><td>";
                            echo $dateava->discountCode;
                            echo "</td>";
                            $drange = date_range($dateava->StartDate, $dateava->EndDate);
                            $alldays = days_in_month($mava->nMonth, $mava->ryear);
                            if (gebdates('m', $drange[0]) == $mava->nMonth) {
                                $diffstart = intval(gebdates('d', $drange[0]));
                                for ($i = 0; $i < $diffstart - 1; $i++) {
                                    echo '<td style="background-color:#eee">-</td>';
                                }
                            }
                            foreach ($drange as $cdate) {
                                if (gebdates('m', $cdate) == $mava->nMonth) {
                                    echo '<td style="background-color:lightgreen;color:blue">' . gebdates('d', $cdate) . '</td>';
                                }
                            }
                        }

                        echo '</tr>';
                        ?>
                    </tbody>
                </table>
        <?php endforeach;
        endif; ?>
    </div>

</div>
</div>