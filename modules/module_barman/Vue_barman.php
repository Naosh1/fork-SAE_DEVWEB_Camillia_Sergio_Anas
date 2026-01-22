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
            
            <a href="index.php?module=barman&action=creerTransaction" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'creerTransaction' ? $activeClass : $inactiveClass) . '">
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
        $this->afficherHeader("Commandes du jour");
        $this->afficherNav();
        ?>
        <div class="max-w-6xl mx-auto space-y-8 animate-fadeIn">

            <div class="flex flex-col md:flex-row justify-between items-end gap-6 border-b border-white/5 pb-8">
                <div>
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-[0.4em] mb-2">Service en
                        cours</p>
                    <h2 class="text-4xl font-[900] text-white italic uppercase tracking-tighter">
                        File des <span class="text-amber-500">Commandes</span>
                    </h2>
                </div>

                <div class="flex gap-2">
                    <div class="bg-white/5 border border-white/5 px-4 py-2 rounded-2xl flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <?= count($commandes) ?> Totales
                    </span>
                    </div>
                </div>
            </div>

            <?php if (empty($commandes)): ?>
                <div class="bg-white/[0.02] border border-white/5 p-20 rounded-[2.5rem] text-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-receipt text-slate-700 text-3xl"></i>
                    </div>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.2em]">Aucune commande enregistrée
                        aujourd'hui</p>
                </div>
            <?php else: ?>
                <div class="bg-white/[0.02] border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-white/[0.02] text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">
                            <th class="p-6">Référence</th>
                            <th class="p-6">Client</th>
                            <th class="p-6">Heure</th>
                            <th class="p-6">Montant</th>
                            <th class="p-6 text-center">Statut</th>
                            <th class="p-6 text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php foreach ($commandes as $commande):
                            $statut = $commande['statut'] ?? 'attente';
                            $isPaid = ($statut == 'payee' || $statut == 'terminee');
                            $badgeClass = $isPaid ? "bg-emerald-500/10 text-emerald-500" : "bg-amber-500/10 text-amber-500";
                            $icon = $isPaid ? "fa-check-double" : "fa-clock";
                            ?>
                            <tr class="group hover:bg-white/[0.01] transition-all">
                                <td class="p-6">
                                <span class="text-white font-black italic tracking-tighter group-hover:text-amber-500 transition-colors">
                                    #<?= htmlspecialchars($commande['commande_id'] ?? $commande['id']) ?>
                                </span>
                                </td>
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-[10px] text-slate-400 font-black">
                                            <?= strtoupper(substr($commande['prenom'] ?? '?', 0, 1)) ?>
                                        </div>
                                        <span class="text-white font-bold text-sm"><?= htmlspecialchars(($commande['prenom'] ?? '') . ' ' . ($commande['nom'] ?? '')) ?></span>
                                    </div>
                                </td>
                                <td class="p-6 text-slate-500 text-xs font-bold italic">
                                    <?= isset($commande['date_vente']) ? date('H:i', strtotime($commande['date_vente'])) : '--:--' ?>
                                </td>
                                <td class="p-6">
                                    <span class="text-white font-[900]"><?= number_format($commande['montant_total'], 2) ?> €</span>
                                </td>
                                <td class="p-6 text-center">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-[900] uppercase tracking-widest <?= $badgeClass ?> inline-flex items-center gap-2">
                                    <i class="fa-solid <?= $icon ?> text-[10px]"></i>
                                    <?= htmlspecialchars($commande['statut_affichage'] ?? $statut) ?>
                                </span>
                                </td>
                                <td class="p-6 text-right">
                                    <a href="index.php?module=barman&action=detailCommande&id=<?= $commande['commande_id'] ?? $commande['id'] ?>"
                                       class="inline-flex items-center justify-center h-10 px-6 rounded-xl bg-white/5 text-white text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all border border-white/5">
                                        Détails
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
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
        </style>
        <?php
        $this->afficherFooter();
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


    public function afficherAccueil()
    {
        $this->afficherHeader("Dashboard Barman");
        $this->afficherNav();

        $prenom = $_SESSION['prenom'] ?? 'Barman';
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
                    <p class="text-2xl font-black text-white italic">142.50 €</p>
                </div>
                <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Commandes</p>
                    <p class="text-2xl font-black text-blue-500 italic">12 Prêtes</p>
                </div>
                <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl border-red-500/20">
                    <p class="text-[9px] font-black text-red-500 uppercase tracking-widest mb-1">Stock Critique</p>
                    <p class="text-2xl font-black text-white italic">3 Articles</p>
                </div>
                <div class="bg-[#0f172a] border-2 border-blue-600 p-6 rounded-3xl shadow-[0_0_20px_rgba(37,99,235,0.2)]">
                    <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">Client Actif</p>
                    <p class="text-lg font-bold text-white truncate">Lucas Morel</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-4">
                    <div class="flex justify-between items-center px-2">
                        <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Flux des ventes
                            récentes</h3>
                        <a href="index.php?module=barman&action=historiqueCommandes"
                           class="text-[10px] font-bold text-blue-500 hover:text-white transition-colors uppercase">Voir
                            tout</a>
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
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="p-5 text-white">Emma Petit</td>
                                <td class="p-5 text-center">3</td>
                                <td class="p-5 text-right text-blue-400">7.50 €</td>
                                <td class="p-5 text-right text-slate-500">14:22</td>
                            </tr>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="p-5 text-white">Mathieu Blanc</td>
                                <td class="p-5 text-center">1</td>
                                <td class="p-5 text-right text-blue-400">2.50 €</td>
                                <td class="p-5 text-right text-slate-500">14:15</td>
                            </tr>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="p-5 text-white">Laura Dupuis</td>
                                <td class="p-5 text-center">2</td>
                                <td class="p-5 text-right text-blue-400">5.00 €</td>
                                <td class="p-5 text-right text-slate-500">13:58</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-xs font-black text-white uppercase tracking-[0.2em] px-2 text-center md:text-left">
                        Infos Buvette</h3>

                    <div class="bg-red-500/5 border border-red-500/20 p-6 rounded-[2rem] space-y-4">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-widest">Réapprovisionnement nécessaire</span>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex justify-between items-center text-xs font-bold text-white">
                                <span>Coca-Cola 33cl</span>
                                <span class="bg-red-500/20 px-2 py-0.5 rounded text-[10px]">Restant: 4</span>
                            </li>
                            <li class="flex justify-between items-center text-xs font-bold text-white">
                                <span>Sandwich Jambon</span>
                                <span class="bg-red-500/20 px-2 py-0.5 rounded text-[10px]">Restant: 2</span>
                            </li>
                        </ul>
                    </div>


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

                    // Indispensable : empêche le navigateur d'ouvrir une nouvelle fenêtre vide
                    event.preventDefault();

                    // Redirection vers ta page de vente
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

                // Pré-calcul pour le bilan
                foreach ($commandes as $c) {
                    if (($c['statut'] ?? '') === 'payee' || ($c['statut'] ?? '') === 'terminee') {
                        $totalTerminees++;
                        $montantTotal += $c['montant_total'];
                    } elseif (($c['statut'] ?? '') === 'annulee') {
                        $totalAnnulees++;
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
                        <?php foreach ($commandes as $commande):
                            $statut = $commande['statut'] ?? 'attente';
                            $badgeClass = "bg-orange-500/10 text-orange-500";
                            if ($statut === 'payee' || $statut === 'terminee') $badgeClass = "bg-emerald-500/10 text-emerald-500";
                            if ($statut === 'annulee') $badgeClass = "bg-red-500/10 text-red-500";
                            ?>
                            <tr class="group hover:bg-white/[0.01] transition-all">
                                <td class="p-6">
                                    <span class="text-white font-black opacity-40 group-hover:opacity-100 transition-opacity">#<?= htmlspecialchars($commande['commande_id']) ?></span>
                                </td>
                                <td class="p-6 text-slate-400 font-medium">
                                    <?= htmlspecialchars($commande['date_heure_affichage'] ?? 'Date inconnue') ?>
                                </td>
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-600/10 flex items-center justify-center text-[10px] text-blue-500">
                                            <?= strtoupper(substr($commande['prenom'], 0, 1)) ?>
                                        </div>
                                        <span class="text-white"><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></span>
                                    </div>
                                </td>
                                <td class="p-6 text-blue-400 font-black">
                                    <?= number_format($commande['montant_total'], 2) ?> €
                                </td>
                                <td class="p-6 text-center">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest <?= $badgeClass ?>">
                                <?= htmlspecialchars($commande['statut_affichage'] ?? $statut) ?>
                            </span>
                                </td>
                                <td class="p-6 text-right">
                                    <a href="index.php?action=detailCommande&id=<?= $commande['commande_id'] ?>"
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
        $this->afficherHeader("Annuaire Clients");
        $this->afficherNav();
        ?>
        <div class="max-w-6xl mx-auto space-y-8 animate-fadeIn">

            <div class="flex flex-col md:flex-row justify-between items-center gap-6 border-b border-white/5 pb-8">
                <div>
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.4em] mb-2">Base de
                        données</p>
                    <h2 class="text-4xl font-[900] text-white italic uppercase tracking-tighter">
                        Gestion <span class="text-emerald-500">Clients</span>
                    </h2>
                </div>

                <form method="get" class="relative w-full md:w-96 group">
                    <input type="hidden" name="module" value="barman">
                    <input type="hidden" name="action" value="rechercherClient">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-emerald-500 transition-colors"></i>
                    <input type="text" name="search"
                           placeholder="Nom, prénom ou ID..."
                           value="<?= htmlspecialchars($search ?? '') ?>"
                           class="w-full bg-white/[0.02] border border-white/10 rounded-2xl py-4 pl-12 pr-4 text-white font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white/[0.05] transition-all">
                    <button type="submit" class="hidden">Rechercher</button>
                </form>
            </div>

            <?php if ($search): ?>
                <div class="space-y-4">
                    <div class="flex justify-between items-center px-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            Résultats pour : <span class="text-white">"<?= htmlspecialchars($search) ?>"</span>
                        </p>
                        <span class="text-[10px] font-bold text-slate-500 italic"><?= count($clients) ?> client(s) trouvé(s)</span>
                    </div>

                    <?php if (empty($clients)): ?>
                        <div class="bg-white/[0.02] border border-white/5 p-16 rounded-[2.5rem] text-center">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-user-slash text-slate-600 text-2xl"></i>
                            </div>
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun membre ne
                                correspond à cette recherche.</p>
                        </div>
                    <?php else: ?>
                        <div class="bg-white/[0.02] border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                <tr class="bg-white/[0.02] text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                    <th class="p-6">Membre</th>
                                    <th class="p-6 text-center">Identifiant</th>
                                    <th class="p-6">Solde Actuel</th>
                                    <th class="p-6 text-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                <?php foreach ($clients as $c):
                                    $solde = (float)($c['solde'] ?? 0);
                                    // Couleur du solde : Rouge si < 5€, Vert sinon
                                    $soldeClass = $solde < 5 ? "text-red-500 bg-red-500/10" : "text-emerald-500 bg-emerald-500/10";
                                    ?>
                                    <tr class="group hover:bg-white/[0.01] transition-all">
                                        <td class="p-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-black shadow-lg shadow-emerald-900/20">
                                                    <?= strtoupper(substr($c['prenom'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <p class="text-white font-bold group-hover:text-emerald-400 transition-colors uppercase tracking-tight">
                                                        <?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?>
                                                    </p>
                                                    <p class="text-[10px] text-slate-500 font-medium">Membre
                                                        Association</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-6 text-center">
                                            <span class="text-xs font-mono text-slate-500">#<?= str_pad($c['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                        </td>
                                        <td class="p-6">
                                        <span class="px-3 py-1 rounded-lg font-black text-sm <?= $soldeClass ?>">
                                            <?= number_format($solde, 2) ?> €
                                        </span>
                                        </td>
                                        <td class="p-6 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="index.php?module=barman&action=creerTransaction&client_id=<?= $c['id'] ?>"
                                                   class="h-10 px-4 flex items-center gap-2 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/20">
                                                    <i class="fa-solid fa-cart-plus"></i> Encaisser
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 opacity-50">
                    <div class="border-2 border-dashed border-white/5 rounded-[2.5rem] p-12 text-center">
                        <i class="fa-solid fa-keyboard text-4xl text-slate-700 mb-4"></i>
                        <p class="text-slate-500 font-bold uppercase text-[10px] tracking-[0.2em]">Tapez un nom ou un ID
                            pour commencer</p>
                    </div>
                    <div class="border-2 border-dashed border-white/5 rounded-[2.5rem] p-12 text-center">
                        <i class="fa-solid fa-address-card text-4xl text-slate-700 mb-4"></i>
                        <p class="text-slate-500 font-bold uppercase text-[10px] tracking-[0.2em]">Les soldes sont mis à
                            jour en temps réel</p>
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
        </style>
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

    public function afficherFormTransaction($produits = [], $erreur = null, $donneesSaisies = null)
    {
        $this->afficherHeader("Vente");
        $this->afficherNav();
        ?>
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-[900] text-white italic uppercase tracking-tighter mb-8">
                Encaisser <span class="text-blue-600">une vente</span>
            </h2>

            <?php if ($erreur): ?>
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl text-red-500 font-bold">
                    <?= $erreur ?>
                </div>
            <?php endif; ?>

            <form method="post" action="index.php?module=barman&action=traiterTransaction" class="space-y-6">
                <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl shadow-xl">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-4">Rechercher
                        le client</label>
                    <div class="relative" id="client_search_container">
                        <i class="fa-solid fa-search absolute left-4 top-4 text-slate-500"></i>
                        <input type="text" id="client_input" autocomplete="off" placeholder="Nom, Prénom ou ID..."
                               class="w-full bg-[#020617] border border-white/10 rounded-xl py-4 pl-12 pr-4 text-white font-bold focus:border-blue-600 outline-none transition-all">

                        <div id="client_results"
                             class="absolute z-50 w-full mt-2 bg-slate-900 border border-white/10 rounded-xl hidden shadow-2xl overflow-hidden"></div>
                        <input type="hidden" name="client_id" id="client_id_final" required>
                    </div>

                    <div id="selected_client_badge"
                         class="mt-4 hidden p-4 bg-blue-600/10 border border-blue-600/20 rounded-xl flex items-center justify-between animate-fadeIn">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-black">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <span class="text-blue-400 font-bold" id="client_name_display"></span>
                        </div>
                        <button type="button" onclick="resetClient()"
                                class="text-slate-500 hover:text-white transition-colors">
                            <i class="fa-solid fa-circle-xmark text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white/[0.02] border border-white/5 p-6 rounded-3xl shadow-xl">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-4">Ajouter
                        des produits</label>
                    <div class="relative mb-6">
                        <input type="text" id="prod_input" autocomplete="off"
                               placeholder="Chercher un produit (Bière, Soda...)"
                               class="w-full bg-[#020617] border border-white/10 rounded-xl py-4 px-6 text-white font-bold focus:border-blue-600 outline-none transition-all">
                        <div id="prod_results"
                             class="absolute z-50 w-full mt-2 bg-slate-900 border border-white/10 rounded-xl hidden shadow-2xl overflow-hidden"></div>
                    </div>

                    <table class="w-full">
                        <tbody id="panier_list" class="divide-y divide-white/5">
                        </tbody>
                    </table>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 py-5 rounded-2xl text-white font-[900] uppercase tracking-widest hover:bg-blue-500 transition-all shadow-lg shadow-blue-600/20 active:scale-95">
                    Confirmer le paiement
                </button>
            </form>
        </div>

        <script>
            let itemIndex = 0;
            const baseUrl = "index.php?module=barman&action=";

            // --- RECHERCHE CLIENT ---
            const clientInput = document.getElementById('client_input');
            const clientResults = document.getElementById('client_results');

            clientInput.addEventListener('input', async (e) => {
                const val = e.target.value;
                if (val.length < 2) {
                    clientResults.classList.add('hidden');
                    return;
                }

                try {
                    const response = await fetch(`${baseUrl}rechercherClientAjax&q=${encodeURIComponent(val)}`);
                    const clients = await response.json();

                    clientResults.innerHTML = '';
                    if (clients && clients.length > 0) {
                        clients.forEach(c => {
                            const div = document.createElement('div');
                            div.className = "p-4 hover:bg-white/5 cursor-pointer border-b border-white/5 flex justify-between items-center";
                            div.innerHTML = `<div><p class="text-white font-bold">${c.nom} ${c.prenom}</p><p class="text-[10px] text-slate-500 uppercase font-black">ID: ${c.id}</p></div><span class="bg-blue-600/20 text-blue-400 px-3 py-1 rounded-lg font-black text-xs">${c.solde} €</span>`;
                            div.onclick = () => selectClient(c);
                            clientResults.appendChild(div);
                        });
                        clientResults.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error("Erreur client search:", error);
                }
            });

            function selectClient(c) {
                document.getElementById('client_id_final').value = c.id;
                document.getElementById('client_name_display').innerText = `${c.nom} ${c.prenom} (Solde: ${c.solde}€)`;
                document.getElementById('selected_client_badge').classList.remove('hidden');
                document.getElementById('client_search_container').classList.add('hidden');
                clientResults.classList.add('hidden');
            }

            function resetClient() {
                document.getElementById('client_id_final').value = '';
                document.getElementById('selected_client_badge').classList.add('hidden');
                document.getElementById('client_search_container').classList.remove('hidden');
                clientInput.value = '';
                clientInput.focus();
            }

            // --- RECHERCHE PRODUIT ---
            const prodInput = document.getElementById('prod_input');
            const prodResults = document.getElementById('prod_results');

            prodInput.addEventListener('input', async (e) => {
                const val = e.target.value;
                if (val.length < 1) {
                    prodResults.classList.add('hidden');
                    return;
                }

                try {
                    const response = await fetch(`${baseUrl}rechercherProduitAjax&q=${encodeURIComponent(val)}`);
                    const produits = await response.json();

                    // Debug console pour voir ce que le serveur renvoie vraiment
                    console.log("Produits reçus:", produits);

                    prodResults.innerHTML = '';
                    if (produits && produits.length > 0) {
                        produits.forEach(p => {
                            const div = document.createElement('div');
                            div.className = "p-4 hover:bg-white/10 cursor-pointer flex justify-between border-b border-white/5 transition-all";
                            div.innerHTML = `<span class="text-white font-bold">${p.nom}</span><span class="text-blue-400 font-black">${p.prix}€</span>`;

                            // Utilisation de addEventListener pour éviter les conflits
                            div.addEventListener('click', () => {
                                addProdToPanier(p);
                            });

                            prodResults.appendChild(div);
                        });
                        prodResults.classList.remove('hidden');
                    } else {
                        prodResults.innerHTML = '<div class="p-4 text-slate-500 italic">Aucun produit trouvé</div>';
                        prodResults.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error("Erreur produit search:", error);
                }
            });

            function addProdToPanier(p) {
                const list = document.getElementById('panier_list');
                const row = document.createElement('tr');
                row.className = "group hover:bg-white/[0.01]";
                row.innerHTML = `
                <td class="py-4 font-bold text-white">${p.nom} <span class="text-[10px] text-slate-500 ml-2">(${p.prix}€)</span></td>
                <td class="py-4">
                    <div class="flex items-center bg-[#020617] border border-white/10 rounded-lg w-fit">
                        <input type="number" name="produits[${itemIndex}][quantite]" value="1" min="1"
                               class="bg-transparent px-3 py-1 text-white w-16 outline-none font-bold">
                    </div>
                    <input type="hidden" name="produits[${itemIndex}][id]" value="${p.id}">
                    <input type="hidden" name="produits[${itemIndex}][prix]" value="${p.prix}">
                </td>
                <td class="py-4 text-right">
                    <button type="button" onclick="this.closest('tr').remove()" class="w-10 h-10 rounded-xl text-red-500 hover:bg-red-500/20 transition-all">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            `;
                list.appendChild(row);
                itemIndex++;
                prodResults.classList.add('hidden');
                prodInput.value = '';
            }

            // Fermer les fenêtres de résultats au clic extérieur
            document.addEventListener('click', (e) => {
                if (!clientInput.contains(e.target) && !clientResults.contains(e.target)) clientResults.classList.add('hidden');
                if (!prodInput.contains(e.target) && !prodResults.contains(e.target)) prodResults.classList.add('hidden');
            });
        </script>

        <style>
            .animate-fadeIn {
                animation: fadeIn 0.3s ease-out;
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
        </style>
        <?php
        $this->afficherFooter();
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
}