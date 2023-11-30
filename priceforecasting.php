<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forecast Data</title>
</head>
<body>

    <h1>Forecast Data</h1>
    
    <!-- Display the forecast table -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        /* Add a media query for smaller screens */
        @media screen and (max-width: 600px) {
            th, td {
                font-size: 14px;
            }
        }
    </style>
    <table>
        <thead>
            <tr>
                <th>Year&Month</th>
                <th>Forecast</th>
                <th>Lower Bound</th>
                <th>Upper Bound</th>
            </tr>
        </thead>
        <tbody>
            {% for row in aggregated_forecast_table.iterrows() %}
                <tr>
                    <td>{{ row[1]['Year&Month'] }}</td>
                    <td>{{ row[1]['Forecast'] }}</td>
                    <td>{{ row[1]['yhat_lower'] }}</td>
                    <td>{{ row[1]['yhat_upper'] }}</td>
                </tr>
            {% endfor %}
        </tbody>
    </table>

</body>
</html>
