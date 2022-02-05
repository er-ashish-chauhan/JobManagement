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
            <th>Bargain Detaiils</th>
            <th>Entry Date</th>
            <th>Inward no No</th>
            <th>Truck No</th>
            <th>Quantity (qts)</th>
            <th>Quantity (bags)</th>
            <th>Party</th>
            <th>Party Location</th>
            <th>Firm</th>
        </tr>
        <?php
        if ($entries) {
            foreach ($entries as $value) {
                echo "
                    <tr>
                        <td>" . $value->BargainDetaiils . "</td>
                        <td>" . $value->EntryDate . "</td>
                        <td>" . $value->kantaSlipNo . "</td>
                        <td>" . $value->TruckNo . "</td>
                        <td>" . $value->Quantity_in_qts . "</td>
                        <td>" . $value->Quantity_in_bags . "</td>
                        <td>" . $value->FirmName . "</td>
                        <td>" . $value->FirmAddress . "</td>
                        <td>" . $value->userFirm . "</td>
                     </tr>";
            }
        } else { ?>
            <tr>
                <td colspan="9" style="align-self: center;">No entries found for your selection!</td>
            </tr>
        <?php }

        ?>
    </table>
</body>

</html>