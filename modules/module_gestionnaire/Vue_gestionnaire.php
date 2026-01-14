<?php
include_once '../vue_generique.php';

class VueGestionnaire extends VueGenerique
{

    private function afficherAlerte($type, $message, $returnHTML = false)
    {
        $icons = ['success' => 'check-circle', 'error' => 'exclamation-circle', 'info' => 'info-circle'];
        $classes = [
                'success' => 'bg-green-100 border-green-500 text-green-700',
                'error' => 'bg-red-100 border-red-500 text-red-700',
                'info' => 'bg-blue-100 border-blue-500 text-blue-700'
        ];
        $icon = $icons[$type] ?? 'info-circle';
        $class = $classes[$type] ?? 'bg-blue-100 border-blue-500 text-blue-700';

        $html = '<div class="border-l-4 p-4 mb-4 rounded ' . $class . '">
                    <i class="fas fa-' . $icon . ' mr-2"></i>
                    ' . htmlspecialchars($message) . '
                </div>';

        if ($returnHTML) return $html;
        echo $html;
    }

    public function afficherNav()
    {
        $prenom = $_SESSION['prenom'] ?? 'Gestionnaire';
        $actionActuelle = $_GET['action'] ?? 'accueil';

        $activeClass = "bg-blue-600/10 text-blue-400 border-r-4 border-blue-600 shadow-[inset_0_0_15px_rgba(37,99,235,0.1)]";
        $inactiveClass = "text-slate-400 hover:bg-white/5 hover:text-white border-r-4 border-transparent";

        echo '
    <div class="flex min-h-screen bg-[#020617] font-montserrat">
        <aside class="w-72 bg-[#020617] border-r border-white/5 hidden md:flex flex-col sticky top-0 h-screen shadow-2xl z-50">
            
            <div class="h-24 flex items-center px-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/40">
                        <i class="fa-solid fa-bolt text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-black text-white tracking-tighter uppercase">Asso<span class="text-blue-600">Manager</span></span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
                
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mb-4 mt-2">Principal</p>
                
                <a href="index.php?action=accueil" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'accueil' ? $activeClass : $inactiveClass) . '">
                    <i class="fa-solid fa-gauge-high text-lg ' . ($actionActuelle == 'accueil' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                    <span class="font-bold text-sm tracking-tight">Dashboard</span>
                </a>

