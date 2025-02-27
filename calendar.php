<?php
session_start();
require_once 'Asset/php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM events WHERE user_id = ?");
$stmt->execute([$user_id]);
$events = $stmt->fetchAll();

$eventsMap = [];
foreach ($events as $event) {
    $dateTime = new DateTime($event['date_heure_debut']);
    $day = $dateTime->format('N');
    $hour = $dateTime->format('G');
    $eventsMap[$day][$hour] = $event;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
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
    <style>
        .calendar-cell {
            cursor: pointer;
            height: 80px;
            vertical-align: top;
            padding: 5px;
        }
        .event {
            background-color: #007bff;
            color: white;
            padding: 5px;
            border-radius: 3px;
            margin-bottom: 2px;
            cursor: pointer;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container d-flex justify-content-center">
                <div class="text-center mt-4">
                    <a href="profil.php" class="btn me-2">Mon Profil</a>
                    <a href="events.php" class="btn me-2">Mes Événements</a>
                    <a href="?logout=1" class="btn">Déconnexion</a>
                </div>
            </div>
        </nav>

        <div class="calendar-container p-3">
            <h2 class="text-center mb-4">Mon Calendrier</h2>
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
                        <td class="calendar-cell" data-day="<?php echo $day; ?>" data-hour="<?php echo $hour; ?>">
                            <?php if (isset($eventsMap[$day][$hour])): ?>
                                <div class="event" 
                                     data-event-id="<?php echo $eventsMap[$day][$hour]['id']; ?>"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#eventModal" 
                                     data-action="edit">
                                    <?php echo htmlspecialchars($eventsMap[$day][$hour]['titre']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <?php endfor; ?>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Ajouter un événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm" action="Asset/php/events.php" method="POST">
                        <input type="hidden" id="action" name="action" value="create">
                        <input type="hidden" id="event_id" name="event_id" value="">
                        <input type="hidden" id="day" name="day" value="">
                        <input type="hidden" id="hour" name="hour" value="">
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3" id="datetime-display">
                            <p>Date et heure: <span id="date-time-text"></span></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Ajouter</button>
                            <button type="button" class="btn btn-danger" id="deleteBtn" style="display:none;">Supprimer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const currentDay = now.getDay() || 7;
            const currentDate = now.getDate();
            const currentMonth = now.getMonth();
            const currentYear = now.getFullYear();
            
            const mondayOffset = 1 - currentDay;
            const mondayDate = new Date(currentYear, currentMonth, currentDate + mondayOffset);
            
            document.querySelectorAll('.calendar-cell').forEach(cell => {
                cell.addEventListener('click', function(e) {
                    if (e.target.classList.contains('event')) return;
                    
                    const day = this.dataset.day;
                    const hour = this.dataset.hour;
                    
                    const eventDate = new Date(mondayDate);
                    eventDate.setDate(mondayDate.getDate() + parseInt(day) - 1);
                    eventDate.setHours(hour, 0, 0, 0);
                    
                    const dateTimeStr = eventDate.toLocaleString('fr-FR', {
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    const formattedDateTime = `${eventDate.getFullYear()}-${(eventDate.getMonth()+1).toString().padStart(2, '0')}-${eventDate.getDate().toString().padStart(2, '0')} ${eventDate.getHours().toString().padStart(2, '0')}:00:00`;
                    
                    document.getElementById('eventModalLabel').textContent = 'Ajouter un événement';
                    document.getElementById('action').value = 'create';
                    document.getElementById('event_id').value = '';
                    document.getElementById('titre').value = '';
                    document.getElementById('description').value = '';
                    document.getElementById('day').value = day;
                    document.getElementById('hour').value = hour;
                    document.getElementById('date-time-text').textContent = dateTimeStr;
                    document.getElementById('submitBtn').textContent = 'Ajouter';
                    document.getElementById('deleteBtn').style.display = 'none';
                    
                    let dateTimeInput = document.getElementById('date_heure_debut');
                    if (!dateTimeInput) {
                        dateTimeInput = document.createElement('input');
                        dateTimeInput.type = 'hidden';
                        dateTimeInput.id = 'date_heure_debut';
                        dateTimeInput.name = 'date_heure_debut';
                        document.getElementById('eventForm').appendChild(dateTimeInput);
                    }
                    dateTimeInput.value = formattedDateTime;
                    
                    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    modal.show();
                });
            });
            
            document.querySelectorAll('.event').forEach(eventElem => {
                eventElem.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const eventId = this.dataset.eventId;
                    
                    // Rediriger vers la page de détail de l'événement
                    window.location.href = `events.php?id=${eventId}`;
                });
            });
            
            document.getElementById('deleteBtn').addEventListener('click', function() {
                const eventId = document.getElementById('event_id').value;
                document.getElementById('action').value = 'delete';
                document.getElementById('eventForm').submit();
            });
        });
    </script>
</body>
</html>
