<?php
include_once 'modules/module_commun/vue_commun.php';
class Vue_client extends VueCommun
{
    public function __construct()
    {
    }

    public function afficherFooter()
    {
        ?>
        </main>
        </div>

        <footer style="background-color: #020617; color: white; text-align: center; padding: 20px; font-size: 0.8rem; border-top: 1px solid rgba(255,255,255,0.05);">
            <p>Copyright Buvette du 93 &copy; Tous droits réservés</p>
        </footer>
        <?php
    }

    public function afficherNav()
    {
        $prenom = $_SESSION['prenom'] ?? 'Client';
        $photo = $_SESSION['photo'] ?? null;
        $actionActuelle = $_GET['action'] ?? 'espace';

        $activeClass = "bg-blue-600/15 text-blue-400 border-r-4 border-blue-600 shadow-[0_0_20px_rgba(37,99,235,0.1)]";
        $inactiveClass = "text-slate-500 border-r-4 border-transparent";

        $cheminPhoto = !empty($photo) ? "uploads/profiles/" . basename($photo) : null;
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            .font-montserrat { font-family: "Montserrat", sans-serif; }
            .glass-sidebar {
                background: rgba(2, 6, 23, 0.95) !important;
                backdrop-filter: blur(20px);
                border-right: 1px solid rgba(255, 255, 255, 0.05);
            }
            .bfor-card {
                background: linear-gradient(145deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.6));
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 20px; padding: 25px; margin-bottom: 20px;
            }
            .bfor-account h1 { font-size: 36px; font-weight: 900; color: white; }
            table { width: 100%; color: white; border-collapse: collapse; margin-top: 15px; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
            input[type="submit"], button { background: #2563eb; color: white; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; }
        </style>

        <div class="flex min-h-screen bg-[#020617] font-montserrat">
            <aside class="w-72 glass-sidebar hidden md:flex flex-col sticky top-0 h-screen z-[1001]">
                <div class="h-24 flex items-center px-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg transform -rotate-6">
                            <i class="fa-solid fa-user-astronaut text-white text-lg rotate-6"></i>
                        </div>
                        <span class="text-xl font-[900] text-white uppercase italic">Buvette<span class="text-blue-600">OS</span></span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1">
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mb-3">Principal</p>
                    <a href="index.php?module=client&action=espace" class="flex items-center gap-4 px-4 py-3.5 rounded-xl <?= ($actionActuelle == 'espace' ? $activeClass : $inactiveClass) ?>">
                        <i class="fa-solid fa-rocket text-lg"></i>
                        <span class="font-bold text-sm">Mon Espace</span>
                    </a>
                    <a href="index.php?module=client&action=form_produits_utilisateur" class="flex items-center gap-4 px-4 py-3.5 rounded-xl <?= ($actionActuelle == 'form_produits_utilisateur' ? $activeClass : $inactiveClass) ?>">
                        <i class="fa-solid fa-utensils text-lg"></i>
                        <span class="font-bold text-sm">Carte & Produits</span>
                    </a>
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mt-8 mb-3">Mes Commandes</p>
                    <a href="index.php?module=client&action=form_panier_utilisateur" class="flex items-center gap-4 px-4 py-3.5 rounded-xl <?= ($actionActuelle == 'form_panier_utilisateur' ? $activeClass : $inactiveClass) ?>">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        <span class="font-bold text-sm">Mon Panier</span>
                    </a>
                    <a href="index.php?module=client&action=form_rechargement_utilisateur" class="flex items-center gap-4 px-4 py-3.5 rounded-xl <?= ($actionActuelle == 'form_rechargement_utilisateur' ? $activeClass : $inactiveClass) ?>">
                        <i class="fa-solid fa-wallet text-lg text-emerald-500"></i>
                        <span class="font-bold text-sm text-emerald-500">Recharger</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-white/5 space-y-3">
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-blue-400 p-0.5 shadow-lg">
                            <div class="w-full h-full rounded-[10px] bg-[#020617] overflow-hidden flex items-center justify-center text-white">
                                <?= ($cheminPhoto && file_exists($cheminPhoto)) ? '<img src="'.$cheminPhoto.'" class="w-full h-full object-cover">' : strtoupper(substr($prenom, 0, 1)) ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white"><?= htmlspecialchars($prenom) ?></p>
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Client</p>
                        </div>
                    </div>
                    <a href="index.php?module=connexion&action=deconnexion" class="flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl text-red-500 bg-red-500/5 border border-red-500/10">
                        <i class="fa-solid fa-power-off text-sm"></i>
                        <span class="font-black text-[11px] uppercase">Déconnexion</span>
                    </a>
                </div>
            </aside>
            <main class="flex-1 p-8">
        <?php
    }
public function form_rechargement() {
        $this->afficherNav();
        ?>
        <div class="max-w-2xl mx-auto">
            <div class="bfor-card">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-wallet text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Recharger mon compte</h2>
                        <p class="text-slate-400 text-sm">Approvisionnez votre solde instantanément</p>
                    </div>
                </div>

                <form method="post" action="index.php?module=client&action=verif_rechargement" class="space-y-6">
                    <div>
                        <label class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Montant à recharger (€)</label>
                        <input type="number" name="montant" min="1" max="500" step="1"
                               class="w-full bg-[#0f172a]/60 border border-white/10 rounded-xl p-4 text-white focus:border-blue-500 outline-none transition-all"
                               placeholder="Ex: 20" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Numéro de carte</label>
                            <input type="text" name="codeCarte" placeholder="XXXX XXXX XXXX XXXX"
                                   class="w-full bg-[#0f172a]/60 border border-white/10 rounded-xl p-4 text-white focus:border-blue-500 outline-none transition-all" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Expiration</label>
                                <input type="text" name="exp" placeholder="MM/AA"
                                       class="w-full bg-[#0f172a]/60 border border-white/10 rounded-xl p-4 text-white focus:border-blue-500 outline-none transition-all" required>
                            </div>
                            <div>
                                <label class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-2">CVV</label>
                                <input type="text" name="cvv" placeholder="123"
                                       class="w-full bg-[#0f172a]/60 border border-white/10 rounded-xl p-4 text-white focus:border-blue-500 outline-none transition-all" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-3">
                        <i class="fa-solid fa-shield-check"></i>
                        Confirmer le rechargement
                    </button>
                </form>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-blue-600/5 border border-blue-600/10 flex items-start gap-4">
                <i class="fa-solid fa-circle-info text-blue-400 mt-1"></i>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Le rechargement est limité à 500€ par transaction. Votre solde sera mis à jour immédiatement après la validation du paiement sécurisé.
                </p>
            </div>
        </div>
        <?php
        $this->afficherFooter();
    }
    public function form_espace($solde, $historique)
{
    $this->afficherNav();
    ?>
    <div class="max-w-5xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight">Tableau de bord</h1>
                <p class="text-slate-400 mt-1">Heureux de vous revoir, <?= htmlspecialchars($_SESSION['prenom']) ?>.</p>
            </div>
            <a href="index.php?module=client&action=form_rechargement_utilisateur"
               class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-blue-600/20">
                <i class="fa-solid fa-plus-circle"></i>
                Recharger
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bfor-card !mb-0 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-wallet text-6xl text-white"></i>
                </div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Solde Actuel</p>
                <h2 class="text-4xl font-black text-white mt-2"><?= number_format($solde, 2) ?> <span class="text-blue-500 text-2xl">€</span></h2>
                <div class="mt-4 flex items-center gap-2 text-emerald-400 text-xs font-bold">
                    <i class="fa-solid fa-shield-check"></i>
                    Fonds sécurisés
                </div>
            </div>

            <div class="bfor-card !mb-0 border-white/5">
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Statut Compte</p>
                <div class="mt-3 flex items-center gap-3">
                    <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-white font-bold">Actif</span>
                </div>
                <p class="text-slate-500 text-xs mt-2">Prêt pour commande</p>
            </div>

            <div class="bfor-card !mb-0 border-white/5">
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Action Rapide</p>
                <a href="index.php?module=client&action=form_produits_utilisateur" class="mt-3 block text-blue-400 hover:text-white font-bold text-sm transition-colors">
                    <i class="fa-solid fa-utensils mr-2"></i> Consulter la carte →
                </a>
            </div>
        </div>

        <div class="bfor-card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fa-solid fa-clock-rotate-left text-blue-500"></i>
                    Derniers rechargements
                </h2>
                <span class="text-[10px] bg-white/5 text-slate-400 px-3 py-1 rounded-full border border-white/10 uppercase font-black">Historique</span>
            </div>

            <?php if (empty($historique)): ?>
                <div class="py-12 text-center">
                    <i class="fa-solid fa-receipt text-slate-700 text-4xl mb-4"></i>
                    <p class="text-slate-500 italic">Aucun rechargement effectué pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-white/5 text-slate-500 text-[10px] uppercase font-black tracking-widest">
                                <th class="pb-4 px-2">Référence</th>
                                <th class="pb-4">Date & Heure</th>
                                <th class="pb-4">Méthode</th>
                                <th class="pb-4 text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.02]">
                            <?php foreach ($historique as $index => $r): ?>
                                <tr class="group hover:bg-white/[0.01] transition-colors">
                                    <td class="py-4 px-2 text-slate-500 text-xs">#<?= str_pad(count($historique) - $index, 3, '0', STR_PAD_LEFT) ?></td>
                                    <td class="py-4">
                                        <div class="text-white font-bold text-sm">
                                            <?= date('d M Y', strtotime($r['date_rechargement'])) ?>
                                        </div>
                                        <div class="text-[10px] text-slate-500 italic">
                                            <?= date('H:i', strtotime($r['date_rechargement'])) ?>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-[10px] text-slate-300 bg-white/5 px-2 py-1 rounded border border-white/5 uppercase font-bold tracking-tighter">
                                            <i class="fa-solid fa-credit-card mr-1 text-blue-500"></i> Carte Bancaire
                                        </span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <span class="text-emerald-400 font-black text-sm">
                                            + <?= number_format($r['valeur'], 2) ?> €
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    $this->afficherFooter();
}

    public function form_liste_produits($produits)
    {
        $this->afficherNav();
        ?>
        <h2 class="text-2xl font-bold text-white mb-6">Produits disponibles</h2>
        <div class="bfor-card">
            <table>
                <thead><tr><th>Produit</th><th>Prix</th><th>Stock</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($produits as $p): ?>
                        <tr>
                            <td class="font-bold"><?= htmlspecialchars($p['nom']) ?></td>
                            <td><?= number_format($p['prix'], 2) ?> €</td>
                            <td><?= (int)$p['quantiteActuelle'] ?></td>
                            <td>
                                <form method="post" action="index.php?module=client&action=ajouter_panier">
                                    <input type="hidden" name="idProduit" value="<?= (int)$p['id'] ?>">
                                    <button type="submit">Prendre</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        $this->afficherFooter();
    }

    public function form_panier_utilisateur($panier_details, $total_general)
    {
        $this->afficherNav();
        ?>
        <h2 class="text-2xl font-bold text-white mb-6">Mon Panier</h2>
        <?php if (empty($panier_details)): ?>
            <p class="text-slate-500">Votre panier est vide.</p>
        <?php else: ?>
            <div class="bfor-card">
                <table>
                    <thead><tr><th>Produit</th><th>Prix</th><th>Qté</th><th>Total</th></tr></thead>
                    <tbody>
                        <?php foreach ($panier_details as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nom']) ?></td>
                                <td><?= number_format($item['prix'], 2) ?> €</td>
                                <td>x<?= (int)$item['qte'] ?></td>
                                <td class="font-bold text-blue-400"><?= number_format($item['sous_total'], 2) ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="bfor-card mt-8">
                <p class="text-slate-400">Total à payer</p>
                <h1><?= number_format($total_general, 2) ?> €</h1>
                <form method="post" action="index.php?module=client&action=valider_commande" class="mt-4">
                    <input type="submit" value="Confirmer la commande" style="width: 100%; background: #10b981;">
                </form>
            </div>
        <?php endif; ?>
        <?php
        $this->afficherFooter();
    }
}