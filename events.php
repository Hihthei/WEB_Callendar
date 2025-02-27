<?php
session_start();
require 'Asset/php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event = null;

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
    $stmt->execute([$event_id, $user_id]);
    $event = $stmt->fetch();
    
    if (!$event) {
        header("Location: events.php");
        exit();
    }
}

$stmt = $pdo->prepare("SELECT * FROM events WHERE user_id = ? ORDER BY date_heure_debut DESC");
$stmt->execute([$user_id]);
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <nav class="mb-4">
        <div class="d-flex justify-content-between">
            <a href="calendar.php" class="btn btn-primary">Retour au calendrier</a>
            <a href="profil.php" class="btn btn-outline-secondary">Mon profil</a>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <h2 class="mb-4">Mes Événements</h2>
            <?php if (empty($events)): ?>
                <div class="alert alert-info">Vous n'avez pas encore d'événements.</div>
            <?php else: ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Titre</th>
                            <th>Date et Heure</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $evt): ?>
                        <tr <?php echo (isset($_GET['id']) && $_GET['id'] == $evt['id']) ? 'class="table-primary"' : ''; ?>>
                            <td><?= htmlspecialchars($evt['titre']) ?></td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($evt['date_heure_debut'])) ?> - 
                                <?= date('H:i', strtotime($evt['date_heure_fin'])) ?>
                            </td>
                            <td><?= htmlspecialchars($evt['description']) ?></td>
                            <td>
                                <a href="events.php?id=<?= $evt['id'] ?>" class="btn btn-sm btn-info">Modifier</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <?php if ($event): ?>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Modifier l'événement</h4>
                    </div>
                    <div class="card-body">
                        <form action="Asset/php/events.php" method="POST">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                            
                            <div class="mb-3">
                                <label for="titre" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="titre" name="titre" value="<?= htmlspecialchars($event['titre']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($event['description']) ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <p>
                                    Date: <?= date('d/m/Y', strtotime($event['date_heure_debut'])) ?><br>
                                    Heure: <?= date('H:i', strtotime($event['date_heure_debut'])) ?> - <?= date('H:i', strtotime($event['date_heure_fin'])) ?>
                                </p>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                                <button type="submit" class="btn btn-danger" formaction="Asset/php/events.php" 
                                        onclick="document.querySelector('[name=action]').value='delete'">
                                    Supprimer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="card bg-light">
                    <div class="card-body">
                        <h4 class="card-title">Gestion des événements</h4>
                        <p class="card-text">Cliquez sur un événement dans la liste pour le modifier ou le supprimer.</p>
                        <p class="card-text">Vous pouvez également créer de nouveaux événements directement depuis votre calendrier.</p>
                        <a href="calendar.php" class="btn btn-primary">Aller au calendrier</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>