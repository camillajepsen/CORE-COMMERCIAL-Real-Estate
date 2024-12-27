<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

//delete agent
if (isset($_POST['delete_agent_id'])) {
    $agentId = $_POST['delete_agent_id'];
    $deleteStatement = $pdo->prepare("DELETE FROM agents WHERE id = :agent_id");
    $deleteStatement->execute(['agent_id' => $agentId]);
    header("Location: manage-agents.php");
    exit();
}

// get all agents
$agentsStatement = $pdo->query("SELECT * FROM agents");
$agents = $agentsStatement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Agents</title>
</head>

<body>

    <div class="admin-dashboard">
        <!--Header with nav bar-->
        <header class="navbar">
            <nav class="nav-bar-container">
                <div class="nav-left">
                    <h1><a href="../index.php"><b>CORE COMMERCIAL</b> • Real Estate</a></h1>
                </div>
                <div class="nav-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span id="greeting">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
                    <?php endif; ?>
                </div>
            </nav>

            <nav class="nav-links">
                <ul>
                    <div class="centered-links">
                        <!-- shared nav links -->
                        <li><a href="../about.php">About Us</a></li>
                        <li><a href="../customer/properties.php">Properties</a></li>
                        <li><a href="../contact.php">Contact Us</a></li>
                        <li><a href="dashboard.php">My Dashboard</a></li>
                    </div>

                    <div class="right-hand-links">
                        <li><a href="../my_account.php">My Account</a></li>
                        <li><a href="../logout.php">Logout</a></li>
                    </div>
                </ul>
            </nav>
        </header>

        <!-- Admin menu buttons -->
        <main class="admin-overview">
            <div class="admin-menu">
                <div class="admin-button">
                    <a href="manage-users.php">Manage Users</a>
                </div>
                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="manage-agents.php" style="color: black">Manage Agents</a>
                </div>
                <div class="admin-button">
                    <a href="manage-owners.php">Manage Owners</a>
                </div>
                <div class="admin-button">
                    <a href="manage-cities.php">Manage Cities</a>
                </div>
                <div class="admin-button">
                    <a href="manage-states.php">Manage States</a>
                </div>
                <div class="admin-button">
                    <a href="manage-countries.php">Manage Countries</a>
                </div>
                <div class="admin-button">
                    <a href="manage-content.php">Manage About/Contact</a>
                </div>
                <div class="admin-button">
                    <a href="manage-reviews.php">Manage Reviews</a>
                </div>
                <div class="admin-button">
                    <a href="manage-property-types.php">Manage Property Types</a>
                </div>
                <div class="admin-button">
                    <a href="view-properties.php">View Properties</a>
                </div>
            </div>

            <div class="admin-stats">
                <div class="admin-title">
                </div>
                <!-- Agents overview -->
                <div class="object-list">
                    <?php if (!empty($agents)): ?>
                        <?php foreach ($agents as $agent): ?>
                            <div class="object-card">
                                <img src="../assets/logos/agent.png" alt="Agent Logo" class="object-logo">
                                <h3><?php echo htmlspecialchars($agent['name']); ?></h3>
                                <p>Email: <?php echo htmlspecialchars($agent['email']); ?></p>
                                <p>Phone: <?php echo htmlspecialchars($agent['phone']); ?></p>

                                <form method="POST" action="manage-agents.php"
                                    onsubmit="return confirm('Are you sure you want to remove this agent?');">
                                    <input type="hidden" name="delete_agent_id"
                                        value="<?php echo htmlspecialchars($agent['id']); ?>">
                                    <button type="submit" class="delete-button">Remove</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No agents found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>