                <a href="index.php?action=associations" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'associations' ? $activeClass : $inactiveClass) . '">
                    <i class="fa-solid fa-sitemap text-lg ' . ($actionActuelle == 'associations' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                    <span class="font-bold text-sm tracking-tight">Associations</span>
                </a>


                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mt-10 mb-4">Logistique</p>

                <a href="index.php?action=barmans" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'barmans' ? $activeClass : $inactiveClass) . '">
                    <i class="fa-solid fa-user-ninja text-lg ' . ($actionActuelle == 'barmans' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                    <span class="font-bold text-sm tracking-tight">Employés</span>
                </a>

                <a href="index.php?action=fournisseurs" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'fournisseurs' ? $activeClass : $inactiveClass) . '">
                    <i class="fa-solid fa-truck-fast text-lg ' . ($actionActuelle == 'fournisseurs' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                    <span class="font-bold text-sm tracking-tight">Fournisseurs</span>
                </a>

                <a href="index.php?action=voirProduits" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'produits' ? $activeClass : $inactiveClass) . '">
                    <i class="fa-solid fa-boxes-stacked text-lg ' . ($actionActuelle == 'produits' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                    <span class="font-bold text-sm tracking-tight">Stock Produits</span>
                </a>
            </nav>

            <div class="p-6 mt-auto">
                <div class="bg-white/5 rounded-[2rem] p-4 border border-white/5 shadow-inner">
                    <div class="flex items-center gap-3 mb-4 px-2">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-lg shadow-blue-900/40">
                                ' . strtoupper(substr($prenom, 0, 1)) . '
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-[#020617] rounded-full"></div>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-xs font-black text-white truncate uppercase tracking-tighter">' . htmlspecialchars($prenom) . '</p>
                            <p class="text-[10px] font-bold text-blue-500/80 uppercase tracking-widest leading-none mt-1">Gest</p>
                        </div>
                    </div>
                    
                    <a href="index.php?action=deconnexion" class="flex items-center justify-center gap-2 w-full bg-white/5 hover:bg-rose-600/10 text-slate-400 hover:text-rose-500 py-3 rounded-2xl transition-all duration-300 text-[11px] font-black uppercase tracking-widest border border-white/5 hover:border-rose-500/20">
                        <i class="fa-solid fa-power-off text-xs"></i> Déconnexion
                    </a>
                </div>
            </div>
        </aside>
        <main class="flex-1 overflow-x-hidden">';
    }

    public function afficherTableauDeBordAccueil($prenom, $data)
    {
        $this->afficherNav();

        $assos = $data['associations'] ?? [];
        $alertes = $data['alertes'] ?? [];
        $topProduits = $data['topProduits'] ?? [];
        $totalSolde = array_sum(array_column($assos, 'solde'));

        $nbBarmans = $data['nbBarmans'] ?? (isset($data['barmans']) ? count($data['barmans']) : 0);
        $totalPertes = $data['totalPertes'] ?? 0;

        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background: #020617 radial-gradient(circle at 50% -20%, #1e1b4b 0%, #020617 80%) no-repeat fixed;
                margin: 0;
                height: 100vh;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: #f8fafc;
            }

            .scrollable-content {
                flex-grow: 1;
                overflow-y: auto;
                background: transparent;
            }

            .content-limit {
                max-width: 1500px;
                margin: 0 auto;
                padding: 3rem 2rem;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 2.5rem;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

            .glass-card:hover {
                background: rgba(255, 255, 255, 0.06);
                border-color: rgba(59, 130, 246, 0.4);
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .anim-fade { animation: slideUp 0.6s ease-out forwards; }

            @keyframes fillProgress {
                from { width: 0; }
                to { width: var(--target); }
            }

            .bar-fill {
                height: 100%;
                border-radius: 99px;
                animation: fillProgress 1.5s cubic-bezier(0.19, 1, 0.22, 1) forwards 0.5s;
            }

            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.3); border-radius: 10px; }
        </style>

        <div class="scrollable-content">
            <div class="content-limit">

                <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6 anim-fade">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-8 h-[2px] bg-blue-600"></span>
                            <span class="text-blue-500 font-black uppercase text-[10px] tracking-[0.3em] italic">Panel Gestionnaire</span>
                        </div>
                        <h1 class="text-5xl font-black tracking-tighter text-white uppercase italic">
                            Tableau de <span class="text-blue-600">bord</span>
                        </h1>
                        <p class="text-slate-400 font-medium mt-2">Content de vous revoir, <span class="text-white font-bold"><?= htmlspecialchars($prenom) ?></span> 👋</p>
                    </div>
                </div>

                <?php if (!empty($alertes)): ?>
                    <div class="mb-12 anim-fade" style="animation-delay: 0.1s">
                        <div class="glass-card overflow-hidden border-l-4 border-rose-600 shadow-2xl shadow-rose-900/10">
                            <div class="bg-rose-600/10 px-8 py-4 flex justify-between items-center border-b border-white/5">
                                <h2 class="text-rose-400 font-black text-[10px] uppercase tracking-[0.2em] flex items-center gap-3">
                                    <i class="fa-solid fa-triangle-exclamation animate-pulse"></i> Alertes Stocks Critiques
                                </h2>
                            </div>
                            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <?php foreach (array_slice($alertes, 0, 4) as $alerte): ?>
                                    <div class="bg-white/5 p-4 rounded-2xl border border-white/5 flex items-center gap-4 hover:bg-white/10 transition-colors">
                                        <i class="fa-solid fa-box text-rose-500/50 text-xl"></i>
                                        <div>
                                            <p class="text-[11px] font-black text-slate-200 uppercase truncate w-32"><?= htmlspecialchars($alerte['nom']) ?></p>
                                            <p class="text-[10px] font-bold text-rose-400 uppercase"><?= $alerte['quantiteActuelle'] ?> EN STOCK</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 anim-fade" style="animation-delay: 0.2s">
                    <div class="glass-card p-8 group">
                        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-xl mb-6 shadow-[0_0_20px_rgba(37,99,235,0.4)]">
                            <i class="fa-solid fa-wallet text-white"></i>
                        </div>
                        <h3 class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Trésorerie Totale</h3>
                        <p class="text-3xl font-black text-white"><?= number_format($totalSolde, 2) ?> €</p>
                    </div>

                    <div class="glass-card p-8 group">
                        <div class="w-12 h-12 bg-rose-600 rounded-2xl flex items-center justify-center text-xl mb-6 shadow-[0_0_20px_rgba(225,29,72,0.4)]">
                            <i class="fa-solid fa-arrow-down-long text-white"></i>
                        </div>
                        <h3 class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Pertes enregistrées</h3>
                        <p class="text-3xl font-black text-rose-500">- <?= number_format($totalPertes, 2) ?> €</p>
                    </div>

                    <div class="glass-card p-8 group">
                        <div class="w-12 h-12 bg-slate-700 rounded-2xl flex items-center justify-center text-xl mb-6 border border-white/10">
                            <i class="fa-solid fa-users text-white"></i>
                        </div>
                        <h3 class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Staff Actif</h3>
                        <p class="text-3xl font-black text-white"><?= $nbBarmans ?></p>
                    </div>

                    <div class="bg-gradient-to-br from-blue-600 to-indigo-900 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group border border-white/10">
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-xl mb-6 backdrop-blur-md">
                                <i class="fa-solid fa-handshake text-white"></i>
                            </div>
                            <h3 class="text-blue-200 text-[10px] font-black uppercase tracking-widest mb-1">Associations</h3>
                            <p class="text-3xl font-black text-white"><?= count($assos) ?></p>
                        </div>
                        <i class="fa-solid fa-building-columns absolute -right-4 -bottom-4 text-white/10 text-8xl rotate-12"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 anim-fade" style="animation-delay: 0.3s">

                    <div class="lg:col-span-3 glass-card p-10">
                        <div class="flex justify-between items-center mb-10">
                            <h3 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3 italic">
                                <i class="fa-solid fa-fire text-orange-500"></i> Top Ventes
                            </h3>
                        </div>

                        <div class="space-y-8">
                            <?php if (empty($topProduits)): ?>
                                <div class="text-center py-20 bg-black/20 rounded-[2rem] border border-dashed border-white/10">
                                    <i class="fa-solid fa-chart-bar text-4xl text-slate-800 mb-4 block"></i>
                                    <p class="text-slate-600 font-bold uppercase text-xs tracking-widest">Aucune donnée de vente</p>
                                </div>
                            <?php else:
                                $premier = reset($topProduits);
                                $cle = isset($premier['nb_ventes']) ? 'nb_ventes' : (isset($premier['total_vendu']) ? 'total_vendu' : null);

                                if($cle):
                                    $valeurs = array_column($topProduits, $cle);
                                    $max = !empty($valeurs) ? max($valeurs) : 1;

                                    foreach ($topProduits as $top):
                                        $val = $top[$cle] ?? 0;
                                        $pct = ($max > 0) ? ($val / $max) * 100 : 0;
                                        ?>
                                        <div class="group">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-xs font-black text-slate-300 uppercase tracking-widest"><?= htmlspecialchars($top['nom']) ?></span>
                                                <span class="text-[10px] font-black text-blue-500"><?= $val ?> VENTES</span>
                                            </div>
                                            <div class="w-full bg-slate-900 rounded-full h-3 p-1 border border-white/5 shadow-inner">
                                                <div class="bg-gradient-to-r from-blue-700 to-blue-400 bar-fill shadow-[0_0_15px_rgba(59,130,246,0.3)]"
                                                     style="--target: <?= $pct ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                endif;
                            endif; ?>
                        </div>
                    </div>

                    <div class="lg:col-span-2 glass-card p-10 flex flex-col">
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-10 italic flex items-center gap-3">
                            <i class="fa-solid fa-bolt text-blue-500"></i> Accès Rapide
                        </h3>
                        <div class="space-y-4 max-h-[460px] overflow-y-auto pr-2 custom-scrollbar">
                            <?php foreach ($assos as $a): ?>
                                <a href="index.php?action=gererAssociation&id=<?= $a['id'] ?>"
                                   class="flex items-center gap-5 p-5 rounded-[2rem] bg-white/5 border border-transparent hover:border-blue-500/30 hover:bg-blue-600/10 transition-all group active:scale-95">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-800 text-blue-400 flex items-center justify-center font-black text-lg border border-white/5 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        <?= strtoupper(substr($a['nom'], 0, 1)) ?>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[13px] font-black text-slate-100 uppercase group-hover:text-blue-400 transition-colors"><?= htmlspecialchars($a['nom']) ?></p>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1"><?= number_format($a['solde'], 2) ?> €</p>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-slate-800 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }
    public function gererAssociation($association, $barmans, $produits, $clients)
    {
        $this->afficherNav();
        ?>
        <style>
            body {
                background: #020617 radial-gradient(circle at 50% -20%, #1e1b4b 0%, #020617 80%) no-repeat fixed;
                color: #f8fafc;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 2.5rem;
                transition: all 0.3s ease;
            }
            .glass-card:hover {
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(59, 130, 246, 0.3);
            }
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.2); border-radius: 10px; }

            .btn-action-glass {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #94a3b8;
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                font-size: 10px;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }
            .btn-action-glass:hover {
                background: rgba(37, 99, 235, 0.2);
                border-color: rgba(37, 99, 235, 0.5);
                color: #60a5fa;
                transform: translateY(-2px);
            }
        </style>

        <div class="max-w-[1500px] mx-auto p-12 anim-fade">

            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-8 h-[2px] bg-blue-600"></span>
                        <span class="text-blue-500 font-black uppercase text-[10px] tracking-[0.3em] italic">Gestion d'entité</span>
                    </div>
                    <h1 class="text-5xl font-black tracking-tighter text-white uppercase italic">
                        <?= htmlspecialchars($association['nom']) ?>
                    </h1>
                    <p class="text-slate-400 font-medium mt-2 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-blue-500"></i>
                        <?= htmlspecialchars($association['adresse']) ?>
                    </p>
                </div>

                <div class="bg-blue-600/10 border border-blue-500/20 p-6 rounded-[2rem] text-right backdrop-blur-xl shadow-2xl shadow-blue-900/20">
                    <p class="text-[10px] text-blue-400 uppercase font-black tracking-widest mb-1">Trésorerie Actuelle</p>
                    <p class="text-4xl font-black text-white italic"><?= number_format($association['solde'], 2) ?> <span class="text-blue-500">€</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <a href="index.php?action=faireInventaire&id=<?= $association['id'] ?>"
                   class="glass-card p-8 group border-b-4 border-b-orange-500/50 hover:bg-orange-500/10 transition-all text-center">
                    <div class="w-14 h-14 bg-orange-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-clipboard-list text-orange-500 text-2xl"></i>
                    </div>
                    <span class="block text-white font-black uppercase tracking-tighter italic text-lg">Inventaire</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Vérifier pertes & stocks</span>
                </a>

                <a href="index.php?action=nouvelAchat&id=<?= $association['id'] ?>"
                   class="glass-card p-8 group border-b-4 border-b-emerald-500/50 hover:bg-emerald-500/10 transition-all text-center">
                    <div class="w-14 h-14 bg-emerald-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-truck-loading text-emerald-500 text-2xl"></i>
                    </div>
                    <span class="block text-white font-black uppercase tracking-tighter italic text-lg">Achats</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Réapprovisionnement</span>
                </a>

                <a href="index.php?action=rapportTresorerie&id=<?= $association['id'] ?>"
                   class="glass-card p-8 group border-b-4 border-b-indigo-500/50 hover:bg-indigo-500/10 transition-all text-center">
                    <div class="w-14 h-14 bg-indigo-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-indigo-500 text-2xl"></i>
                    </div>
                    <span class="block text-white font-black uppercase tracking-tighter italic text-lg">Finance</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Bilan Ventes / Dépenses</span>
                </a>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-5 gap-10">

                <div class="xl:col-span-3 glass-card p-10">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-xl font-black text-white uppercase tracking-tighter italic flex items-center gap-3">
                                <i class="fa-solid fa-boxes-stacked text-blue-500"></i> Stocks Localisés
                            </h3>
                            <p class="text-[10px] font-black text-slate-500 mt-1 uppercase tracking-widest">Inventaire propre à l'entité</p>
                        </div>
                        <a href="index.php?action=voirProduitsAsso&id_asso=<?= $association['id'] ?>" class="btn-action-glass">
                            Gérer catalogue <i class="fa-solid fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>

                    <div class="space-y-3 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                        <?php foreach ($produits as $p): ?>
                            <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5 hover:border-blue-500/30 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-xs font-bold text-blue-400 border border-white/5">
                                        <?= $p['prix'] ?>€
                                    </div>
                                    <div>
                                        <p class="font-black text-sm text-slate-200 uppercase tracking-tight"><?= htmlspecialchars($p['nom']) ?></p>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest"><?= htmlspecialchars($p['type']) ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                <span class="px-4 py-1.5 <?= $p['quantiteActuelle'] < 10 ? 'bg-rose-500/20 text-rose-500' : 'bg-blue-600/20 text-blue-400' ?> rounded-xl font-black text-xs border border-white/5">
                                    <?= $p['quantiteActuelle'] ?> EN STOCK
                                </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="xl:col-span-2 glass-card p-10">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-xl font-black text-white uppercase tracking-tighter italic flex items-center gap-3">
                                <i class="fa-solid fa-user-shield text-blue-500"></i> Équipe Dédiée
                            </h3>
                            <p class="text-[10px] font-black text-slate-500 mt-1 uppercase tracking-widest">Membres rattachés</p>
                        </div>
                        <a href="index.php?action=voirBarmansAsso&id_asso=<?= $association['id'] ?>" class="btn-action-glass">
                            Gérer équipe <i class="fa-solid fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>

                    <div class="space-y-4">
                        <?php foreach ($barmans as $b): ?>
                            <div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/5">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-slate-700 to-slate-800 flex items-center justify-center font-black text-blue-400 border border-white/10">
                                    <?= strtoupper(substr($b['prenom'] ?? 'B', 0, 1)) ?>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-black text-slate-200 uppercase"><?= htmlspecialchars(($b['prenom'] ?? '') . ' ' . ($b['nom'] ?? '')) ?></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="w-2 h-2 bg-green-500 rounded-full shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Actif sur le site</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    public function formulaireInventaire($produits, $id_assos)
    {
        $this->afficherNav();
        ?>
        <style>
            body {
                background: #020617 radial-gradient(circle at 50% -20%, #251a05 0%, #020617 80%) no-repeat fixed;
                color: #f8fafc;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.02);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(249, 115, 22, 0.1);
                border-radius: 2.5rem;
            }
            .input-orange {
                background: rgba(0, 0, 0, 0.3);
                border: 1px solid rgba(249, 115, 22, 0.2);
                color: #fb923c;
                transition: all 0.3s ease;
            }
            .input-orange:focus {
                outline: none;
                border-color: #f97316;
                box-shadow: 0 0 15px rgba(249, 115, 22, 0.2);
                background: rgba(249, 115, 22, 0.05);
            }
            input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
                -webkit-appearance: none; margin: 0;
            }
        </style>

        <div class="max-w-5xl mx-auto p-12 anim-fade">

            <div class="mb-10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-8 h-[2px] bg-orange-500"></span>
                    <span class="text-orange-500 font-black uppercase text-[10px] tracking-[0.3em] italic">Contrôle Logistique</span>
                </div>
                <h1 class="text-5xl font-black tracking-tighter text-white uppercase italic">
                    Saisie <span class="text-orange-500">Inventaire</span>
                </h1>
                <p class="text-slate-500 font-medium mt-2">Ajustez les stocks physiques pour corriger les écarts de vente.</p>
            </div>

            <div class="glass-card overflow-hidden shadow-2xl">
                <form action="index.php?action=enregistrerInventaire" method="POST" class="p-10">
                    <input type="hidden" name="association_id" value="<?= $id_assos ?>">

                    <table class="w-full">
                        <thead>
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5">
                            <th class="pb-6 text-left">Produit</th>
                            <th class="pb-6 text-center">Théorique (Logiciel)</th>
                            <th class="pb-6 text-right">Réel (Physique)</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php foreach ($produits as $p): ?>
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_#f97316]"></div>
                                        <div>
                                            <p class="font-black text-slate-200 uppercase tracking-tight"><?= htmlspecialchars($p['nom']) ?></p>
                                            <p class="text-[10px] text-slate-600 font-bold uppercase"><?= htmlspecialchars($p['type']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-center">
                                    <span class="font-mono text-lg text-slate-400"><?= $p['quantiteActuelle'] ?></span>
                                </td>
                                <td class="py-6 text-right">
                                    <input type="number" name="stock_reel[<?= $p['id'] ?>]"
                                           value="<?= $p['quantiteActuelle'] ?>"
                                           class="w-32 py-3 px-4 rounded-xl input-orange font-black text-center text-lg"
                                           required>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="mt-12 flex justify-between items-center">
                        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-widest italic">
                            <i class="fa-solid fa-circle-info mr-2"></i> Les stocks seront mis à jour dès validation
                        </div>
                        <button type="submit"
                                class="bg-orange-600 hover:bg-orange-500 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-tighter italic transition-all shadow-[0_10px_30px_rgba(234,88,12,0.3)] hover:shadow-[0_15px_40px_rgba(234,88,12,0.5)] active:scale-95">
                            Valider & Recalculer <i class="fa-solid fa-rotate ml-2 text-sm"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public function afficherClients($clients)
    {
        $this->afficherNav();
        ?>
        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate-fade-in">
                <div>
                    <span class="text-blue-500 font-black uppercase text-[10px] tracking-[0.3em] mb-2 block italic">Administration</span>
                    <h1 class="text-4xl font-black tracking-tighter text-white uppercase flex items-center gap-4">
                        <i class="fa-solid fa-address-book text-blue-600"></i> Base Clients
                    </h1>
                </div>
                <a href="index.php?action=ajouterClient"
                   class="flex items-center gap-3 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-blue-900/20 active:scale-95">
                    <i class="fa-solid fa-user-plus"></i> Nouveau Client
                </a>
            </div>

            <?php foreach (['success' => 'emerald', 'error' => 'rose'] as $type => $color): ?>
                <?php if (isset($_SESSION[$type])): ?>
                    <div class="mb-8 p-5 rounded-2xl border border-<?= $color ?>-500/30 bg-<?= $color ?>-500/10 text-<?= $color ?>-200 flex items-center gap-4 animate-fade-in shadow-xl">
                        <i class="fa-solid <?= $type == 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?> text-xl"></i>
                        <span class="text-sm font-bold"><?= htmlspecialchars($_SESSION[$type]) ?></span>
                    </div>
                    <?php unset($_SESSION[$type]); ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-0">
                        <thead>
                        <tr class="bg-white/[0.02]">
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                ID
                            </th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                Identité
                            </th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                Contact
                            </th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                Solde
                            </th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                Rang
                            </th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php foreach ($clients as $client):
                            $isPositive = ($client['solde'] ?? 0) >= 0;
                            $roleBarman = !empty($client['est_barman']);
                            ?>
                            <tr class="hover:bg-white/[0.03] transition-colors group">
                                <td class="px-8 py-5 text-slate-500 font-mono text-xs italic">
                                    #<?= htmlspecialchars($client['id']) ?></td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                <span class="text-white font-black uppercase text-sm tracking-tight block group-hover:text-blue-400 transition-colors">
                                    <?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom']) ?>
                                </span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="text-slate-400 font-medium text-xs italic"><?= htmlspecialchars($client['email']) ?></span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap font-black">
                                <span class="<?= $isPositive ? 'text-emerald-400' : 'text-rose-500' ?> text-sm">
                                    <?= number_format($client['solde'] ?? 0, 2, ',', ' ') ?> €
                                </span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <?php if ($roleBarman): ?>
                                        <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-lg">
                                        <i class="fa-solid fa-user-ninja mr-1"></i> Staff
                                    </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest bg-slate-800 text-slate-500 border border-white/5 rounded-lg">
                                        Client
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="index.php?action=modifierClient&id=<?= $client['id'] ?>"
                                           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/5 text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-inner"
                                           title="Modifier">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <a href="index.php?action=supprimerClient&id=<?= $client['id'] ?>"
                                           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/5 text-slate-400 hover:bg-rose-600 hover:text-white transition-all shadow-inner"
                                           onclick="return confirm('Supprimer ce client ?')"
                                           title="Supprimer">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($clients)): ?>
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <i class="fa-solid fa-users-slash text-4xl text-slate-800 mb-4 block"></i>
                                    <p class="text-slate-600 font-bold italic tracking-widest uppercase">Aucun client
                                        enregistré dans le système.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    public function formulaireAjouterBarman($clients, $associations) {
        $this->afficherNav();
        ?>
        <style>
            .glass-panel {
                background: rgba(15, 23, 42, 0.65);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
                border-radius: 1.5rem;
            }

            .client-card {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.08);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .client-card:hover {
                background: rgba(37, 99, 235, 0.1);
                border-color: rgba(37, 99, 235, 0.5);
                transform: translateY(-2px);
            }

            .client-card.selected {
                background: linear-gradient(135deg, rgba(37, 99, 235, 0.2), rgba(29, 78, 216, 0.4));
                border-color: #3b82f6;
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
            }

            .client-card.selected::after {
                content: '✓';
                position: absolute;
                top: 10px;
                right: 10px;
                background: #3b82f6;
                color: white;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: bold;
            }

            .custom-select {
                background: rgba(15, 23, 42, 0.8);
                border: 2px solid rgba(255, 255, 255, 0.1);
                transition: border-color 0.3s;
                cursor: pointer;
            }

            .custom-select:focus {
                border-color: #3b82f6;
                outline: none;
            }

            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.2);
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(59, 130, 246, 0.5);
                border-radius: 10px;
            }

            #btnSubmit:not(:disabled) {
                background: linear-gradient(90deg, #2563eb, #3b82f6);
                box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
                cursor: pointer;
            }

            #btnSubmit:not(:disabled):hover {
                filter: brightness(1.1);
                transform: scale(1.01);
            }

            .step-number {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
                background: #3b82f6;
                color: white;
                border-radius: 50%;
                font-size: 12px;
                margin-right: 8px;
            }
        </style>

        <div class="content-limit py-10 max-w-4xl mx-auto">
            <h1 class="text-4xl font-black uppercase mb-2 tracking-tighter">
                PROMOUVOIR UN <span class="text-blue-500">BARMAN</span>
            </h1>
            <p class="text-slate-400 mb-8 font-medium">Élevez un client au rang de personnel de service.</p>

            <div class="glass-panel p-8">
                <form action="index.php?action=ajouterBarman" method="POST" id="mainForm">

                    <div class="mb-10">
                        <label class="flex items-center text-sm font-bold uppercase text-slate-200 mb-4">
                            <span class="step-number">1</span> Choisir le futur barman
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-80 overflow-y-auto p-3 bg-black/40 rounded-2xl custom-scrollbar">
                            <?php foreach($clients as $c): ?>
                                <div class="client-card p-4 rounded-xl cursor-pointer transition-all flex flex-col justify-center"
                                     onclick="selectClient(<?= $c['id'] ?>, this)">
                                    <p class="text-sm font-black text-white uppercase tracking-tight">
                                        <?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?>
                                    </p>
                                    <p class="text-[11px] text-blue-400/80 font-medium italic">
                                        <?= htmlspecialchars($c['email']) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <input type="hidden" name="client_id" id="input_client_id" required>

                    <div class="mb-10">
                        <label class="flex items-center text-sm font-bold uppercase text-slate-200 mb-4">
                            <span class="step-number">2</span> Association de rattachement
                        </label>
                        <div class="relative">
                            <select name="association_id" class="custom-select text-white w-full p-4 rounded-xl font-bold appearance-none" required>
                                <option value="" disabled selected>-- Sélectionner l'entité --</option>
                                <?php foreach($associations as $a): ?>
                                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-blue-500">
                                ▼
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit" disabled
                            class="w-full py-5 rounded-2xl font-black uppercase text-sm bg-slate-800 text-slate-500 transition-all duration-300">
                        Confirmer le recrutement
                    </button>
                </form>
            </div>
        </div>

        <script>
            function selectClient(id, element) {
                document.querySelectorAll('.client-card').forEach(c => {
                    c.classList.remove('selected');
                });

                element.classList.add('selected');

                document.getElementById('input_client_id').value = id;

                const btn = document.getElementById('btnSubmit');
                btn.disabled = false;
                btn.classList.remove('bg-slate-800', 'text-slate-500');
                btn.classList.add('text-white');
                btn.innerHTML = "✨ Confirmer le recrutement ✨";
            }
        </script>
        <?php
    }
    public function afficherBarmans($barmans, $associations)
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background: #020617 radial-gradient(circle at 50% -20%, #1e1b4b 0%, #020617 80%) no-repeat fixed;
                margin: 0; height: 100vh; display: flex; flex-direction: column; overflow: hidden;
                font-family: 'Plus Jakarta Sans', sans-serif; color: #f8fafc;
            }

            .scrollable-content { flex-grow: 1; overflow-y: auto; padding-bottom: 5rem; scroll-behavior: smooth; }
            .content-limit { max-width: 1300px; margin: 0 auto; padding: 3rem 2rem; }

            .page-header {
                display: flex; justify-content: space-between; align-items: flex-end;
                margin-bottom: 3rem; animation: fadeInDown 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            }

            .glass-search-bar {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 2rem;
                padding: 1.25rem 2.5rem;
                display: flex; gap: 1.5rem; align-items: center;
                margin-bottom: 3rem;
                box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            }

            .member-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 2rem;
                padding: 1.5rem 2.5rem;
                margin-bottom: 1rem;
                display: grid;
                grid-template-columns: 100px 1.5fr 1fr 1fr 150px;
                align-items: center;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                text-decoration: none;
                position: relative; overflow: hidden;
            }

            .member-card:hover {
                background: rgba(255, 255, 255, 0.07);
                border-color: #3b82f6;
                transform: scale(1.02) translateX(15px);
                box-shadow: -10px 10px 40px rgba(0,0,0,0.4);
            }

            .member-card::before {
                content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px;
                background: #3b82f6; transform: scaleY(0); transition: 0.3s;
            }
            .member-card:hover::before { transform: scaleY(1); }

            .id-pill {
                font-family: 'JetBrains Mono', monospace; font-size: 11px;
                color: #64748b; background: rgba(0,0,0,0.3);
                padding: 5px 12px; border-radius: 8px; width: fit-content;
            }

            .avatar-box {
                width: 45px; height: 45px; border-radius: 14px;
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                border: 1px solid rgba(255,255,255,0.1);
                display: flex; align-items: center; justify-content: center;
                font-weight: 900; color: #3b82f6; transition: 0.3s;
            }
            .member-card:hover .avatar-box { background: #3b82f6; color: white; transform: rotate(-5deg); }

            .status-tag {
                font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;
                display: flex; align-items: center; gap: 8px;
            }
            .dot { width: 8px; height: 8px; border-radius: 50%; }
            .dot-online { background: #10b981; box-shadow: 0 0 12px #10b981; }
            .dot-offline { background: #475569; }

            .btn-view {
                background: white; color: black; padding: 10px 20px;
                border-radius: 12px; font-size: 10px; font-weight: 900;
                text-transform: uppercase; opacity: 0; transform: translateX(20px); transition: 0.4s;
            }
            .member-card:hover .btn-view { opacity: 1; transform: translateX(0); }

            @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        </style>

        <div class="scrollable-content">
            <div class="content-limit">

                <header class="page-header">
                    <div>
                        <h1 class="text-6xl font-black italic tracking-tighter uppercase leading-none">
                            Staff<span class="text-blue-600">.</span>
                        </h1>
                        <p class="text-slate-500 font-bold mt-2 ml-1 tracking-widest uppercase text-[10px]">Gestion des effectifs & accès</p>
                    </div>
                    <a href="index.php?action=ajouterBarman" class="group flex items-center gap-4 bg-white text-black px-8 py-4 rounded-2xl font-black text-xs uppercase transition-all hover:bg-blue-600 hover:text-white">
                        Recruter un membre
                        <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-2"></i>
                    </a>
                </header>

                <div class="glass-search-bar">
                    <i class="fa-solid fa-magnifying-glass text-blue-500"></i>
                    <input type="text" id="filterSearch" placeholder="Rechercher par nom, mail ou matricule..."
                           class="flex-1 bg-transparent border-none text-white outline-none font-bold text-sm placeholder:text-slate-600">

                    <div class="h-6 w-[1px] bg-white/10"></div>

                    <select id="filterAsso" class="bg-transparent text-slate-400 border-none outline-none font-black text-[10px] uppercase tracking-widest cursor-pointer hover:text-white transition-colors">
                        <option value="all">Toutes les entités</option>
                        <?php foreach ($associations as $asso): ?>
                            <option value="<?= htmlspecialchars($asso['nom']) ?>"><?= htmlspecialchars($asso['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="barmanList">
                    <?php foreach ($barmans as $barman):
                        $isActif = $barman['actif'] ?? true;
                        ?>
                        <a href="index.php?action=voirProfilBarman&id=<?= $barman['id'] ?>"
                           class="member-card barman-row"
                           data-asso="<?= htmlspecialchars($barman['nom_association'] ?? 'Aucune') ?>">

                            <span class="id-pill">ID-<?= str_pad($barman['id'], 3, '0', STR_PAD_LEFT) ?></span>

                            <div class="flex items-center gap-5">
                                <div class="avatar-box">
                                    <?= strtoupper(substr($barman['prenom'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h3 class="text-sm font-black text-white uppercase search-target leading-none mb-1">
                                        <?= htmlspecialchars($barman['prenom'] . ' ' . $barman['nom']) ?>
                                    </h3>
                                    <p class="text-[10px] text-slate-500 font-bold tracking-tight lowercase"><?= htmlspecialchars($barman['email']) ?></p>
                                </div>
                            </div>

                            <div class="status-tag <?= $isActif ? 'text-emerald-400' : 'text-slate-500' ?>">
                                <span class="dot <?= $isActif ? 'dot-online' : 'dot-offline' ?>"></span>
                                <?= $isActif ? 'En service' : 'Inactif' ?>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-600 uppercase mb-1">Rattachement</span>
                                <span class="text-[11px] font-extrabold text-white uppercase tracking-tighter">
                                <?= htmlspecialchars($barman['nom_association'] ?? 'Non assigné') ?>
                            </span>
                            </div>

                            <div class="text-right">
                                <span class="btn-view">Fiche Profil</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('filterSearch');
                const assoSelect = document.getElementById('filterAsso');
                const rows = document.querySelectorAll('.barman-row');

                function filterTable() {
                    const searchValue = searchInput.value.toLowerCase();
                    const assoValue = assoSelect.value;

                    rows.forEach(row => {
                        const content = row.innerText.toLowerCase();
                        const rowAsso = row.getAttribute('data-asso');

                        const matchesSearch = content.includes(searchValue);
                        const matchesAsso = (assoValue === 'all' || rowAsso === assoValue);

                        if (matchesSearch && matchesAsso) {
                            row.style.display = 'grid';
                            row.style.opacity = '1';
                        } else {
                            row.style.display = 'none';
                            row.style.opacity = '0';
                        }
                    });
                }

                searchInput.addEventListener('input', filterTable);
                assoSelect.addEventListener('change', filterTable);
            });
        </script>
        <?php
    }


    public function afficherProduits($produits, $titre = "Stock")
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background-color: #020617;
                margin: 0;
                height: 100vh;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                font-family: 'Inter', sans-serif;
            }

            .fixed-top-section {
                flex-shrink: 0;
                background: #020617;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                z-index: 100;
                position: sticky;
                top: 0;
            }

            .scrollable-content {
                flex-grow: 1;
                overflow-y: auto;
                overflow-x: hidden;
                background: radial-gradient(circle at top right, #0f172a, #020617);
            }

            .scrollable-content::-webkit-scrollbar {
                width: 6px;
            }

            .scrollable-content::-webkit-scrollbar-track {
                background: transparent;
            }

            .scrollable-content::-webkit-scrollbar-thumb {
                background: #1e1b4b;
                border-radius: 10px;
            }

            .content-limit {
                max-width: 1600px;
                margin: 0 auto;
                padding: 0 40px;
            }

            .fade-in-up {
                animation: fadeInUp 0.5s ease-out forwards;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>

        <div class="fixed-top-section">
            <div class="py-8">
                <div class="content-limit flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-500/20">
                            <i class="fa-solid fa-boxes-stacked text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-5xl font-black uppercase italic tracking-tighter text-white leading-none">
                                <?= htmlspecialchars($titre) ?>
                            </h1>
                            <p class="text-indigo-400 text-[9px] font-black uppercase tracking-[0.4em] mt-2 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Inventaire en direct
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden xl:flex bg-white/5 border border-white/10 px-6 py-3 rounded-2xl items-center gap-3">
                            <i class="fa-solid fa-list-check text-indigo-500"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-white"><?= count($produits) ?> Références</span>
                        </div>

                        <div class="relative group">
                            <select onchange="window.location.href=this.value"
                                    class="appearance-none bg-[#0f172a] border border-white/10 rounded-2xl pl-10 pr-12 py-4 text-[10px] font-black uppercase text-white outline-none cursor-pointer hover:border-indigo-500 transition-all shadow-lg">
                                <option value="" class="text-black">Trier par...</option>
                                <option value="index.php?action=voirProduits&tri=stock" class="text-black">Urgences
                                    Stock
                                </option>
                                <option value="index.php?action=voirProduits&tri=nom" class="text-black">Nom (A-Z)
                                </option>
                                <option value="index.php?action=voirProduits&tri=prix" class="text-black">Prix</option>
                            </select>
                            <i class="fa-solid fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="scrollable-content">
            <div class="content-limit mt-24 pb-32">
                <div style="height: 40px; background: linear-gradient(to bottom, #020617, transparent); position: relative; z-index: 10; margin-top: -1px;"></div>


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                    <?php foreach ($produits as $p):
                        $stock = (int)($p['quantiteActuelle'] ?? 0);
                        $isLow = $stock < 15;
                        $color = $isLow ? 'rose-500' : 'indigo-500';
                        $percent = min(100, max(0, ($stock / 100) * 100));
                        ?>
                        <div class="fade-in-up group bg-white/[0.03] border border-white/10 rounded-[2.5rem] p-8 transition-all duration-300 hover:border-<?= $color ?>/50 hover:-translate-y-2">

                            <div class="flex justify-between items-start mb-6">
                                <div class="text-<?= $color ?> text-2xl bg-<?= $color ?>/10 w-14 h-14 rounded-2xl flex items-center justify-center border border-<?= $color ?>/20 shadow-inner">
                                    <i class="fa-solid <?= ($p['type'] ?? '') == 'boisson' ? 'fa-wine-glass' : 'fa-utensils' ?>"></i>
                                </div>
                                <div class="text-right">
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Prix
                                        Unitaire</p>
                                    <span class="font-black italic text-2xl tracking-tighter text-white">
                                    <?= number_format($p['prix'] ?? 0, 2) ?>€
                                </span>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h3 class="text-xl font-black uppercase mb-3 truncate text-white group-hover:text-<?= $color ?> transition-colors">
                                    <?= htmlspecialchars($p['nom']) ?>
                                </h3>
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/5 border border-white/10 rounded-full">
                                    <i class="fa-solid fa-building-columns text-[10px] text-indigo-400"></i>
                                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                                    <?= htmlspecialchars($p['nom_association'] ?? 'Général') ?>
                                </span>
                                </div>
                            </div>

                            <div class="bg-black/40 rounded-3xl p-5 border border-white/5">
                                <div class="flex justify-between text-[10px] font-black mb-3 uppercase tracking-widest">
                                    <span class="text-slate-500"><i
                                                class="fa-solid fa-warehouse mr-1"></i> Réserve</span>
                                    <span class="<?= $isLow ? 'text-rose-500 animate-pulse' : 'text-emerald-400' ?>">
                                    <i class="fa-solid <?= $isLow ? 'fa-triangle-exclamation' : 'fa-check' ?> mr-1"></i>
                                    <?= $stock ?> pcs
                                </span>
                                </div>
                                <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-<?= $color ?> transition-all duration-1000 shadow-[0_0_10px_rgba(0,0,0,0.5)]"
                                         style="width: <?= $percent ?>%"></div>
                                </div>
                            </div>

                            <div class="mt-8 flex gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <a href="index.php?action=modifierProduit&id=<?= $p['id'] ?>"
                                   class="flex-1 bg-white/5 py-4 rounded-xl text-center text-[9px] font-black uppercase text-white hover:bg-white/10 border border-white/5 transition-all">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Éditer
                                </a>
                                <a href="index.php?action=ajouterStock&id=<?= $p['id'] ?>"
                                   class="flex-[2] bg-<?= $color ?> py-4 rounded-xl text-center text-[9px] font-black uppercase text-white shadow-lg shadow-<?= $color ?>/20 transition-all flex items-center justify-center gap-2 hover:brightness-110">
                                    <i class="fa-solid fa-plus"></i> Ajouter Stock
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function formulaireModificationProduit($produit)
    {
        $this->afficherNav();
        ?>
        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white flex justify-center items-start">

            <div class="w-full max-w-2xl animate-fade-in">
                <a href="index.php?action=voirProduits"
                   class="text-slate-500 hover:text-white text-[10px] font-black uppercase tracking-[0.3em] mb-6 inline-flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-arrow-left-long"></i> Retour au catalogue
                </a>

                <div class="glass-card bg-white/5 border border-white/10 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 opacity-5">
                        <i class="fa-solid fa-pen-nib text-[12rem]"></i>
                    </div>

                    <div class="relative z-10">
                        <div class="mb-10">
                            <h1 class="text-3xl font-black tracking-tighter text-white uppercase italic">
                                Éditer le produit
                            </h1>
                            <p class="text-blue-500 font-bold text-xs uppercase tracking-widest mt-2">
                                ID Produit: #<?= $produit['id'] ?>
                            </p>
                        </div>

                        <form action="index.php?action=modifierProduit" method="post" class="space-y-8">
                            <input type="hidden" name="id" value="<?= $produit['id'] ?>">

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Désignation
                                    du produit</label>
                                <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-tag"></i>
                                </span>
                                    <input type="text" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>"
                                           required
                                           class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-6 py-4 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none transition-all font-bold placeholder:text-slate-700">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Catégorie</label>
                                    <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500">
                                        <i class="fa-solid fa-layer-group text-xs"></i>
                                    </span>
                                        <select name="type" required
                                                class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-6 py-4 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none transition-all font-bold appearance-none cursor-pointer">
                                            <option value="boisson" <?= $produit['type'] == 'boisson' ? 'selected' : '' ?>>
                                                Boisson
                                            </option>
                                            <option value="nourriture" <?= $produit['type'] == 'nourriture' ? 'selected' : '' ?>>
                                                Nourriture
                                            </option>
                                            <option value="autre" <?= $produit['type'] == 'autre' ? 'selected' : '' ?>>
                                                Autre
                                            </option>
                                        </select>
                                        <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Prix
                                        Unitaire (€)</label>
                                    <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 group-focus-within:text-emerald-500 transition-colors">
                                        <i class="fa-solid fa-euro-sign"></i>
                                    </span>
                                        <input type="number" name="prix" step="0.01" min="0"
                                               value="<?= $produit['prix'] ?>" required
                                               class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-6 py-4 rounded-2xl focus:ring-2 focus:ring-emerald-600 outline-none transition-all font-bold">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Quantité
                                    en stock</label>
                                <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 group-focus-within:text-amber-500 transition-colors">
                                    <i class="fa-solid fa-box-archive"></i>
                                </span>
                                    <input type="number" name="stock" min="0"
                                           value="<?= $produit['quantiteActuelle'] ?>" required
                                           class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-6 py-4 rounded-2xl focus:ring-2 focus:ring-amber-600 outline-none transition-all font-bold">
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black uppercase tracking-[0.3em] py-5 rounded-[2rem] transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-4 group active:scale-[0.98]">
                                    Enregistrer les modifications
                                    <i class="fa-solid fa-check-double text-lg group-hover:scale-125 transition-transform"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }

    public function formulaireAjoutStock($produit)
    {
        $this->afficherNav();
        ?>
        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white flex justify-center items-start">

            <div class="w-full max-w-xl animate-fade-in">
                <a href="index.php?action=voirProduits"
                   class="text-slate-500 hover:text-white text-[10px] font-black uppercase tracking-[0.3em] mb-6 inline-flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-chevron-left"></i> Retour aux produits
                </a>

                <div class="glass-card bg-white/5 border border-white/10 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">

                    <div class="relative z-10">
                        <div class="mb-10 text-center">
                            <div class="w-16 h-16 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-500 mx-auto mb-4">
                                <i class="fa-solid fa-plus-plus text-2xl"></i>
                            </div>
                            <h1 class="text-2xl font-black tracking-tighter text-white uppercase italic">
                                Réapprovisionnement
                            </h1>
                        </div>

                        <div class="bg-white/5 rounded-2xl p-6 mb-8 border border-white/5 flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Produit
                                    sélectionné</p>
                                <h2 class="text-xl font-black text-white uppercase"><?= htmlspecialchars($produit['nom']) ?></h2>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">État
                                    actuel</p>
                                <p class="text-xl font-black text-blue-400"><?= $produit['quantiteActuelle'] ?> <span
                                            class="text-[10px]">PCS</span></p>
                            </div>
                        </div>

                        <form action="index.php?action=ajouterStock" method="post" class="space-y-8">
                            <input type="hidden" name="id" value="<?= $produit['id'] ?>">

                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Quantité
                                    à réceptionner</label>
                                <div class="relative group max-w-[200px] mx-auto">
                                    <input type="number" name="quantite" min="1" placeholder="0" required autofocus
                                           class="w-full bg-[#0f172a] border-2 border-white/5 text-white py-6 rounded-[2rem] focus:border-emerald-500 outline-none transition-all font-black text-4xl text-center placeholder:text-slate-800 shadow-inner">
                                </div>
                                <p class="text-[9px] text-slate-500 text-center italic mt-2 uppercase font-bold tracking-widest">
                                    Entrez le nombre d'unités reçues</p>
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                        class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black uppercase tracking-[0.3em] py-5 rounded-[2rem] transition-all shadow-lg shadow-emerald-900/30 flex items-center justify-center gap-4 group">
                                    Confirmer l'entrée en stock
                                    <i class="fa-solid fa-arrow-up-right-dots text-lg group-hover:translate-y--1 group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="absolute -left-10 -bottom-10 opacity-5 pointer-events-none">
                        <i class="fa-solid fa-truck-ramp-box text-[10rem]"></i>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }

    public function formulaireStock($produit)
    {
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <div class="min-h-screen bg-[#020617] flex items-center justify-center p-6 font-sans">

            <div class="absolute w-96 h-96 bg-indigo-600/10 blur-[120px] rounded-full -z-10 animate-pulse"></div>

            <div class="w-full max-w-md bg-white/[0.03] border border-white/10 backdrop-blur-2xl rounded-[3.5rem] p-10 shadow-2xl relative overflow-hidden group">

                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fa-solid fa-layer-group text-6xl text-white"></i>
                </div>

                <div class="text-center mb-10 relative">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 text-indigo-400 rounded-3xl mb-6 shadow-inner border border-white/5">
                        <i class="fa-solid fa-arrow-up-right-dots text-3xl"></i>
                    </div>

                    <h2 class="text-4xl font-black uppercase italic tracking-tighter text-white mb-2">
                        Mise à jour <span class="text-indigo-500 italic">Stock</span>
                    </h2>

                    <div class="inline-block px-4 py-1.5 bg-indigo-500/10 border border-indigo-500/20 rounded-full">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300">
                            <?= htmlspecialchars($produit['nom']) ?>
                        </p>
                    </div>
                </div>

                <form action="index.php?action=ajouterStock" method="POST" class="space-y-8 relative">
                    <input type="hidden" name="id_produit" value="<?= $produit['id'] ?>">

                    <div class="bg-black/40 rounded-[2.5rem] p-8 border border-white/5 shadow-inner transition-all hover:border-indigo-500/30">

                        <div class="flex justify-between items-center mb-6">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-500">État actuel</span>
                                <span class="text-xl font-bold text-white"><?= $produit['quantiteActuelle'] ?> <small
                                            class="text-[10px] text-slate-500 uppercase">unités</small></span>
                            </div>
                            <div class="h-10 w-[1px] bg-white/10"></div>
                            <div class="flex flex-col text-right">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-500">Catégorie</span>
                                <span class="text-sm font-bold text-indigo-400 uppercase tracking-tighter"><?= htmlspecialchars($p['type'] ?? 'Produit') ?></span>
                            </div>
                        </div>

                        <div class="relative group/input">
                            <label class="block text-[10px] font-black text-indigo-500 uppercase tracking-[0.3em] mb-3 px-1">Quantité
                                à réceptionner</label>
                            <input type="number" name="quantite" required min="1" autofocus placeholder="00"
                                   class="w-full bg-[#0f172a]/50 border-2 border-white/5 text-white text-center text-5xl font-black py-6 rounded-[2rem] focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all duration-300 placeholder:text-white/5">

                            <div class="absolute left-6 top-[60%] -translate-y-1/2 text-slate-600 font-black text-xl">
                                +
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="index.php?action=voirProduits"
                           class="flex-1 bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase py-5 rounded-2xl text-center transition-all border border-white/5 order-2 sm:order-1">
                            <i class="fa-solid fa-xmark mr-2 opacity-50"></i> Annuler
                        </a>

                        <button type="submit"
                                class="flex-[2] bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase py-5 rounded-2xl shadow-xl shadow-indigo-900/40 transition-all hover:-translate-y-1 active:scale-95 order-1 sm:order-2">
                            Valider l'entrée stock <i class="fa-solid fa-check ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public function formulaireAjoutProduit($associations)
    {
        $this->afficherNav();
        ?>
        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white flex justify-center items-start">

            <div class="w-full max-w-3xl animate-fade-in">
                <div class="mb-10 text-center">
                    <span class="text-blue-500 font-black uppercase text-[10px] tracking-[0.4em] mb-3 block italic text-center">Nouveau Référencement</span>
                    <h1 class="text-4xl font-black tracking-tighter text-white uppercase italic">
                        Ajouter au catalogue
                    </h1>
                </div>

                <div class="glass-card bg-white/5 border border-white/10 rounded-[3rem] p-8 md:p-12 shadow-2xl relative overflow-hidden">

                    <form action="index.php?action=ajouterProduit" method="post" class="relative z-10 space-y-8">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Désignation</label>
                                <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-signature"></i>
                                </span>
                                    <input type="text" name="nom" placeholder="Ex: Bière Blonde 33cl" required
                                           class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-6 py-4 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none transition-all font-bold placeholder:text-slate-700">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Catégorie</label>
                                <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500">
                                    <i class="fa-solid fa-list-ul"></i>
                                </span>
                                    <select name="type" required
                                            class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-6 py-4 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none transition-all font-bold appearance-none cursor-pointer">
                                        <option value="" disabled selected class="text-slate-600">Choisir un type
                                        </option>
                                        <option value="boisson">Boisson</option>
                                        <option value="nourriture">Nourriture</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Prix
                                    de vente (€)</label>
                                <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 group-focus-within:text-emerald-500 transition-colors">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </span>
                                    <input type="number" name="prix" step="0.01" min="0" placeholder="0.00" required
                                           class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-6 py-4 rounded-2xl focus:ring-2 focus:ring-emerald-600 outline-none transition-all font-bold">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Stock
                                    Initial</label>
                                <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 group-focus-within:text-amber-500 transition-colors">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </span>
                                    <input type="number" name="stock" min="0" placeholder="0" required
                                           class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-6 py-4 rounded-2xl focus:ring-2 focus:ring-amber-600 outline-none transition-all font-bold">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Association
                                Propriétaire</label>
                            <div class="relative">
        <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 pointer-events-none">
            <i class="fa-solid fa-building-columns"></i>
        </span>

                                <select name="association_id" required
                                        class="w-full bg-[#0f172a] border border-white/5 text-white pl-12 pr-10 py-4 rounded-2xl focus:ring-2 focus:ring-indigo-600 outline-none transition-all font-bold cursor-pointer appearance-none">

                                    <option value="" disabled selected class="bg-[#0f172a] text-slate-500">Sélectionner
                                        l'entité
                                    </option>

                                    <?php foreach ($associations as $asso): ?>
                                        <option value="<?= $asso['id'] ?>" class="bg-[#0f172a] text-white">
                                            <?= htmlspecialchars($asso['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>

                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8">
                            <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black uppercase tracking-[0.3em] py-5 rounded-[2rem] transition-all shadow-lg shadow-blue-900/40 flex items-center justify-center gap-4 group active:scale-[0.98]">
                                <i class="fa-solid fa-plus-circle text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                                Créer le produit
                            </button>
                        </div>
                    </form>

                    <div class="absolute -right-20 -bottom-20 opacity-5 pointer-events-none">
                        <i class="fa-solid fa-box-open text-[20rem]"></i>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }

    public function afficherStock($stocks)
    {
        echo '<h2>État du stock</h2>';

        if (empty($stocks)) {
            echo '<p>Aucun stock disponible.</p>';
        } else {
            foreach ($stocks as $stock) {
                echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
                echo '<strong>' . htmlspecialchars($stock['produit']) . '</strong><br>';
                echo 'Quantité : ' . htmlspecialchars($stock['quantite']) . '<br>';
                echo '<a href="index.php?action=ajouterStock&id=' . $stock['id'] . '">Réapprovisionner</a>';
                echo '</div>';
            }
        }
    }

    public function afficherVentes($ventes)
    {
        echo '<h2>Historique des ventes</h2>';

        if (empty($ventes)) {
            echo '<p>Aucune vente enregistrée.</p>';
        } else {
            foreach ($ventes as $vente) {
                echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
                echo 'ID Vente : ' . $vente['id'] . '<br>';
                echo 'Date : ' . $vente['date_vente'] . '<br>';
                echo 'Montant total : ' . $vente['montant_total'] . '€<br>';
                echo 'Client ID : ' . $vente['compte_id'] . '<br>';
                echo '</div>';
            }
        }
    }

    public function afficherUtilisateurs($utilisateurs)
    {
        echo '<h2>Liste des utilisateurs</h2>';

        if (empty($utilisateurs)) {
            echo '<p>Aucun utilisateur trouvé.</p>';
        } else {
            foreach ($utilisateurs as $utilisateur) {
                echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
                echo 'Nom : ' . htmlspecialchars($utilisateur['nom']) . ' ' . htmlspecialchars($utilisateur['prenom']) . '<br>';
                echo 'Email : ' . htmlspecialchars($utilisateur['email']) . '<br>';
                echo 'Rôle : ' . htmlspecialchars($utilisateur['role']) . '<br>';
                echo 'Solde : ' . htmlspecialchars($utilisateur['solde']) . '€<br>';
                echo '</div>';
            }
        }
    }


    public function afficherStatistiques($stats)
    {
        echo '<h2>Statistiques</h2>';
        echo '<div style="border: 1px solid #ccc; padding: 10px;">';
        echo 'Total ventes : ' . $stats['totalVentes'] . '€<br>';
        echo 'Nombre de produits : ' . $stats['nbProduits'] . '<br>';
        echo 'Nombre d\'utilisateurs : ' . $stats['nbUtilisateurs'] . '<br>';
        echo 'Nombre d\'associations : ' . $stats['nbAssociations'] . '<br>';
        echo '</div>';
    }


    public function afficherAssociationsValidees($associations)
    {
        $this->afficherNav();

        echo '<div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white">';

        echo '
    <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 animate-fade-in">
        <div>
            <span class="text-blue-500 font-black uppercase text-[10px] tracking-[0.3em] mb-2 block italic">Gestion des entités</span>
            <h1 class="text-4xl font-black tracking-tighter text-white uppercase flex items-center gap-4">
                <i class="fa-solid fa-building-shield text-blue-600"></i> Mes Associations
            </h1>
            <p class="text-slate-400 font-medium mt-2 italic text-sm">Liste des organisations validées sous votre supervision.</p>
        </div>
        <div class="bg-blue-600/10 border border-blue-500/20 px-6 py-3 rounded-2xl flex items-center gap-3 shadow-lg backdrop-blur-md">
            <i class="fa-solid fa-check-double text-blue-400"></i>
            <span class="text-xs font-black text-blue-100 uppercase tracking-widest">' . count($associations) . ' Entités Actives</span>
        </div>
    </div>';

        foreach (['success' => 'emerald', 'error' => 'rose'] as $type => $color) {
            if (!empty($_SESSION[$type])) {
                echo '
            <div class="mb-8 p-5 rounded-2xl border border-' . $color . '-500/30 bg-' . $color . '-500/10 text-' . $color . '-200 flex items-center gap-4 animate-fade-in shadow-lg">
                <i class="fa-solid ' . ($type == 'success' ? 'fa-circle-check' : 'fa-circle-exclamation') . ' text-xl"></i>
                <span class="text-sm font-bold tracking-tight">' . htmlspecialchars($_SESSION[$type]) . '</span>
            </div>';
                unset($_SESSION[$type]);
            }
        }

        if (empty($associations)) {
            echo '
        <div class="glass-card rounded-[3rem] p-20 text-center border border-white/5">
            <i class="fa-solid fa-folder-open text-6xl text-slate-700 mb-6 block"></i>
            <p class="text-slate-400 font-bold italic tracking-widest uppercase">Aucune association validée pour le moment.</p>
        </div>';
        } else {
            echo '<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">';

            foreach ($associations as $asso) {
                $solde = $asso['solde'] ?? 0;
                $isPositive = $solde >= 0;
                $soldeColor = $isPositive ? 'text-emerald-400' : 'text-rose-400';
                $soldeBg = $isPositive ? 'bg-emerald-500/10' : 'bg-rose-500/10';
                $soldeBorder = $isPositive ? 'border-emerald-500/20' : 'border-rose-500/20';

                echo '
            <div class="glass-card rounded-[2.5rem] border border-white/5 hover:border-blue-500/30 hover:-translate-y-2 transition-all duration-500 group overflow-hidden flex flex-col h-full shadow-2xl">
                <div class="p-8 pb-4">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center text-2xl font-black text-blue-500 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-lg">
                            ' . strtoupper(substr($asso['nom'], 0, 1)) . '
                        </div>
                        <div class="px-4 py-2 rounded-xl border ' . $soldeBorder . ' ' . $soldeBg . ' ' . $soldeColor . ' font-black text-sm tracking-tighter">
                            ' . number_format($solde, 2, ',', ' ') . ' €
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-2 group-hover:text-blue-400 transition-colors">' . htmlspecialchars($asso['nom'] ?? '') . '</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]">Affiliée & Validée</span>
                    </div>
                </div>

                <div class="px-8 py-6 space-y-4 flex-1">
                    <div class="flex items-center gap-4 group/item">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-500 group-hover/item:text-blue-400 transition-colors">
                            <i class="fa-solid fa-location-dot text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-400 truncate tracking-tight">' . htmlspecialchars($asso['adresse'] ?? 'Non renseigné') . '</span>
                    </div>
                    <div class="flex items-center gap-4 group/item">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-500 group-hover/item:text-blue-400 transition-colors">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-400 truncate tracking-tight">' . htmlspecialchars($asso['email'] ?? 'Non renseigné') . '</span>
                    </div>
                    <div class="flex items-center gap-4 group/item">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-500 group-hover/item:text-blue-400 transition-colors">
                            <i class="fa-solid fa-phone-flip text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-400 tracking-tight">' . htmlspecialchars($asso['telephone'] ?? '-- -- -- -- --') . '</span>
                    </div>
                </div>

                <div class="p-6 bg-white/[0.02] border-t border-white/5 mt-auto">
                    <a href="index.php?action=voirAssociation&id=' . ($asso['id'] ?? 0) . '" 
                       class="flex items-center justify-center gap-3 w-full bg-blue-600 hover:bg-blue-500 text-white py-4 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] transition-all shadow-lg active:scale-95 group/btn">
                       Gérer
                       <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>';
            }

            echo '</div>';
        }

        echo '</div>';
    }


    public function afficherDetailsAssos($assos)
    {
        $this->afficherNav();
        ?>
        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white">

            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8 mb-10 shadow-2xl">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-3xl flex items-center justify-center text-3xl font-black text-white shadow-lg shadow-blue-900/40">
                            <?= strtoupper(substr($assos['nom'], 0, 1)) ?>
                        </div>
                        <div>
                            <h1 class="text-4xl font-black text-white uppercase tracking-tighter leading-none mb-3">
                                <?= htmlspecialchars($assos['nom']) ?>
                            </h1>
                            <div class="flex flex-wrap gap-4 text-slate-400 text-sm italic">
                                <span><i class="fa-solid fa-location-dot mr-2 text-blue-500"></i><?= htmlspecialchars($assos['adresse']) ?></span>
                                <span><i class="fa-solid fa-envelope mr-2 text-blue-500"></i><?= htmlspecialchars($assos['email']) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#0f172a] border border-white/5 p-6 rounded-[2rem] text-right min-w-[200px]">
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Trésorerie</p>
                        <p class="text-3xl font-black <?= $assos['solde'] >= 0 ? 'text-emerald-400' : 'text-rose-500' ?>">
                            <?= number_format($assos['solde'], 2, ',', ' ') ?> €
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-boxes-stacked text-blue-500"></i> Stock Produits
                        </h2>
                        <a href="index.php?action=voirProduits&id=<?= $assos['id'] ?>"
                           class="text-[10px] font-black uppercase text-blue-400 hover:text-white transition-colors">Voir
                            tout</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-slate-500 text-[10px] uppercase font-black tracking-widest border-b border-white/5">
                            <tr>
                                <th class="pb-4">Nom</th>
                                <th class="pb-4">Prix</th>
                                <th class="pb-4 text-right">Stock</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                            <?php foreach (array_slice($assos['produits'], 0, 5) as $p): ?>
                                <tr>
                                    <td class="py-4 font-bold text-white text-sm"><?= htmlspecialchars($p['nom']) ?></td>
                                    <td class="py-4 text-slate-400 text-xs font-bold"><?= number_format($p['prix'], 2) ?>
                                        €
                                    </td>
                                    <td class="py-4 text-right">
                                        <span class="text-xs font-black <?= $p['quantiteActuelle'] < 5 ? 'text-rose-500' : 'text-emerald-400' ?>">
                                            <?= $p['quantiteActuelle'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8 text-white">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-user-ninja text-blue-500"></i> Équipe Staff
                        </h2>
                        <a href="index.php?action=voirBarmans&id=<?= $assos['id'] ?>"
                           class="text-[10px] font-black uppercase text-blue-400 hover:text-white transition-colors">Gérer</a>
                    </div>
                    <div class="space-y-3">
                        <?php foreach (array_slice($assos['barmans'], 0, 4) as $b):
                            $actif = isset($b['est_barman']) && $b['est_barman']; ?>
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 group hover:border-blue-500/30 transition-all">
                                <span class="font-bold text-white text-sm"><?= htmlspecialchars($b['prenom'] . ' ' . $b['nom']) ?></span>
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase <?= $actif ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' ?>">
                                <?= $actif ? 'Actif' : 'Off' ?>
                            </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-users text-blue-500"></i> Base Clients
                        </h2>
                        <a href="index.php?action=voirListeClients&id=<?= $assos['id'] ?>"
                           class="text-[10px] font-black uppercase text-blue-400 hover:text-white transition-colors">Liste</a>
                    </div>
                    <div class="space-y-2">
                        <?php foreach (array_slice($assos['clients'], 0, 5) as $c): ?>
                            <div class="flex items-center justify-between p-4 bg-[#0f172a]/50 hover:bg-white/10 rounded-2xl transition-all group">
                                <span class="font-bold text-white text-sm group-hover:text-blue-400"><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></span>
                                <span class="font-black text-xs <?= $c['solde'] >= 0 ? 'text-emerald-400' : 'text-rose-500' ?>">
                                <?= number_format($c['solde'], 2) ?> €
                            </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8">
                    <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-8 flex items-center gap-3">
                        <i class="fa-solid fa-receipt text-blue-500"></i> Derniers Flux
                    </h2>
                    <div class="space-y-4">
                        <?php if (!empty($assos['ventes'])): ?>
                            <?php foreach (array_slice($assos['ventes'], 0, 4) as $v): ?>
                                <div class="flex items-center justify-between p-4 bg-white/[0.02] border-l-4 border-blue-600 rounded-r-2xl">
                                    <div>
                                        <p class="text-sm font-bold text-white mb-1"><?= htmlspecialchars($v['client_prenom'] . ' ' . $v['client_nom']) ?></p>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase"><?= $v['date_vente'] ?></p>
                                    </div>
                                    <p class="text-lg font-black text-white tracking-tighter"><?= number_format($v['montant_total'], 2) ?>
                                        €</p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-slate-600 italic text-sm text-center py-6">Aucun flux récent.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }


}