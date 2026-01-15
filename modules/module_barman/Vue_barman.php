<?php

class Vue_barman
{

    private function afficherHeader($titre = "Caisse Barman") {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= htmlspecialchars($titre) ?></title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Montserrat', sans-serif; }
                .custom-scrollbar::-webkit-scrollbar { width: 4px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
            </style>
        </head>
        <body class="bg-[#020617] text-slate-200">
        <?php
    }

    public function afficherNav() {
        $prenom = $_SESSION['prenom'] ?? 'Barman';
        $photo = $_SESSION['photo'] ?? null;
        $actionActuelle = $_GET['action'] ?? 'accueil';

        $activeClass = "bg-blue-600/10 text-blue-400 border-r-4 border-blue-600 shadow-[inset_0_0_15px_rgba(37,99,235,0.1)]";
        $inactiveClass = "text-slate-400 hover:bg-white/5 hover:text-white border-r-4 border-transparent";

        echo '
        <div class="flex min-h-screen bg-[#020617]">
            <aside class="w-72 bg-[#020617] border-r border-white/5 hidden md:flex flex-col sticky top-0 h-screen shadow-2xl z-50">
                
                <div class="h-24 flex items-center px-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/40">
                            <i class="fa-solid fa-bolt text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-black text-white tracking-tighter uppercase">Bar<span class="text-blue-600">Soft</span></span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
                    
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mb-4 mt-2">Service</p>
                    
                    <a href="index.php?action=accueil" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'accueil' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-gauge-high text-lg ' . ($actionActuelle == 'accueil' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                        <span class="font-bold text-sm tracking-tight">Dashboard</span>
                    </a>

                    <a href="index.php?action=creerTransaction" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'creerTransaction' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-cash-register text-lg ' . ($actionActuelle == 'creerTransaction' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                        <span class="font-bold text-sm tracking-tight">Vendre</span>
                    </a>

                    <a href="index.php?action=commandesEnCours" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'commandesEnCours' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-fire text-lg ' . ($actionActuelle == 'commandesEnCours' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                        <span class="font-bold text-sm tracking-tight">Commandes</span>
                    </a>

                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mt-10 mb-4">Données</p>

                    <a href="index.php?action=rechercherClient" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'rechercherClient' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-user-tag text-lg ' . ($actionActuelle == 'rechercherClient' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                        <span class="font-bold text-sm tracking-tight">Clients</span>
                    </a>

                    <a href="index.php?action=historiqueCommandes" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'historiqueCommandes' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-list-check text-lg ' . ($actionActuelle == 'historiqueCommandes' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                        <span class="font-bold text-sm tracking-tight">Journal</span>
                    </a>

                    <a href="index.php?action=afficherProduits" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 group ' . ($actionActuelle == 'afficherProduits' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-box text-lg ' . ($actionActuelle == 'afficherProduits' ? 'text-blue-500' : 'group-hover:text-blue-400') . '"></i>
                        <span class="font-bold text-sm tracking-tight">Stocks</span>
                    </a>
                </nav>

                <div class="p-6 mt-auto">
                    <div class="bg-white/5 rounded-[2rem] p-4 border border-white/5">
                        <div class="flex items-center gap-3 mb-4 px-2">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 p-0.5 overflow-hidden">
                                    <div class="w-full h-full rounded-full bg-[#020617] flex items-center justify-center overflow-hidden">';

        if (!empty($photo)) {
            echo '<img src="uploads/profiles/'.$photo.'" class="w-full h-full object-cover">';
        } else {
            echo '<span class="text-white font-black text-sm">'.strtoupper(substr($prenom,0,1)).'</span>';
        }

        echo '                      </div>
                                </div>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-xs font-black text-white truncate uppercase">'.htmlspecialchars($prenom).'</p>
                                <p class="text-[10px] font-bold text-blue-500 uppercase">Barman</p>
                            </div>
                        </div>
                        <a href="index.php?action=deconnexion" class="flex items-center justify-center gap-2 w-full bg-white/5 hover:bg-rose-600/10 text-slate-400 hover:text-rose-500 py-3 rounded-2xl transition-all duration-300 text-[11px] font-black uppercase tracking-widest border border-white/5">
                            <i class="fa-solid fa-power-off"></i> Déconnexion
                        </a>
                    </div>
                </div>
            </aside>
            <main class="flex-1 overflow-x-hidden p-8">';
    }

    private function afficherFooter() {
        echo '</main></div></body></html>';
    }

    public function afficherAccueil()
    {
        $this->afficherHeader("Accueil");
        $this->afficherNav();
        ?>
        <h1>Bienvenue dans le gestionnaire de buvette</h1>
        <p>Veuillez choisir une section dans le menu ci-dessus.</p>
        <?php
        $this->afficherFooter();
    }

    public function afficherDerniereTransaction($transaction)
    {
        $this->afficherHeader("Dernière transaction");
        $this->afficherNav(); // On utilise afficherNav() pour la sidebar latérale
        ?>
        <div class="max-w-4xl mx-auto">
            <header class="mb-8">
                <h1 class="text-3xl font-black text-white uppercase italic tracking-tighter">Détails <span class="text-blue-600">Transaction</span></h1>
                <p class="text-slate-400 font-bold text-xs tracking-[0.2em] uppercase mt-2">Récapitulatif de la dernière opération</p>
            </header>

            <?php if (!$transaction): ?>
                <div class="bg-white/5 border border-white/5 rounded-[2rem] p-10 text-center">
                    <i class="fa-solid fa-magnifying-glass text-slate-600 text-4xl mb-4"></i>
                    <p class="text-slate-400 font-bold">Aucune transaction trouvée dans l'historique récent.</p>
                </div>
            <?php else:
                $isAnnulee = ($transaction['statut'] ?? 'payee') === 'annulee';
                ?>
                <div class="bg-white/5 border border-white/5 rounded-[2.5rem] p-8 mb-8 relative overflow-hidden shadow-2xl">
                    <div class="absolute top-8 right-8">
                        <?php if($isAnnulee): ?>
                            <span class="bg-rose-600/10 text-rose-500 border border-rose-500/20 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest">
                            <i class="fa-solid fa-circle-xmark mr-1"></i> Annulée
                        </span>
                        <?php else: ?>
                            <span class="bg-green-600/10 text-green-500 border border-green-500/20 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest">
                            <i class="fa-solid fa-check-circle mr-1"></i> Confirmée
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-6 mb-8">
                        <div class="w-16 h-16 bg-blue-600/20 rounded-2xl flex items-center justify-center border border-blue-500/30">
                            <i class="fa-solid fa-receipt text-blue-500 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white tracking-tighter uppercase">Ticket #<?= htmlspecialchars($transaction['transaction_id']) ?></h3>
                            <p class="text-slate-500 text-sm font-bold"><?= htmlspecialchars($transaction['date_vente']) ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-white/5 pt-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Client</p>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs font-bold text-white">
                                    <?= strtoupper(substr($transaction['prenom'], 0, 1)) ?>
                                </div>
                                <p class="text-white font-bold"><?= htmlspecialchars($transaction['prenom'] . ' ' . $transaction['nom']) ?></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Montant Total</p>
                            <p class="text-3xl font-black text-blue-500 italic tracking-tighter"><?= number_format($transaction['montant_total'], 2) ?> €</p>
                        </div>
                    </div>
                </div>

                <?php if (!empty($transaction['produits'])): ?>
                <div class="bg-white/5 border border-white/5 rounded-[2rem] overflow-hidden mb-8 shadow-xl">
                    <div class="px-8 py-4 bg-white/5 border-b border-white/5">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Articles commandés</p>
                    </div>
                    <table class="w-full text-left">
                        <thead>
                        <tr class="text-slate-500 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-8 py-4">Produit</th>
                            <th class="px-8 py-4 text-center">Qté</th>
                            <th class="px-8 py-4 text-right">Prix</th>
                            <th class="px-8 py-4 text-right">Total</th>
                        </tr>
                        </thead>
                        <tbody class="text-white font-bold text-sm">
                        <?php foreach ($transaction['produits'] as $produit): ?>
                            <tr class="border-t border-white/5 hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-4"><?= htmlspecialchars($produit['nom']) ?></td>
                                <td class="px-8 py-4 text-center">
                                    <span class="bg-white/5 px-3 py-1 rounded-lg">x<?= htmlspecialchars($produit['quantite']) ?></span>
                                </td>
                                <td class="px-8 py-4 text-right text-slate-400"><?= number_format($produit['prix_unitaire'], 2) ?> €</td>
                                <td class="px-8 py-4 text-right text-blue-400"><?= number_format($produit['quantite'] * $produit['prix_unitaire'], 2) ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

                <?php if (!$isAnnulee): ?>
                <div class="bg-rose-600/5 border border-rose-500/20 rounded-[2rem] p-8">
                    <div class="flex flex-col md:flex-row items-center gap-6 justify-between">
                        <div class="flex items-center gap-4 text-rose-500">
                            <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
                            <div>
                                <h4 class="font-black uppercase tracking-tighter text-lg">Action de sécurité</h4>
                                <p class="text-rose-500/60 text-xs font-bold uppercase tracking-widest">L'annulation rembourse le client et restaure les stocks</p>
                            </div>
                        </div>
                        <form method="post" action="index.php" onsubmit="return confirm('Voulez-vous vraiment annuler cette transaction ?')">
                            <input type="hidden" name="action" value="annulerTransaction">
                            <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['transaction_id']) ?>">
                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-black uppercase text-[10px] tracking-[0.2em] px-8 py-4 rounded-2xl transition-all shadow-lg shadow-rose-900/40">
                                Annuler la transaction
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white/5 border border-white/5 rounded-[2rem] p-8 text-center border-dashed">
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-widest">
                        <i class="fa-solid fa-info-circle mr-2"></i> Historique archivé : cette vente est déjà annulée.
                    </p>
                </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
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
        $this->afficherNav(); // Correction du nom de la méthode nav
        ?>
        <div class="w-full h-full px-4 md:px-10 pb-10">

            <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/5 pb-6">
                <div>
                    <h1 class="text-3xl font-black text-white uppercase italic tracking-tighter leading-none">
                        Archives <span class="text-blue-600">Ventes</span>
                    </h1>
                    <p class="text-slate-500 font-bold text-[10px] tracking-[0.2em] uppercase mt-2">
                        Historique complet des transactions
                    </p>
                </div>
            </header>

            <?php if (empty($commandes)): ?>
                <div class="bg-white/5 border border-white/5 rounded-[2rem] p-20 text-center shadow-2xl">
                    <i class="fa-solid fa-box-open text-slate-700 text-5xl mb-4"></i>
                    <h3 class="text-white font-black uppercase tracking-tighter text-xl">Historique vide</h3>
                    <p class="text-slate-500 font-bold text-sm mt-2 uppercase tracking-widest">Aucune donnée enregistrée pour le moment</p>
                </div>
            <?php else:
                // Initialisation des compteurs pour le bilan
                $totalTerminees = 0;
                $totalAnnulees = 0;
                $montantTotal = 0;
                foreach ($commandes as $cmd) {
                    if (($cmd['statut'] ?? '') === 'payee') {
                        $totalTerminees++;
                        $montantTotal += $cmd['montant_total'];
                    } elseif (($cmd['statut'] ?? '') === 'annulee') {
                        $totalAnnulees++;
                    }
                }
                ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="bg-white/5 border border-white/5 p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 text-green-500/10 text-6xl rotate-12 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Commandes Termis</p>
                        <p class="text-3xl font-black text-green-500 italic"><?= $totalTerminees ?></p>
                    </div>

                    <div class="bg-white/5 border border-white/5 p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 text-rose-500/10 text-6xl rotate-12 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Commandes Annulées</p>
                        <p class="text-3xl font-black text-rose-500 italic"><?= $totalAnnulees ?></p>
                    </div>

                    <div class="bg-blue-600/10 border border-blue-500/20 p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 text-blue-500/10 text-6xl rotate-12 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-euro-sign"></i>
                        </div>
                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Chiffre d'Affaires</p>
                        <p class="text-3xl font-black text-white italic"><?= number_format($montantTotal, 2) ?> €</p>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <table class="w-full text-left">
                        <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date & Heure</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Client</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Montant</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Statut</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php foreach ($commandes as $commande):
                            $isAnnulee = ($commande['statut'] ?? '') === 'annulee';
                            ?>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6 font-black text-blue-500 italic">#<?= htmlspecialchars($commande['commande_id']) ?></td>
                                <td class="px-8 py-6 text-slate-400 text-xs font-bold"><?= htmlspecialchars($commande['date_heure_affichage']) ?></td>
                                <td class="px-8 py-6 text-white font-bold tracking-tight">
                                    <?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?>
                                </td>
                                <td class="px-8 py-6 text-right font-black text-white italic">
                                    <?= number_format($commande['montant_total'], 2) ?> €
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php if($isAnnulee): ?>
                                        <span class="bg-rose-500/10 text-rose-500 border border-rose-500/20 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">Annulée</span>
                                    <?php else: ?>
                                        <span class="bg-green-500/10 text-green-500 border border-green-500/20 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">Payée</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <a href="index.php?action=detailCommande&id=<?= $commande['commande_id'] ?>"
                                       class="bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border border-white/5">
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
        <?php
        $this->afficherFooter();
    }
    public function afficherProduits($produits)
    {
        $this->afficherHeader("Liste des produits");
        $this->afficherNav();
        ?>
        <div class="w-full h-full px-4 md:px-10 pb-10">

            <header class="mb-8 flex flex-col md:flex-row md:items-end justify-between border-b border-white/5 pb-6">
                <div>
                    <h1 class="text-3xl font-black text-white uppercase italic tracking-tighter leading-none">
                        Gestion <span class="text-blue-600">Stocks</span>
                    </h1>
                    <p class="text-slate-500 font-bold text-[10px] tracking-[0.2em] uppercase mt-2 italic">
                        Inventaire et tarifs en temps réel
                    </p>
                </div>

                <div class="mt-4 md:mt-0 flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Total articles</p>
                        <p class="text-xl font-black text-white italic"><?= count($produits) ?></p>
                    </div>
                </div>
            </header>

            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" id="rechercheProduit" onkeyup="filtrerProduits()"
                           placeholder="Rechercher un produit..."
                           class="w-full bg-white/5 border border-white/10 rounded-2xl pl-14 pr-6 py-4 text-white font-bold placeholder:text-slate-600 focus:ring-2 focus:ring-blue-600/50 outline-none transition-all border-none">
                </div>

                <div class="relative min-w-[200px]">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-blue-500">
                        <i class="fa-solid fa-filter text-xs"></i>
                    </div>
                    <select id="triProduit" onchange="trierProduits()"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl pl-10 pr-6 py-4 text-white font-black text-[10px] uppercase tracking-widest focus:ring-2 focus:ring-blue-600/50 outline-none appearance-none border-none cursor-pointer">
                        <option value="nom">Trier par Nom</option>
                        <option value="prix-asc">Prix Croissant</option>
                        <option value="prix-desc">Prix Décroissant</option>
                        <option value="stock-desc">Stock (Max-Min)</option>
                        <option value="stock-asc">Stock (Min-Max)</option>
                    </select>
                </div>
            </div>

            <?php if (empty($produits)): ?>
                <div class="bg-white/5 border border-white/5 rounded-[2rem] p-20 text-center shadow-2xl">
                    <h3 class="text-white font-black uppercase tracking-tighter text-xl">Inventaire vide</h3>
                </div>
            <?php else: ?>
                <div class="bg-white/5 border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <table class="w-full text-left" id="tableProduits">
                        <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Désignation</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tarif</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Disponibilité</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php foreach ($produits as $p): ?>
                            <tr class="produit-item hover:bg-white/[0.02] transition-colors group"
                                data-nom="<?= strtolower(htmlspecialchars($p['nom'])) ?>"
                                data-prix="<?= $p['prix'] ?>"
                                data-stock="<?= $p['disponibilite'] ?>">

                                <td class="px-8 py-6 text-slate-600 font-black text-xs italic">#<?= $p['id'] ?></td>
                                <td class="px-8 py-6">
                                    <span class="nom-produit text-white font-bold uppercase tracking-tight italic"><?= htmlspecialchars($p['nom']) ?></span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-white font-black text-lg tracking-tighter italic">
                                        <?= number_format($p['prix'], 2) ?> <span class="text-blue-600">€</span>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <?php
                                    $stock = (int)$p['disponibilite'];
                                    $stockClass = ($stock <= 0) ? "text-rose-500 bg-rose-500/10 border-rose-500/20" : (($stock < 10) ? "text-amber-500 bg-amber-500/10 border-amber-500/20" : "text-green-500 bg-green-500/10 border-green-500/20");
                                    ?>
                                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-xl border <?= $stockClass ?>">
                                        <span class="text-xs font-black uppercase tracking-widest"><?= $stock ?></span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-current <?= $stock < 10 ? 'animate-pulse' : '' ?>"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <script>
            function filtrerProduits() {
                const input = document.getElementById('rechercheProduit').value.toLowerCase();
                const items = document.querySelectorAll('.produit-item');

                items.forEach(item => {
                    const nom = item.getAttribute('data-nom');
                    item.style.display = nom.includes(input) ? '' : 'none';
                });
            }

            function trierProduits() {
                const tri = document.getElementById('triProduit').value;
                const tbody = document.querySelector('#tableProduits tbody');
                const items = Array.from(tbody.querySelectorAll('.produit-item'));

                items.sort((a, b) => {
                    let valA, valB;
                    switch(tri) {
                        case 'nom':
                            return a.getAttribute('data-nom').localeCompare(b.getAttribute('data-nom'));
                        case 'prix-asc':
                            return parseFloat(a.getAttribute('data-prix')) - parseFloat(b.getAttribute('data-prix'));
                        case 'prix-desc':
                            return parseFloat(b.getAttribute('data-prix')) - parseFloat(a.getAttribute('data-prix'));
                        case 'stock-asc':
                            return parseInt(a.getAttribute('data-stock')) - parseInt(b.getAttribute('data-stock'));
                        case 'stock-desc':
                            return parseInt(b.getAttribute('data-stock')) - parseInt(a.getAttribute('data-stock'));
                    }
                });

                items.forEach(item => tbody.appendChild(item));
            }
        </script>
        <?php
        $this->afficherFooter();
    }

