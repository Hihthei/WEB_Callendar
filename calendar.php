<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=unauthorized");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Réservation</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Asset/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Système de Réservation</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Calendrier</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Mes Réservations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Mon Profil</a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <button class="btn btn-danger" onclick="window.location.href='Asset/php/logout.php'">Se déconnecter</button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="calendar-container p-3">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Lundi</th>
                        <th>Mardi</th>
                        <th>Mercredi</th>
                        <th>Jeudi</th>
                        <th>Vendredi</th>
                        <th>Samedi</th>
                        <th>Dimanche</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($hour = 8; $hour <= 18; $hour++): ?>
                    <tr>
                        <td><?php echo $hour . ':00'; ?></td>
                        <?php for ($day = 1; $day <= 7; $day++): ?>
                        <td></td>
                        <?php endfor; ?>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
