<!-- query.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Run SQL Query - Fly KUET</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="query.css">
</head>
<body>

    <?php include 'dashboard.php'; ?>

    <main class="content">
        <div class="query-container">
            <?php
            // Database Connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "airlinesdb";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

            // Predefined queries
            $queries = [
                "pilot_hours" => "
                    SELECT p.pilot_id, CONCAT(p.first_name, ' ', p.last_name) AS pilot_name,
                           YEAR(f.departure_time) AS flight_year,
                           ROUND(SUM(TIMESTAMPDIFF(MINUTE, f.departure_time, f.arrival_time)) / 60, 2) AS total_hours
                    FROM pilots p
                    JOIN flights f ON p.assigned_aircraft = f.aircraft_id
                    GROUP BY p.pilot_id, flight_year
                    ORDER BY total_hours DESC;
                ",
                "top_airport" => "
                    SELECT a.airport_code, a.name, COUNT(f.flight_number) AS arrival_count
                    FROM airports a
                    JOIN flights f ON a.airport_code = f.arrival_airport
                    GROUP BY a.airport_code
                    ORDER BY arrival_count DESC
                    LIMIT 1;
                ",
                "most_used_aircraft" => "
                    SELECT ac.model, COUNT(f.flight_number) AS total_flights
                    FROM aircrafts ac
                    JOIN flights f ON ac.aircraft_id = f.aircraft_id
                    GROUP BY ac.model
                    ORDER BY total_flights DESC
                    LIMIT 1;
                ",
                "frequent_passengers" => "
                    SELECT p.passengers_id, CONCAT(p.first_name, ' ', p.last_name) AS passenger_name,
                           COUNT(r.reservation_id) AS total_reservations
                    FROM passengers p
                    JOIN reservations r ON p.passengers_id = r.passenger_id
                    GROUP BY p.passengers_id
                    ORDER BY total_reservations DESC
                    LIMIT 5;
                ",
                "long_flights" => "
                    SELECT f.flight_number, f.departure_time, f.arrival_time,
                    TIMESTAMPDIFF(HOUR, f.departure_time, f.arrival_time) 
                    AS flight_duration_hours
                    FROM flights f
                    WHERE TIMESTAMPDIFF(HOUR, f.departure_time, f.arrival_time) > 4
                    ORDER BY flight_duration_hours DESC;
                ",
                "crew_today" => "
                    SELECT c.crew_id, CONCAT(c.first_name, ' ', c.last_name) AS crew_name, c.role,
                           ac.model AS aircraft_model, f.flight_number
                    FROM crew_members c
                    JOIN aircrafts ac ON c.assigned_aircraft = ac.aircraft_id
                    JOIN flights f ON ac.aircraft_id = f.aircraft_id
                    WHERE DATE(f.departure_time) = CURDATE();
                ",
                "daily_traffic" => "
                    SELECT DATE(departure_time) AS flight_day, COUNT(*) AS total_flights
                    FROM flights
                    WHERE departure_time >= CURDATE() - INTERVAL 30 DAY
                    GROUP BY flight_day
                    ORDER BY flight_day DESC;
                ",
                "maintenance_usage" => "
                    SELECT ac.aircraft_id, ac.model, ac.maintenance_status, COUNT(f.flight_number) AS flight_count
                    FROM aircrafts ac
                    LEFT JOIN flights f ON ac.aircraft_id = f.aircraft_id
                    GROUP BY ac.aircraft_id
                    ORDER BY flight_count DESC;
                ",
                "peak_hours" => "
                    SELECT HOUR(departure_time) AS departure_hour, COUNT(*) AS flight_count
                    FROM flights
                    GROUP BY departure_hour
                    ORDER BY flight_count DESC;
                ",
                "avg_load" => "
                    SELECT ac.model, AVG(passenger_count) AS avg_passengers
                    FROM (
                        SELECT f.aircraft_id, COUNT(r.reservation_id) AS passenger_count
                        FROM flights f
                        LEFT JOIN reservations r ON f.flight_number = r.flight_number
                        GROUP BY f.flight_number
                    ) AS flight_passenger_counts
                    JOIN aircrafts ac ON flight_passenger_counts.aircraft_id = ac.aircraft_id
                    GROUP BY ac.model
                    ORDER BY avg_passengers DESC;
                "
            ];

            $selected = $_POST["query_select"] ?? "";

            ?>

            <form class="query-form" method="post">
                <label for="query_select">Choose a query:</label>
                <select name="query_select" id="query_select">
                    <option value="">-- Select a Query --</option>
                    <option value="pilot_hours" <?= $selected == "pilot_hours" ? "selected" : "" ?>>Pilot Hours per Year</option>
                    <option value="top_airport" <?= $selected == "top_airport" ? "selected" : "" ?>>Airport with Most Arrivals</option>
                    <option value="most_used_aircraft" <?= $selected == "most_used_aircraft" ? "selected" : "" ?>>Most Used Aircraft Model</option>
                    <option value="frequent_passengers" <?= $selected == "frequent_passengers" ? "selected" : "" ?>>Top 5 Frequent Flyers</option>
                    <option value="long_flights" <?= $selected == "long_flights" ? "selected" : "" ?>>Flights Longer Than 4 Hours</option>
                    <option value="crew_today" <?= $selected == "crew_today" ? "selected" : "" ?>>Crew on Flights Today</option>
                    <option value="daily_traffic" <?= $selected == "daily_traffic" ? "selected" : "" ?>>Flight Traffic (Last 30 Days)</option>
                    <option value="maintenance_usage" <?= $selected == "maintenance_usage" ? "selected" : "" ?>>Aircraft Usage & Maintenance</option>
                    <option value="peak_hours" <?= $selected == "peak_hours" ? "selected" : "" ?>>Peak Departure Hours</option>
                    <option value="avg_load" <?= $selected == "avg_load" ? "selected" : "" ?>>Average Load per Aircraft</option>
                </select>

                <button type="submit" name="show_query">Show Query</button>
                <button type="submit" name="run_query">Run Query</button>
            </form>

            <?php
            // Show SQL Query
            if (isset($_POST["show_query"])) {
                if (!empty($selected) && array_key_exists($selected, $queries)) {
                    echo "<div class='query-output'><pre>" . htmlspecialchars($queries[$selected]) . "</pre></div>";
                } else {
                    echo "<div class='query-message error'>Please select a valid query to show.</div>";
                }
            }

            // Run Query
            if (isset($_POST["run_query"])) {
                if (!empty($selected) && array_key_exists($selected, $queries)) {
                    $sql = $queries[$selected];
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        echo "<div class='query-results'><table><tr>";
                        while ($fieldinfo = $result->fetch_field()) {
                            echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
                        }
                        echo "</tr>";

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $val) {
                                echo "<td>" . htmlspecialchars($val) . "</td>";
                            }
                            echo "</tr>";
                        }

                        echo "</table></div>";
                    } else {
                        echo "<div class='query-message error'>No results found or query error.</div>";
                    }
                } else {
                    echo "<div class='query-message error'>Please select a valid query to run.</div>";
                }
            }

            $conn->close();
            ?>
        </div>
    </main>

</body>
</html>
