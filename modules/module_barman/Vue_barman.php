<?php
include_once 'vue_generique.php';
include "modules/module_commun/vue_commun.php";
include "modules/module_staff/vue_staff.php";

class Vue_barman extends VueStaff
{
private function afficherHeader($titre = "Gestionnaire de buvette")
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($titre) ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            :root {
                --primary: #10b981; /* Emeraude */
                --secondary: #3b82f6; /* Bleu */
                --danger: #ef4444; /* Rouge */
                --dark: #0f172a; /* Slate 900 */
                --nav-bg: #1e293b; /* Slate 800 */
                --light: #f8fafc;
                --accent: #f59e0b; /* Ambre */
            }

            body {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f1f5f9;
                color: var(--dark);
            }

            /* --- NAVIGATION --- */
            .main-nav {
                background: var(--dark);
                color: white;
                padding: 0 40px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                height: 70px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                position: sticky;
                top: 0;
                z-index: 1000;
            }

            .nav-brand {
                font-size: 1.4rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: -0.5px;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .nav-brand i {
                color: var(--accent);
                filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.4));
            }

            .nav-groups {
                display: flex;
                gap: 10px;
                align-items: center;
            }

            .nav-link {
                color: #94a3b8;
                text-decoration: none;
                font-size: 0.85rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s ease;
                padding: 10px 16px;
                border-radius: 10px;
            }

            .nav-link:hover {
                color: white;
                background: rgba(255, 255, 255, 0.05);
            }

            .nav-link.active {
                color: white;
                background: var(--accent);
                box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            }

            .nav-link.logout {
                color: #fca5a5;
                margin-left: 10px;
            }

            .nav-link.logout:hover {
                background: rgba(239, 68, 68, 0.1);
                color: var(--danger);
            }

            /* --- LAYOUT --- */
            .container {
                max-width: 1200px;
                margin: 40px auto;
                padding: 0 20px;
            }

            h2 {
                font-weight: 800;
                letter-spacing: -1px;
                margin-bottom: 25px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            /* --- TABLES --- */
            .table-wrapper {
                background: white;
                padding: 10px;
                border-radius: 16px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th {
                text-align: left;
                padding: 16px;
                font-size: 0.75rem;
                text-transform: uppercase;
                color: #64748b;
                border-bottom: 2px solid #f1f5f9;
            }

            table td {
                padding: 16px;
                border-bottom: 1px solid #f1f5f9;
                font-size: 0.95rem;
            }

            /* --- BOUTONS --- */
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                border-radius: 8px;
                font-weight: 700;
                text-decoration: none;
                transition: 0.2s;
                border: none;
                cursor: pointer;
            }

            .btn-primary {
                background: var(--accent);
                color: white;
            }

            .btn-secondary {
                background: #e2e8f0;
                color: var(--dark);
            }

            .btn-danger {
                background: var(--danger);
                color: white;
            }

            .btn:hover {
                opacity: 0.9;
                transform: translateY(-1px);
            }
        </style>
    </head>
    <body>
    <?php
    }

    public function afficherNav()
    {
        $prenom = $_SESSION['prenom'] ?? 'Barman';
        $photo = $_SESSION['photo'] ?? null;
        $actionActuelle = $_GET['action'] ?? 'accueil';

        $activeClass = "bg-blue-600/15 text-blue-400 border-r-4 border-blue-600 shadow-[0_0_20px_rgba(37,99,235,0.1)]";
        $inactiveClass = "text-slate-500 hover:bg-white/[0.03] hover:text-white border-r-4 border-transparent";

        $cheminPhoto = !empty($photo) ? "uploads/profiles/" . basename($photo) : null;
        $nbMessages = $this->nbMessages ?? 0;

        echo '
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
    .font-montserrat { font-family: "Montserrat", sans-serif; }
    .glass-sidebar {
        background: rgba(2, 6, 23, 0.9) !important;
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
    }
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
</style>

<div class="flex min-h-screen bg-[#020617] font-montserrat text-slate-200">
    <aside class="w-72 glass-sidebar border-r border-white/5 hidden md:flex flex-col sticky top-0 h-screen z-50">
        
        <div class="h-24 flex items-center px-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/40 transform -rotate-6">
                    <i class="fa-solid fa-bolt text-white text-lg rotate-6"></i>
                </div>
                <span class="text-xl font-[900] text-white tracking-tighter uppercase italic">Asso<span class="text-blue-600">Manager</span></span>
            </div>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
            
            <a href="index.php?module=barman&action=accueil" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'accueil' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-house-user text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Accueil Bar</span>
            </a>

            <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mb-3 mt-8">Service</p>
            
            <a href="index.php?module=barman&action=vendre" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'creerTransaction' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-cash-register text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Vendre / Encaisser</span>
            </a>

            <a href="index.php?module=barman&action=commandesEnCours" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'commandesEnCours' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-beer-mug-empty text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Commandes du jour</span>
            </a>

            <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mt-8 mb-3">Suivi & Stocks</p>

            <a href="index.php?module=barman&action=historiqueCommandes" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'historiqueCommandes' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Historique Ventes</span>
            </a>

           <a href="index.php?module=barman&action=rechercherClient" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'rechercherClient' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-magnifying-glass text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Chercher un Client</span>
            </a>

            <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mt-8 mb-3">Communication</p>

            <a href="index.php?module=barman&action=messagerie" class="flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'messagerie' ? $activeClass : $inactiveClass) . '">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-paper-plane text-lg"></i>
                    <span class="font-bold text-sm tracking-tight">Messages</span>
                </div>' .
            ($nbMessages > 0 ? '<span class="bg-blue-600 text-[10px] font-black text-white px-2 py-0.5 rounded-lg shadow-lg shadow-blue-600/20">' . $nbMessages . '</span>' : '') . '
            </a>

            <div class="pt-4 mt-4 border-t border-white/5">
                <a href="index.php?reset=1" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 text-amber-500/70 hover:bg-amber-500/10 hover:text-amber-500 border-r-4 border-transparent">
                    <i class="fa-solid fa-right-left text-lg"></i>
                    <span class="font-bold text-sm tracking-tight">Changer Association</span>
                </a>
            </div>

        </nav>

        <div class="p-4 border-t border-white/5 space-y-3 bg-white/[0.01]">
            <a href="index.php?module=barman&action=monProfil" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/5 transition-all group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-blue-400 p-0.5 shadow-lg shadow-blue-600/20">
                    <div class="w-full h-full rounded-[10px] bg-[#020617] overflow-hidden flex items-center justify-center text-white">
                        ' . (($cheminPhoto && file_exists($cheminPhoto))
                ? '<img src="' . $cheminPhoto . '" class="w-full h-full object-cover">'
                : '<span class="text-xs font-black">' . strtoupper(substr($prenom, 0, 1)) . '</span>') . '
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">' . htmlspecialchars($prenom) . '</p>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic">Barman Staff</p>
                </div>
            </a>

            <a href="index.php?module=connexion&action=deconnexion" class="flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl text-red-500 bg-red-500/5 hover:bg-red-500 hover:text-white transition-all duration-300 group shadow-lg shadow-red-900/5 border border-red-500/10">
                <i class="fa-solid fa-power-off text-sm group-hover:rotate-90 transition-transform duration-500"></i>
                <span class="font-black text-[11px] uppercase tracking-widest">Quitter</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 max-h-screen overflow-y-auto custom-scrollbar bg-[#020617] p-8">';
    }
    public function afficherCommandes($commandes)
    {
    $this->afficherNav();
    ?>
    <div style="padding: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 2rem;">
            <i class="fa-solid fa-list-check" style="color: #f59e0b;"></i> Commandes du Jour
        </h1>

        <?php if (empty($commandes)): ?>
            <div style="text-align: center; padding: 4rem; background: rgba(255,255,255,0.02); border-radius: 20px; border: 2px dashed rgba(255,255,255,0.1);">
                <i class="fa-solid fa-check-circle" style="font-size: 4rem; color: #10b981; margin-bottom: 1rem;"></i>
                <p style="color: #94a3b8; font-size: 1.2rem; font-weight: 600;">Aucune commande en cours</p>
            </div>
        <?php else: ?>
            <div style="display: grid; gap: 1.5rem;">
                <?php foreach ($commandes as $cmd):
                    // Configuration des statuts
                    $statutsConfig = [
                        'en_attente' => [
                            'label' => 'En attente',
                            'color' => '#f59e0b',
                            'bg' => 'rgba(245, 158, 11, 0.1)',
                            'border' => 'rgba(245, 158, 11, 0.3)',
                            'icon' => 'clock',
                            'next' => 'validee',
                            'nextLabel' => 'Valider'
                        ],
                        'validee' => [
                            'label' => 'Validée',
                            'color' => '#3b82f6',
                            'bg' => 'rgba(59, 130, 246, 0.1)',
                            'border' => 'rgba(59, 130, 246, 0.3)',
                            'icon' => 'check',
                            'next' => 'en_preparation',
                            'nextLabel' => 'Mettre en préparation'
                        ],
                        'en_preparation' => [
                            'label' => 'En préparation',
                            'color' => '#f59e0b',
                            'bg' => 'rgba(245, 158, 11, 0.1)',
                            'border' => 'rgba(245, 158, 11, 0.3)',
                            'icon' => 'spinner',
                            'next' => 'prete',
                            'nextLabel' => 'Marquer comme prête'
                        ],
                        'prete' => [
                            'label' => 'Prête',
                            'color' => '#10b981',
                            'bg' => 'rgba(16, 185, 129, 0.1)',
                            'border' => 'rgba(16, 185, 129, 0.3)',
                            'icon' => 'check-double',
                            'next' => 'livree',
                            'nextLabel' => 'Marquer comme livrée'
                        ]
                    ];

                    $statutActuel = $cmd['statut'] ?? 'en_attente';
                    $config = $statutsConfig[$statutActuel] ?? $statutsConfig['en_attente'];
                    ?>
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 1.5rem;">
                        <!-- En-tête -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <div>
                                <p style="font-size: 1.3rem; font-weight: 900; color: #f59e0b; font-family: monospace;">
                                    #<?= $cmd['commande_id'] ?? 'N/A' ?>
                                </p>
                                <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.3rem;">
                                    <i class="fa-solid fa-user"></i> <?= htmlspecialchars($cmd['prenom'] . ' ' . $cmd['nom']) ?>
                                </p>
                                <p style="color: #64748b; font-size: 0.85rem;">
                                    <i class="fa-solid fa-clock"></i> <?= date('H:i', strtotime($cmd['date_vente'])) ?>
                                </p>
                            </div>
                            <div>
                                <!-- Badge statut -->
                                <span style="padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700; font-size: 0.85rem; background: <?= $config['bg'] ?>; color: <?= $config['color'] ?>; border: 1px solid <?= $config['border'] ?>;">
                                    <i class="fa-solid fa-<?= $config['icon'] ?>"></i> <?= $config['label'] ?>
                                </span>
                            </div>
                        </div>

                        <!-- Montant -->
                        <div style="margin-bottom: 1rem;">
                            <span style="color: #94a3b8; font-size: 0.9rem; text-transform: uppercase; font-weight: 700;">Total : </span>
                            <span style="font-size: 1.5rem; font-weight: 900; color: #f59e0b;"><?= number_format($cmd['montant_total'], 2) ?> €</span>
                        </div>

                        <!-- Boutons d'action -->
                        <div style="display: flex; gap: 0.75rem;">
                            <!-- Bouton voir détails -->
                            <a href="index.php?module=barman&action=detailCommande&id=<?= $cmd['commande_id'] ?>"
                               style="flex: 1; text-align: center; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: #94a3b8; text-decoration: none; font-weight: 700; font-size: 0.85rem;">
                                <i class="fa-solid fa-eye"></i> Voir détails
                            </a>

                            <!-- Bouton changer statut (si pas livree) -->
                            <?php if (isset($config['next'])): ?>
                                <form method="POST" action="index.php?module=barman&action=changerStatut" style="flex: 2;">
                                    <input type="hidden" name="vente_id" value="<?= $cmd['commande_id'] ?>">
                                    <input type="hidden" name="nouveau_statut" value="<?= $config['next'] ?>">
                                    <button type="submit" style="width: 100%; padding: 0.75rem; background: #f59e0b; border: none; border-radius: 10px; color: white; font-weight: 900; font-size: 0.85rem; cursor: pointer; text-transform: uppercase;">
                                        <i class="fa-solid fa-arrow-right"></i> <?= $config['nextLabel'] ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    </main></div></body></html>
    <?php
}
    private function afficherFooter() {
        ?>
        </div>
        <footer style="text-align: center; padding: 40px; color: #94a3b8; font-size: 0.8rem;">
            &copy; <?= date('Y') ?> - Gestion Buvette Staff - Connecté en tant que Barman
        </footer>
        </body>
        </html>
        <?php
    }


    public function afficherAccueil($stats, $dernierClient, $ventesRecentes, $stockCritiqueListe)
    {
        $this->afficherHeader("Dashboard Barman");
        $this->afficherNav();

        $prenom = $_SESSION['prenom'] ?? 'Barman';

        // Formatage des données pour l'affichage
        $ca = number_format($stats['ca'], 2);
        $nomClientActif = $dernierClient ? htmlspecialchars($dernierClient['prenom'] . ' ' . $dernierClient['nom']) : 'Aucun';
        ?>
        <div class="max-w-6xl mx-auto space-y-8 animate-fadeIn">

        <div class="flex flex-col md:flex-row justify-between items-end gap-4 border-b border-white/5 pb-6">
            <div>
                <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.4em] mb-2">Statut du service :
                    Ouvert</p>
                <h1 class="text-5xl font-[900] text-white italic uppercase tracking-tighter">
                    Dashboard <span class="text-blue-600">Global</span>
                </h1>
            </div>
            <div class="text-right">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Session de service</p>
                <p class="text-white font-black text-xl italic" id="liveClock"><?= date('H:i') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Ventes (24h)</p>
                <p class="text-2xl font-black text-white italic"><?= $ca ?> €</p>
            </div>

            <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Commandes</p>
                <p class="text-2xl font-black text-blue-500 italic"><?= $stats['nb_commandes'] ?> En cours</p>
            </div>

            <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl border-red-500/20">
                <p class="text-[9px] font-black text-red-500 uppercase tracking-widest mb-1">Stock Critique</p>
                <p class="text-2xl font-black text-white italic"><?= $stats['stock_critique'] ?> Articles</p>
            </div>

            <div class="bg-[#0f172a] border-2 border-blue-600 p-6 rounded-3xl shadow-[0_0_20px_rgba(37,99,235,0.2)]">
                <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">Dernier Client</p>
                <p class="text-lg font-bold text-white truncate"><?= $nomClientActif ?></p>
            </div>
        </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-4">
                    <div class="flex justify-between items-center px-2">
                        <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Flux des ventes récentes</h3>
                        <a href="index.php?module=barman&action=historiqueCommandes"
                           class="text-[10px] font-bold text-blue-500 hover:text-white transition-colors uppercase">Voir tout</a>
                    </div>

                    <div class="bg-white/[0.02] border border-white/5 rounded-[2rem] overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-white/[0.02] text-[9px] font-black text-slate-500 uppercase tracking-widest">
                            <tr>
                                <th class="p-5">Client</th>
                                <th class="p-5 text-center">Articles</th>
                                <th class="p-5 text-right">Total</th>
                                <th class="p-5 text-right">Heure</th>
                            </tr>
                            </thead>
                            <tbody class="text-sm font-bold text-slate-300 divide-y divide-white/5">

                            <?php if (empty($ventesRecentes)): ?>
                                <tr><td colspan="4" class="p-5 text-center text-slate-500 italic">Aucune vente aujourd'hui</td></tr>
                            <?php else: ?>
                                <?php foreach ($ventesRecentes as $vente):
                                    $heure = date('H:i', strtotime($vente['date_vente']));
                                    ?>
                                    <tr class="hover:bg-white/[0.02] transition-colors">
                                        <td class="p-5 text-white"><?= htmlspecialchars($vente['prenom'] . ' ' . $vente['nom']) ?></td>
                                        <td class="p-5 text-center"><?= $vente['nb_articles'] ?></td>
                                        <td class="p-5 text-right text-blue-400"><?= number_format($vente['montant_total'], 2) ?> €</td>
                                        <td class="p-5 text-right text-slate-500"><?= $heure ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-xs font-black text-white uppercase tracking-[0.2em] px-2 text-center md:text-left">
                        Infos Buvette</h3>

                    <?php if (!empty($stockCritiqueListe)): ?>
                        <div class="bg-red-500/5 border border-red-500/20 p-6 rounded-[2rem] space-y-4">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                                <span class="text-[10px] font-black text-red-500 uppercase tracking-widest">Réapprovisionnement nécessaire</span>
                            </div>
                            <ul class="space-y-3">
                                <?php foreach ($stockCritiqueListe as $produit): ?>
                                    <li class="flex justify-between items-center text-xs font-bold text-white">
                                        <span><?= htmlspecialchars($produit['nom']) ?></span>
                                        <span class="bg-red-500/20 px-2 py-0.5 rounded text-[10px]">Restant: <?= $produit['quantiteActuelle'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="bg-emerald-500/5 border border-emerald-500/20 p-6 rounded-[2rem] space-y-4">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-check-circle text-emerald-500"></i>
                                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Stocks OK</span>
                            </div>
                            <p class="text-xs text-slate-400">Aucun produit en rupture critique.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <script>
            // Gestion de l'horloge
            function updateClock() {
                const now = new Date();
                const time = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
                const clockElement = document.getElementById('liveClock');
                if (clockElement) clockElement.innerText = time;
            }

            setInterval(updateClock, 60000);

            // --- AJOUT DU RACCOURCI CLAVIER ---
            document.addEventListener('keydown', function (event) {
                // Détecte CTRL + N (ou CMD + N sur Mac)
                if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'n') {
                    event.preventDefault();
                    window.location.href = 'index.php?module=barman&action=creerTransaction';
                }
            });
        </script>
        <?php
    }
    public function afficherDerniereTransaction($transaction)
    {
        $this->afficherHeader("Dernière transaction");
        $this->afficherNav();
        ?>
        <h2>Dernière transaction effectuée</h2>

        <?php if (!$transaction): ?>
        <p>Aucune transaction trouvée.</p>
    <?php else: ?>
        <div style="background-color: <?= ($transaction['statut'] ?? 'payee') === 'annulee' ? '#ffebee' : '#f9f9f9' ?>; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>Transaction #<?= htmlspecialchars($transaction['transaction_id']) ?></h3>
            <p><strong>Date:</strong> <?= htmlspecialchars($transaction['date_vente']) ?></p>
            <p><strong>Client:</strong> <?= htmlspecialchars($transaction['prenom'] . ' ' . $transaction['nom']) ?>
                (ID: <?= htmlspecialchars($transaction['client_id']) ?>)</p>
            <p><strong>Montant total:</strong> <?= htmlspecialchars($transaction['montant_total']) ?> €</p>
            <p><strong>Statut:</strong>
                <span style="color: <?= ($transaction['statut'] ?? 'payee') === 'annulee' ? 'red' : 'green' ?>; font-weight: bold;">
                    <?= htmlspecialchars(ucfirst($transaction['statut'] ?? 'payee')) ?>
                </span>
            </p>
        </div>

        <?php if (!empty($transaction['produits'])): ?>
            <h3>Produits commandés</h3>
            <table>
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($transaction['produits'] as $produit): ?>
                    <tr>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td><?= htmlspecialchars($produit['quantite']) ?></td>
                        <td><?= htmlspecialchars($produit['prix_unitaire']) ?> €</td>
                        <td><?= htmlspecialchars($produit['quantite'] * $produit['prix_unitaire']) ?> €</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (($transaction['statut'] ?? 'payee') !== 'annulee'): ?>
            <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 5px;">
                <h4>⚠️ Annuler cette transaction</h4>
                <p>L'annulation de cette transaction va:</p>
                <ul>
                    <li>Rembourser le client de <?= htmlspecialchars($transaction['montant_total']) ?> €</li>
                    <li>Remettre les produits en stock</li>
                    <li>Marquer la transaction comme annulée</li>
                </ul>
                <form method="post" action="index.php"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette transaction ? Cette action est irréversible.')">
                    <input type="hidden" name="action" value="annulerTransaction">
                    <input type="hidden" name="transaction_id"
                           value="<?= htmlspecialchars($transaction['transaction_id']) ?>">
                    <button type="submit" class="btn btn-danger">Annuler la transaction</button>
                </form>
            </div>
        <?php else: ?>
            <div style="margin-top: 20px; padding: 15px; background-color: #ffebee; border: 1px solid #f44336; border-radius: 5px;">
                <p><strong>Cette transaction a déjà été annulée.</strong></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
        <?php
        $this->afficherFooter();
    }

    public function afficherConfirmationAnnulation($transaction_id)
    {
        $this->afficherHeader("Transaction annulée");
        $this->afficherNav();
        ?>
        <h2>Transaction annulée avec succès</h2>
        <div class="succes" style="background-color: #d4edda; border-color: #c3e6cb;">
            <h3>✓ Transaction #<?= htmlspecialchars($transaction_id) ?> annulée</h3>
            <p>La transaction a été annulée avec succès.</p>
            <ul>
                <li>Le client a été remboursé</li>
                <li>Les stocks ont été remis à jour</li>
                <li>La transaction est maintenant marquée comme annulée</li>
            </ul>
        </div>

        <div style="margin-top: 20px;">
            <a href="index.php?action=derniereTransaction" class="btn btn-secondary">Voir la dernière transaction</a>
            <a href="index.php?action=creerTransaction" class="btn btn-primary">Nouvelle transaction</a>
            <a href="index.php?action=commandesEnCours" class="btn btn-secondary">Voir toutes les commandes</a>
        </div>
        <?php
        $this->afficherFooter();
    }

    public function afficherHistoriqueCommandes($commandes)
    {
        $this->afficherHeader("Historique des ventes");
        $this->afficherNav();
        ?>
        <div class="max-w-6xl mx-auto space-y-8 animate-fadeIn">

            <div class="flex flex-col md:flex-row justify-between items-end gap-4 border-b border-white/5 pb-6">
                <div>
                    <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.4em] mb-2">Suivi de
                        l'activité</p>
                    <h2 class="text-4xl font-[900] text-white italic uppercase tracking-tighter">
                        Historique <span class="text-blue-600">des ventes</span>
                    </h2>
                </div>
            </div>

            <?php if (empty($commandes)): ?>
                <div class="bg-white/[0.02] border border-white/5 p-12 rounded-[2rem] text-center">
                    <i class="fa-solid fa-box-open text-4xl text-slate-700 mb-4"></i>
                    <p class="text-slate-500 font-bold uppercase tracking-widest">Aucune commande dans l'historique.</p>
                </div>
            <?php else:
                $totalTerminees = 0;
                $totalAnnulees = 0;
                $montantTotal = 0;

                foreach ($commandes as $c) {
                    if (($c['statut'] ?? '') === 'annulee') {
                        $totalAnnulees++;
                    }
                    else {
                        $totalTerminees++;
                        $montantTotal += $c['montant_total'];
                    }
                }
                ?>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-8 h-8 bg-emerald-500/20 text-emerald-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Payées /
                                Terminées</p>
                        </div>
                        <p class="text-3xl font-black text-white italic"><?= $totalTerminees ?></p>
                    </div>

                    <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-8 h-8 bg-red-500/20 text-red-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-xmark-circle"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Annulées</p>
                        </div>
                        <p class="text-3xl font-black text-white italic"><?= $totalAnnulees ?></p>
                    </div>

                    <div class="bg-blue-600 p-6 rounded-3xl shadow-xl shadow-blue-900/20">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-8 h-8 bg-white/20 text-white rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-euro-sign"></i>
                            </div>
                            <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest">Chiffre
                                d'affaires</p>
                        </div>
                        <p class="text-3xl font-black text-white italic"><?= number_format($montantTotal, 2) ?> €</p>
                    </div>
                </div>

                <div class="bg-white/[0.02] border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-white/[0.02] text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <th class="p-6">ID</th>
                            <th class="p-6">Date & Heure</th>
                            <th class="p-6">Client</th>
                            <th class="p-6">Montant</th>
                            <th class="p-6 text-center">Statut</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm font-bold">
                        <?php foreach ($commandes as $commande): ?>
                            <tr class="group hover:bg-white/[0.01] transition-all">
                                <td class="p-6">
                <span class="text-white font-black opacity-40 group-hover:opacity-100 transition-opacity">
                    #<?= htmlspecialchars($commande['commande_id'] ?? '') ?>
                </span>
                                </td>

                                <td class="p-6 text-slate-400 font-medium">
                                    <?= htmlspecialchars($commande['date_heure_affichage'] ?? '') ?>
                                </td>

                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-600/10 flex items-center justify-center text-[10px] text-blue-500">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <span class="text-white">
                        <?= htmlspecialchars(($commande['prenom'] ?? '') . ' ' . ($commande['nom'] ?? '')) ?>
                    </span>
                                    </div>
                                </td>

                                <td class="p-6 text-blue-400 font-black">
                                    <?= number_format($commande['montant_total'] ?? 0, 2) ?> €
                                </td>

                                <td class="p-6 text-center">
                                    <?php
                                    if (($commande['statut'] ?? '') === 'annulee') {
                                        // Si c'est annulé -> Rouge
                                        echo '<span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-red-500/10 text-red-500">Annulée</span>';
                                    } else {
                                        // Sinon (payee, null, etc) -> Vert "Terminée"
                                        echo '<span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-500">Terminée</span>';
                                    }
                                    ?>
                                </td>

                                <td class="p-6 text-right">
                                    <a href="index.php?module=barman&action=detailCommande&id=<?= $commande['commande_id'] ?? '' ?>"
                                       class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/5 text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-lg">
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function afficherClients($clients, $search = null)
    {
        $this->afficherNav();
        ?>
        <div style="padding: 2rem;">
            <h1 style="font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 2rem;">
                <i class="fa-solid fa-users" style="color: #f59e0b;"></i> Rechercher un Client
            </h1>

            <!-- Formulaire de recherche -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.5rem; margin-bottom: 2rem;">
                <form method="GET" action="index.php" style="display: flex; gap: 1rem; align-items: end;">
                    <input type="hidden" name="module" value="barman">
                    <input type="hidden" name="action" value="rechercherClient">

                    <div style="flex: 1;">
                        <label style="display: block; color: #94a3b8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase;">
                            <i class="fa-solid fa-search"></i> Rechercher par nom, prénom ou email
                        </label>
                        <input type="text"
                               name="search"
                               value="<?= htmlspecialchars($search ?? '') ?>"
                               placeholder="Ex: Dupont, Jean, jean@example.com..."
                               autofocus
                               style="width: 100%; padding: 1rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: white; font-weight: 600; font-size: 1rem;">
                    </div>

                    <button type="submit" style="padding: 1rem 2rem; background: #f59e0b; border: none; border-radius: 12px; color: white; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 0.9rem;">
                        <i class="fa-solid fa-search"></i> Rechercher
                    </button>

                    <?php if ($search): ?>
                        <a href="index.php?module=barman&action=rechercherClient"
                           style="padding: 1rem 2rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #94a3b8; font-weight: 900; text-transform: uppercase; font-size: 0.9rem; text-decoration: none; display: inline-block;">
                            <i class="fa-solid fa-rotate"></i> Réinitialiser
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Résultats -->
            <?php if ($search && empty($clients)): ?>
                <div style="text-align: center; padding: 3rem; background: rgba(255,255,255,0.02); border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1);">
                    <i class="fa-solid fa-user-slash" style="font-size: 3rem; color: #64748b; margin-bottom: 1rem;"></i>
                    <p style="color: #94a3b8; font-size: 1.1rem; font-weight: 600;">Aucun client trouvé</p>
                    <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">
                        Essayez une autre recherche ou vérifiez que le client fait partie de votre association
                    </p>
                </div>

            <?php elseif (!$search): ?>
                <div style="text-align: center; padding: 3rem; background: rgba(255,255,255,0.02); border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1);">
                    <i class="fa-solid fa-search" style="font-size: 3rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                    <p style="color: #94a3b8; font-size: 1.1rem; font-weight: 600;">Recherchez un client pour commencer</p>
                    <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">
                        Entrez le nom, prénom ou email du client de votre association
                    </p>
                </div>

            <?php else: ?>
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.5rem;">
                    <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 1.5rem;">
                        <i class="fa-solid fa-users"></i> <?= count($clients) ?> client(s) trouvé(s)
                    </p>

                    <div style="display: grid; gap: 1rem;">
                        <?php foreach ($clients as $client): ?>
                            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3 style="color: white; font-weight: 900; font-size: 1.2rem; margin-bottom: 0.5rem;">
                                        <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?>
                                    </h3>
                                    <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.25rem;">
                                        <i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($client['email'] ?? 'N/A') ?>
                                    </p>
                                    <?php if (isset($client['nom_association'])): ?>
                                        <p style="color: #3b82f6; font-size: 0.85rem;">
                                            <i class="fa-solid fa-building"></i> <?= htmlspecialchars($client['nom_association']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <p style="color: #10b981; font-weight: 700; font-size: 1.1rem; margin-top: 0.5rem;">
                                        <i class="fa-solid fa-wallet"></i> Solde: <?= number_format($client['solde'] ?? 0, 2) ?> €
                                    </p>
                                </div>

                                <a href="index.php?module=barman&action=vendre&search_client=<?= urlencode($search) ?>&id_client=<?= $client['id'] ?>"
                                   style="padding: 1rem 2rem; background: #f59e0b; border-radius: 12px; color: white; font-weight: 900; text-transform: uppercase; font-size: 0.9rem; text-decoration: none; white-space: nowrap;">
                                    <i class="fa-solid fa-cash-register"></i> Encaisser
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- NE PAS FERMER </main></div></body></html> ICI CAR afficherNav() L'A DÉJÀ OUVERT -->
        <?php
    }
    public function afficherDetailCommande($commande, $produits)
    {
        $this->afficherHeader("Détail commande");
        $this->afficherNav();
        ?>
        <div class="max-w-4xl mx-auto space-y-8 animate-fadeIn">

            <div class="flex items-center justify-between">
                <a href="index.php?module=barman&action=historiqueCommandes"
                   class="group flex items-center gap-2 text-slate-500 hover:text-white transition-colors">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Retour à l'historique</span>
                </a>
                <button onclick="window.print()" class="text-slate-500 hover:text-blue-500 transition-colors">
                    <i class="fa-solid fa-print"></i>
                </button>
            </div>

            <?php if (!$commande): ?>
                <div class="bg-red-500/10 border border-red-500/20 p-8 rounded-3xl text-center">
                    <p class="text-red-500 font-bold uppercase tracking-widest">Commande introuvable.</p>
                </div>
            <?php else:
                $datetime = new DateTime($commande['date_heure_affichage'] ?? $commande['date_vente']);
                ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white/[0.02] border border-white/5 p-8 rounded-[2.5rem] shadow-xl">
                            <div class="mb-8 border-b border-white/5 pb-4">
                                <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-2">
                                    Ticket</p>
                                <h2 class="text-4xl font-[900] text-white italic tracking-tighter uppercase">
                                    #<?= htmlspecialchars($commande['commande_id'] ?? $commande['id']) ?>
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                                        Client</p>
                                    <p class="text-white font-bold text-lg"><?= htmlspecialchars(($commande['prenom'] ?? '') . ' ' . ($commande['nom'] ?? 'Client Inconnu')) ?></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Date
                                        & Heure</p>
                                    <p class="text-white font-bold"><?= $datetime->format('d/m/Y') ?></p>
                                    <p class="text-slate-400 text-sm italic"><?= $datetime->format('H:i') ?></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                                        Statut</p>
                                    <span class="inline-block px-3 py-1 bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase rounded-lg tracking-widest">
                                    <?= htmlspecialchars($commande['statut_affichage'] ?? 'Validé') ?>
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-600 p-8 rounded-[2.5rem] shadow-2xl shadow-blue-900/40">
                            <p class="text-blue-100 text-[10px] font-black uppercase tracking-widest mb-2">Total
                                Encaissé</p>
                            <p class="text-4xl font-[900] text-white italic">
                                <?= number_format((float)($commande['montant_total'] ?? 0), 2) ?> €
                            </p>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white/[0.02] border border-white/5 rounded-[2.5rem] overflow-hidden shadow-xl">
                            <div class="p-8 border-b border-white/5 bg-white/[0.01]">
                                <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Détails des
                                    articles</h3>
                            </div>

                            <?php if (empty($produits)): ?>
                                <div class="p-12 text-center">
                                    <i class="fa-solid fa-ghost text-slate-700 text-3xl mb-4"></i>
                                    <p class="text-slate-500 font-bold uppercase text-[10px] tracking-widest">Le panier
                                        est vide</p>
                                </div>
                            <?php else: ?>
                                <table class="w-full">
                                    <thead class="bg-white/[0.02] text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                    <tr>
                                        <th class="p-6">Produit</th>
                                        <th class="p-6 text-center">Quantité</th>
                                        <th class="p-6 text-right">Sous-total</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                    <?php foreach ($produits as $p):
                                        // PROTECTION : On cherche 'prix' ou 'prix_unitaire'
                                        $prixUnitaire = (float)($p['prix'] ?? $p['prix_unitaire'] ?? 0);
                                        $quantite = (int)($p['quantite'] ?? 0);
                                        $ligneTotal = $prixUnitaire * $quantite;
                                        ?>
                                        <tr class="group hover:bg-white/[0.01] transition-all">
                                            <td class="p-6">
                                                <p class="text-white font-bold group-hover:text-blue-400 transition-colors">
                                                    <?= htmlspecialchars($p['nom'] ?? 'Produit inconnu') ?>
                                                </p>
                                                <p class="text-[10px] text-slate-500 font-medium italic">
                                                    Prix unitaire : <?= number_format($prixUnitaire, 2) ?> €
                                                </p>
                                            </td>
                                            <td class="p-6 text-center">
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/5 text-white font-black text-sm">
                                                <?= $quantite ?>
                                            </span>
                                            </td>
                                            <td class="p-6 text-right">
                                                <span class="text-white font-black"><?= number_format($ligneTotal, 2) ?> €</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr class="bg-white/[0.04]">
                                        <td colspan="2"
                                            class="p-6 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                            Montant Total du Ticket
                                        </td>
                                        <td class="p-6 text-right text-2xl font-[900] text-blue-500 italic">
                                            <?= number_format((float)($commande['montant_total'] ?? 0), 2) ?> €
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <style>
            .animate-fadeIn {
                animation: fadeIn 0.4s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Nettoyage pour l'impression du ticket */
            @media print {
                nav, .sidebar, button, a {
                    display: none !important;
                }

                body {
                    background: white !important;
                    color: black !important;
                }

                .max-w-4xl {
                    max-width: 100% !important;
                    margin: 0 !important;
                }

                .bg-white\/\[0\.02\], .bg-blue-600 {
                    background: none !important;
                    border: 1px solid #ddd !important;
                    color: black !important;
                    box-shadow: none !important;
                }

                .text-white, .text-blue-500, .text-blue-100 {
                    color: black !important;
                }

                .rounded-\[2\.5rem\] {
                    border-radius: 0 !important;
                }
            }
        </style>
        <?php
    }

    public function afficherErreur($message)
    {
        $this->afficherHeader("Erreur");
        $this->afficherNav();
        ?>
        <div class="erreur">
            <strong>Erreur:</strong> <?= htmlspecialchars($message) ?>
        </div>
        <?php
        $this->afficherFooter();
    }

    public function afficherFormTransaction($produits = [], $types = [], $clientId = null, $searchProduit = null, $typeProduit = null, $erreur = null, $donneesSaisies = null)
    {
        $this->afficherNav();
        ?>
        <div style="padding: 2rem;">
            <h1 style="font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 2rem;">
                <i class="fa-solid fa-cash-register" style="color: #f59e0b;"></i> Nouvelle Transaction
            </h1>

            <?php if ($erreur): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #ef4444;">
                    <strong><i class="fa-solid fa-exclamation-triangle"></i> Erreur:</strong> <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <!-- Barre de recherche et filtres -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.5rem; margin-bottom: 2rem;">
                <form method="GET" action="index.php" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                    <input type="hidden" name="module" value="barman">
                    <input type="hidden" name="action" value="creerTransaction">
                    <input type="hidden" name="id_client" value="<?= htmlspecialchars($clientId) ?>">

                    <!-- Recherche par nom -->
                    <div style="flex: 2; min-width: 250px;">
                        <label style="display: block; color: #94a3b8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase;">
                            <i class="fa-solid fa-search"></i> Rechercher un produit
                        </label>
                        <input type="text" name="search_produit" value="<?= htmlspecialchars($searchProduit ?? '') ?>" placeholder="Nom du produit..."
                               style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; font-weight: 600;">
                    </div>

                    <!-- Filtre par type -->
                    <div style="flex: 1; min-width: 200px;">
                        <label style="display: block; color: #94a3b8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase;">
                            <i class="fa-solid fa-filter"></i> Type
                        </label>
                        <select name="type_produit" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; font-weight: 600;">
                            <option value="tous" <?= !$typeProduit || $typeProduit === 'tous' ? 'selected' : '' ?>>Tous les types</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= htmlspecialchars($type) ?>" <?= $typeProduit === $type ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(ucfirst($type)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" style="padding: 0.75rem 1.5rem; background: #f59e0b; border: none; border-radius: 10px; color: white; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 0.85rem;">
                            <i class="fa-solid fa-search"></i> Filtrer
                        </button>
                        <a href="index.php?module=barman&action=creerTransaction&id_client=<?= htmlspecialchars($clientId) ?>"
                           style="padding: 0.75rem 1.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: #94a3b8; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 0.85rem; text-decoration: none; display: inline-block;">
                            <i class="fa-solid fa-rotate"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <!-- Formulaire transaction -->
            <form method="POST" action="index.php?module=barman&action=traiterTransaction" id="formTransaction">
                <input type="hidden" name="client_id" value="<?= htmlspecialchars($clientId) ?>">

                <!-- Liste des produits -->
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.5rem; margin-bottom: 2rem;">
                    <h3 style="color: white; font-weight: 900; margin-bottom: 1.5rem; font-size: 1.2rem;">
                        <i class="fa-solid fa-box-open" style="color: #f59e0b;"></i> Sélectionner les produits
                    </h3>

                    <?php if (empty($produits)): ?>
                        <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                            <i class="fa-solid fa-box"></i> Aucun produit trouvé
                        </p>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                            <?php foreach ($produits as $p): ?>
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1rem; position: relative;">
                                    <!-- Nom et prix -->
                                    <div style="margin-bottom: 0.75rem;">
                                        <h4 style="color: white; font-weight: 900; font-size: 1rem; margin-bottom: 0.25rem;">
                                            <?= htmlspecialchars($p['nom']) ?>
                                        </h4>
                                        <p style="color: #f59e0b; font-weight: 700; font-size: 1.2rem;">
                                            <?= number_format($p['prix'], 2) ?> €
                                        </p>
                                        <?php if (!empty($p['type'])): ?>
                                            <span style="display: inline-block; padding: 0.25rem 0.5rem; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 6px; font-size: 0.7rem; font-weight: 700; margin-top: 0.25rem;">
                                                         <?= htmlspecialchars(ucfirst($p['type'])) ?>
                                         </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Stock -->
                                    <p style="color: <?= $p['disponibilite'] > 0 ? '#10b981' : '#ef4444' ?>; font-size: 0.85rem; margin-bottom: 0.75rem;">
                                        <i class="fa-solid fa-box"></i> Stock: <?= $p['disponibilite'] ?>
                                    </p>

                                    <!-- Quantité -->
                                    <?php if ($p['disponibilite'] > 0): ?>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="number"
                                                   name="produits[<?= $p['id'] ?>]"
                                                   min="0"
                                                   max="<?= $p['disponibilite'] ?>"
                                                   value="0"
                                                   data-prix="<?= $p['prix'] ?>"
                                                   data-nom="<?= htmlspecialchars($p['nom']) ?>"
                                                   class="quantite-input"
                                                   style="flex: 1; padding: 0.5rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-weight: 700; text-align: center;">
                                            <button type="button" onclick="incrementer(<?= $p['id'] ?>, <?= $p['disponibilite'] ?>)"
                                                    style="padding: 0.5rem 0.75rem; background: #f59e0b; border: none; border-radius: 8px; color: white; font-weight: 900; cursor: pointer;">
                                                +
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <p style="color: #ef4444; font-size: 0.85rem; font-weight: 700;">
                                            <i class="fa-solid fa-ban"></i> Rupture de stock
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Récapitulatif et validation -->
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.5rem;">
                    <h3 style="color: white; font-weight: 900; margin-bottom: 1rem; font-size: 1.2rem;">
                        <i class="fa-solid fa-receipt" style="color: #f59e0b;"></i> Récapitulatif
                    </h3>

                    <div id="recap" style="min-height: 50px; margin-bottom: 1rem;">
                        <p style="color: #94a3b8; font-style: italic;">Aucun produit sélectionné</p>
                    </div>

                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem; margin-top: 1rem;">
                        <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">Total à encaisser :</p>
                        <p style="color: #f59e0b; font-size: 2rem; font-weight: 900;" id="totalPrice">0.00 €</p>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                        <a href="index.php?module=barman&action=rechercherClient"
                           style="flex: 1; text-align: center; padding: 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #94a3b8; text-decoration: none; font-weight: 900; text-transform: uppercase; font-size: 0.9rem;">
                            <i class="fa-solid fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" id="btnValider"
                                style="flex: 2; padding: 1rem; background: #10b981; border: none; border-radius: 12px; color: white; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 0.9rem;">
                            <i class="fa-solid fa-check"></i> Valider la transaction
                        </button>
                    </div>
                </div>
            </form>

            <script>
                function incrementer(id, max) {
                    const input = document.querySelector(`input[name="produits[${id}]"]`);
                    if (parseInt(input.value) < max) {
                        input.value = parseInt(input.value) + 1;
                        updateRecap();
                    }
                }

                // Mise à jour automatique du récap
                document.querySelectorAll('.quantite-input').forEach(input => {
                    input.addEventListener('change', updateRecap);
                });

                function updateRecap() {
                    const inputs = document.querySelectorAll('.quantite-input');
                    let total = 0;
                    let recap = [];

                    inputs.forEach(input => {
                        const qty = parseInt(input.value) || 0;
                        if (qty > 0) {
                            const prix = parseFloat(input.dataset.prix);
                            const nom = input.dataset.nom;
                            total += prix * qty;
                            recap.push(`${nom} × ${qty} = ${(prix * qty).toFixed(2)} €`);
                        }
                    });

                    document.getElementById('totalPrice').textContent = total.toFixed(2) + ' €';

                    if (recap.length > 0) {
                        document.getElementById('recap').innerHTML = recap.map(r =>
                            `<p style="color: white; margin: 0.5rem 0;"><i class="fa-solid fa-check" style="color: #10b981;"></i> ${r}</p>`
                        ).join('');
                        document.getElementById('btnValider').disabled = false;
                    } else {
                        document.getElementById('recap').innerHTML = '<p style="color: #94a3b8; font-style: italic;">Aucun produit sélectionné</p>';
                        document.getElementById('btnValider').disabled = true;
                    }
                }

                // Init
                updateRecap();
            </script>
        </div>
        </main></div></body></html>
        <?php
    }
    public function afficherResultatTransaction($vente_id)
    {
        $this->afficherHeader("Transaction réussie");
        $this->afficherNav();
        ?>
        <h2>Transaction créée avec succès !</h2>
        <div class="succes">
            <h3>Transaction #<?= htmlspecialchars($vente_id) ?></h3>
            <p><strong>Numéro de transaction:</strong> <?= htmlspecialchars($vente_id) ?></p>
            <p><strong>Statut:</strong> Transaction créée avec succès</p>
        </div>

        <p>La transaction a été enregistrée sous le numéro: <strong><?= htmlspecialchars($vente_id) ?></strong></p>
        <p>Le solde du client a été débité et les stocks ont été mis à jour.</p>

        <div style="margin-top: 20px;">
            <a href="index.php?action=creerTransaction" class="btn btn-primary">Nouvelle transaction</a>
            <a href="index.php?action=detailCommande&id=<?= $vente_id ?>" class="btn btn-secondary">Voir les détails</a>
        </div>
        <?php
    }
    public function afficherPageVente($clients, $clientSelectionne, $produits, $types, $searchClient, $searchProduit, $typeProduit)
    {
        $this->afficherNav();
        ?>
        <div style="padding: 2rem;">
            <h1 style="font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 2rem;">
                <i class="fa-solid fa-cash-register" style="color: #f59e0b;"></i> Encaissement
            </h1>

            <!-- ===== SECTION 1 : RECHERCHE CLIENT ===== -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.5rem; margin-bottom: 2rem;">
                <h2 style="color: white; font-weight: 900; margin-bottom: 1rem; font-size: 1.2rem;">
                    <i class="fa-solid fa-user" style="color: #f59e0b;"></i> 1. Sélectionner un client
                </h2>

                <?php if (!$clientSelectionne): ?>
                    <!-- Formulaire de recherche -->
                    <form method="GET" action="index.php" style="margin-bottom: 1rem;">
                        <input type="hidden" name="module" value="barman">
                        <input type="hidden" name="action" value="vendre">

                        <div style="display: flex; gap: 1rem; align-items: end;">
                            <div style="flex: 1;">
                                <label style="display: block; color: #94a3b8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase;">
                                    <i class="fa-solid fa-search"></i> Nom, prénom ou email
                                </label>
                                <input type="text" name="search_client" value="<?= htmlspecialchars($searchClient ?? '') ?>"
                                       placeholder="Ex: Dupont, Jean..." autofocus
                                       style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; font-weight: 600;">
                            </div>
                            <button type="submit" style="padding: 0.75rem 1.5rem; background: #f59e0b; border: none; border-radius: 10px; color: white; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 0.85rem;">
                                <i class="fa-solid fa-search"></i> Rechercher
                            </button>
                        </div>
                    </form>

                    <!-- Résultats de recherche -->
                    <?php if ($searchClient && !empty($clients)): ?>
                        <div style="display: grid; gap: 0.75rem; margin-top: 1rem;">
                            <?php foreach ($clients as $client): ?>
                                <a href="index.php?module=barman&action=vendre&search_client=<?= urlencode($searchClient) ?>&id_client=<?= $client['id'] ?>"
                                   style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; padding: 1rem; text-decoration: none; transition: all 0.2s;"
                                   onmouseover="this.style.background='rgba(245, 158, 11, 0.1)'; this.style.borderColor='rgba(245, 158, 11, 0.3)'"
                                   onmouseout="this.style.background='rgba(255,255,255,0.02)'; this.style.borderColor='rgba(255,255,255,0.05)'">
                                    <div>
                                        <p style="color: white; font-weight: 900; font-size: 1rem; margin-bottom: 0.25rem;">
                                            <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?>
                                        </p>
                                        <p style="color: #94a3b8; font-size: 0.85rem;">
                                            <i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($client['email']) ?>
                                        </p>
                                        <?php if (isset($client['nom_association'])): ?>
                                            <p style="color: #3b82f6; font-size: 0.75rem; margin-top: 0.25rem;">
                                                <i class="fa-solid fa-building"></i> <?= htmlspecialchars($client['nom_association']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div style="text-align: right;">
                                        <p style="color: #10b981; font-weight: 700; font-size: 1.1rem;">
                                            <?= number_format($client['solde'], 2) ?> €
                                        </p>
                                        <span style="color: #f59e0b; font-size: 0.85rem; font-weight: 700;">
                                        <i class="fa-solid fa-arrow-right"></i> Sélectionner
                                    </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($searchClient && empty($clients)): ?>
                        <p style="color: #94a3b8; font-style: italic; text-align: center; padding: 1rem;">
                            <i class="fa-solid fa-user-slash"></i> Aucun client trouvé
                        </p>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Client sélectionné -->
                    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 10px; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="color: #10b981; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.25rem;">
                                <i class="fa-solid fa-check-circle"></i> Client sélectionné
                            </p>
                            <p style="color: white; font-weight: 900; font-size: 1.2rem; margin-bottom: 0.25rem;">
                                <?= htmlspecialchars($clientSelectionne['prenom'] . ' ' . $clientSelectionne['nom']) ?>
                            </p>
                            <p style="color: #94a3b8; font-size: 0.85rem;">
                                Solde: <span style="color: #10b981; font-weight: 700;"><?= number_format($clientSelectionne['solde'], 2) ?> €</span>
                            </p>
                        </div>
                        <a href="index.php?module=barman&action=vendre"
                           style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; color: #94a3b8; font-weight: 700; font-size: 0.85rem; text-decoration: none;">
                            <i class="fa-solid fa-rotate"></i> Changer
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ===== SECTION 2 : SÉLECTION PRODUITS (uniquement si client sélectionné) ===== -->
            <?php if ($clientSelectionne): ?>
                <form method="POST" action="index.php?module=barman&action=traiterTransaction" id="formVente">
                    <input type="hidden" name="client_id" value="<?= $clientSelectionne['id'] ?>">

                    <!-- Filtres produits -->
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.5rem; margin-bottom: 2rem;">
                        <h2 style="color: white; font-weight: 900; margin-bottom: 1rem; font-size: 1.2rem;">
                            <i class="fa-solid fa-box-open" style="color: #f59e0b;"></i> 2. Sélectionner les produits
                        </h2>

                        <!-- Barre de recherche -->
                        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                            <div style="flex: 2; min-width: 250px;">
                                <input type="text" id="searchProduit" placeholder="Rechercher un produit..."
                                       style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; font-weight: 600;">
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <select id="filterType" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; font-weight: 600;">
                                    <option value="">Tous les types</option>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars(ucfirst($type)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Grille produits -->
                        <div id="gridProduits" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                            <?php foreach ($produits as $p): ?>
                                <div class="produit-card" data-nom="<?= strtolower(htmlspecialchars($p['nom'])) ?>" data-type="<?= htmlspecialchars($p['type'] ?? '') ?>"
                                     style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1rem;">
                                    <div style="margin-bottom: 0.75rem;">
                                        <h4 style="color: white; font-weight: 900; font-size: 1rem; margin-bottom: 0.25rem;">
                                            <?= htmlspecialchars($p['nom']) ?>
                                        </h4>
                                        <p style="color: #f59e0b; font-weight: 700; font-size: 1.2rem;">
                                            <?= number_format($p['prix'], 2) ?> €
                                        </p>
                                        <?php if (!empty($p['type'])): ?>
                                            <span style="display: inline-block; padding: 0.25rem 0.5rem; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 6px; font-size: 0.7rem; font-weight: 700; margin-top: 0.25rem;">
                                                 <?= htmlspecialchars(ucfirst($p['type'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <p style="color: <?= $p['disponibilite'] > 0 ? '#10b981' : '#ef4444' ?>; font-size: 0.85rem; margin-bottom: 0.75rem;">
                                        <i class="fa-solid fa-box"></i> Stock: <?= $p['disponibilite'] ?>
                                    </p>

                                    <?php if ($p['disponibilite'] > 0): ?>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="number" name="produits[<?= $p['id'] ?>]" min="0" max="<?= $p['disponibilite'] ?>" value="0"
                                                   data-prix="<?= $p['prix'] ?>" data-nom="<?= htmlspecialchars($p['nom']) ?>" class="quantite-input"
                                                   style="flex: 1; padding: 0.5rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-weight: 700; text-align: center;">
                                            <button type="button" onclick="incrementer(<?= $p['id'] ?>, <?= $p['disponibilite'] ?>)"
                                                    style="padding: 0.5rem 0.75rem; background: #f59e0b; border: none; border-radius: 8px; color: white; font-weight: 900; cursor: pointer;">
                                                +
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <p style="color: #ef4444; font-size: 0.85rem; font-weight: 700; text-align: center;">
                                            <i class="fa-solid fa-ban"></i> Rupture
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Récapitulatif -->
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.5rem;">
                        <h3 style="color: white; font-weight: 900; margin-bottom: 1rem; font-size: 1.2rem;">
                            <i class="fa-solid fa-receipt" style="color: #f59e0b;"></i> Récapitulatif
                        </h3>

                        <div id="recap" style="min-height: 50px; margin-bottom: 1rem;">
                            <p style="color: #94a3b8; font-style: italic;">Aucun produit sélectionné</p>
                        </div>

                        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                            <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">Total à encaisser :</p>
                            <p style="color: #f59e0b; font-size: 2rem; font-weight: 900;" id="totalPrice">0.00 €</p>
                        </div>

                        <button type="submit" id="btnValider" disabled
                                style="width: 100%; margin-top: 1.5rem; padding: 1rem; background: #10b981; border: none; border-radius: 12px; color: white; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 1rem;">
                            <i class="fa-solid fa-check"></i> Valider la transaction
                        </button>
                    </div>
                </form>

                <script>
                    // Recherche produits
                    document.getElementById('searchProduit').addEventListener('input', filterProduits);
                    document.getElementById('filterType').addEventListener('change', filterProduits);

                    function filterProduits() {
                        const search = document.getElementById('searchProduit').value.toLowerCase();
                        const type = document.getElementById('filterType').value.toLowerCase();
                        const cards = document.querySelectorAll('.produit-card');

                        cards.forEach(card => {
                            const nom = card.dataset.nom;
                            const cardType = card.dataset.type.toLowerCase();
                            const matchSearch = !search || nom.includes(search);
                            const matchType = !type || cardType === type;

                            card.style.display = (matchSearch && matchType) ? 'block' : 'none';
                        });
                    }

                    // Incrémenter quantité
                    function incrementer(id, max) {
                        const input = document.querySelector(`input[name="produits[${id}]"]`);
                        if (parseInt(input.value) < max) {
                            input.value = parseInt(input.value) + 1;
                            updateRecap();
                        }
                    }

                    // Mise à jour récap
                    document.querySelectorAll('.quantite-input').forEach(input => {
                        input.addEventListener('change', updateRecap);
                    });

                    function updateRecap() {
                        const inputs = document.querySelectorAll('.quantite-input');
                        let total = 0;
                        let recap = [];

                        inputs.forEach(input => {
                            const qty = parseInt(input.value) || 0;
                            if (qty > 0) {
                                const prix = parseFloat(input.dataset.prix);
                                const nom = input.dataset.nom;
                                total += prix * qty;
                                recap.push(`${nom} × ${qty} = ${(prix * qty).toFixed(2)} €`);
                            }
                        });

                        document.getElementById('totalPrice').textContent = total.toFixed(2) + ' €';

                        if (recap.length > 0) {
                            document.getElementById('recap').innerHTML = recap.map(r =>
                                `<p style="color: white; margin: 0.5rem 0;"><i class="fa-solid fa-check" style="color: #10b981;"></i> ${r}</p>`
                            ).join('');
                            document.getElementById('btnValider').disabled = false;
                            document.getElementById('btnValider').style.opacity = '1';
                            document.getElementById('btnValider').style.cursor = 'pointer';
                        } else {
                            document.getElementById('recap').innerHTML = '<p style="color: #94a3b8; font-style: italic;">Aucun produit sélectionné</p>';
                            document.getElementById('btnValider').disabled = true;
                            document.getElementById('btnValider').style.opacity = '0.5';
                            document.getElementById('btnValider').style.cursor = 'not-allowed';
                        }
                    }

                    updateRecap();
                </script>
            <?php endif; ?>
        </div>
        </main></div></body></html>
        <?php
    }
    public function afficherDemandeCodeValidation($client, $client_id, $produits, $recap, $montantTotal, $erreur = null)
    {
        $this->afficherNav();
        ?>
        <div style="padding: 2rem;">
            <h1 style="font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 2rem;">
                <i class="fa-solid fa-key" style="color: #f59e0b;"></i> Validation de la Transaction
            </h1>

            <?php if ($erreur): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #ef4444;">
                    <strong><i class="fa-solid fa-exclamation-triangle"></i> Erreur :</strong> <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Colonne gauche : Récapitulatif -->
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 2rem;">
                    <h2 style="color: white; font-weight: 900; margin-bottom: 1.5rem; font-size: 1.3rem;">
                        <i class="fa-solid fa-receipt" style="color: #f59e0b;"></i> Récapitulatif de la commande
                    </h2>

                    <!-- Client -->
                    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem;">
                        <p style="color: #f59e0b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">
                            Client
                        </p>
                        <p style="color: white; font-weight: 900; font-size: 1.2rem;">
                            <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?>
                        </p>
                    </div>

                    <!-- Produits -->
                    <div style="margin-bottom: 1.5rem;">
                        <h3 style="color: #94a3b8; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem;">
                            Produits
                        </h3>
                        <?php foreach ($recap as $item): ?>
                            <div style="display: flex; justify-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <div>
                                    <p style="color: white; font-weight: 700; margin: 0;">
                                        <?= htmlspecialchars($item['nom']) ?>
                                    </p>
                                    <p style="color: #94a3b8; font-size: 0.85rem; margin: 0;">
                                        <?= $item['quantite'] ?> × <?= number_format($item['prix_unitaire'], 2) ?> €
                                    </p>
                                </div>
                                <p style="color: #f59e0b; font-weight: 900; font-size: 1.1rem; margin: 0;">
                                    <?= number_format($item['sous_total'], 2) ?> €
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Total -->
                    <div style="border-top: 2px solid rgba(245, 158, 11, 0.3); padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <p style="color: #94a3b8; font-size: 0.9rem; text-transform: uppercase; font-weight: 700; margin: 0;">
                                Total à payer :
                            </p>
                            <p style="color: #f59e0b; font-size: 2.5rem; font-weight: 900; margin: 0;">
                                <?= number_format($montantTotal, 2) ?> €
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Saisie du code -->
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 2rem; display: flex; flex-direction: column; justify-content: center;">
                    <div style="text-align: center;">
                        <div style="width: 100px; height: 100px; margin: 0 auto 2rem; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
                            <i class="fa-solid fa-key" style="font-size: 3rem; color: white;"></i>
                        </div>

                        <h2 style="color: white; font-weight: 900; margin-bottom: 1rem; font-size: 1.5rem;">
                            Demandez le code au client
                        </h2>

                        <p style="color: #94a3b8; margin-bottom: 2rem; font-size: 0.95rem;">
                            Le client doit générer un code à 4 chiffres depuis son espace personnel et vous le communiquer
                        </p>

                        <form method="POST" action="index.php?module=barman&action=traiterTransaction">
                            <input type="hidden" name="client_id" value="<?= $client_id ?>">

                            <!-- Produits en hidden -->
                            <?php foreach ($produits as $produit_id => $quantite): ?>
                                <input type="hidden" name="produits[<?= $produit_id ?>]" value="<?= $quantite ?>">
                            <?php endforeach; ?>

                            <!-- Champ code -->
                            <div style="margin-bottom: 2rem;">
                                <input type="text"
                                       name="code_validation"
                                       placeholder="0000"
                                       maxlength="4"
                                       pattern="[0-9]{4}"
                                       required
                                       autofocus
                                       style="width: 100%; padding: 1.5rem; background: rgba(0,0,0,0.5); border: 2px solid rgba(245, 158, 11, 0.3); border-radius: 15px; color: #f59e0b; font-weight: 900; font-size: 3rem; text-align: center; letter-spacing: 1rem; font-family: 'Courier New', monospace;">
                            </div>

                            <!-- Boutons -->
                            <div style="display: flex; gap: 1rem;">
                                <a href="index.php?module=barman&action=vendre"
                                   style="flex: 1; text-align: center; padding: 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #94a3b8; text-decoration: none; font-weight: 900; text-transform: uppercase; font-size: 0.9rem;">
                                    <i class="fa-solid fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit"
                                        style="flex: 2; padding: 1rem; background: #10b981; border: none; border-radius: 12px; color: white; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 0.9rem;">
                                    <i class="fa-solid fa-check"></i> Valider la transaction
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </main></div></body></html>
        <?php
    }

}