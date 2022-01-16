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

    <h2>Bargain's Report</h2>

    <table>
        <tr>
            <th>Bargain Detaiils</th>
            <th>Entry Date</th>
            <th>Truck No</th>
            <th>Quantity (qts)</th>
            <th>Quantity (bags)</th>
            <th>Firm</th>
        </tr>
        <?php
        foreach ($entries as $value) {
            echo "
                <tr>
                    <td>" . $value->BargainDetaiils . "</td>
                    <td>" . $value->EntryDate . "</td>
                    <td>" . $value->TruckNo . "</td>
                    <td>" . $value->Quantity_in_qts . "</td>
                    <td>" . $value->Quantity_in_bags . "</td>
                    <td>" . $value->FirmName . "</td>
                 </tr>";
        }
        ?>
        <!-- <tr>
            <td>Alfreds Futterkiste</td>
            <td>Maria Anders</td>
            <td>Germany</td>
            <td>Germany</td>
            <td>Germany</td>
            <td>Germany</td>
        </tr>
        <tr>
            <td>Centro comercial Moctezuma</td>
            <td>Francisco Chang</td>
            <td>Mexico</td>
            <td>Mexico</td>
            <td>Mexico</td>
            <td>Mexico</td>
        </tr>
        <tr>
            <td>Ernst Handel</td>
            <td>Roland Mendel</td>
            <td>Austria</td>
            <td>Austria</td>
            <td>Austria</td>
            <td>Austria</td>
        </tr>
        <tr>
            <td>Island Trading</td>
            <td>Helen Bennett</td>
            <td>UK</td>
            <td>UK</td>
            <td>UK</td>
            <td>UK</td>
        </tr>
        <tr>
            <td>Laughing Bacchus Winecellars</td>
            <td>Yoshi Tannamuri</td>
            <td>Canada</td>
            <td>Canada</td>
            <td>Canada</td>
            <td>Canada</td>
        </tr>
        <tr>
            <td>Magazzini Alimentari Riuniti</td>
            <td>Giovanni Rovelli</td>
            <td>Italy</td>
            <td>Italy</td>
            <td>Italy</td>
            <td>Italy</td>
        </tr> -->
    </table>
</body>

</html>