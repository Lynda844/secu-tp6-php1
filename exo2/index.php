<?php
// =================================================================
// PHP Block 1: Exercice 1 & 3 (Étudiants-Note)
// =================================================================

// Déclaration du tableau associatif "etudiantsNote"
$etudiantsNote = [
    "JOHN"    => 9,
    "BOB"     => 15.5,
    "RAYANE"  => 7,
    "ROSIE"   => 13
];

// Déclaration de la fonction estAdmissible(note)
function estAdmissible($note) {
    return $note >= 10;
}

// Couleurs RGB spécifiées
$VERT_ADMISSIBLE = 'rgb(136,248,136)'; 
$ROUGE_NON_ADMISSIBLE = 'rgb(251,173,173)';


// =================================================================
// PHP Block 2: Exercice 2 (Monitoring de serveurs)
// =================================================================

// Déclaration du tableau associatif "serveurs"
$serveurs = [
    "api-01"    => ["cpu" => 60, "ram" => 60, "status" => 1, "score" => 47.5],
    "api-02"    => ["cpu" => 92, "ram" => 78, "status" => 1, "score" => 15],
    "db-prod"   => ["cpu" => 70, "ram" => 95, "status" => 1, "score" => 17.5],
    "web-01"    => ["cpu" => 30, "ram" => 40, "status" => 1, "score" => 65],
    "web-02"    => ["cpu" => 55, "ram" => 65, "status" => 0, "score" => 40],
    "cache-01"  => ["cpu" => 88, "ram" => 92, "status" => 1, "score" => 10]
];

// Initialisation des variables pour le Résumé
$serveurs_online = 0;
$somme_scores = 0;
$meilleur_score = -1;
$meilleur_serveur = "";

/**
 * Fonction pour calculer l'état (État, Classe CSS) du serveur.
 */
function calculerEtat($stats) {
    global $meilleur_score, $meilleur_serveur;

    $cpu = $stats["cpu"];
    $ram = $stats["ram"];
    $status = $stats["status"];
    $score = $stats["score"];
    $etat = "OK";
    $css_class = "";

    if ($score > $meilleur_score) {
        $meilleur_score = $score;
        $meilleur_serveur = $stats["nom"];
    }

    // Règles de criticité
    if ($cpu > 80 || $ram > 90 || $score < 20) {
        $etat = "CRITIQUE";
        $css_class = "critique";
    } elseif ($status == 0 || $score < 50) {
        $etat = "ATTENTION";
        $css_class = "attention";
    } else {
        $etat = "OK";
        $css_class = "ok";
    }

    return ["etat" => $etat, "class" => $css_class];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TP 6: Exercices PHP</title>
    <style>
        /* Styles pour l'Exercice 2 */
        #table2 {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        #table2 th, #table2 td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        #table2 th { background-color: #f2f2f2; }
        
        /* Couleurs pour l'Exercice 2 */
        .critique { background-color: #f8d7da; color: #721c24; }
        .attention { background-color: #fff3cd; color: #856404; }
        .ok { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <h2>TP 6: Exercice 1 & 3 - Étudiants-Note</h2>

    <table id="table1" border="1">
        <thead>
            <tr class="row">
                <th>Etudiant</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Boucle de l'Exercice 1
            foreach ($etudiantsNote as $etudiant => $note) {
                
                $admissible = estAdmissible($note);
                $couleurFond = $admissible ? $VERT_ADMISSIBLE : $ROUGE_NON_ADMISSIBLE;
                
                echo '<tr class="row" style="background-color: ' . $couleurFond . ';">';
                echo '    <td class="etud">' . htmlspecialchars($etudiant) . '</td>';
                echo '    <td class="note">' . number_format($note, 1, '.', '') . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>

    <hr>

    <h2>TP 6: Exercice 2 - Monitoring de serveurs</h2>

    <table id="table2" border="1">
        <thead>
            <tr>
                <th>Serveur</th><th>CPU%</th><th>RAM%</th><th>Status</th><th>Score</th><th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Boucle de l'Exercice 2
            foreach ($serveurs as $nom_serveur => $stats) {
                $stats["nom"] = $nom_serveur;
                $resultat = calculerEtat($stats);
                
                if ($stats["status"] == 1) { $serveurs_online++; }
                $somme_scores += $stats["score"];

                echo "<tr>";
                echo "<td>" . htmlspecialchars($nom_serveur) . "</td>";
                echo "<td class='{$resultat['class']}'>" . $stats["cpu"] . "</td>";
                echo "<td class='{$resultat['class']}'>" . $stats["ram"] . "</td>";
                echo "<td>" . ($stats["status"] == 1 ? 'online' : 'offline') . "</td>";
                echo "<td>" . $stats["score"] . "</td>";
                echo "<td class='{$resultat['class']}'><b>" . $resultat["etat"] . "</b></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <h3>Résumé</h3>
    <?php 
    $score_moyen = count($serveurs) > 0 ? number_format($somme_scores / count($serveurs), 1) : 0;
    ?>
    <p>Serveurs online: <?php echo $serveurs_online; ?></p>
    <p>Score moyen: <?php echo $score_moyen; ?></p>
    <p>Meilleur serveur: <?php echo $meilleur_serveur . " (" . $meilleur_score . ")"; ?></p>

</body>
</html>