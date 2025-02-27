# Système de Réservation avec Calendrier

## Description du Projet

Ce projet est un système de réservation en ligne avec un calendrier intégré, similaire à Google Agenda. Il permet aux utilisateurs de créer un compte, de gérer leurs événements et rendez-vous, de modifier leurs informations personnelles, et de visualiser facilement leur emploi du temps.

L'application est développée en PHP avec une base de données MySQL et utilise Bootstrap pour l'interface utilisateur.

## Fonctionnalités

### Gestion des Utilisateurs

- **Création de compte ✅**
  - Formulaire d'inscription complet avec:
    - Nom, prénom
    - Date de naissance
    - Adresse postale
    - Numéro de téléphone
    - Email (vérification d'unicité)
    - Mot de passe sécurisé

- **Connexion et déconnexion ✅**
  - Système d'authentification sécurisé
  - Protection CSRF
  - Redirection vers le calendrier après connexion

- **Modification des informations personnelles ✅**
  - Interface de profil pour mettre à jour les informations
  - Vérification de l'unicité de l'email
  - Changement de mot de passe

- **Suppression de compte ✅**
  - Possibilité de supprimer définitivement le compte utilisateur

### Gestion des Événements

- **Création d'événements ✅**
  - Calendrier interactif hebdomadaire
  - Sélection de créneaux horaires
  - Ajout de titre et description
  - Vérification de disponibilité

- **Affichage des événements ✅**
  - Vue calendrier avec tous les événements de l'utilisateur
  - Page dédiée avec liste des événements
  - Visualisation détaillée d'un événement

- **Modification d'événements ✅**
  - Possibilité de modifier le titre et la description
  - Interface intuitive

- **Suppression d'événements ✅**
  - Possibilité de supprimer un événement

### Sécurité

- **Protection CSRF ✅**
  - Jetons CSRF pour les formulaires
  - Validation côté serveur

- **Hachage des mots de passe ✅**
  - Utilisation de `password_hash()` pour sécuriser les mots de passe

- **Prévention des injections SQL ✅**
  - Utilisation de requêtes préparées PDO

- **Protection XSS ✅**
  - Échappement des données utilisateur avec `htmlspecialchars()`

## Structure du Projet

### Pages Principales

- **index.php** - Page de connexion
- **inscription.php** - Création de compte
- **calendar.php** - Vue principale du calendrier
- **events.php** - Gestion des événements
- **profil.php** - Gestion du profil utilisateur

### Dossier Asset

- **php/** - Scripts PHP de traitement
  - config.php - Configuration de la base de données
  - connexion.php - Traitement de la connexion
  - events.php - Gestion des événements
  - inscription.php - Traitement de l'inscription
  - delete.php - Suppression de compte

- **css/** - Feuilles de style
  - style.css - Styles personnalisés
  - dashboard.css - Styles pour le tableau de bord

## Base de Données

Le projet utilise une base de données MySQL avec deux tables principales:

### Table `users`

- id (PK)
- nom
- prenom
- email (unique)
- mot_de_passe (haché)
- date_de_naissance
- adresse_postale
- telephone

### Table `events`

- id (PK)
- user_id (FK)
- titre
- description
- date_heure_debut
- date_heure_fin

## Installation et Configuration

1. Cloner le dépôt
2. Créer une base de données MySQL nommée "calendar"
3. Importer la structure de base de données (script SQL fourni séparément)
4. Configurer les paramètres de connexion dans `Asset/php/config.php` selon votre environnement
5. Déployer les fichiers sur un serveur PHP (version 7.4+ recommandée)

## Capture d'écran

(Ajoutez des captures d'écran de votre application ici)

## Utilisation

1. Accédez à la page d'accueil pour vous connecter ou créer un compte
2. Une fois connecté, vous serez redirigé vers le calendrier
3. Cliquez sur un créneau horaire pour créer un événement
4. Gérez vos événements depuis la page "Mes Événements"
5. Modifiez votre profil depuis la page "Mon Profil"

## Auteur

Hihthei/BOUILLON_Célin ESIEA - Laval - 3A.

## Licence

Ce projet est distribué sous licence MIT.