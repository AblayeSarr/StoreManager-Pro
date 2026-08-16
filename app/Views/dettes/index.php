<?php

require_once dirname(__DIR__, 2) . '/Service/DebtService.php';
require_once dirname(__DIR__, 2) . '/Model/Repository/DetteRepository.php';
require_once dirname(__DIR__, 2) . '/Core/Database.php';

$debtService = new DebtService();
$dettes = $debtService->getAllDettes();

foreach ($dettes as &$dette) {
    $dette['remboursements'] = $debtService->getRemboursements((int) $dette['id']);
}
unset($dette);

$totalCreances = 0;
$totalRecouvrements = 0;
$clientsDebiteurs = [];

foreach ($dettes as $dette) {
    $totalCreances += (float) $dette['montant_restant'];
    $totalRecouvrements += (float) $dette['montant'] - (float) $dette['montant_restant'];
    $clientsDebiteurs[$dette['client_id']] = true;
}

$nombreClientsDebiteurs = count($clientsDebiteurs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des dettes</title>
</head>

<body>
<div id="view-dettes" class="view-section">

    <!-- Statistiques -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">

        <div class="panel-card" style="padding:16px;display:flex;align-items:center;justify-content:space-between;border-left:4px solid var(--danger);">
            <div>
                <span style="font-size:10px;color:var(--text-muted);text-transform:uppercase;font-weight:700;">Créances Actives</span>
                <div style="font-size:18px;font-weight:800;color:white;margin-top:4px;">
                    <?= number_format($totalCreances, 0, ',', ' ') ?> F
                </div>
            </div>
            <span style="font-size:24px;">💸</span>
        </div>

        <div class="panel-card" style="padding:16px;display:flex;align-items:center;justify-content:space-between;border-left:4px solid var(--warning);">
            <div>
                <span style="font-size:10px;color:var(--text-muted);text-transform:uppercase;font-weight:700;">Clients Débiteurs</span>
                <div style="font-size:18px;font-weight:800;color:white;margin-top:4px;">
                    <?= $nombreClientsDebiteurs ?> client(s)
                </div>
            </div>
            <span style="font-size:24px;">👥</span>
        </div>

        <div class="panel-card" style="padding:16px;display:flex;align-items:center;justify-content:space-between;border-left:4px solid var(--success);">
            <div>
                <span style="font-size:10px;color:var(--text-muted);text-transform:uppercase;font-weight:700;">Total Recouvrements</span>
                <div style="font-size:18px;font-weight:800;color:white;margin-top:4px;">
                    <?= number_format($totalRecouvrements, 0, ',', ' ') ?> F
                </div>
            </div>
            <span style="font-size:24px;">📈</span>
        </div>

    </div>

    <!-- Tableau -->
    <div class="panel-card">
        <div class="panel-title">
            <span>Registre des Dettes</span>
            <input type="text" id="debt-search" class="search-control"
                   placeholder="Rechercher un client..." onkeyup="filterDebtsTable()">
        </div>

        <table class="debt-table" id="debts-main-table">
            <thead>
                <tr>
                    <th>ID Dette</th>
                    <th>Date Création</th>
                    <th>Client</th>
                    <th>Montant Initial</th>
                    <th>Montant Payé</th>
                    <th>Reste Dû</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($dettes)): ?>

                <tr>
                    <td colspan="8" style="text-align:center;padding:30px;color:var(--text-muted);">
                        Aucune dette enregistrée.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($dettes as $dette): ?>
                    <?php
                    $id = (int) $dette['id'];
                    $montant = (float) $dette['montant'];
                    $reste = (float) $dette['montant_restant'];
                    $paye = $montant - $reste;
                    $client = trim($dette['prenom'] . ' ' . $dette['nom']);

                    switch ($dette['statut']) {
                        case 'en_cours':
                            $statutLabel = 'NON SOLDÉE';
                            break;
                        case 'partiellement_remboursee':
                            $statutLabel = 'PARTIELLE';
                            break;
                        case 'soldee':
                            $statutLabel = 'SOLDÉE';
                            break;
                        default:
                            $statutLabel = strtoupper($dette['statut']);
                    }

                    $statutClass = $dette['statut'] === 'soldee'
                        ? 'badge-success'
                        : 'badge-danger';
                    ?>

                    <!-- Dette -->
                    <tr id="debt-row-<?= $id ?>"
                        data-client-name="<?= htmlspecialchars(strtolower($client . ' ' . ($dette['telephone'] ?? ''))) ?>">

                        <td style="font-weight:700;color:var(--text-muted);">
                            #DT-<?= $id ?>
                            <span style="font-size:10px;color:var(--text-muted);display:block;font-weight:normal;">
                                #CMD-<?= (int) $dette['vente_id'] ?>
                            </span>
                        </td>

                        <td style="font-size:12px;">
                            <?= htmlspecialchars(date('d M Y H:i', strtotime($dette['date']))) ?>
                        </td>

                        <td style="font-weight:700;">
                            <?= htmlspecialchars($client) ?>
                            <div style="font-size:11px;color:var(--text-muted);font-weight:normal;">
                                Tél : <?= htmlspecialchars($dette['telephone'] ?? '-') ?>
                            </div>
                        </td>

                        <td style="font-weight:700;">
                            <?= number_format($montant, 0, ',', ' ') ?> F
                        </td>

                        <td style="font-weight:700;color:var(--success);">
                            <?= number_format($paye, 0, ',', ' ') ?> F
                        </td>

                        <td style="font-weight:800;color:var(--danger);">
                            <?= number_format($reste, 0, ',', ' ') ?> F
                        </td>

                        <td>
                            <span class="badge <?= $statutClass ?>">
                                <?= $statutLabel ?>
                            </span>
                        </td>

                        <td style="display:flex;gap:6px;flex-wrap:wrap;">
                            <button type="button" class="btn-quick-action"
                                    onclick="toggleDetails('debt-details-<?= $id ?>')">
                                💳 Paiements
                            </button>

                            <?php if ($reste > 0): ?>
                                <button type="button" class="btn-quick-action"
                                        style="border-color:var(--warning);color:var(--warning);"
                                        onclick="toggleDetails('debt-repay-<?= $id ?>')">
                                    Rembourser
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Détails -->
                    <tr>
                        <td colspan="8" style="padding:0;border:none;">

                            <!-- Historique -->
                            <div class="details-drawer" id="debt-details-<?= $id ?>">
                                <div style="font-weight:700;font-size:12px;color:var(--accent);margin-bottom:8px;">
                                    Paiements enregistrés :
                                </div>

                                <table class="debt-table" style="font-size:11px;">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Versement</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php if (empty($dette['remboursements'])): ?>

                                        <tr>
                                            <td colspan="2" style="text-align:center;color:var(--text-muted);">
                                                Aucun acompte versé.
                                            </td>
                                        </tr>

                                    <?php else: ?>

                                        <?php foreach ($dette['remboursements'] as $remboursement): ?>
                                            <tr>
                                                <td>
                                                    <?= htmlspecialchars($remboursement['date'] ?? '-') ?>
                                                </td>
                                                <td style="font-weight:700;color:var(--success);">
                                                    <?= number_format((float) $remboursement['montant'], 0, ',', ' ') ?> F
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Remboursement -->
                            <?php if ($reste > 0): ?>

                                <div class="details-drawer" id="debt-repay-<?= $id ?>"
                                     style="border:1px solid rgba(45,212,191,.25);background:#0b0f19;border-radius:14px;padding:18px 20px;max-width:850px;margin:12px 0;">

                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;border-bottom:1px dashed var(--border-color);padding-bottom:10px;">
                                        <span style="font-weight:800;font-size:13px;">
                                            💳 Nouveau Remboursement —
                                            <span style="color:var(--accent);"><?= htmlspecialchars($client) ?></span>
                                        </span>

                                        <span style="background:rgba(244,63,94,.12);border:1px solid rgba(244,63,94,.3);padding:4px 12px;border-radius:20px;font-size:11px;font-weight:800;color:var(--danger);">
                                            Reste dû : <?= number_format($reste, 0, ',', ' ') ?> FCFA
                                        </span>
                                    </div>

                                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:16px;">
                                        <span style="font-size:10px;text-transform:uppercase;color:var(--text-muted);font-weight:700;">
                                            Raccourcis :
                                        </span>

                                        <button type="button"
                                                onclick="setRepayAmount(<?= $id ?>,<?= $reste ?>)"
                                                style="background:rgba(45,212,191,.1);border:1px solid var(--accent);color:var(--accent);font-size:10px;font-weight:700;padding:4px 10px;border-radius:6px;cursor:pointer;">
                                            Tout solder
                                        </button>

                                        <button type="button"
                                                onclick="setRepayAmount(<?= $id ?>,<?= floor($reste / 2) ?>)"
                                                style="background:rgba(255,255,255,.04);border:1px solid var(--border-color);color:var(--text-main);font-size:10px;font-weight:700;padding:4px 10px;border-radius:6px;cursor:pointer;">
                                            50%
                                        </button>
                                    </div>

                                    <form method="POST" action=""
                                          style="display:flex;gap:16px;align-items:flex-end;flex-wrap:wrap;">

                                        <input type="hidden" name="action" value="add_payment">
                                        <input type="hidden" name="dette_id" value="<?= $id ?>">

                                        <div style="flex:1;min-width:200px;">
                                            <label style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:6px;">
                                                Montant du Versement
                                            </label>

                                            <input type="number" name="montant_verse"
                                                   id="repay-input-<?= $id ?>"
                                                   class="form-control"
                                                   min="1"
                                                   max="<?= $reste ?>"
                                                   value="<?= $reste ?>"
                                                   required
                                                   style="font-size:13px;font-weight:700;padding:10px 12px;background:#0b0f19;color:white;width:100%;">
                                        </div>

                                        <div style="flex:1;min-width:200px;">
                                            <label style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:6px;">
                                                Canal de Paiement
                                            </label>

                                            <select name="mode_paiement" class="form-control" required
                                                    style="font-size:13px;font-weight:600;padding:10px 12px;background:#0b0f19;color:white;width:100%;">
                                                <option value="especes">💵 Espèces</option>
                                                <option value="mobile_money">📱 Mobile Money</option>
                                                <option value="carte">💳 Carte</option>
                                                <option value="virement">🏦 Virement</option>
                                                <option value="cheque">🧾 Chèque</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn-submit btn-success"
                                                style="padding:11px 24px;font-size:12px;font-weight:800;border-radius:10px;height:42px;">
                                            ✓ Enregistrer
                                        </button>
                                    </form>
                                </div>

                            <?php endif; ?>

                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>