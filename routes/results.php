<?php
session_start();

if (!isset($_SESSION['userdata'])) {
    header("Location: login.php");
    exit();
}

$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'];

$groupNames = [];
$groupVotes = [];
$groupData = [];
$totalVotes = 0;

foreach ($groupsdata as $group) {
    $groupNames[] = $group['name'];
    $groupVotes[] = $group['votes'];
    $totalVotes += $group['votes'];
    $groupData[] = [
        'x' => $group['name'],  // Replace with numerical value if needed
        'y' => $group['votes']
    ];
}

$totalVoters = 100;
$nonVoters = $totalVoters - $totalVotes;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System - Results</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #343a40;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .sidebar ul {
            list-style-type: none;
            padding-left: 0;
        }
        .sidebar li {
            margin-bottom: 10px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #555;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .charts-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        canvas {
            max-width: 400px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Online Voting System</h1>
    </header>
    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="dashboard.php">Back</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <main class="main-content">
            <section id="resultSection">
                <h2>Results</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Votes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupsdata as $group): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($group['name']); ?></td>
                            <td><?php echo htmlspecialchars($group['votes']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="charts-container">
                    <canvas id="voteChart"></canvas>
                    <canvas id="voterParticipationChart"></canvas>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctxVote = document.getElementById('voteChart').getContext('2d');
            var ctxParticipation = document.getElementById('voterParticipationChart').getContext('2d');
            
            var voteChart = new Chart(ctxVote, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($groupNames); ?>,
                    datasets: [{
                        label: 'Votes',
                        data: <?php echo json_encode($groupVotes); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Votes per Group'
                        }
                    }
                }
            });

            var voterParticipationChart = new Chart(ctxParticipation, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Votes',
                        data: <?php echo json_encode($groupData); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Group Names'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Votes'
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Votes Scatter Plot'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
