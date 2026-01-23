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

                <a href="index.php?module=client&action=form_commande_statut_panier_utilisateur" class="flex items-center gap-4 px-4 py-3.5 rounded-xl <?= ($actionActuelle == 'form_commande_statut_panier_utilisateur' ? $activeClass : $inactiveClass) ?>">
                    <i class="fa-solid fa-clock text-lg"></i>
                    <span class="font-bold text-sm">Suivi Commandes</span>
                </a>

                <a href="index.php?module=client&action=form_historique_utilisateur" class="flex items-center gap-4 px-4 py-3.5 rounded-xl <?= ($actionActuelle == 'form_historique_utilisateur' ? $activeClass : $inactiveClass) ?>">
                    <i class="fa-solid fa-history text-lg"></i>
                    <span class="font-bold text-sm">Historique</span>
                </a>
                <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mt-8 mb-3">Sécurité</p>

                <a href="index.php?module=client&action=afficherCode" class="flex items-center gap-4 px-4 py-3.5 rounded-xl <?= ($actionActuelle == 'afficherCode' ? $activeClass : $inactiveClass) ?>">
                    <i class="fa-solid fa-key text-lg text-emerald-500"></i>
                    <span class="font-bold text-sm text-emerald-500">Code Validation</span>
                </a>
                <a href="index.php?module=client&action=form_rechargement_utilisateur" class="flex items-center gap-4 px-4 py-3.5 rounded-xl <?= ($actionActuelle == 'form_rechargement_utilisateur' ? $activeClass : $inactiveClass) ?>">
                    <i class="fa-solid fa-wallet text-lg text-emerald-500"></i>
                    <span class="font-bold text-sm text-emerald-500">Recharger</span>
                </a>
                <div class="pt-4 mt-4 border-t border-white/5">
                    <a href="index.php?reset=1" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 text-amber-500/70 hover:bg-amber-500/10 hover:text-amber-500 border-r-4 border-transparent">
                        <i class="fa-solid fa-right-left text-lg"></i>
                        <span class="font-bold text-sm tracking-tight">Changer Association</span>
                    </a>
                </div>

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
            <div class="bfor-card">
                <h1 style="margin-bottom: 20px; color: white;">
                    <i class="fa-solid fa-utensils" style="color: #3b82f6;"></i>
                    Carte & Produits
                </h1>

                <?php if (empty($produits)): ?>
                    <div style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.02); border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-box" style="font-size: 4rem; color: #475569; margin-bottom: 20px;"></i>
                        <p style="color: #94a3b8; font-size: 1.1rem; font-weight: 600;">Aucun produit disponible</p>
                    </div>
                <?php else: ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                        <?php foreach ($produits as $p): ?>
                            <div style="background: linear-gradient(145deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.6)); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 25px; transition: all 0.3s ease;" onmouseover="this.style.borderColor='rgba(37, 99, 235, 0.4)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.transform='translateY(0)';">

                                <!-- Icône produit -->
                                <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);">
                                    <i class="fa-solid fa-wine-glass" style="font-size: 2.5rem; color: white;"></i>
                                </div>

                                <!-- Nom produit -->
                                <h3 style="text-align: center; font-size: 1.3rem; font-weight: 900; color: white; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.05em;">
                                    <?= htmlspecialchars($p['nom']) ?>
                                </h3>

                                <!-- Prix -->
                                <p style="text-align: center; font-size: 2rem; font-weight: 900; color: #3b82f6; margin-bottom: 15px;">
                                    <?= number_format($p['prix'], 2) ?> €
                                </p>

                                <!-- Stock -->
                                <div style="text-align: center; margin-bottom: 20px;">
                                    <?php if ($p['quantiteActuelle'] > 10): ?>
                                        <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; border: 1px solid rgba(16, 185, 129, 0.3);">
                                    <i class="fa-solid fa-check"></i> En stock (<?= $p['quantiteActuelle'] ?>)
                                </span>
                                    <?php elseif ($p['quantiteActuelle'] > 0): ?>
                                        <span style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; border: 1px solid rgba(245, 158, 11, 0.3);">
                                    <i class="fa-solid fa-exclamation-triangle"></i> Stock limité (<?= $p['quantiteActuelle'] ?>)
                                </span>
                                    <?php else: ?>
                                        <span style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; border: 1px solid rgba(239, 68, 68, 0.3);">
                                    <i class="fa-solid fa-times"></i> Rupture de stock
                                </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Formulaire avec quantité -->
                                <?php if ($p['quantiteActuelle'] > 0): ?>
                                    <form method="POST" action="index.php?module=client&action=ajouter_panier">
                                        <input type="hidden" name="idProduit" value="<?= $p['id'] ?>">

                                        <!-- Sélecteur de quantité -->
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 15px;">
                                            <button type="button" onclick="decrementQuantity(this)" style="width: 40px; height: 40px; background: rgba(37, 99, 235, 0.1); color: #3b82f6; border: 1px solid rgba(37, 99, 235, 0.3); border-radius: 10px; cursor: pointer; font-weight: 900; font-size: 1.3rem;">
                                                −
                                            </button>

                                            <input type="number" name="quantite" value="1" min="1" max="<?= $p['quantiteActuelle'] ?>"
                                                   style="width: 70px; text-align: center; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; font-weight: 900; font-size: 1.3rem; padding: 8px;"
                                                   onchange="this.value = Math.max(1, Math.min(<?= $p['quantiteActuelle'] ?>, this.value))">

                                            <button type="button" onclick="incrementQuantity(this, <?= $p['quantiteActuelle'] ?>)" style="width: 40px; height: 40px; background: rgba(37, 99, 235, 0.1); color: #3b82f6; border: 1px solid rgba(37, 99, 235, 0.3); border-radius: 10px; cursor: pointer; font-weight: 900; font-size: 1.3rem;">
                                                +
                                            </button>
                                        </div>

                                        <!-- Bouton ajouter -->
                                        <button type="submit" style="width: 100%; background: #2563eb; color: white; padding: 12px 20px; border-radius: 12px; font-weight: 900; cursor: pointer; border: none; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.3s ease;" onmouseover="this.style.background='#1d4ed8'; this.style.transform='scale(1.02)';" onmouseout="this.style.background='#2563eb'; this.style.transform='scale(1)';">
                                            <i class="fa-solid fa-cart-plus"></i> Ajouter au panier
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button disabled style="width: 100%; background: rgba(100, 116, 139, 0.2); color: #64748b; padding: 12px 20px; border-radius: 12px; font-weight: 900; cursor: not-allowed; border: 1px solid rgba(100, 116, 139, 0.3); font-size: 0.95rem; text-transform: uppercase;">
                                        <i class="fa-solid fa-ban"></i> Indisponible
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <script>
                function incrementQuantity(button, max) {
                    const input = button.previousElementSibling;
                    if (parseInt(input.value) < max) {
                        input.value = parseInt(input.value) + 1;
                    }
                }

                function decrementQuantity(button) {
                    const input = button.nextElementSibling;
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                    }
                }
            </script>

        </main></div>
    <?php
}
    public function form_panier_utilisateur($panier, $total)
    {
        $this->afficherNav();
        ?>
        <div class="bfor-card">
            <h1 style="margin-bottom: 20px; color: white;">
                <i class="fa-solid fa-cart-shopping" style="color: #3b82f6;"></i>
                Mon Panier
            </h1>

            <?php if (empty($panier)): ?>
                <div style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.02); border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1);">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 4rem; color: #475569; margin-bottom: 20px;"></i>
                    <p style="color: #94a3b8; font-size: 1.1rem; font-weight: 600;">Votre panier est vide</p>
                    <p style="color: #64748b; font-size: 0.9rem; margin-top: 10px;">Ajoutez des produits pour commencer</p>
                    <a href="index.php?module=client&action=form_produits_utilisateur" style="display: inline-block; margin-top: 20px; background: #2563eb; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 700;">
                        <i class="fa-solid fa-box"></i> Voir les produits
                    </a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                    <tr>
                        <th>Produit</th>
                        <th style="text-align: center;">Prix unitaire</th>
                        <th style="text-align: center;">Quantité</th>
                        <th style="text-align: right;">Sous-total</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($panier as $item): ?>
                        <tr>
                            <td style="font-weight: 700; font-size: 1rem;">
                                <?= htmlspecialchars($item['nom'] ?? 'Produit') ?>
                                <?php if (($item['stock'] ?? 0) < ($item['quantite'] ?? 1)): ?>
                                    <span style="color: #ef4444; font-size: 0.75rem; display: block; margin-top: 4px;">
                        <i class="fa-solid fa-exclamation-triangle"></i> Stock insuffisant (<?= $item['stock'] ?? 0 ?> disponible)
                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center; color: #3b82f6; font-weight: 700;">
                                <?= number_format($item['prix'] ?? 0, 2) ?> €
                            </td>
                            <td style="text-align: center;">
                                <form method="POST" action="index.php?module=client&action=modifier_quantite_panier" style="display: inline-flex; align-items: center; gap: 10px;">
                                    <input type="hidden" name="idProduit" value="<?= $item['id'] ?? '' ?>">

                                    <!-- Bouton - -->
                                    <button type="submit" name="quantite" value="<?= max(1, ($item['quantite'] ?? 1) - 1) ?>"
                                            style="width: 32px; height: 32px; background: rgba(37, 99, 235, 0.1); color: #3b82f6; border: 1px solid rgba(37, 99, 235, 0.3); border-radius: 8px; cursor: pointer; font-weight: 900; font-size: 1.2rem;"
                                        <?= ($item['quantite'] ?? 1) <= 1 ? 'disabled style="opacity: 0.3; cursor: not-allowed;"' : '' ?>>
                                        −
                                    </button>

                                    <!-- Affichage quantité -->
                                    <span style="font-weight: 900; font-size: 1.3rem; min-width: 40px; text-align: center;">
                        <?= $item['quantite'] ?? 1 ?>
                    </span>

                                    <!-- Bouton + -->
                                    <button type="submit" name="quantite" value="<?= ($item['quantite'] ?? 1) + 1 ?>"
                                            style="width: 32px; height: 32px; background: rgba(37, 99, 235, 0.1); color: #3b82f6; border: 1px solid rgba(37, 99, 235, 0.3); border-radius: 8px; cursor: pointer; font-weight: 900; font-size: 1.2rem;"
                                        <?= ($item['quantite'] ?? 1) >= ($item['stock'] ?? 0) ? 'disabled style="opacity: 0.3; cursor: not-allowed;"' : '' ?>>
                                        +
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: right; font-weight: 900; font-size: 1.2rem; color: #3b82f6;">
                                <?= number_format($item['sous_total'] ?? 0, 2) ?> €
                            </td>
                            <td style="text-align: center;">
                                <form method="POST" action="index.php?module=client&action=enlever_panier" style="display: inline;">
                                    <input type="hidden" name="idProduit" value="<?= $item['id'] ?? '' ?>">
                                    <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 0.85rem;" onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='rgba(239, 68, 68, 0.1)'; this.style.color='#ef4444';">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr style="background: rgba(37, 99, 235, 0.05); border-top: 2px solid rgba(37, 99, 235, 0.3);">
                        <td colspan="3" style="text-align: right; padding: 20px; font-weight: 900; text-transform: uppercase; font-size: 1rem; letter-spacing: 0.05em;">
                            <i class="fa-solid fa-calculator"></i> Total :
                        </td>
                        <td colspan="2" style="text-align: right; padding: 20px;">
                            <span style="font-size: 2rem; font-weight: 900; color: #3b82f6;">
                                <?= number_format($total ?? 0, 2) ?> €
                            </span>
                        </td>
                    </tr>
                    </tfoot>
                </table>

                <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: flex-end;">
                    <a href="index.php?module=client&action=form_produits_utilisateur" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-arrow-left"></i>
                        Continuer mes achats
                    </a>
                    <form method="POST" action="index.php?module=client&action=valider_commande" style="display: inline;">
                        <button type="submit" style="background: #10b981; color: white; padding: 12px 32px; border-radius: 10px; font-weight: 900; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px; font-size: 1rem;">
                            <i class="fa-solid fa-check"></i>
                            Valider la commande
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        </main></div>
        <?php
    }
    public function form_historique($commandes)
    {
        $this->afficherNav();
        ?>
        <div class="bfor-card">
            <h1 style="margin-bottom: 20px; color: white;">
                <i class="fa-solid fa-history" style="color: #3b82f6;"></i>
                Historique de mes commandes
            </h1>

            <?php if (empty($commandes)): ?>
                <div style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.02); border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1);">
                    <i class="fa-solid fa-archive" style="font-size: 4rem; color: #475569; margin-bottom: 20px;"></i>
                    <p style="color: #94a3b8; font-size: 1.1rem; font-weight: 600;">Aucune commande dans l'historique</p>
                    <p style="color: #64748b; font-size: 0.9rem; margin-top: 10px;">Vos commandes livrées apparaîtront ici</p>
                </div>
            <?php else: ?>
                <?php foreach ($commandes as $cmd): ?>
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 20px; margin-bottom: 15px;">

                        <!-- En-tête de la commande -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <div>
                                <p style="color: #3b82f6; font-weight: 900; font-family: monospace; font-size: 1.1rem; margin-bottom: 5px;">
                                    Commande #<?= $cmd['vente_id'] ?? 'N/A' ?>
                                </p>
                                <p style="color: #64748b; font-size: 0.85rem;">
                                    <i class="fa-solid fa-calendar"></i>
                                    <?= date('d/m/Y à H:i', strtotime($cmd['date'] ?? 'now')) ?>
                                </p>
                            </div>
                            <div>
                            <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 8px 16px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; border: 1px solid rgba(16, 185, 129, 0.3);">
                                <i class="fa-solid fa-check-circle"></i> Livrée
                            </span>
                            </div>
                        </div>

                        <!-- Liste des produits -->
                        <?php if (!empty($cmd['produits'])): ?>
                            <div style="margin-bottom: 15px;">
                                <h4 style="color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 900; letter-spacing: 0.1em; margin-bottom: 10px;">
                                    <i class="fa-solid fa-box-open"></i> Produits
                                </h4>
                                <?php foreach ($cmd['produits'] as $p): ?>
                                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                                    <span style="color: #94a3b8;">
                                        <?= htmlspecialchars($p['nom'] ?? 'Produit') ?> × <?= $p['quantite'] ?? 1 ?>
                                    </span>
                                        <span style="color: white; font-weight: 700;">
                                        <?= number_format(($p['prix'] ?? 0) * ($p['quantite'] ?? 1), 2) ?> €
                                    </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Total -->
                        <div style="text-align: right; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1);">
                        <span style="color: #94a3b8; font-size: 0.9rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">
                            Total :
                        </span>
                            <span style="font-size: 1.5rem; font-weight: 900; color: #3b82f6; margin-left: 15px;">
                            <?= number_format($cmd['montant'] ?? 0, 2) ?> €
                        </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        </main></div>
        <?php
    }
    public function form_commande_statut_panier($commandes)
    {
        $this->afficherNav();
        ?>
        <div class="bfor-card">
            <h1 style="margin-bottom: 20px; color: white;">
                <i class="fa-solid fa-clock" style="color: #3b82f6;"></i>
                Mes Commandes en Cours
            </h1>

            <?php if (empty($commandes)): ?>
                <div style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.02); border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1);">
                    <i class="fa-solid fa-check-circle" style="font-size: 4rem; color: #10b981; margin-bottom: 20px;"></i>
                    <p style="color: #94a3b8; font-size: 1.1rem; font-weight: 600;">Aucune commande en cours</p>
                    <p style="color: #64748b; font-size: 0.9rem; margin-top: 10px;">Vos commandes en cours apparaîtront ici</p>
                    <a href="index.php?module=client&action=form_produits_utilisateur" style="display: inline-block; margin-top: 20px; background: #2563eb; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 700;">
                        <i class="fa-solid fa-box"></i> Commander maintenant
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($commandes as $cmd):
                    $statutConfig = [
                        'en_attente' => ['label' => 'En attente', 'class' => 'background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);', 'icon' => 'clock'],
                        'validee' => ['label' => 'Validée', 'class' => 'background: rgba(37, 99, 235, 0.1); color: #3b82f6; border: 1px solid rgba(37, 99, 235, 0.3);', 'icon' => 'check'],
                        'en_preparation' => ['label' => 'En préparation', 'class' => 'background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);', 'icon' => 'spinner'],
                        'prete' => ['label' => 'Prête', 'class' => 'background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);', 'icon' => 'check-double'],
                    ];
                    $config = $statutConfig[$cmd['statut'] ?? 'en_attente'] ?? $statutConfig['en_attente'];
                    ?>
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 20px; margin-bottom: 15px;">

                        <!-- En-tête de la commande -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <div>
                                <p style="color: #3b82f6; font-weight: 900; font-family: monospace; font-size: 1.1rem; margin-bottom: 5px;">
                                    Commande #<?= $cmd['vente_id'] ?? 'N/A' ?>
                                </p>
                                <p style="color: #64748b; font-size: 0.85rem;">
                                    <i class="fa-solid fa-calendar"></i>
                                    <?= date('d/m/Y à H:i', strtotime($cmd['dateVente'] ?? $cmd['date'] ?? 'now')) ?>
                                </p>
                            </div>
                            <div>
                            <span style="padding: 8px 16px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; <?= $config['class'] ?>">
                                <i class="fa-solid fa-<?= $config['icon'] ?>"></i> <?= $config['label'] ?>
                            </span>
                            </div>
                        </div>

                        <!-- Liste des produits -->
                        <?php if (!empty($cmd['produits'])): ?>
                            <div style="margin-bottom: 15px;">
                                <h4 style="color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 900; letter-spacing: 0.1em; margin-bottom: 10px;">
                                    <i class="fa-solid fa-box-open"></i> Produits
                                </h4>
                                <?php foreach ($cmd['produits'] as $p): ?>
                                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                                    <span style="color: #94a3b8;">
                                        <?= htmlspecialchars($p['nom'] ?? 'Produit') ?> × <?= $p['quantite'] ?? 1 ?>
                                    </span>
                                        <span style="color: white; font-weight: 700;">
                                        <?= number_format(($p['prix'] ?? 0) * ($p['quantite'] ?? 1), 2) ?> €
                                    </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Total -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1);">
                        <span style="color: #94a3b8; font-size: 0.9rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">
                            Total :
                        </span>
                            <span style="font-size: 1.5rem; font-weight: 900; color: #3b82f6;">
                            <?= number_format($cmd['montant'] ?? 0, 2) ?> €
                        </span>
                        </div>

                        <!-- Bouton annuler (uniquement si en attente) -->
                        <?php if (($cmd['statut'] ?? 'en_attente') === 'en_attente'): ?>
                            <form method="POST" action="index.php?module=client&action=enlever_commande" style="margin-top: 15px;" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                <input type="hidden" name="vente_id" value="<?= $cmd['vente_id'] ?? '' ?>">
                                <button type="submit" style="width: 100%; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 12px 24px; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 0.9rem;" onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='rgba(239, 68, 68, 0.1)'; this.style.color='#ef4444';">
                                    <i class="fa-solid fa-times"></i> Annuler la commande
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Légende des statuts -->
                <div style="background: rgba(37, 99, 235, 0.05); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 15px; padding: 20px; margin-top: 20px;">
                    <h4 style="color: #3b82f6; font-weight: 900; margin-bottom: 15px; font-size: 0.9rem;">
                        <i class="fa-solid fa-info-circle"></i> Légende des statuts
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="padding: 6px 12px; border-radius: 8px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 0.75rem; font-weight: 700;">
                            🟠 En attente
                        </span>
                            <span style="color: #94a3b8; font-size: 0.75rem;">Validation barman</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="padding: 6px 12px; border-radius: 8px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 0.75rem; font-weight: 700;">
                            🟡 Préparation
                        </span>
                            <span style="color: #94a3b8; font-size: 0.75rem;">En cours</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="padding: 6px 12px; border-radius: 8px; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 0.75rem; font-weight: 700;">
                            🟢 Prête
                        </span>
                            <span style="color: #94a3b8; font-size: 0.75rem;">À récupérer</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        </main></div>
        <?php
    }
    public function afficherCodeGenere($codeData)
    {
        $this->afficherNav();

        if (!$codeData) {
            ?>
            <div class="bfor-card" style="text-align: center;">
                <h1 style="margin-bottom: 20px;">
                    <i class="fa-solid fa-key" style="color: #3b82f6;"></i>
                    Code de Validation
                </h1>
                <p style="color: #94a3b8; margin-bottom: 30px; font-size: 1.1rem;">Vous n'avez pas de code actif</p>
                <a href="index.php?module=client&action=genererCode" style="display: inline-block; background: #2563eb; color: white; padding: 15px 40px; border-radius: 12px; text-decoration: none; font-weight: 900; text-transform: uppercase; font-size: 1rem;">
                    <i class="fa-solid fa-rotate"></i> Générer un code
                </a>
            </div>
            </main></div>
            <?php
        } else {
            $code = $codeData['code'];
            $expiration = $codeData['expiration'];
            ?>
            <div class="bfor-card" style="text-align: center;">
                <h1 style="margin-bottom: 20px;">
                    <i class="fa-solid fa-key" style="color: #3b82f6;"></i>
                    Code de Validation
                </h1>

                <!-- Grand affichage du code -->
                <div style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 30px; padding: 50px; margin: 30px auto; max-width: 500px; box-shadow: 0 20px 60px rgba(37, 99, 235, 0.4);">
                    <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 15px;">
                        Votre Code
                    </p>
                    <p style="font-size: 5rem; font-weight: 900; color: white; letter-spacing: 0.3em; margin: 20px 0; font-family: 'Courier New', monospace; text-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                        <?= $code ?>
                    </p>
                    <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; font-weight: 700; margin-top: 20px;">
                        Communiquez ce code au barman
                    </p>
                </div>

                <!-- Compte à rebours -->
                <div style="background: rgba(245, 158, 11, 0.1); border: 2px solid rgba(245, 158, 11, 0.3); border-radius: 15px; padding: 25px; margin: 30px auto; max-width: 500px;">
                    <p style="color: #f59e0b; font-weight: 900; font-size: 1.3rem; margin-bottom: 10px;">
                        <i class="fa-solid fa-clock"></i> Expire dans : <span id="countdown" style="font-family: 'Courier New', monospace;">60</span>s
                    </p>
                    <p style="color: #94a3b8; font-size: 0.9rem;">
                        Valide jusqu'à : <?= date('H:i:s', strtotime($expiration)) ?>
                    </p>
                </div>

                <!-- Boutons d'action -->
                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px; flex-wrap: wrap;">
                    <a href="index.php?module=client&action=genererCode" style="display: inline-flex; align-items: center; gap: 10px; background: #2563eb; color: white; padding: 15px 30px; border-radius: 12px; text-decoration: none; font-weight: 900; text-transform: uppercase; font-size: 0.9rem;">
                        <i class="fa-solid fa-rotate"></i> Nouveau Code
                    </a>
                    <a href="index.php?module=client&action=espace" style="display: inline-flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; padding: 15px 30px; border-radius: 12px; text-decoration: none; font-weight: 900; text-transform: uppercase; font-size: 0.9rem;">
                        <i class="fa-solid fa-home"></i> Accueil
                    </a>
                </div>

                <!-- Script compte à rebours -->
                <script>
                    const expirationTime = new Date('<?= $expiration ?>').getTime();
                    const countdownElement = document.getElementById('countdown');

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const timeLeft = Math.floor((expirationTime - now) / 1000);

                        if (timeLeft <= 0) {
                            countdownElement.textContent = '0';
                            countdownElement.parentElement.style.color = '#ef4444';
                            setTimeout(function() {
                                alert('⚠️ Code expiré ! Génération d\'un nouveau code...');
                                window.location.href = 'index.php?module=client&action=genererCode';
                            }, 100);
                        } else {
                            countdownElement.textContent = timeLeft;

                            // Changer la couleur selon le temps restant
                            if (timeLeft <= 10) {
                                countdownElement.parentElement.style.color = '#ef4444';
                            } else if (timeLeft <= 30) {
                                countdownElement.parentElement.style.color = '#f59e0b';
                            }
                        }
                    }

                    // Mettre à jour toutes les secondes
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                </script>
            </div>
            </main></div>
            <?php
        }
    }
}