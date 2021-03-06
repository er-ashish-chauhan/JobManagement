<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center;">Bargain's Report</h2>
    <div class="row" style="">
        <?php echo $filterValues['title'] != "" ? "<div class='col-sm-6 col-xs-12' style='align-self: center;'><h4>" . $filterValues['title'] . "</h4></div>" : ""; ?>
        <?php echo $filterValues['startDate'] != "" && $filterValues['endDate'] != "" ?
            "<div class='col-sm-6 col-xs-12'><h4>" . $filterValues['startDate'] . " - " . $filterValues['endDate'] . "</h4></div>" : ""; ?>
        <?php echo $filterValues['startDate'] == "" && $filterValues['endDate'] != "" ?
            "<div class='col-sm-6 col-xs-12'><h4>Result for: - " . $filterValues['endDate'] . "</h4></div>" : ""; ?>
    </div>
    <table>
        <tr>
            <th colspan="1">#</th>
            <th colspan="8">Bargain's</th>
        </tr>
        <?php
        if ($entries) {
            $j = 1;
            foreach ($entries as $value) {
                echo "
                    <tr>
                        <td>" . $j . "</td>
                        <td colspan='8'>" . $value["bargain"]->BargainDetaiils . "</td>
                       </tr>" ?>
            <?php
                if ($value["entries"]) {
                    echo "<tr>
                        <th>#</th>
                        <th>Entry Date</th>
                        <th>Inward No</th>
                        <th>Truck No</th>
                        <th>Quantity (qts)</th>
                        <th>Quantity (bags)</th>
                        <th>Party</th>
                        <th>Party Location</th>
                        <th>Firm</th>
                    </tr>";
                    $i = 1;
                    foreach ($value["entries"] as $list) {
                        echo "<tr><td>" . $i . "</td>
                                <td>" . $list->EntryDate . "</td>
                                <td>" . $list->kantaSlipNo . "</td>
                                <td>" . $list->TruckNo . "</td>
                                <td>" . $list->Quantity_in_qts . "</td>
                                <td>" . $list->Quantity_in_bags . "</td>
                                <td>" . $value["bargain"]->FirmName . "</td>
                                <td>" . $value["bargain"]->FirmAddress . "</td>
                                <td>" . $list->userFirm . "</td></tr>";
                        $i++;
                    };
                }
                $j++;
            }
        } else { ?>
            <tr>
                <td colspan="9" style="align-self: center;">No bargains found for your selection!</td>
            </tr>
        <?php }

        ?>
    </table>
</body>

</html>