    public function afficherClients($clients, $search = null)
    {
        $this->afficherHeader("Gestion Clients");
        $this->afficherNav();
        ?>
        <div class="w-full h-full px-4 md:px-10 pb-10">

            <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/5 pb-6">
                <div>
                    <h1 class="text-3xl font-black text-white uppercase italic tracking-tighter leading-none">
                        Base <span class="text-blue-600">Clients</span>
                    </h1>
                    <p class="text-slate-500 font-bold text-[10px] tracking-[0.2em] uppercase mt-2">
                        Consultation des soldes et comptes
                    </p>
                </div>
            </header>

            <div class="bg-white/5 border border-white/5 rounded-[2rem] p-8 mb-10 shadow-2xl">
                <form method="get" action="index.php" class="flex flex-col md:flex-row gap-4">
                    <input type="hidden" name="action" value="rechercherClient">

                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition-colors">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <input type="text" name="search"
                               placeholder="Rechercher par Nom, Prénom ou ID..."
                               value="<?= htmlspecialchars($search ?? '') ?>"
                               class="w-full bg-[#020617] border border-white/10 rounded-2xl pl-14 pr-6 py-5 text-white font-bold placeholder:text-slate-600 focus:ring-2 focus:ring-blue-600/50 outline-none transition-all border-none">
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-black uppercase text-xs tracking-[0.2em] px-10 py-5 rounded-2xl transition-all shadow-xl shadow-blue-900/20 active:scale-95">
                        Rechercher
                    </button>
                </form>
            </div>

            <?php if ($search): ?>
                <?php if (empty($clients)): ?>
                    <div class="bg-white/5 border border-white/5 rounded-[2rem] p-16 text-center shadow-xl border-dashed">
                        <div class="text-slate-600 text-5xl mb-4 italic">!</div>
                        <h3 class="text-white font-black uppercase tracking-tighter text-xl">Aucun résultat</h3>
                        <p class="text-slate-500 font-bold text-sm mt-2 uppercase tracking-widest">Le client "<?= htmlspecialchars($search) ?>" n'existe pas</p>
                    </div>
                <?php else: ?>
                    <div class="bg-white/5 border border-white/5 rounded-[2rem] overflow-hidden shadow-2xl">
                        <table class="w-full text-left">
                            <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Client</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Solde Actuel</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                            <?php foreach ($clients as $c):
                                $isNegative = $c['solde'] <= 0;
                                ?>
                                <tr class="hover:bg-white/[0.02] transition-colors group">
                                    <td class="px-8 py-6">
                                        <span class="text-slate-500 font-black text-xs">#<?= htmlspecialchars($c['id']) ?></span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white font-black border border-white/10 group-hover:border-blue-500/50 transition-colors">
                                                <?= strtoupper(substr($c['prenom'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <p class="text-white font-bold uppercase tracking-tight"><?= htmlspecialchars($c['nom']) ?></p>
                                                <p class="text-slate-500 text-xs font-medium lowercase"><?= htmlspecialchars($c['prenom']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span class="<?= $isNegative ? 'text-rose-500 bg-rose-500/10 border-rose-500/20' : 'text-blue-500 bg-blue-500/10 border-blue-500/20' ?> border px-4 py-2 rounded-xl font-black text-lg tracking-tighter">
                                            <?= number_format($c['solde'], 2) ?> €
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-20">
                    <i class="fa-solid fa-arrow-up text-blue-600 animate-bounce text-2xl mb-4"></i>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.3em] text-[10px]">Saisissez un nom ou un ID pour commencer</p>
                </div>
            <?php endif; ?>
        </div>
        <?php
        $this->afficherFooter();
    }

    public function afficherCommandes($commandes)
    {
        $this->afficherHeader("Commandes");
        $this->afficherNav();
        ?>
        <div class="w-full h-full px-4 md:px-10 pb-10">

            <header class="mb-8 flex flex-col md:flex-row md:items-end justify-between border-b border-white/5 pb-6">
                <div>
                    <h1 class="text-3xl font-black text-white uppercase italic tracking-tighter leading-none">
                        Commandes <span class="text-blue-600">En Cours</span>
                    </h1>
                    <p class="text-slate-500 font-bold text-[10px] tracking-[0.2em] uppercase mt-2 italic">
                        File d'attente des préparations (Aujourd'hui)
                    </p>
                </div>

                <div class="mt-4 md:mt-0">
                <span class="bg-blue-600/10 text-blue-500 border border-blue-500/20 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                    <i class="fa-solid fa-clock mr-2"></i> Temps Réel
                </span>
                </div>
            </header>

            <?php if (empty($commandes)): ?>
                <div class="bg-white/5 border border-white/5 rounded-[2rem] p-20 flex flex-col items-center justify-center text-center shadow-2xl">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6 border border-white/10">
                        <i class="fa-solid fa-mug-hot text-slate-600 text-3xl"></i>
                    </div>
                    <h3 class="text-white font-black uppercase tracking-tighter text-xl">Calme plat...</h3>
                    <p class="text-slate-500 font-bold text-sm mt-2">Aucune commande payée n'est en attente pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="bg-white/5 border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-white/5">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">N° Commande</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Client</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Heure</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Total</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php foreach ($commandes as $c):
                            $datetime = new DateTime($c['date_vente']);
                            ?>
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6">
                                    <span class="text-blue-500 font-black tracking-tighter text-lg italic">
                                        #<?= htmlspecialchars($c['commande_id']) ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-white font-bold tracking-tight">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-600/10 flex items-center justify-center text-blue-500 text-[10px] font-black border border-blue-500/20">
                                            <?= strtoupper(substr($c['prenom'], 0, 1)) ?>
                                        </div>
                                        <?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="bg-slate-800 text-slate-300 font-black px-3 py-1.5 rounded-lg text-xs tracking-widest border border-white/5">
                                        <?= $datetime->format('H:i') ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-white font-black text-lg tracking-tighter">
                                        <?= number_format($c['montant_total'], 2) ?> <span class="text-blue-600">€</span>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <a href="index.php?action=detailCommande&id=<?= $c['commande_id'] ?>"
                                       class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-black uppercase text-[10px] tracking-widest px-6 py-3 rounded-xl transition-all shadow-lg shadow-blue-900/40 hover:-translate-y-0.5 active:scale-95">
                                        Servir <i class="fa-solid fa-arrow-right-long"></i>
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
        $this->afficherFooter();
    }

    public function afficherDetailCommande($commande, $produits)
    {
        $this->afficherHeader("Détail commande");
        $this->afficherNav();
        ?>
        <h2>Détail de la commande</h2>
        <?php if (!$commande): ?>
        <p>Commande introuvable.</p>
    <?php else:
        $datetime = new DateTime($commande['date_vente']);
        ?>
        <div style="background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #4CAF50;">
            <h3 style="margin-top: 0;">Commande n°<?= htmlspecialchars($commande['commande_id']) ?></h3>
            <p><strong>Client :</strong> <?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></p>
            <p><strong>Date/Heure :</strong> <?= $datetime->format('d/m/Y à H:i') ?></p>
            <p><strong>Montant total :</strong> <span
                        style="font-size: 20px; color: #4CAF50; font-weight: bold;"><?= htmlspecialchars(number_format($commande['montant_total'], 2)) ?> €</span>
            </p>
        </div>

        <h3>📦 Liste des produits</h3>
        <?php if (empty($produits)): ?>
        <p>Aucun produit dans cette commande.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Produit</th>
                <th style="text-align: center;">Quantité</th>
                <th style="text-align: right;">Prix unitaire</th>
                <th style="text-align: right;">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            foreach ($produits as $p):
                $sousTotal = $p['quantite'] * $p['prix_unitaire'];
                $total += $sousTotal;
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($p['nom']) ?></strong></td>
                    <td style="text-align: center; font-size: 18px;">
                        <strong><?= htmlspecialchars($p['quantite']) ?></strong></td>
                    <td style="text-align: right;"><?= htmlspecialchars(number_format($p['prix_unitaire'], 2)) ?> €</td>
                    <td style="text-align: right; font-weight: bold;"><?= htmlspecialchars(number_format($sousTotal, 2)) ?>
                        €
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="3" style="text-align: right; padding: 15px;">TOTAL :</td>
                <td style="text-align: right; font-size: 18px; color: #4CAF50; padding: 15px;"><?= htmlspecialchars(number_format($total, 2)) ?>
                    €
                </td>
            </tr>
            </tbody>
        </table>
    <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="index.php?action=commandesEnCours" class="btn btn-secondary">← Retour aux commandes</a>
        </div>
    <?php endif; ?>
        <?php
        $this->afficherFooter();
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
        $this->afficherHeader("Nouvelle transaction");
        $this->afficherNav();
        ?>
        <div class="w-full h-full px-4 md:px-10 pb-10">

            <header class="mb-6 flex flex-col md:flex-row md:items-end justify-between border-b border-white/5 pb-6">
                <div>
                    <h1 class="text-3xl font-black text-white uppercase italic tracking-tighter leading-none">
                        Nouvelle <span class="text-blue-600">Vente</span>
                    </h1>
                    <p class="text-slate-500 font-bold text-[10px] tracking-[0.2em] uppercase mt-2">
                        Terminal de saisie rapide
                    </p>
                </div>

                <?php if ($erreur): ?>
                    <div class="bg-rose-600/10 border border-rose-500/20 text-rose-500 px-4 py-2 rounded-xl flex items-center gap-3 animate-pulse text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span class="font-black uppercase italic italic">Erreur : <?= $erreur ?></span>
                    </div>
                <?php endif; ?>
            </header>

            <form method="post" action="index.php" onsubmit="return validerFormulaire()" class="space-y-6">
                <input type="hidden" name="action" value="traiterTransaction">

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white/5 border border-white/5 rounded-3xl p-6 shadow-xl">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-blue-600/20 rounded-lg flex items-center justify-center border border-blue-500/30">
                                    <i class="fa-solid fa-user-tag text-blue-500 text-sm"></i>
                                </div>
                                <h3 class="text-sm font-black text-white uppercase tracking-tighter">Client</h3>
                            </div>

                            <div class="relative group">
                                <label for="client_id" class="absolute -top-2 left-4 bg-[#020617] px-2 text-[9px] font-black text-blue-500 uppercase tracking-widest z-10">ID Compte</label>
                                <input type="number" name="client_id" id="client_id"
                                       value="<?= htmlspecialchars($donneesSaisies['client_id'] ?? '') ?>"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white font-bold text-lg focus:ring-2 focus:ring-blue-600/50 outline-none transition-all"
                                       min="1" required autofocus>
                            </div>
                            <p class="text-[9px] text-slate-500 mt-4 font-bold uppercase tracking-tight italic text-center italic opacity-50">Scanner le badge ou saisir l'ID</p>
                        </div>

                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black uppercase text-xs tracking-[0.2em] py-6 rounded-3xl transition-all shadow-xl shadow-blue-900/20 flex items-center justify-center gap-3 group">
                            Encaisser
                            <i class="fa-solid fa-bolt text-lg group-hover:scale-125 transition-transform"></i>
                        </button>
                    </div>

                    <div class="lg:col-span-3">
                        <div class="bg-white/5 border border-white/5 rounded-3xl p-6 shadow-xl min-h-[500px] flex flex-col">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-600/20 rounded-lg flex items-center justify-center border border-blue-500/30">
                                        <i class="fa-solid fa-list text-blue-500 text-sm"></i>
                                    </div>
                                    <h3 class="text-sm font-black text-white uppercase tracking-tighter">Panier</h3>
                                </div>

                                <button type="button" onclick="ajouterProduit()"
                                        class="bg-blue-600/10 hover:bg-blue-600 text-blue-400 hover:text-white font-black uppercase text-[10px] tracking-widest px-4 py-2 rounded-xl border border-blue-600/20 transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-plus"></i> Ajouter
                                </button>
                            </div>

                            <div id="produits-container" class="space-y-3 overflow-y-auto pr-2 custom-scrollbar flex-1">
                                <?php
                                $produits_saisis = $donneesSaisies['produits'] ?? [['id' => '', 'quantite' => '1']];
                                foreach ($produits_saisis as $index => $produit_saisi):
                                    ?>
                                    <div class="produit-row flex items-center gap-4 p-4 bg-white/[0.03] border border-white/5 rounded-2xl group transition-all">
                                        <div class="flex-1">
                                            <select name="produits[<?= $index ?>][id]" required
                                                    class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm focus:ring-1 focus:ring-blue-600 outline-none">
                                                <option value="">Sélectionner un produit...</option>
                                                <?php foreach ($produits as $produit): ?>
                                                    <option value="<?= $produit['id'] ?>" <?= ($produit_saisi['id'] ?? '') == $produit['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($produit['nom']) ?> — <?= number_format($produit['prix'], 2) ?>€ (Stock: <?= $produit['disponibilite'] ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="w-24">
                                            <input type="number" name="produits[<?= $index ?>][quantite]"
                                                   value="<?= htmlspecialchars($produit_saisi['quantite'] ?? '1') ?>"
                                                   class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-center text-sm focus:ring-1 focus:ring-blue-600 outline-none"
                                                   min="1" required>
                                        </div>

                                        <button type="button" onclick="supprimerProduit(this)"
                                                class="bg-rose-600/5 hover:bg-rose-600 text-rose-500 hover:text-white w-10 h-10 rounded-xl flex items-center justify-center transition-all border border-rose-500/10 <?= $index == 0 ? 'opacity-0 pointer-events-none' : '' ?>">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <script>
            let produitsDisponibles = <?= json_encode($produits) ?>;
            let compteurProduit = <?= count($produits_saisis) ?>;

            function ajouterProduit() {
                const container = document.getElementById("produits-container");
                const div = document.createElement("div");
                div.className = "produit-row flex items-center gap-4 p-4 bg-white/[0.03] border border-white/5 rounded-2xl animate-fadeIn";

                let optionsHtml = '<option value="">Sélectionner un produit...</option>';
                produitsDisponibles.forEach(function (produit) {
                    optionsHtml += `<option value="${produit.id}">${produit.nom} — ${parseFloat(produit.prix).toFixed(2)}€ (${produit.disponibilite} stk)</option>`;
                });

                div.innerHTML = `
                <div class="flex-1">
                    <select name="produits[${compteurProduit}][id]" required class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm focus:ring-1 focus:ring-blue-600 outline-none">
                        ${optionsHtml}
                    </select>
                </div>
                <div class="w-24">
                    <input type="number" name="produits[${compteurProduit}][quantite]" value="1" min="1" required class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-center text-sm focus:ring-1 focus:ring-blue-600 outline-none">
                </div>
                <button type="button" onclick="supprimerProduit(this)" class="bg-rose-600/5 hover:bg-rose-600 text-rose-500 hover:text-white w-10 h-10 rounded-xl flex items-center justify-center transition-all border border-rose-500/10">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;

                container.appendChild(div);
                // Auto-scroll vers le nouveau produit
                container.scrollTop = container.scrollHeight;
                compteurProduit++;
            }

            function supprimerProduit(bouton) {
                bouton.closest('.produit-row').remove();
            }

            function validerFormulaire() {
                const selects = document.querySelectorAll('select[name^="produits"]');
                let check = false;
                selects.forEach(s => { if(s.value !== "") check = true; });

                if(!check) {
                    alert("Veuillez sélectionner au moins un produit.");
                    return false;
                }

                return confirm('Confirmer la vente ?'); // Retourne true ou false
            }
        </script>

        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateX(10px); }
                to { opacity: 1; transform: translateX(0); }
            }
            .animate-fadeIn { animation: fadeIn 0.2s ease-out forwards; }
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
        $this->afficherFooter();
    }
}