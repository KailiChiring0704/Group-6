<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .card {
            margin-top: 20px;
        }

        .input-background {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <br>
    <br>
    <br>
    <div class="container mt-4">
        <div class="input-background">
            <h2>Dashboard</h2>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Today Revenue</h5>
                        <p class="card-text" id="dailyRevenue">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Weekly Revenue</h5>
                        <p class="card-text" id="weeklyRevenue">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Revenue</h5>
                        <p class="card-text" id="monthlyRevenue">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders Today</h5>
                        <p class="card-text" id="totalOrders">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="salesOverTime"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function fetchData() {
                $.ajax({
                    url: 'partials/_dashboardData.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#dailyRevenue').text(`PHP ${parseFloat(response.data.daily_revenue).toFixed(2)}`);
                            $('#weeklyRevenue').text(`PHP ${parseFloat(response.data.weekly_revenue).toFixed(2)}`);
                            $('#monthlyRevenue').text(`PHP ${parseFloat(response.data.monthly_revenue).toFixed(2)}`);
                            $('#totalOrders').text(response.data.total_orders);

                            var ctx = document.getElementById('salesOverTime').getContext('2d');
                            var salesOverTime = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: response.data.labels,
                                    datasets: [{
                                        label: 'Revenue Over Time',
                                        data: response.data.revenue_over_time,
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        } else {
                            console.error('Failed to fetch data:', response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Failed to fetch data:", textStatus, jqXHR.responseText);
                    }
                });
            }

            fetchData();
            setInterval(fetchData, 10000); // Fetch data every 10 seconds
        });
    </script>
</body>

</html>