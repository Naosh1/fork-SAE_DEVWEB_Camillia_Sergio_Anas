<?php
include_once 'vue_generique.php';
include "modules/module_commun/vue_commun.php";
include "modules/module_staff/vue_staff.php";

class VueGestionnaire extends VueStaff
{
    private $nbMessages = 0;

    public function afficherDetailsFournisseur($fournisseur, $produits)
    {
        $this->afficherNav();
        $id_asso_selectionnee = $_GET['id_asso'] ?? $_SESSION['id_asso_courante'] ?? null;
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            html, body {
                height: auto;
                overflow-y: visible;
                background-color: #020617;
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: white;
            }

            .header-section {
                background: rgba(2, 6, 23, 0.98);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(139, 92, 246, 0.2);
                padding: 3rem 0;
            }

            .content-limit {
                max-width: 1000px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .products-container {
                padding: 40px 0 150px 0;
            }

            .product-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(139, 92, 246, 0.1);
                border-radius: 2rem;
                padding: 1.5rem 2rem;
                transition: all 0.3s ease;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .product-card:hover {
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(139, 92, 246, 0.4);
                transform: scale(1.01);
            }

            .qty-btn {
                width: 45px;
                height: 45px;
                background: rgba(139, 92, 246, 0.1);
                border: 1px solid rgba(139, 92, 246, 0.2);
                border-radius: 12px;
                color: #a78bfa;
                cursor: pointer;
                font-size: 1.2rem;
                transition: all 0.2s;
            }

            .qty-btn:hover {
                background: #8b5cf6;
                color: white;
            }

            .qty-input {
                background: transparent;
                border: none;
                width: 50px;
                text-align: center;
                color: white;
                font-weight: 900;
                font-size: 1.3rem;
                outline: none;
            }

            .cart-summary {
                position: fixed;
                bottom: 30px;
                left: 50%;
                transform: translateX(-50%);
                width: 90%;
                max-width: 600px;
                background: rgba(139, 92, 246, 0.95);
                backdrop-filter: blur(15px);
                border-radius: 2rem;
                padding: 1.2rem 2.5rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
                z-index: 1000;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .confirm-btn {
                background: white;
                color: #7c3aed;
                padding: 1rem 2rem;
                border-radius: 1.2rem;
                font-weight: 900;
                text-transform: uppercase;
                border: none;
                cursor: pointer;
            }
        </style>

        <div class="header-section">
            <div class="content-limit flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-truck-ramp-box text-3xl text-white"></i>
                    </div>
                    <div>
                        <span class="text-amber-500 font-black uppercase text-[10px] tracking-widest">Commande Fournisseur</span>
                        <h1 class="text-5xl font-black uppercase italic tracking-tighter leading-none">
                            <?= htmlspecialchars($fournisseur['nom']) ?>
                        </h1>
                    </div>
                </div>
                <a href="index.php?module=gestionnaire&action=voirFournisseurs"
                   class="opacity-50 hover:opacity-100 transition-opacity">
                    <i class="fa-solid fa-circle-xmark text-3xl"></i>
                </a>
            </div>
        </div>

        <div class="products-container">
            <form id="orderForm" action="index.php?module=gestionnaire&action=validerCommandeFournisseur" method="POST">
                <input type="hidden" name="id_fournisseur" value="<?= htmlspecialchars($fournisseur['id']) ?>">
                <input type="hidden" name="id_association" value="<?= htmlspecialchars($id_asso_selectionnee) ?>">

                <div class="content-limit">
                    <?php if (empty($produits)): ?>
                        <div class="py-20 text-center opacity-30">
                            <i class="fa-solid fa-box-open text-6xl mb-4"></i>
                            <p class="text-xl font-bold uppercase">Aucun produit</p>
                        </div>
                    <?php else: ?>
                        <div class="grid gap-3">
                            <?php foreach ($produits as $p):
                                $id_p = $p['id'] ?? $p['id_produit'];
                                ?>
                                <div class="product-card">
                                    <div>
                                        <h3 class="text-xl font-black uppercase italic leading-tight"><?= htmlspecialchars($p['nom']) ?></h3>
                                        <p class="text-violet-400 font-black">
                                            <span class="price-val"><?= number_format($p['prix_achat'], 2) ?></span>€
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <button type="button" class="qty-btn" onclick="updateQty(this, -1)">-</button>
                                        <input type="number"
                                               name="produits[<?= (int)$id_p ?>][quantite]"
                                               class="qty-input"
                                               value="0" min="0" readonly>
                                        <button type="button" class="qty-btn" onclick="updateQty(this, 1)">+</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="cart-summary" id="cartSummary">
            <div class="flex gap-10">
                <div>
                    <p class="text-[9px] font-black uppercase text-white/60">Articles</p>
                    <p class="text-2xl font-black" id="totalItems">0</p>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase text-white/60">Total HT</p>
                    <p class="text-2xl font-black"><span id="totalPrice">0.00</span>€</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('orderForm').submit()" class="confirm-btn">
                Commander <i class="fa-solid fa-chevron-right ml-2"></i>
            </button>
        </div>

        <script>
            function updateQty(btn, delta) {
                const input = delta > 0 ? btn.previousElementSibling : btn.nextElementSibling;
                let val = parseInt(input.value) + delta;
                if (val < 0) val = 0;
                input.value = val;
                calculateTotals();
            }

            function calculateTotals() {
                let totalItems = 0;
                let totalPrice = 0;

                document.querySelectorAll('.product-card').forEach(card => {
                    const qty = parseInt(card.querySelector('.qty-input').value);
                    const price = parseFloat(card.querySelector('.price-val').innerText.replace(',', '.'));
                    if (qty > 0) {
                        totalItems += qty;
                        totalPrice += (qty * price);
                    }
                });

                document.getElementById('totalItems').innerText = totalItems;
                document.getElementById('totalPrice').innerText = totalPrice.toFixed(2);
                document.getElementById('cartSummary').style.opacity = totalItems > 0 ? "1" : "0.7";
            }

            window.addEventListener('DOMContentLoaded', calculateTotals);
        </script>
        <?php
    }

    public function afficherSuccesCommande()
    {
        $this->afficherNav();
        ?>
        <div style="height: 80vh; display: flex; flex-direction: column; justify-content: center; align-items: center; background: #020617; color: white; font-family: 'Montserrat', sans-serif;">

            <div style="background: rgba(16, 185, 129, 0.1); border: 2px solid #10b981; width: 100px; height: 100px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-bottom: 2rem;">
                <i class="fa-solid fa-check" style="font-size: 3rem; color: #10b981;"></i>
            </div>

            <h1 style="font-size: 2.5rem; font-weight: 900; text-transform: uppercase; font-style: italic; margin-bottom: 1rem;">
                Commande bien passée !
            </h1>

            <p style="color: #94a3b8; font-size: 1.1rem; margin-bottom: 2rem;">
                Le solde a été débité. Vous allez être redirigé vers l'historique...
            </p>

            <div style="width: 200px; height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; overflow: hidden;">
                <div id="loader"
                     style="width: 0%; height: 100%; background: #f59e0b; transition: width 3s linear;"></div>
            </div>

            <a href="index.php?module=gestionnaire&action=mesCommandes"
               style="margin-top: 2rem; color: #f59e0b; text-decoration: none; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; border-bottom: 1px solid #f59e0b;">
                Cliquer ici si la redirection ne fonctionne pas
            </a>
        </div>

        <script>
            setTimeout(() => {
                document.getElementById('loader').style.width = '100%';
            }, 100);
            setTimeout(() => {
                window.location.href = "index.php?module=gestionnaire&action=mesCommandes";
            }, 3000);
        </script>
        <?php
    }

    public function afficherMesCommandes($commandes)
    {
        $this->afficherNav();
        ?>
        <style>
            .status-badge {
                padding: 0.4rem 0.8rem;
                border-radius: 8px;

                font-[

                900 ] uppercase text- [10px] tracking-widest;
            }

            .status-pending {
                background: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
                border: 1px solid #f59e0b;
            }

            .order-row {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.05);
                transition: 0.3s;
            }

            .order-row:hover {
                background: rgba(255, 255, 255, 0.07);
                border-color: #f59e0b;
            }
        </style>

        <div class="max-w-[1000px] mx-auto p-8">
            <div class="mb-12">
                <h1 class="text-4xl font-black italic uppercase tracking-tighter">Historique Commandes</h1>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-[0.3em] mt-2">Suivi des
                    approvisionnements</p>
            </div>

            <div class="space-y-4">
                <?php if (empty($commandes)): ?>
                    <div class="text-center py-20 opacity-30">
                        <i class="fa-solid fa-receipt text-6xl mb-4"></i>
                        <p class="font-black uppercase tracking-widest">Aucune commande enregistrée</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($commandes as $c): ?>
                        <div class="order-row p-6 rounded-3xl flex justify-between items-center">
                            <div class="flex gap-8 items-center">
                                <div class="text-center">
                                    <p class="text-[10px] font-black text-slate-500 uppercase">Date</p>
                                    <p class="font-bold italic"><?= date('d/m/Y', strtotime($c['date_commande'])) ?></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-600 uppercase">Fournisseur</p>
                                    <p class="font-black text-lg uppercase italic"><?= htmlspecialchars($c['nom_fournisseur']) ?></p>
                                </div>
                            </div>

                            <div class="text-center">
                                <p class="text-[10px] font-black text-slate-600 uppercase mb-1">Statut</p>
                                <span class="status-badge status-pending">En cours</span>
                            </div>

                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-600 uppercase">Total HT</p>
                                <p class="text-2xl font-black text-amber-500"><?= number_format($c['montant_total'], 2) ?>
                                    €</p>
                            </div>

                            <a href="index.php?action=detailCommande&id=<?= $c['id'] ?>"
                               class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center hover:bg-white hover:text-black transition-all">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public function afficherProfilBarman($barmans)
    {
        $this->afficherNav();
        if (!$barmans) {
            echo '<div class="text-white p-10">Aucune donnée trouvée pour ce profil ou cette association.</div>';
            return;
        }

        $prenom = htmlspecialchars($barmans['prenom']);
        $nom = htmlspecialchars($barmans['nom']);
        $email_dest = htmlspecialchars($barmans['email']);
        $id_barman = $barmans['id'];

        $photoBarman = !empty($barmans['photo']) ? $barmans['photo'] : null;
        $cheminPhoto = !empty($photoBarman) ? "uploads/profiles/" . $photoBarman : null;
        ?>

        <div class="w-full h-full px-6 md:px-12 py-10">

            <div class="mb-8 flex justify-between items-center">
                <a href="index.php?action=barmans"
                   class="text-slate-500 hover:text-white transition-colors text-[10px] font-black uppercase tracking-widest">
                    <i class="fa-solid fa-chevron-left"></i> Retour
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white/5 border border-white/5 rounded-[3rem] p-8 text-center shadow-2xl">
                        <div class="w-32 h-32 mx-auto rounded-[2rem] bg-gradient-to-tr from-blue-600 to-blue-400 p-1 mb-6">
                            <div class="w-full h-full rounded-[1.8rem] bg-[#020617] flex items-center justify-center overflow-hidden">
                                <?php if ($cheminPhoto && file_exists($cheminPhoto)): ?>
                                    <img src="<?= $cheminPhoto ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-4xl font-black text-white"><?= strtoupper(substr($prenom, 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h2 class="text-2xl font-black text-white uppercase italic italic"><?= $prenom ?> <span
                                    class="text-blue-500"><?= $nom ?></span></h2>
                        <p class="text-slate-500 font-bold text-[9px] tracking-widest uppercase mt-1">Matricule
                            #<?= $id_barman ?></p>
                    </div>

                    <div class="bg-white/5 border border-white/5 rounded-[2.5rem] p-6">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-4">Coordonnées</p>
                        <div class="space-y-3 text-xs text-slate-300 italic">
                            <p><i class="fa-solid fa-envelope text-blue-500 mr-2"></i> <?= $email_dest ?></p>
                            <p>
                                <i class="fa-solid fa-phone text-blue-500 mr-2"></i> <?= htmlspecialchars($barmans['tel'] ?? 'N/A') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <div class="bg-white/5 border border-white/5 rounded-[3rem] p-10 shadow-2xl">
                        <h3 class="text-lg font-black text-white uppercase italic tracking-tighter mb-8 border-b border-white/5 pb-4">
                            Envoyer un <span class="text-blue-500">Message Direct</span>
                        </h3>

                        <form action="index.php?action=envoyerEmailBarman" method="POST" class="space-y-6">
                            <input type="hidden" name="email_destinataire" value="<?= $email_dest ?>">
                            <input type="hidden" name="id_barman" value="<?= $id_barman ?>">

                            <div>
                                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-4 mb-2 block">Objet
                                    du message</label>
                                <input type="text" name="objet" required
                                       placeholder="Ex: Planning de la semaine prochaine"
                                       class="w-full bg-[#020617] border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-blue-600/50 outline-none transition-all">
                            </div>

                            <div>
                                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-4 mb-2 block">Votre
                                    message</label>
                                <textarea name="message" required rows="6" placeholder="Écrivez votre message ici..."
                                          class="w-full bg-[#020617] border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-blue-600/50 outline-none transition-all resize-none"></textarea>
                            </div>

                            <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black uppercase text-xs tracking-[0.2em] py-5 rounded-2xl transition-all shadow-xl shadow-blue-900/40">
                                <i class="fa-solid fa-paper-plane mr-2"></i> Envoyer maintenant
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <?php
    }

    public function afficherFormulaireCommande($fournisseur, $produits, $id_asso_choisie)
    {
        ?>
        <div class="container">
            <h2>Passer une commande chez <?= htmlspecialchars($fournisseur['nom']) ?></h2>

            <form action="index.php?module=gestionnaire&action=validerCommandeFournisseur" method="POST">

                <input type="hidden" name="id_fournisseur" value="<?= $fournisseur['id'] ?>">
                <input type="hidden" name="id_association" value="<?= $id_asso_choisie ?>">

                <table class="table">
                    <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité à commander</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($produits as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nom']) ?></td>
                            <td><?= number_format($p['prix_achat'], 2) ?> €</td>
                            <td>
                                <input type="number"
                                       name="produits[<?= $p['id_produit'] ?>][quantite]"
                                       value="0"
                                       min="0"
                                       class="form-control">

                                <input type="hidden"
                                       name="produits[<?= $p['id_produit'] ?>][prix]"
                                       value="<?= $p['prix_achat'] ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Confirmer et Débiter le solde</button>
                    <a href="index.php?action=fournisseurs" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        <?php
    }

    public function afficherFormulaireSolde($personne)
    {
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">';
        echo '<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">';

        $this->afficherNav();

        $idAsso = $personne['association_id'] ?? ($_GET['id_asso'] ?? '');
        $solde = (float)($personne['solde'] ?? 0);
        $isAsso = isset($personne['nom_association']) || (isset($personne['type']) && $personne['type'] === 'association');
        $nom = $isAsso ? ($personne['nom'] ?? $personne['nom_association']) : ($personne['prenom'] . ' ' . $personne['nom']);

        ?>
        <style>
            .font-cyber {
                font-family: 'Montserrat', sans-serif;
            }

            .wrapper-centrage {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 85vh;
                width: 100%;
                background: radial-gradient(circle at center, rgba(16, 185, 129, 0.02) 0%, transparent 70%);
            }

            .glass-premium {
                background: rgba(15, 23, 42, 0.9);
                backdrop-filter: blur(30px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 3rem;
                width: 100%;
                max-width: 480px;
                position: relative;
                box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
                overflow: hidden;
            }

            /* Le texte passe en blanc pur */
            .glow-text {
                color: #ffffff;
                text-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
                animation: pulse-glow-white 3s infinite alternate;
            }

            @keyframes pulse-glow-white {
                from {
                    text-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
                }
                to {
                    text-shadow: 0 0 25px rgba(255, 255, 255, 0.4);
                }
            }

            .op-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                border: 1px solid rgba(255, 255, 255, 0.05);
                background: rgba(255, 255, 255, 0.02);
            }

            .op-card.selected[data-op="ajouter"] {
                border-color: #10b981;
                background: rgba(16, 185, 129, 0.1);
            }

            .op-card.selected[data-op="retirer"] {
                border-color: #ef4444;
                background: rgba(239, 68, 68, 0.1);
            }

            .m-input {
                background: rgba(0, 0, 0, 0.4);
                border: 2px solid rgba(255, 255, 255, 0.05);
                transition: all 0.4s ease;
            }

            .m-input:focus {
                border-color: #10b981;
                box-shadow: 0 0 30px rgba(16, 185, 129, 0.1);
            }

            /* Bouton dynamique : Vert par défaut (Recharge) */
            .btn-cyber {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                transition: all 0.3s ease;
                color: #020617;
            }

            .btn-cyber:hover {
                transform: translateY(-2px);
                filter: brightness(1.1);
            }

            input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        </style>

        <div class="bg-[#020617] font-cyber min-h-screen">
            <div class="wrapper-centrage">
                <div class="w-full px-4 py-10">
                    <div class="max-w-[480px] mx-auto">

                        <div class="flex justify-between items-center mb-6 px-4">
                            <a href="index.php?action=<?= $isAsso ? 'gererAssociation&id=' : 'voirListeClients&id=' ?><?= $idAsso ?>"
                               class="flex items-center gap-2 text-slate-500 hover:text-emerald-500 transition-all text-[11px] font-black no-underline tracking-widest group">
                                <i class="fas fa-circle-chevron-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                                <span>RETOUR</span>
                            </a>
                        </div>

                        <div class="glass-premium p-10 mx-auto">
                            <div class="text-center mb-10">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-4">
                                    <i class="fa-solid fa-address-book text-emerald-500 text-[10px]"></i>
                                    <span class="text-white text-[9px] font-black uppercase tracking-widest opacity-60">
                                    Client
                                </span>
                                </div>
                                <h2 class="text-white text-base font-black uppercase italic tracking-tighter mb-2">
                                    <?= htmlspecialchars($nom) ?>
                                </h2>
                                <div class="text-6xl font-900 text-amber-500 italic tracking-tighter glow-text">
                                    <?= number_format($solde, 2, ',', ' ') ?><span
                                            class="text-2xl not-italic ml-1 opacity-40">€</span>
                                </div>
                            </div>

                            <form method="POST" action="index.php?action=gererSolde" id="soldeForm" class="space-y-8">
                                <input type="hidden" name="id_association" value="<?= $idAsso ?>">
                                <input type="hidden" name="id_personne" value="<?= $personne['id'] ?? '' ?>">

                                <div class="flex gap-4">
                                    <div class="op-card flex-1 p-5 rounded-3xl text-center selected" data-op="ajouter">
                                        <div class="w-10 h-10 mx-auto bg-emerald-500/10 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-plus text-emerald-500"></i>
                                        </div>
                                        <div class="text-[10px] font-black uppercase text-white tracking-widest">
                                            Recharger
                                        </div>
                                        <input type="radio" name="operation" value="ajouter" class="hidden" checked>
                                    </div>
                                    <div class="op-card flex-1 p-5 rounded-3xl text-center" data-op="retirer">
                                        <div class="w-10 h-10 mx-auto bg-red-500/10 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-minus text-red-500"></i>
                                        </div>
                                        <div class="text-[10px] font-black uppercase text-white tracking-widest">
                                            Encaisser
                                        </div>
                                        <input type="radio" name="operation" value="retirer" class="hidden">
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="relative group">
                                        <input type="number" name="montant" id="mInput" step="0.01" min="0" required
                                               placeholder="0.00"
                                               class="m-input w-full rounded-3xl p-8 text-5xl font-900 text-white text-center outline-none">
                                    </div>

                                    <div class="grid grid-cols-5 gap-2">
                                        <?php foreach ([1, 2, 5, 10, 20] as $v): ?>
                                            <button type="button" onclick="addVal(<?= $v ?>)"
                                                    class="bg-white/5 border border-white/5 py-3 rounded-xl text-[11px] font-black text-emerald-500 hover:bg-emerald-500 hover:text-black hover:scale-105 transition-all">
                                                +<?= $v ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="btn-cyber w-full font-900 uppercase italic py-6 rounded-3xl shadow-2xl active:scale-95 flex items-center justify-center gap-3">
                                    <span class="tracking-[0.3em] text-sm">Valider la transaction</span>
                                    <i class="fas fa-bolt-lightning animate-bounce"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('.op-card').forEach(card => {
                card.addEventListener('click', function () {
                    document.querySelectorAll('.op-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input').checked = true;

                    const btn = document.querySelector('.btn-cyber');
                    const mInput = document.getElementById('mInput');

                    if (this.dataset.op === 'retirer') {
                        btn.style.background = 'linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)';
                        mInput.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                    } else {
                        btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                        mInput.style.borderColor = '#10b981';
                    }
                });
            });

            function addVal(v) {
                const input = document.getElementById('mInput');
                input.value = (parseFloat(input.value || 0) + v).toFixed(2);
            }

            document.getElementById('soldeForm').onsubmit = function () {
                const m = parseFloat(document.getElementById('mInput').value);
                const op = document.querySelector('input[name="operation"]:checked').value;
                if (op === 'retirer' && m > <?= $solde ?>) {
                    alert('🚨 ALERTE : SOLDE INSUFFISANT');
                    return false;
                }
                return confirm('⚠ CONFIRMER ' + m + '€ ?');
            };
        </script>
        <?php
    }

    public function afficherNav()
    {
        $prenom = $_SESSION['prenom'] ?? 'Gestionnaire';
        $photo = $_SESSION['photo'] ?? null;
        $actionActuelle = $_GET['action'] ?? 'accueil';
        $nb = $this->nbMessages ?? 0;

        $activeClass = "bg-blue-600/15 text-blue-400 border-r-4 border-blue-600 shadow-[0_0_20px_rgba(37,99,235,0.1)]";
        $inactiveClass = "text-slate-500 hover:bg-white/[0.03] hover:text-white border-r-4 border-transparent";

        $cheminPhoto = !empty($photo) ? "uploads/profiles/" . basename($photo) : null;

        echo '
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
    .font-montserrat { font-family: "Montserrat", sans-serif; }
    .glass-sidebar {
        background: rgba(2, 6, 23, 0.8) !important;
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
    }
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
</style>

<div class="flex min-h-screen bg-[#020617] font-montserrat">
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
            
            <a href="index.php?action=accueil" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'accueil' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-gauge-high text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Tableau de bord</span>
            </a>

            <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mb-3 mt-8">Mon Espace</p>
            
            <a href="index.php?action=mesMessages" class="flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'mesMessages' ? $activeClass : $inactiveClass) . '">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-paper-plane text-lg"></i>
                    <span class="font-bold text-sm tracking-tight">Messages</span>
                </div>' .
                ($nb > 0 ? '<span class="bg-blue-600 text-[10px] font-black text-white px-2 py-0.5 rounded-lg shadow-lg shadow-blue-600/20">' . $nb . '</span>' : '') . '
            </a>

            <a href="index.php?action=monPlanning" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'monPlanning' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-calendar-day text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Mon Planning</span>
            </a>

            <a href="index.php?action=mesNotes" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'mesNotes' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-note-sticky text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Notes & Rappels</span>
            </a>

            <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mt-8 mb-3">Gestion Globale</p>

            <a href="index.php?action=associations" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'associations' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-sitemap text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Associations</span>
            </a>

            <a href="index.php?action=barmans" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'barmans' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-user-group text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Équipe Barmans</span>
            </a>

            <a href="index.php?action=fournisseurs" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'fournisseurs' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-truck-fast text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Fournisseurs</span>
            </a>

            <a href="index.php?action=distribuer" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'distribuer' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-truck-ramp-box text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Commandes & Stock</span>
            </a>

        </nav>

        <div class="p-4 border-t border-white/5 space-y-3 bg-white/[0.01]">
            <a href="index.php?action=profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/5 transition-all group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-blue-400 p-0.5 shadow-lg shadow-blue-600/20">
                    <div class="w-full h-full rounded-[10px] bg-[#020617] overflow-hidden flex items-center justify-center">
                        ' . (($cheminPhoto && file_exists($cheminPhoto))
                        ? '<img src="' . $cheminPhoto . '" class="w-full h-full object-cover">'
                        : '<span class="text-xs font-black text-white">' . strtoupper(substr($prenom, 0, 1)) . '</span>') . '
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">' . $this->e($prenom) . '</p>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic">Gestionnaire</p>
                </div>
            </a>

            <a href="index.php?action=deconnexion" class="flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl text-red-500 bg-red-500/5 hover:bg-red-500 hover:text-white transition-all duration-300 group shadow-lg shadow-red-900/5 border border-red-500/10">
                <i class="fa-solid fa-power-off text-sm group-hover:rotate-90 transition-transform duration-500"></i>
                <span class="font-black text-[11px] uppercase tracking-widest">Déconnexion</span>
            </a>
        </div>
    </aside>
    <main class="flex-1">';
    }

    public function afficherTableauDeBordAccueil($prenom, $data)
    {
        $this->afficherNav();

        $assos = $data['associations'] ?? [];
        $alertes = $data['alertes'] ?? [];
        $topProduits = $data['topProduits'] ?? [];
        $topBarmans = $data['topBarmans'] ?? [];
        $totalRecettes = $data['totalRecettes'] ?? 0;
        $beneficeNet = $data['beneficeNet'] ?? 0;
        $totalPertes = $data['totalPertes'] ?? 0;
        $nbBarmans = $data['nbBarmans'] ?? 0;

        ?>

        <style>
            .dashboard-container {
                padding: 3rem;
                max-width: 1600px;
                margin: 0 auto;
            }

            .stat-card-ultra {
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 2.5rem;
                position: relative;
                overflow: hidden;
                transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
            }

            .stat-card-ultra:hover {
                transform: translateY(-10px);
                background: rgba(255, 255, 255, 0.07);
                border-color: rgba(245, 158, 11, 0.4);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            }

            .glass-panel {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 3rem;
                padding: 2.5rem;
            }

            .alert-badge-interactive {
                display: flex;
                align-items: center;
                gap: 1rem;
                background: rgba(225, 29, 72, 0.1);
                border: 1px solid rgba(225, 29, 72, 0.2);
                padding: 0.75rem 1.5rem;
                border-radius: 1.5rem;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .alert-badge-interactive:hover {
                background: rgba(225, 29, 72, 0.2);
                border-color: #f43f5e;
                transform: scale(1.05);
            }

            .icon-float {
                position: absolute;
                right: -15px;
                top: -15px;
                font-size: 5rem;
                opacity: 0.04;
                transform: rotate(-15deg);
                transition: 0.5s ease;
            }

            .stat-value-new {
                font-size: 2.8rem;
                font-weight: 950;
                letter-spacing: -2px;
                line-height: 1;
            }

            .progress-track {
                background: rgba(255, 255, 255, 0.05);
                height: 8px;
                border-radius: 20px;
                margin-top: 8px;
            }

            .progress-bar {
                height: 100%;
                border-radius: 20px;
                background: linear-gradient(90deg, #f59e0b, #fbbf24);
                box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
            }

            .quick-action-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 2rem;
                padding: 2rem;
                text-align: center;
                color: #f59e0b;
                text-decoration: none;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .quick-action-card:hover {
                background: #f59e0b;
                color: #000;
                transform: translateY(-5px);
            }
        </style>

        <div class="dashboard-container scrollable-content custom-scrollbar">

            <div class="flex justify-between items-center mb-16 reveal">
                <div>
                    <h1 class="text-7xl font-black text-white uppercase italic tracking-tighter leading-none">
                        Dash<span class="text-amber-500">board</span>
                    </h1>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-[11px] mt-6 flex items-center gap-3">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                        Bienvenue, <strong><?= htmlspecialchars($prenom) ?></strong>
                    </p>
                </div>

                <?php if (!empty($alertes)): ?>
                    <a href="index.php?action=voirProduits&stock=critique" class="alert-badge-interactive">
                        <div class="h-12 w-12 rounded-xl bg-rose-500 flex items-center justify-center text-white shadow-lg shadow-rose-500/30">
                            <i class="fa-solid fa-triangle-exclamation animate-bounce"></i>
                        </div>
                        <div>
                            <p class="text-white font-black leading-none uppercase tracking-tighter"><?= count($alertes) ?>
                                Stocks Critiques</p>
                            <p class="text-[10px] text-rose-500 font-bold uppercase tracking-widest mt-1 italic underline">
                                Voir les urgences</p>
                        </div>
                    </a>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 reveal">
                <div class="stat-card-ultra p-10">
                    <i class="fa-solid fa-vault icon-float text-amber-500"></i>
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Chiffre
                        d'Affaires</p>
                    <div class="stat-value-new text-white"><?= number_format($totalRecettes, 0, '.', ' ') ?>€</div>
                    <p class="text-[10px] text-emerald-500 font-bold mt-4 uppercase italic">Flux Entrant</p>
                </div>

                <div class="stat-card-ultra p-10">
                    <i class="fa-solid fa-wine-glass-empty icon-float text-rose-500"></i>
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Pertes Globales</p>
                    <div class="stat-value-new text-rose-500"><?= number_format($totalPertes, 0, '.', ' ') ?>€</div>
                    <p class="text-[10px] text-rose-900 font-bold mt-4 uppercase italic">Inventaire Négatif</p>
                </div>

                <div class="stat-card-ultra p-10 bg-amber-500/[0.03]">
                    <i class="fa-solid fa-crown icon-float text-amber-500" style="opacity: 0.08"></i>
                    <p class="text-[11px] font-black text-amber-500 uppercase tracking-[0.2em] mb-4">Bénéfice Net</p>
                    <div class="stat-value-new <?= $beneficeNet >= 0 ? 'text-emerald-500' : 'text-rose-500' ?>">
                        <?= ($beneficeNet > 0 ? '+' : '') . number_format($beneficeNet, 0, '.', ' ') ?>€
                    </div>
                    <div class="h-1 w-12 <?= $beneficeNet >= 0 ? 'bg-emerald-500 shadow-emerald-500/50' : 'bg-rose-500 shadow-rose-500/50' ?> mt-6 rounded-full shadow-lg"></div>
                </div>

                <div class="stat-card-ultra p-10">
                    <i class="fa-solid fa-id-badge icon-float text-blue-500"></i>
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Effectif Staff</p>
                    <div class="stat-value-new text-white"><?= $nbBarmans ?> <span
                                class="text-sm text-slate-700 tracking-normal ml-1">PERS.</span></div>
                    <p class="text-[10px] text-slate-600 font-bold mt-4 uppercase italic"><?= count($assos) ?>
                        Associations</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-12 reveal" style="animation-delay: 0.2s">

                <div class="lg:col-span-2 glass-panel">
                    <div class="flex justify-between items-center mb-12">
                        <h3 class="text-xl font-black text-white uppercase italic tracking-tighter">Analyse des flux
                            produits</h3>
                        <div class="flex gap-2">
                            <span class="h-1.5 w-6 rounded-full bg-amber-500"></span>
                            <span class="h-1.5 w-2 rounded-full bg-white/10"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-12">
                        <?php if (!empty($topProduits)):
                            $sample = reset($topProduits);
                            $vKey = isset($sample['nb_ventes']) ? 'nb_ventes' : (isset($sample['quantite']) ? 'quantite' : 'total_vendu');
                            $maxV = max(array_column($topProduits, $vKey) ?: [1]);

                            foreach (array_slice($topProduits, 0, 4) as $top):
                                $val = $top[$vKey] ?? 0;
                                $pct = ($val / $maxV) * 100; ?>
                                <div class="group">
                                    <div class="flex justify-between items-end mb-4">
                                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest group-hover:text-white transition-colors italic"><?= htmlspecialchars($top['nom'] ?? 'Produit') ?></span>
                                        <span class="text-sm font-black text-white"><?= $val ?> <span
                                                    class="text-amber-500 text-[10px] ml-1">UNITÉS</span></span>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-bar" style="width: <?= $pct ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>

                <div class="glass-panel text-center">
                    <h3 class="text-xl font-black text-white uppercase italic tracking-tighter mb-12">Elite Staff</h3>
                    <div class="space-y-6">
                        <?php if (!empty($topBarmans)): ?>
                            <?php foreach (array_slice($topBarmans, 0, 3) as $idx => $b): ?>
                                <div class="flex items-center gap-6 p-5 rounded-3xl bg-white/[0.03] border border-white/5 hover:bg-white/10 transition-all">
                                    <span class="text-2xl font-black italic text-amber-500/50">#<?= $idx + 1 ?></span>
                                    <div class="flex-1 text-left">
                                        <p class="text-xs font-black text-white uppercase tracking-wider"><?= htmlspecialchars($b['prenom'] ?? 'Barman') ?></p>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase mt-1"><?= $b['total_ventes'] ?? 0 ?>
                                            Ventes</p>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-slate-800 text-xs"></i>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-slate-600 text-[10px] font-bold uppercase italic mt-12">Données
                                indisponibles</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }


    public function formulaireAjouterBarman($clients, $associations)
    {
        $this->afficherNav();
        ?>
        <style>
            .glass-panel {
                background: rgba(15, 23, 42, 0.5);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(16, 185, 129, 0.2);
                box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
                border-radius: 2rem;
            }

            .search-wrapper {
                position: relative;
                max-width: 400px;
                margin: 0 auto 1.5rem auto;
            }

            .search-input {
                width: 100%;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 0.8rem 1rem 0.8rem 3rem;
                border-radius: 1rem;
                color: white;
                text-align: center;
                font-size: 0.9rem;
                transition: all 0.3s;
            }

            .client-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                transition: all 0.2s ease-out;
                text-align: center;
                min-height: 80px;
            }

            .client-card:hover {
                background: rgba(16, 185, 129, 0.08);
                transform: translateY(-2px);
                border-color: rgba(16, 185, 129, 0.3);
            }

            .client-card.selected {
                background: rgba(16, 185, 129, 0.15);
                border-color: #10b981;
                box-shadow: 0 0 15px rgba(16, 185, 129, 0.1);
            }

            .id-badge {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
                padding: 2px 6px;
                border-radius: 6px;
                font-family: 'monospace';
                font-size: 9px;
                font-weight: 800;
                border: 1px solid rgba(16, 185, 129, 0.2);
            }

            .custom-select {
                background: rgba(15, 23, 42, 0.9);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: white;
                text-align-last: center;
                font-size: 0.9rem;
            }

            #btnSubmit:not(:disabled) {
                background: linear-gradient(90deg, #059669, #10b981);
                color: white;
                box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
            }

            .step-badge {
                background: #10b981;
                color: #020617;
                padding: 2px 10px;
                border-radius: 99px;
                font-size: 9px;
                font-weight: 900;
                margin-bottom: 0.8rem;
                display: inline-block;
            }
        </style>

        <div class="content-limit py-8 max-w-4xl mx-auto reveal text-center">
            <h1 class="text-4xl font-black uppercase mb-2 tracking-tighter italic">
                <span class="text-white">RECRUTER UN</span> <span class="text-emerald-500">BARMAN</span>
            </h1>
            <p class="text-slate-500 mb-8 font-bold tracking-widest uppercase text-[10px]">Staff Management System</p>

            <div class="glass-panel p-8">
                <form action="index.php?action=ajouterBarman" method="POST" id="mainForm">

                    <div class="mb-10">
                        <span class="step-badge">SÉLECTION CANDIDAT</span>

                        <div class="search-wrapper">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500/50 text-sm"></i>
                            <input type="text" id="clientSearch" class="search-input"
                                   placeholder="Rechercher nom ou ID...">
                        </div>

                        <div id="clientContainer"
                             class="grid grid-cols-2 md:grid-cols-4 gap-3 max-h-64 overflow-y-auto p-2 custom-scrollbar">
                            <?php foreach ($clients as $c): ?>
                                <div class="client-card p-4 rounded-2xl cursor-pointer flex flex-col items-center justify-center group"
                                     data-search="<?= strtolower($c['prenom'] . ' ' . $c['nom'] . ' ' . $c['id']) ?>"
                                     onclick="selectClient(<?= $c['id'] ?>, this)">

                                <span class="id-badge mb-2 group-hover:bg-emerald-500 group-hover:text-black transition-colors">
                                    ID #<?= $c['id'] ?>
                                </span>

                                    <p class="text-[11px] font-black text-white uppercase leading-tight">
                                        <?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <input type="hidden" name="client_id" id="input_client_id" required>

                    <div class="mb-10 max-w-sm mx-auto">
                        <span class="step-badge">AFFECTATION ASSOCIATIONS</span>
                        <div class="relative">
                            <select name="association_id"
                                    class="custom-select w-full p-4 rounded-xl font-bold appearance-none cursor-pointer"
                                    required>
                                <option value="" disabled selected>Choisir l'association</option>
                                <?php foreach ($associations as $a): ?>
                                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 text-xs"></i>
                        </div>
                    </div>

                    <div class="max-w-xs mx-auto">
                        <button type="submit" id="btnSubmit" disabled
                                class="w-full py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.3em] bg-slate-800 text-slate-500 transition-all duration-500">
                            Confirmer l'enrôlement
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.getElementById('clientSearch').addEventListener('input', function (e) {
                const term = e.target.value.toLowerCase();
                document.querySelectorAll('.client-card').forEach(card => {
                    const text = card.getAttribute('data-search');
                    card.style.display = text.includes(term) ? 'flex' : 'none';
                });
            });

            function selectClient(id, element) {
                document.querySelectorAll('.client-card').forEach(c => c.classList.remove('selected'));
                element.classList.add('selected');
                document.getElementById('input_client_id').value = id;

                const btn = document.getElementById('btnSubmit');
                btn.disabled = false;
                btn.innerHTML = "Prêt au recrutement (ID #" + id + ")";
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
            /* On empêche le scroll sur le body pour tout bloquer */
            body {
                background: #020617 radial-gradient(circle at 50% -20%, #064e3b 0%, #020617 80%) no-repeat fixed;
                margin: 0;
                height: 100vh;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: #f8fafc;
            }

            /* Container principal : il prend toute la hauteur de l'écran */
            .page-container {
                max-width: 1300px;
                margin: 0 auto;
                width: 100%;
                height: 100vh;
                display: flex;
                flex-direction: column;
                padding: 2rem;
                box-sizing: border-box;
            }

            /* Le header et la recherche ne bougent pas */
            .fixed-top-section {
                flex-shrink: 0;
                margin-bottom: 1rem;
            }

            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                margin-bottom: 2rem;
            }

            .glass-search-bar {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(16, 185, 129, 0.15);
                border-radius: 2rem;
                padding: 1rem 2.5rem;
                display: flex;
                gap: 1.5rem;
                align-items: center;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            }

            /* LA ZONE SCROLLABLE : Elle prend le reste de la hauteur */
            #barmanList {
                flex-grow: 1;
                overflow-y: auto;
                padding-right: 15px; /* Espace pour la scrollbar */
                padding-bottom: 3rem;
                margin-top: 1rem;
            }

            /* Style des cartes */
            .member-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 2rem;
                padding: 1.2rem 2.5rem;
                margin-bottom: 1rem;
                display: grid;
                grid-template-columns: 80px 1.5fr 1fr 1.2fr 150px;
                align-items: center;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                text-decoration: none;
                color: inherit;
            }

            .member-card:hover {
                background: rgba(16, 185, 129, 0.05);
                border-color: #10b981;
                transform: translateX(10px);
            }

            .id-pill {
                font-size: 10px;
                padding: 4px 10px;
                width: fit-content;
            }

            .avatar-box {
                width: 45px;
                height: 45px;
                border-radius: 14px;
                background: linear-gradient(135deg, #064e3b 0%, #020617 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 900;
                color: #10b981;
            }

            .status-tag {
                font-size: 10px;
                font-weight: 900;
                text-transform: uppercase;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .dot {
                width: 8px;

                .dot-online {
                    background: #10b981;
                    box-shadow: 0 0 12px #10b981;
                }

                .dot-offline {
                    background: #475569;
                }

                .btn-view {
                    background: #10b981;
                    color: #020617;
                    padding: 10px 20px;
                    border-radius: 12px;
                    font-size: 10px;
                    font-weight: 900;
                    text-transform: uppercase;
                    opacity: 0;
                    transition: 0.4s;
                }

                .member-card:hover .btn-view {
                    opacity: 1;
                }

                /* Custom Scrollbar */

                #barmanList::-webkit-scrollbar {
                    width: 6px;
                }

                #barmanList::-webkit-scrollbar-thumb {
                    background: rgba(16, 185, 129, 0.2);
                    border-radius: 10px;
                }

                #barmanList::-webkit-scrollbar-thumb:hover {
                    background: rgba(16, 185, 129, 0.5);
                }

                #filterAsso option {
                    background: #020617;
                }
        </style>

        <div class="page-container">
            <div class="fixed-top-section">
                <header class="page-header">
                    <div>
                        <h1 class="text-6xl font-black italic tracking-tighter uppercase leading-none text-white">
                            Staff<span class="text-emerald-500">.</span>
                        </h1>
                        <p class="text-slate-500 font-bold mt-2 ml-1 tracking-widest uppercase text-[10px]">
                            Effectifs opérationnels & hiérarchie
                        </p>
                    </div>
                    <a href="index.php?action=ajouterBarman"
                       class="group flex items-center gap-4 bg-emerald-500 text-slate-950 px-8 py-4 rounded-2xl font-black text-xs uppercase transition-all hover:bg-white shadow-lg shadow-emerald-900/20">
                        Recruter un membre
                        <i class="fa-solid fa-plus transition-transform group-hover:rotate-90"></i>
                    </a>
                </header>

                <div class="glass-search-bar">
                    <i class="fa-solid fa-magnifying-glass text-emerald-500"></i>
                    <input type="text" id="filterSearch" placeholder="Rechercher par nom, mail ou matricule..."
                           class="flex-1 bg-transparent border-none text-white outline-none font-bold text-sm placeholder:text-slate-600">

                    <div class="h-6 w-[1px] bg-white/10"></div>

                    <select id="filterAsso"
                            class="bg-transparent text-white border-none outline-none font-black text-[10px] uppercase tracking-widest cursor-pointer hover:text-emerald-500 transition-colors">
                        <option value="all">Toutes les associations</option>
                        <?php foreach ($associations as $asso): ?>
                            <option value="<?= htmlspecialchars($asso['nom']) ?>">
                                <?= htmlspecialchars($asso['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div id="barmanList">
                <?php foreach ($barmans as $barman):
                    $isActif = $barman['actif'] ?? true;
                    $nomAsso = !empty($barman['nom_association']) ? $barman['nom_association'] : 'Non assigné';
                    ?>
                    <a href="index.php?action=voirProfilBarman&id=<?= $barman['id'] ?>"
                       class="member-card barman-row"
                       data-asso="<?= htmlspecialchars($nomAsso) ?>"
                       style="display: grid; text-decoration: none; color: inherit;">

                        <div style="display: flex; align-items: center;">
                            <span class="id-pill">ID-<?= str_pad($barman['id'], 3, '0', STR_PAD_LEFT) ?></span>
                        </div>

                        <div class="flex items-center gap-5">
                            <div class="avatar-box">
                                <?= strtoupper(substr($barman['prenom'], 0, 1)) ?>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-white uppercase leading-none mb-1">
                                    <?= htmlspecialchars($barman['prenom'] . ' ' . $barman['nom']) ?>
                                </h3>
                                <p class="text-[10px] text-emerald-500/40 font-bold lowercase">
                                    <?= htmlspecialchars($barman['email']) ?>
                                </p>
                            </div>
                        </div>

                        <div class="status-tag <?= $isActif ? 'text-emerald-400' : 'text-slate-500' ?>">
                            <span class="dot <?= $isActif ? 'dot-online' : 'dot-offline' ?>"></span>
                            <?= $isActif ? 'En service' : 'Inactif' ?>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-emerald-500/50 uppercase mb-1">Rattachement</span>
                            <span class="text-[11px] font-extrabold text-white uppercase tracking-tighter">
            <?= htmlspecialchars($nomAsso) ?>
        </span>
                        </div>

                        <div class="text-right">
                            <span class="btn-view" style="opacity: 1; visibility: visible;">Consulter</span>
                        </div>
                    </a>
                <?php endforeach; ?>
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

                        row.style.display = (matchesSearch && matchesAsso) ? 'grid' : 'none';
                    });
                }

                searchInput.addEventListener('input', filterTable);
                assoSelect.addEventListener('change', filterTable);
            });
        </script>
        <?php
    }

    public function afficherSuccesPromotion()
    {
        $this->afficherNav();
        ?>
        <div style="height: 80vh; display: flex; flex-direction: column; justify-content: center; align-items: center; background: #020617; color: white; font-family: 'Plus Jakarta Sans', sans-serif;">

            <div style="background: rgba(16, 185, 129, 0.1); border: 2px solid #10b981; width: 100px; height: 100px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-bottom: 2rem; animation: pulse 2s infinite;">
                <i class="fa-solid fa-user-check" style="font-size: 3rem; color: #10b981;"></i>
            </div>

            <h1 style="font-size: 2.5rem; font-weight: 900; text-transform: uppercase; font-style: italic; margin-bottom: 1rem; letter-spacing: -1px;">
                Recrutement <span style="color: #10b981;">Confirmé !</span>
            </h1>

            <p style="color: #94a3b8; font-size: 1.1rem; margin-bottom: 2rem; font-weight: 500;">
                Le membre a été promu Barman. Redirection en cours...
            </p>

            <div style="width: 240px; height: 4px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;">
                <div id="loader-bar"
                     style="width: 0%; height: 100%; background: linear-gradient(90deg, #10b981, #34d399); transition: width 2s cubic-bezier(0.4, 0, 0.2, 1);"></div>
            </div>

            <style>
                @keyframes pulse {
                    0% {
                        transform: scale(1);
                        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
                    }
                    70% {
                        transform: scale(1.05);
                        box-shadow: 0 0 0 20px rgba(16, 185, 129, 0);
                    }
                    100% {
                        transform: scale(1);
                        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
                    }
                }
            </style>
        </div>

        <script>
            setTimeout(() => {
                document.getElementById('loader-bar').style.width = '100%';
            }, 100);
            setTimeout(() => {
                window.location.href = "index.php?action=barmans";
            }, 2200);
        </script>
        <?php
    }

    public function formulaireModificationProduit($produit)
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background-color: #020617;
                margin: 0;
                padding: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: #f8fafc;
            }

            .header-title-container {
                overflow: visible;
                padding-bottom: 10px;
            }

            .header-title {
                font-size: 3.5rem;
                font-weight: 900;
                text-transform: uppercase;
                font-style: italic;
                letter-spacing: -0.02em;
                line-height: 1.1;
                display: block;
                padding-right: 30px;
                margin-right: -30px;
                white-space: nowrap;
            }

            .filter-group {
                display: flex;
                align-items: center;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 1.25rem;
                padding: 0.8rem 1.2rem;
                gap: 0.8rem;
                transition: all 0.3s ease;
            }

            .filter-group:focus-within {
                border-color: #8b5cf6;
                box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);
            }

            .input-text, .filter-select {
                background: transparent;
                color: white;
                border: none;
                outline: none;
                width: 100%;
                font-weight: 700;
            }

            .filter-select option {
                background-color: #0f172a;
                color: white;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(139, 92, 246, 0.15);
                backdrop-filter: blur(10px);
                border-radius: 3rem;
                padding: 4rem;
            }

            .btn-submit {
                background: linear-gradient(135deg, #8b5cf6, #d946ef);
                color: white;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.2em;
                padding: 1.5rem;
                border-radius: 1.25rem;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .btn-submit:hover {
                transform: scale(1.02);
                box-shadow: 0 0 30px rgba(139, 92, 246, 0.4);
            }

            .label-tech {
                font-size: 10px;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.15em;
                color: #64748b;
                margin-bottom: 0.5rem;
                display: block;
            }
        </style>

        <div class="p-6 md:p-12 min-h-screen flex justify-center items-center">
            <div class="w-full max-w-2xl">

                <a href="index.php?action=voirProduits"
                   class="flex items-center gap-3 text-violet-400 hover:text-white transition-all mb-10 group">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Retour Monitoring</span>
                </a>

                <div class="glass-card relative overflow-hidden">
                    <div class="relative z-10">
                        <header class="mb-12 header-title-container">
                            <p class="text-violet-500 font-black text-[10px] uppercase tracking-[0.5em] mb-2">Inventory
                                System</p>
                            <h1 class="header-title">
                                Update <span
                                        class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">Produit</span>
                            </h1>
                        </header>

                        <form action="index.php?action=modifierProduit" method="post" class="space-y-8">
                            <input type="hidden" name="id" value="<?= $produit['id'] ?>">

                            <div>
                                <label class="label-tech">Désignation</label>
                                <div class="filter-group">
                                    <i class="fa-solid fa-signature text-violet-500"></i>
                                    <input type="text" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>"
                                           required class="input-text">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="label-tech">Catégorie</label>
                                    <div class="filter-group">
                                        <i class="fa-solid fa-list text-violet-500"></i>
                                        <select name="type" required class="filter-select">
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
                                    </div>
                                </div>

                                <div>
                                    <label class="label-tech">Prix (€)</label>
                                    <div class="filter-group">
                                        <i class="fa-solid fa-wallet text-fuchsia-500"></i>
                                        <input type="number" name="prix" step="0.01" value="<?= $produit['prix'] ?>"
                                               required class="input-text">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="label-tech">Stock Actuel</label>
                                <div class="filter-group">
                                    <i class="fa-solid fa-warehouse text-violet-500"></i>
                                    <input type="number" name="stock" value="<?= $produit['quantiteActuelle'] ?>"
                                           required class="input-text">
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit"
                                        class="btn-submit w-full flex items-center justify-center gap-4 group">
                                    <span>Enregistrer les données</span>
                                    <i class="fa-solid fa-microchip text-lg opacity-70 group-hover:rotate-12 transition-all"></i>
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

    public function afficherFormulaireAjoutClient($associations, $clients, $erreur = null)
    {
        $this->afficherNav();
        ?>
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

        <style>
            :root {
                --nav-bg: #020617;
                --card-bg: #0f172a;
                --input-bg: #1e293b;
                --amber: #f59e0b;
                --amber-glow: rgba(245, 158, 11, 0.4);
                --slate-400: #94a3b8;
            }

            .dashboard-container {
                min-height: 100vh;
                padding: 4rem 2rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                background: var(--nav-bg);
                font-family: 'Montserrat', sans-serif;
            }

            .form-card {
                width: 100%;
                max-width: 580px;
                background: linear-gradient(145deg, rgba(15, 23, 42, 0.9), rgba(2, 6, 23, 0.95));
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 2.5rem;
                padding: 4rem;
                box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.6);
                overflow: visible !important;
                position: relative;
            }

            /* Effet de lueur derrière la carte */
            .form-card::before {
                content: '';
                position: absolute;
                top: -2px;
                left: -2px;
                right: -2px;
                bottom: -2px;
                background: linear-gradient(45deg, transparent, rgba(245, 158, 11, 0.1), transparent);
                border-radius: 2.5rem;
                z-index: -1;
            }

            .label-style {
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.25em;
                color: var(--slate-400);
                margin-bottom: 1.2rem;
                transition: color 0.3s ease;
            }

            /* Style Tom Select Custom */
            .ts-wrapper .ts-control {
                background: var(--input-bg) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                border-radius: 1.25rem !important;
                padding: 1.2rem !important;
                color: #ffffff !important;
                font-size: 0.9rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .ts-wrapper.focus .ts-control {
                border-color: var(--amber) !important;
                box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1) !important;
            }

            .ts-dropdown {
                background: #1e293b !important;
                border: 1px solid rgba(245, 158, 11, 0.3) !important;
                border-radius: 1.25rem !important;
                margin-top: 8px !important;
                padding: 8px !important;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5) !important;
            }

            .ts-dropdown .option {
                border-radius: 0.8rem;
                padding: 12px 15px !important;
                color: #cbd5e1 !important;
            }

            .ts-dropdown .active {
                background: var(--amber) !important;
                color: #000 !important;
                font-weight: 700;
            }

            /* Bouton Dynamique */
            .btn-submit {
                width: 100%;
                padding: 1.4rem;
                background: var(--amber);
                color: #000;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.2em;
                border-radius: 1.5rem;
                border: none;
                cursor: pointer;
                box-shadow: 0 15px 30px -10px var(--amber-glow);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                margin-top: 1.5rem;
            }

            .btn-submit:hover {
                transform: translateY(-4px) scale(1.02);
                box-shadow: 0 20px 40px -10px var(--amber-glow);
                filter: brightness(1.1);
            }

            .btn-submit:active {
                transform: translateY(0);
            }

            .back-link {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-top: 2.5rem;
                font-size: 10px;
                font-weight: 800;
                color: var(--slate-400);
                text-transform: uppercase;
                letter-spacing: 0.2em;
                transition: all 0.3s ease;
            }

            .back-link:hover {
                color: #fff;
                transform: translateX(-5px);
            }

            /* Animation d'apparition */
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-up {
                animation: slideUp 0.6s ease forwards;
            }
        </style>

        <div class="dashboard-container">
            <div class="text-center mb-16 animate-up">
                <h2 class="text-7xl font-black text-white italic tracking-tighter uppercase">
                    Link<span class="text-amber-500">Member</span>
                </h2>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.5em] mt-4">Management Protocol
                    v2.0</p>
            </div>

            <div class="form-card animate-up" style="animation-delay: 0.1s;">
                <?php if ($erreur): ?>
                    <div class="bg-red-500/10 border border-red-500/20 p-5 rounded-2xl mb-8 flex items-center gap-4 text-red-400 text-xs font-bold">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                        <?= htmlspecialchars($erreur) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?action=validerAjoutClient" method="POST" id="clientForm">
                    <div class="mb-10">
                        <label class="label-style">
                            <i class="fa-solid fa-id-card text-amber-500"></i> Dossier Client
                        </label>
                        <select name="id_client" id="select-client" required>
                            <option value="">Entrer un nom ou un email...</option>
                            <?php foreach ($clients as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars($c['nom'] . " " . $c['prenom']) ?>
                                    — <?= htmlspecialchars($c['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-14">
                        <label class="label-style">
                            <i class="fa-solid fa-hubspot text-amber-500"></i> Réseau de destination
                        </label>
                        <select name="id_assos" id="select-asso" required>
                            <option value="">Sélectionner l'entité...</option>
                            <?php foreach ($associations as $a): ?>
                                <option value="<?= $a['id'] ?>">
                                    <?= htmlspecialchars($a['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-bolt mr-2"></i> Finaliser la liaison
                    </button>

                    <a href="index.php?action=accueil" class="back-link">
                        <i class="fa-solid fa-chevron-left"></i> Retour au Dashboard
                    </a>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const config = {
                    create: false,
                    maxOptions: 10,
                    dropdownParent: 'body',
                    onDropdownOpen: function () {
                        this.wrapper.classList.add('focus');
                    },
                    onDropdownClose: function () {
                        this.wrapper.classList.remove('focus');
                    }
                };

                if (typeof TomSelect !== "undefined") {
                    new TomSelect('#select-client', config);
                    new TomSelect('#select-asso', config);
                }
            });
        </script>
        <?php
    }


    public function afficherFormulaireLiaisonFournisseur($fournisseurs, $produits)
    {
        $this->afficherNav();
        ?>
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

        <style>
            :root {
                --bg-dark: #020617;
                --card-slate: #0f172a;
                --input-slate: #1e293b;
                --amber: #f59e0b;
            }

            .page-container {
                min-height: 100vh;
                background: var(--bg-dark);
                padding: 4rem 2rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                font-family: 'Montserrat', sans-serif;
            }

            .glass-card {
                width: 100%;
                max-width: 650px;
                background: rgba(15, 23, 42, 0.9);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 2rem;
                padding: 3rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }

            .section-title {
                font-size: 0.75rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.2em;
                color: #94a3b8;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            /* Customisation Tom Select */
            .ts-control {
                background: var(--input-slate) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                border-radius: 1rem !important;
                padding: 1rem !important;
                color: white !important;
            }

            .input-field {
                background: var(--input-slate);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 1rem;
                padding: 1rem;
                color: white;
                width: 100%;
                outline: none;
                transition: border-color 0.3s;
            }

            .input-field:focus {
                border-color: var(--amber);
            }

            .btn-submit {
                background: var(--amber);
                color: #000;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                padding: 1.2rem;
                border-radius: 1rem;
                width: 100%;
                margin-top: 2rem;
                transition: all 0.3s;
                cursor: pointer;
                border: none;
            }

            .btn-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
            }
        </style>

        <div class="page-container">
            <div class="text-center mb-10">
                <h2 class="text-5xl font-black text-white italic tracking-tighter uppercase">
                    Catalog<span class="text-amber-500">Manager</span>
                </h2>
                <p class="text-slate-500 text-xs font-bold mt-2 tracking-widest uppercase">Liaison Fournisseur &
                    Produits</p>
            </div>

            <div class="glass-card">
                <form action="index.php?action=validerLiaisonFournisseur" method="POST">

                    <div class="mb-8">
                        <label class="section-title"><i class="fa-solid fa-truck-field text-amber-500"></i> Sélectionner
                            le Fournisseur</label>
                        <select name="id_fournisseur" id="select-f" required>
                            <option value="">Chercher un fournisseur...</option>
                            <?php foreach ($fournisseurs as $f): ?>
                                <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?>
                                    (<?= htmlspecialchars($f['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label class="section-title"><i class="fa-solid fa-box-open text-amber-500"></i> Produit à
                            ajouter</label>
                        <select name="id_produit" id="select-p" required>
                            <option value="">Chercher un produit...</option>
                            <?php foreach ($produits as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?>
                                    — <?= htmlspecialchars($p['type']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="section-title"><i class="fa-solid fa-tag text-amber-500"></i> Prix d'Achat (€)</label>
                            <input type="number" step="0.01" name="prix_achat" class="input-field" placeholder="0.00"
                                   required>
                        </div>
                        <div>
                            <label class="section-title"><i class="fa-solid fa-clock text-amber-500"></i> Délai (jours)</label>
                            <input type="number" name="delai" class="input-field" value="7">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Ajouter au catalogue fournisseur
                    </button>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new TomSelect('#select-f', {create: false});
                new TomSelect('#select-p', {create: false});
            });
        </script>
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
                                        l'association
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
        $this->afficherNav();
        ?>
        <div class="min-h-screen bg-[#020617] py-12 px-4 font-montserrat relative overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-amber-500/5 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="max-w-6xl mx-auto relative">

                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                    <div>
                        <span class="text-amber-500 font-black uppercase text-[10px] tracking-[0.4em] block mb-2">Administration</span>
                        <h2 class="text-5xl font-black text-white uppercase italic tracking-tighter leading-none">
                            Base <span class="text-transparent stroke-amber-500"
                                       style="-webkit-text-stroke: 1px #f59e0b;">Utilisateurs</span>
                        </h2>
                    </div>

                    <div class="flex gap-4">
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl min-w-[140px]">
                            <span class="block text-[9px] font-black text-slate-500 uppercase mb-1">Total Membres</span>
                            <span class="text-2xl font-black text-white"><?= count($utilisateurs) ?></span>
                        </div>
                    </div>
                </div>

                <?php if (empty($utilisateurs)): ?>
                    <div class="bg-white/[0.02] border border-white/5 rounded-[3rem] p-20 text-center backdrop-blur-xl">
                        <div class="inline-flex w-20 h-20 bg-amber-500/10 rounded-3xl items-center justify-center text-amber-500 mb-6">
                            <i class="fa-solid fa-users-slash text-3xl"></i>
                        </div>
                        <p class="text-slate-400 font-black uppercase tracking-widest">Aucune donnée synchronisée</p>
                        <a href="index.php?action=accueil"
                           class="mt-6 inline-block text-amber-500 text-xs font-black uppercase border-b border-amber-500/30 pb-1">Retour
                            au terminal</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($utilisateurs as $u):
                            $nom = htmlspecialchars($u['nom'] ?? 'N/A');
                            $prenom = htmlspecialchars($u['prenom'] ?? '');
                            $email = htmlspecialchars($u['email'] ?? 'Non renseigné');
                            $role = strtoupper($u['role'] ?? 'CLIENT');
                            $solde = (float)($u['solde'] ?? 0);
                            ?>
                            <div class="group relative bg-white/[0.03] border border-white/5 hover:border-amber-500/50 rounded-[2.5rem] p-8 transition-all duration-500 shadow-2xl overflow-hidden">
                                <div class="absolute -right-10 -top-10 w-32 h-32 bg-amber-500/10 blur-3xl group-hover:bg-amber-500/20 transition-all"></div>

                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-8">
                                        <div class="p-4 bg-[#020617] rounded-2xl border border-white/5 shadow-inner group-hover:scale-110 transition-transform duration-500">
                                            <i class="fa-solid fa-id-badge text-amber-500 text-xl"></i>
                                        </div>
                                        <span class="py-1 px-4 rounded-full border border-amber-500/20 text-amber-500 text-[8px] font-black tracking-widest bg-amber-500/5">
                                        <?= $role ?>
                                    </span>
                                    </div>

                                    <div class="mb-8">
                                        <h3 class="text-white font-black text-2xl uppercase tracking-tighter group-hover:text-amber-400 transition-colors">
                                            <?= $prenom ?> <br><span class="text-3xl"><?= $nom ?></span>
                                        </h3>
                                        <p class="text-slate-500 text-[10px] font-medium truncate mt-2 font-mono"><?= $email ?></p>
                                    </div>

                                    <div class="bg-[#020617] rounded-3xl p-5 mb-8 border border-white/5 relative overflow-hidden">
                                        <div class="flex justify-between items-center relative z-10">
                                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Solde Disponible</span>
                                            <span class="text-2xl font-black <?= $solde < 0 ? 'text-rose-500' : 'text-white' ?> tracking-tighter">
                                            <?= number_format($solde, 2, ',', ' ') ?> €
                                        </span>
                                        </div>
                                        <div class="absolute bottom-0 left-0 h-[2px] bg-amber-500 shadow-[0_0_10px_#f59e0b] transition-all duration-1000 w-0 group-hover:w-full"></div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <a href="index.php?action=gererSolde&id=<?= $u['id'] ?>"
                                           class="bg-amber-500 hover:bg-amber-400 text-[#020617] py-4 rounded-2xl text-[9px] font-black uppercase tracking-[0.2em] transition-all text-center flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-plus-minus text-[10px]"></i> Solde
                                        </a>
                                        <a href="index.php?action=voirProfil&id=<?= $u['id'] ?>"
                                           class="bg-white/5 hover:bg-white/10 text-white py-4 rounded-2xl text-[9px] font-black uppercase tracking-[0.2em] transition-all text-center flex items-center justify-center gap-2">
                                            Détails <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
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
        ?>
        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white relative overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-900/10 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 animate-fade-in">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-8 h-[2px] bg-blue-600"></span>
                        <span class="text-blue-500 font-black uppercase text-[10px] tracking-[0.3em] italic block">Gestion des Associations</span>
                    </div>
                    <h1 class="text-4xl font-black tracking-tighter text-white uppercase flex items-center gap-4 italic">
                        <i class="fa-solid fa-sitemap text-blue-600"></i> Mes <span
                                class="text-blue-600">Associations</span>
                    </h1>
                    <p class="text-slate-400 font-medium mt-2 italic text-sm">Liste des organisations validées sous
                        votre supervision.</p>
                </div>

                <div class="bg-blue-600/10 border border-blue-500/20 px-6 py-3 rounded-2xl flex items-center gap-3 shadow-lg backdrop-blur-md">
                    <i class="fa-solid fa-check-double text-blue-400"></i>
                    <span class="text-xs font-black text-blue-100 uppercase tracking-widest"><?= count($associations) ?> Associations Actives</span>
                </div>
            </div>

            <?php if (empty($associations)): ?>
                <div class="bg-white/5 rounded-[3rem] p-20 text-center border border-white/5 border-dashed">
                    <i class="fa-solid fa-folder-open text-6xl text-slate-700 mb-6 block"></i>
                    <p class="text-slate-400 font-bold italic tracking-widest uppercase">Aucune association validée pour
                        le moment.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    <?php foreach ($associations as $asso):
                        $solde = $asso['solde'] ?? 0;
                        $isPositive = $solde >= 0;
                        ?>
                        <div class="bg-white/5 backdrop-blur-md rounded-[2.5rem] border border-white/5 hover:border-blue-500/30 hover:-translate-y-2 transition-all duration-500 group overflow-hidden flex flex-col h-full shadow-2xl">
                            <div class="p-8 pb-4">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center text-2xl font-black text-blue-500 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-lg shadow-blue-900/20">
                                        <?= strtoupper(substr($asso['nom'], 0, 1)) ?>
                                    </div>
                                    <div class="px-4 py-2 rounded-xl border <?= $isPositive ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-400' : 'border-rose-500/20 bg-rose-500/10 text-rose-500' ?> font-black text-sm tracking-tighter">
                                        <?= number_format($solde, 2, ',', ' ') ?> €
                                    </div>
                                </div>

                                <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-2 group-hover:text-blue-400 transition-colors"><?= htmlspecialchars($asso['nom']) ?></h3>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]">Affiliée & Validée</span>
                                </div>
                            </div>

                            <div class="px-8 py-6 space-y-4 flex-1">
                                <div class="flex items-center gap-4 group/item">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-500 group-hover/item:text-blue-400 transition-colors border border-white/5">
                                        <i class="fa-solid fa-location-dot text-xs"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 truncate tracking-tight"><?= htmlspecialchars($asso['adresse'] ?? 'Non renseigné') ?></span>
                                </div>
                                <div class="flex items-center gap-4 group/item">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-500 group-hover/item:text-blue-400 transition-colors border border-white/5">
                                        <i class="fa-solid fa-envelope text-xs"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 truncate tracking-tight"><?= htmlspecialchars($asso['email'] ?? 'Non renseigné') ?></span>
                                </div>
                            </div>

                            <div class="p-6 bg-white/[0.02] border-t border-white/5 mt-auto">
                                <a href="index.php?action=voirAssociation&id=<?= $asso['id'] ?>"
                                   class="flex items-center justify-center gap-3 w-full bg-blue-600 hover:bg-blue-500 text-white py-4 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-900/20 active:scale-95 group/btn">
                                    Gérer l'association
                                    <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function afficherFournisseurs($fournisseurs, $associations)
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            :root {
                --chrome-bg: #020617;
                --chrome-card: #0f172a;
                --electric-orange: #f59e0b;
                --silver: #94a3b8;
            }

            body {
                background-color: var(--chrome-bg);
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: var(--silver);
                margin: 0;
            }

            .fixed-top-section {
                position: sticky;
                top: 0;
                width: 100%;
                background: rgba(2, 6, 23, 0.95);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(245, 158, 11, 0.2);
                z-index: 1000;
                padding: 1.2rem 0;
            }

            .global-selector-bar {
                background: rgba(15, 23, 42, 0.8);
                border: 1px solid rgba(245, 158, 11, 0.3);
                padding: 0.6rem 1.2rem;
                border-radius: 1rem;
                display: flex;
                align-items: center;
                gap: 15px;
                width: 100%;
                max-width: 550px;
            }

            .search-box {
                position: relative;
                flex-grow: 1;
            }

            .search-box input {
                width: 100%;
                background: rgba(2, 6, 23, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 10px 12px;
                border-radius: 8px;
                color: white;
                outline: none;
            }

            .search-results {
                position: absolute;
                top: 115%;
                left: 0;
                right: 0;
                background: #1e293b;
                border: 1px solid var(--electric-orange);
                max-height: 250px;
                overflow-y: auto;
                display: none;
                z-index: 2000;
                border-radius: 8px;
            }

            .result-item {
                padding: 12px;
                cursor: pointer;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                color: #fff;
                font-size: 13px;
            }

            .result-item:hover {
                background: var(--electric-orange);
                color: black;
            }

            .supplier-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
                gap: 2.5rem;
                padding: 3rem 40px;
                max-width: 1600px;
                margin: 0 auto;
            }

            .supplier-card {
                background: var(--chrome-card);
                border-radius: 2rem;
                padding: 2.2rem;
                border: 1px solid rgba(255, 255, 255, 0.05);
            }

            .catalog-btn {
                background: #1e293b;
                color: #475569;
                pointer-events: none;
                font-size: 0.75rem;
                font-weight: 800;
                text-transform: uppercase;
                padding: 1rem;
                border-radius: 0.8rem;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                transition: 0.3s;
            }

            .catalog-btn.active {
                background: var(--electric-orange);
                color: #000;
                pointer-events: auto;
            }

            .action-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                margin-top: 1.5rem;
                padding-top: 1.5rem;
                border-top: 1px solid rgba(255, 255, 255, 0.05);
            }
        </style>

        <div class="fixed-top-section">
            <div class="max-w-[1600px] mx-auto px-10 flex justify-between items-center">
                <h1 class="font-black italic text-2xl text-white uppercase">LOGISTIQUE <span
                            style="color:var(--electric-orange)">RÉSEAU</span></h1>

                <div class="global-selector-bar">
                    <span class="text-[9px] font-black uppercase text-amber-500">ASSOCIATION :</span>
                    <div class="search-box">
                        <input type="text" id="mainAssoInput" placeholder="Rechercher...">
                        <div id="mainAssoResults" class="search-results">
                            <?php foreach ($associations as $asso): ?>
                                <div class="result-item" data-id="<?= $asso['id'] ?>">
                                    <?= htmlspecialchars($asso['nom']) ?>
                                    <span style="opacity:0.5; font-size:11px;">(<?= number_format($asso['solde'], 2) ?>€)</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <a href="index.php?module=gestionnaire&action=rechercherPrix"
                   class="text-amber-500 font-bold uppercase text-[10px] border border-amber-500 px-4 py-2 rounded-lg">Comparer</a>
            </div>
        </div>

        <div class="supplier-grid">
            <?php foreach ($fournisseurs as $f): ?>
                <div class="supplier-card">
                    <h2 class="text-2xl font-black text-white italic uppercase mb-6"><?= htmlspecialchars($f['nom']) ?></h2>

                    <a href="#"
                       data-base-url="index.php?module=gestionnaire&action=voirFournisseur&id=<?= $f['id'] ?>"
                       class="catalog-btn">
                        Ouvrir Catalogue
                    </a>

                    <div class="action-grid">
                        <a href="mailto:<?= $f['email'] ?>" class="catalog-btn active"
                           style="background: #0f172a; color: #fff; border: 1px solid rgba(255,255,255,0.1);">Contacter</a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'SuperAdmin'): ?>
                            <a href="index.php?module=gestionnaire&action=supprimerFournisseur&id=<?= $f['id'] ?>"
                               class="catalog-btn active" style="background: transparent; color: #64748b;"
                               onclick="return confirm('Supprimer ?')">Retirer</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <script src="js/recherche_assos.js"></script>        <?php
    }

    public function afficherRechercheGlobale($resultats, $recherche)
    {
        $this->afficherNav();
        ?>
        <style>
            body, html {
                overflow: hidden;
                height: 100%;
            }

            .page-container {
                background: radial-gradient(circle at top right, #0f172a, #020617);
                height: 100vh;
                display: flex;
                flex-direction: column;
                color: white;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .scroll-section {
                flex-grow: 1;
                overflow-y: auto;
                padding-bottom: 5rem;
                scrollbar-width: thin;
                scrollbar-color: #f59e0b transparent;
            }

            .scroll-section::-webkit-scrollbar {
                width: 6px;
            }

            .scroll-section::-webkit-scrollbar-thumb {
                background-color: #f59e0b;
                border-radius: 20px;
            }

            .result-card {
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.05);
                transition: all 0.3s ease;
            }

            .result-card:hover {
                transform: translateY(-2px);
                background: rgba(30, 41, 59, 0.8);
                border-color: rgba(245, 158, 11, 0.4);
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            }

            .price-tag {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .sort-select {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #94a3b8;
                font-size: 0.75rem;
                font-weight: 800;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                outline: none;
                cursor: pointer;
                transition: all 0.2s;
            }

            .sort-select:focus,
            .sort-select:active {
                background: rgba(15, 23, 42, 0.95);
                color: white;
                border-color: #f59e0b;
            }

            .sort-select option {
                background-color: #020617;
                color: white;
            }

            .sort-select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }

            .sort-select:hover {
                border-color: #f59e0b;
                color: white;
            }

        </style>

        <div class="page-container">
            <div class="w-full max-w-4xl mx-auto px-6 pt-10 pb-6">
                <header class="mb-8 text-center">
                    <h1 class="text-4xl md:text-5xl font-black italic uppercase leading-tight">
                        Comparer les <span class="text-amber-500">Prix</span>
                    </h1>
                </header>

                <form action="index.php" method="GET" class="mb-4">
                    <input type="hidden" name="action" value="rechercherPrix">
                    <div class="relative flex flex-col md:flex-row gap-4">
                        <div class="relative flex-grow">
                            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                            <input type="text" name="q" value="<?= htmlspecialchars($recherche ?? '') ?>"
                                   placeholder="Ex: Coca, Bière Blonde..."
                                   class="w-full bg-slate-900/80 border border-white/10 rounded-2xl py-5 pl-14 pr-4 focus:border-amber-500 outline-none transition-all text-lg shadow-2xl">
                        </div>
                        <button type="submit"
                                class="bg-amber-500 text-black font-black px-10 py-5 rounded-2xl hover:bg-amber-400 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-amber-500/20">
                            RECHERCHER
                        </button>
                    </div>
                </form>

                <?php if ($recherche && !empty($resultats)): ?>
                    <div class="flex items-center justify-between py-4 border-b border-white/5">
                        <div class="flex items-center gap-4">
                        <span class="bg-amber-500 text-black px-3 py-1 rounded-full text-[10px] font-black uppercase">
                            <?= count($resultats) ?> trouvé(s)
                        </span>
                            <select id="sortResults" class="sort-select">
                                <option value="default">TRIER PAR</option>
                                <option value="price-asc">Prix : Croissant</option>
                                <option value="price-desc">Prix : Décroissant</option>
                                <option value="delivery-asc">Livraison : Rapide</option>
                            </select>
                        </div>
                        <h2 class="hidden md:block text-slate-500 font-bold uppercase tracking-widest text-[10px]">
                            Recherche : <span class="text-white">"<?= htmlspecialchars($recherche) ?>"</span>
                        </h2>
                    </div>
                <?php endif; ?>
            </div>

            <div class="scroll-section px-6">
                <div id="resultsContainer" class="max-w-4xl mx-auto">
                    <?php if ($recherche && empty($resultats)): ?>
                        <div class="text-center p-20 bg-slate-900/30 rounded-3xl border border-dashed border-white/10 mt-10">
                            <i class="fa-solid fa-box-open text-5xl text-slate-800 mb-6"></i>
                            <p class="text-slate-500 text-lg">Aucun produit correspondant.</p>
                        </div>
                    <?php elseif ($recherche): ?>
                        <div class="grid gap-4 mt-4" id="resultsGrid">
                            <?php foreach ($resultats as $res): ?>
                                <div class="result-card flex items-center justify-between p-6 rounded-2xl"
                                     data-price="<?= $res['prix_achat'] ?>"
                                     data-delivery="<?= $res['delai_livraison'] ?>">
                                    <div class="flex flex-col gap-1">
                                    <span class="w-fit px-2 py-0.5 bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase rounded-md">
                                        <?= htmlspecialchars($res['type']) ?>
                                    </span>
                                        <h4 class="text-xl font-extrabold tracking-tight"><?= htmlspecialchars($res['produit_nom']) ?></h4>
                                        <p class="text-slate-400 text-sm">
                                            Vendu par : <span
                                                    class="text-slate-200 font-semibold italic"><?= htmlspecialchars($res['fournisseur_nom']) ?></span>
                                        </p>
                                    </div>

                                    <div class="text-right flex flex-col items-end gap-1">
                                        <div class="text-3xl font-black price-tag tracking-tighter">
                                            <?= number_format($res['prix_achat'], 2) ?> €
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500 text-[11px] font-bold uppercase">
                                            <i class="fa-solid fa-truck-fast"></i>
                                            <span><?= $res['delai_livraison'] ?> j</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('sortResults')?.addEventListener('change', function () {
                const grid = document.getElementById('resultsGrid');
                const cards = Array.from(grid.children);
                const sortBy = this.value;

                cards.sort((a, b) => {
                    const priceA = parseFloat(a.dataset.price);
                    const priceB = parseFloat(b.dataset.price);
                    const deliveryA = parseInt(a.dataset.delivery);
                    const deliveryB = parseInt(b.dataset.delivery);

                    if (sortBy === 'price-asc') return priceA - priceB;
                    if (sortBy === 'price-desc') return priceB - priceA;
                    if (sortBy === 'delivery-asc') return deliveryA - deliveryB;
                    return 0;
                });

                // Ré-injecter les éléments triés avec une petite animation
                grid.innerHTML = '';
                cards.forEach(card => {
                    grid.appendChild(card);
                });
            });
        </script>
        <?php
    }

    public function afficherListeCommandes($commandes)
    {
        $this->afficherNav();
        ?>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap');

            .history-container {
                background: #020617; /* Slate 950 */
                min-height: 100vh;
                font-family: 'Montserrat', sans-serif;
                color: white;
                padding: 3rem 2rem;
            }

            .order-card {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 24px;
                padding: 1.5rem 2rem;
                margin-bottom: 1.25rem;
                display: grid;
                grid-template-columns: 1.5fr 1fr 1fr 1fr 0.5fr;
                align-items: center;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .order-card:hover {
                background: rgba(255, 255, 255, 0.06);
                border-color: #f59e0b;
                transform: translateY(-2px);
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            }

            .status-pill {
                padding: 6px 14px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .status-en_attente {
                background: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
                border: 1px solid rgba(245, 158, 11, 0.2);
            }

            .status-livree {
                background: rgba(34, 197, 94, 0.1);
                color: #22c55e;
                border: 1px solid rgba(34, 197, 94, 0.2);
            }

            .status-annulee {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
                border: 1px solid rgba(239, 68, 68, 0.2);
            }

            .label-text {
                font-size: 9px;
                font-weight: 800;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                margin-bottom: 6px;
            }

            .view-btn {
                width: 45px;
                height: 45px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #f59e0b;
                transition: 0.3s;
            }

            .view-btn:hover {
                background: #f59e0b;
                color: black;
                transform: rotate(90deg);
            }
        </style>

        <div class="history-container">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <h1 class="text-5xl font-black italic uppercase tracking-tighter">Historique</h1>
                        <p class="text-amber-500 font-bold text-xs uppercase tracking-[0.4em] mt-2">Suivi des commandes
                            fournisseurs</p>
                    </div>
                    <div class="bg-white/5 px-6 py-3 rounded-2xl border border-white/10">
                        <span class="text-slate-500 font-bold text-[10px] uppercase mr-3">Total Commandes</span>
                        <span class="text-xl font-black italic"><?= count($commandes) ?></span>
                    </div>
                </div>

                <?php if (empty($commandes)): ?>
                    <div class="flex flex-col items-center justify-center py-32 bg-white/2 rounded-[40px] border border-dashed border-white/10">
                        <i class="fa-solid fa-box-open text-6xl text-slate-800 mb-6"></i>
                        <p class="text-slate-500 font-bold uppercase tracking-widest">Aucune trace de commande</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($commandes as $c):
                            $statut = $c['statut'] ?? 'en_attente';
                            $icon = ($statut == 'en_attente') ? 'fa-clock' : (($statut == 'livree') ? 'fa-check-circle' : 'fa-xmark-circle');
                            ?>
                            <div class="order-card">
                                <div>
                                    <p class="label-text">Fournisseur & Date</p>
                                    <p class="text-xl font-black uppercase italic leading-none mb-1"><?= htmlspecialchars($c['nom_fournisseur']) ?></p>
                                    <p class="text-[11px] font-bold text-slate-400 italic">Passée
                                        le <?= date('d/m/Y', strtotime($c['date_commande'])) ?></p>
                                </div>

                                <div>
                                    <p class="label-text">Référence</p>
                                    <p class="font-bold text-sm">#ORD-<?= str_pad($c['id'], 5, '0', STR_PAD_LEFT) ?></p>
                                </div>

                                <div class="text-center">
                                    <p class="label-text">État actuel</p>
                                    <span class="status-pill status-<?= $statut ?>">
                                    <i class="fa-solid <?= $icon ?>"></i>
                                    <?= str_replace('_', ' ', $statut) ?>
                                </span>
                                </div>

                                <div class="text-right pr-8">
                                    <p class="label-text">Investissement HT</p>
                                    <p class="text-2xl font-black text-amber-500 tracking-tighter"><?= number_format($c['montant_total'], 2, ',', ' ') ?>
                                        €</p>
                                </div>

                                <div class="flex justify-end">
                                    <a href="index.php?action=detailCommande&id=<?= $c['id'] ?>" class="view-btn"
                                       title="Voir les détails">
                                        <i class="fa-solid fa-plus text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public function afficherAffectationStock($associations, $reserve, $commandesFournisseurs, $historiqueAchats)
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

            body {
                background: #020617;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: #f8fafc;
            }

            .glass-card {
                background: rgba(30, 41, 59, 0.4);
                border: 1px solid rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(16px);
                border-radius: 1.5rem;
            }

            .filter-group {
                display: flex;
                gap: 8px;
                margin-bottom: 20px;
            }

            .filter-input {
                background: rgba(15, 23, 42, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: white;
                padding: 8px 12px;
                border-radius: 10px;
                font-size: 11px;
                outline: none;
                width: 100%;
            }

            .filter-input:focus {
                border-color: #3b82f6;
            }

            .hidden-item {
                display: none !important;
            }

            .view-more-btn {
                width: 100%;
                padding: 12px;
                margin-top: 15px;
                background: rgba(255, 255, 255, 0.03);
                border: 1px dashed rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                font-size: 10px;
                font-weight: 800;
                color: #64748b;
                cursor: pointer;
            }

            .status-livré {
                color: #10b981;
                background: rgba(16, 185, 129, 0.1);
                padding: 2px 8px;
                border-radius: 4px;
            }

            .status-en_attente {
                color: #f59e0b;
                background: rgba(245, 158, 11, 0.1);
                padding: 2px 8px;
                border-radius: 4px;
            }
        </style>

        <div class="max-w-[1500px] mx-auto p-6">

            <div class="mb-8 text-center lg:text-left">
                <h1 class="text-3xl font-black italic uppercase tracking-tighter">Flux <span class="text-blue-500">Logistique</span>
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="glass-card p-6 border-t-4 border-amber-500">
                    <h2 class="text-xs font-black uppercase text-amber-500 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-receipt"></i> Commandes Fournisseurs
                    </h2>

                    <div class="filter-group">
                        <input type="text" id="searchCmd" onkeyup="filterCommandes()" placeholder="Fournisseur..."
                               class="filter-input">
                        <select id="statusCmd" onchange="filterCommandes()" class="filter-input">
                            <option value="all">Statuts</option>
                            <option value="livré">Livré</option>
                            <option value="en attente">En attente</option>
                        </select>
                    </div>

                    <div id="container-commandes" class="space-y-3">
                        <?php
                        $limitC = ceil(count($commandesFournisseurs) / 2);
                        foreach ($commandesFournisseurs as $idx => $cmd):
                            $hide = ($idx >= $limitC) ? 'hidden-item' : '';
                            ?>
                            <div class="cmd-item bg-slate-900/60 p-4 rounded-xl border border-white/5 <?= $hide ?>"
                                 data-vendor="<?= strtolower($cmd['nom_fournisseur']) ?>"
                                 data-status="<?= strtolower($cmd['statut']) ?>">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[9px] font-bold text-slate-500">ID #<?= $cmd['id'] ?></span>
                                    <span class="status-<?= str_replace(' ', '_', $cmd['statut']) ?> text-[8px] font-black uppercase"><?= $cmd['statut'] ?></span>
                                </div>
                                <p class="text-xs font-bold uppercase"><?= htmlspecialchars($cmd['nom_fournisseur']) ?></p>
                                <div class="mt-3 flex justify-between items-center text-[10px]">
                                    <span class="text-slate-500"><?= date('d/m/Y', strtotime($cmd['date_commande'])) ?></span>
                                    <span class="font-black"><?= number_format($cmd['montant_total'], 2) ?> €</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button onclick="showAll('container-commandes', this)" class="view-more-btn">VOIR PLUS</button>
                </div>

                <div class="glass-card p-6 border-t-4 border-blue-500">
                    <h2 class="text-xs font-black uppercase text-blue-400 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-box"></i> Entrées de Stock
                    </h2>

                    <div class="filter-group">
                        <input type="text" id="searchHist" onkeyup="filterEntrees()" placeholder="Produit ou Source..."
                               class="filter-input">
                    </div>

                    <div id="container-historique" class="space-y-2">
                        <?php
                        $limitH = ceil(count($historiqueAchats) / 2);
                        foreach ($historiqueAchats as $idx => $h):
                            $hide = ($idx >= $limitH) ? 'hidden-item' : '';
                            ?>
                            <div class="hist-item flex justify-between items-center p-3 bg-white/[0.02] border border-white/5 rounded-xl <?= $hide ?>"
                                 data-info="<?= strtolower($h['produit'] . ' ' . $h['fournisseur']) ?>">
                                <div>
                                    <p class="text-[10px] font-black uppercase"><?= htmlspecialchars($h['produit']) ?></p>
                                    <p class="text-[8px] text-slate-500 font-bold italic"><?= htmlspecialchars($h['fournisseur']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-black text-green-500">+ <?= $h['quantite'] ?></p>
                                    <p class="text-[8px] text-slate-600 font-bold"><?= date('d/m H:i', strtotime($h['date'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button onclick="showAll('container-historique', this)" class="view-more-btn">VOIR PLUS</button>
                </div>

            </div>
        </div>

        <script>
            // Filtrage spécifique aux Commandes
            function filterCommandes() {
                const val = document.getElementById('searchCmd').value.toLowerCase();
                const status = document.getElementById('statusCmd').value.toLowerCase();

                document.querySelectorAll('.cmd-item').forEach(item => {
                    const matchVal = item.getAttribute('data-vendor').includes(val);
                    const matchStatus = (status === 'all' || item.getAttribute('data-status') === status);
                    item.style.display = (matchVal && matchStatus) ? 'block' : 'none';
                });
            }

            // Filtrage spécifique aux Entrées
            function filterEntrees() {
                const val = document.getElementById('searchHist').value.toLowerCase();
                document.querySelectorAll('.hist-item').forEach(item => {
                    item.style.display = item.getAttribute('data-info').includes(val) ? 'flex' : 'none';
                });
            }

            // Fonction Voir Plus
            function showAll(containerId, btn) {
                const container = document.getElementById(containerId);
                const hiddenItems = container.querySelectorAll('.hidden-item');

                if (btn.innerText === "VOIR PLUS") {
                    hiddenItems.forEach(item => {
                        item.classList.remove('hidden-item');
                        item.setAttribute('data-was-hidden', 'true');
                    });
                    btn.innerText = "RÉDUIRE";
                } else {
                    container.querySelectorAll('[data-was-hidden="true"]').forEach(item => {
                        item.classList.add('hidden-item');
                    });
                    btn.innerText = "VOIR PLUS";
                }
            }
        </script>
        <?php
    }

    public function afficherDetailsAssos($assos)
    {
        $this->afficherNav();
        ?>
        <style>
            @keyframes fadeInScale {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                    filter: blur(10px);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                    filter: blur(0);
                }
            }

            .anim-focus {
                animation: fadeInScale 0.7s cubic-bezier(0.19, 1, 0.22, 1) forwards;
            }

            .glass-panel {
                background: rgba(255, 255, 255, 0.02);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(245, 158, 11, 0.08);
                transition: all 0.4s ease;
            }

            .glass-panel:hover {
                border-color: rgba(245, 158, 11, 0.2);
                background: rgba(245, 158, 11, 0.02);
            }

            .amber-tag {
                background: rgba(245, 158, 11, 0.1);
                color: #fbbf24;
                border: 1px solid rgba(245, 158, 11, 0.2);
            }

            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(245, 158, 11, 0.2);
                border-radius: 10px;
            }
        </style>

        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white relative">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-amber-900/10 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="glass-panel rounded-[2.5rem] p-8 mb-10 anim-focus shadow-2xl">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-amber-500 rounded-3xl flex items-center justify-center text-3xl font-black text-[#020617] shadow-[0_0_30px_rgba(245,158,11,0.3)]">
                            <?= strtoupper(substr($assos['nom'], 0, 1)) ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">Certifiée</span>
                            </div>
                            <h1 class="text-4xl font-black text-white uppercase tracking-tighter leading-none mb-4">
                                <?= htmlspecialchars($assos['nom']) ?>
                            </h1>
                            <div class="flex flex-wrap gap-6 text-slate-400 text-xs font-bold italic">
                                <span><i class="fa-solid fa-location-dot mr-2 text-amber-500"></i><?= htmlspecialchars($assos['adresse']) ?></span>
                                <span><i class="fa-solid fa-envelope mr-2 text-amber-500"></i><?= htmlspecialchars($assos['email']) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black/40 border border-white/5 p-7 rounded-[2.2rem] text-right min-w-[240px] shadow-inner">
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] mb-2">Trésorerie
                            Actuelle</p>
                        <p class="text-3xl font-black <?= $assos['solde'] >= 0 ? 'text-amber-400' : 'text-rose-500' ?> tracking-tighter">
                            <?= number_format($assos['solde'], 2, ',', ' ') ?> <span class="text-sm">€</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="glass-panel rounded-[2.5rem] p-8 anim-focus" style="animation-delay: 0.1s;">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-layer-group text-amber-500"></i> Stock Produits
                        </h2>
                        <a href="index.php?action=voirProduits&id=<?= $assos['id'] ?>"
                           class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Inventaire
                            Complet</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-slate-500 text-[9px] uppercase font-black tracking-[0.2em] border-b border-white/5">
                            <tr>
                                <th class="pb-4">Désignation</th>
                                <th class="pb-4">Prix Unit.</th>
                                <th class="pb-4 text-right">Disponibilité</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                            <?php foreach (array_slice($assos['produits'], 0, 5) as $p): ?>
                                <tr class="group hover:bg-white/[0.02] transition-colors">
                                    <td class="py-4 font-bold text-white text-sm"><?= htmlspecialchars($p['nom']) ?></td>
                                    <td class="py-4 text-slate-400 text-xs font-bold"><?= number_format($p['prix'], 2) ?>
                                        €
                                    </td>
                                    <td class="py-4 text-right">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black <?= $p['quantiteActuelle'] < 5 ? 'bg-rose-500/10 text-rose-500' : 'bg-amber-500/5 text-amber-500' ?>">
                                        <?= $p['quantiteActuelle'] ?> UNITÉS
                                    </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="glass-panel rounded-[2.5rem] p-8 anim-focus" style="animation-delay: 0.2s;">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-bolt text-amber-500"></i> Équipe Staff
                        </h2>
                        <a href="index.php?action=voirBarmans&id=<?= $assos['id'] ?>"
                           class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Gérer</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php foreach (array_slice($assos['barmans'], 0, 4) as $b):
                            $actif = isset($b['est_barman']) && $b['est_barman']; ?>
                            <div class="flex items-center justify-between p-4 bg-white/[0.03] rounded-2xl border border-white/5 group hover:border-amber-500/40 transition-all">
                                <span class="font-bold text-white text-xs truncate mr-2"><?= htmlspecialchars($b['prenom'] . ' ' . $b['nom']) ?></span>
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $actif ? 'bg-amber-500 shadow-[0_0_8px_#f59e0b]' : 'bg-slate-700' ?>"></span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter <?= $actif ? 'text-amber-500' : 'text-slate-500' ?>">
                                    <?= $actif ? 'Online' : 'Off' ?>
                                </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="glass-panel rounded-[2.5rem] p-8 anim-focus" style="animation-delay: 0.3s;">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-address-book text-amber-500"></i> Base Clients
                        </h2>
                        <a href="index.php?action=voirListeClients&id=<?= $assos['id'] ?>"
                           class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Répertoire</a>
                    </div>
                    <div class="space-y-2">
                        <?php foreach (array_slice($assos['clients'], 0, 5) as $c): ?>
                            <div class="flex items-center justify-between p-4 bg-black/20 hover:bg-amber-500/5 rounded-2xl transition-all border border-transparent hover:border-amber-500/20 group">
                                <span class="font-bold text-white text-sm group-hover:text-amber-400"><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></span>
                                <span class="font-black text-xs <?= $c['solde'] >= 0 ? 'text-amber-400' : 'text-rose-500' ?>">
                                <?= number_format($c['solde'], 2) ?> €
                            </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="glass-panel rounded-[2.5rem] p-8 anim-focus" style="animation-delay: 0.4s;">
                    <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-8 flex items-center gap-3">
                        <i class="fa-solid fa-terminal text-amber-500"></i> Derniers Flux
                    </h2>
                    <div class="space-y-3">
                        <?php if (!empty($assos['ventes'])): ?>
                            <?php foreach (array_slice($assos['ventes'], 0, 4) as $v): ?>
                                <div class="flex items-center justify-between p-4 bg-white/[0.02] border-l-2 border-amber-500 rounded-r-2xl hover:bg-white/[0.04] transition-all">
                                    <div>
                                        <p class="text-xs font-black text-white mb-1 uppercase tracking-tight"><?= htmlspecialchars($v['client_prenom'] . ' ' . $v['client_nom']) ?></p>
                                        <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest"><?= $v['date_vente'] ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-black text-amber-400 tracking-tighter">
                                            +<?= number_format($v['montant_total'], 2) ?> €</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-10 opacity-20">
                                <i class="fa-solid fa-ghost text-3xl mb-3 block"></i>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em]">Aucun mouvement détecté</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }

    public function afficherInfoBarman($barman)
    {
        $this->afficherNav();
        if (!$barman) {
            echo "<div class='text-white p-10'>Données du personnel introuvables.</div>";
            return;
        }

        $isGest = ($barman['role'] === 'gestionnaire');
        ?>
        <style>
            .font-cyber {
                font-family: 'Montserrat', sans-serif;
            }

            .glass-panel {
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }

            .stat-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                border-color: #3b82f6;
                background: rgba(59, 130, 246, 0.05);
            }
        </style>

        <div class="min-h-screen bg-[#020617] font-cyber p-6 md:p-12">
            <div class="max-w-4xl mx-auto">

                <a href="index.php?action=afficherStaff"
                   class="inline-flex items-center gap-2 text-slate-500 hover:text-white text-[10px] font-black uppercase tracking-widest mb-8 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Retour à l'équipe
                </a>

                <div class="glass-panel rounded-[3rem] p-8 md:p-12 relative overflow-hidden mb-8">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl"></div>

                    <div class="flex flex-col md:flex-row items-center gap-10 relative z-10">
                        <div class="w-32 h-32 rounded-[2.5rem] flex items-center justify-center text-white font-black text-5xl shadow-2xl <?= $isGest ? 'bg-blue-600 shadow-blue-900/40' : 'bg-emerald-600 shadow-emerald-900/40' ?>">
                            <?= strtoupper(substr($barman['prenom'], 0, 1)) ?>
                        </div>

                        <div class="text-center md:text-left flex-1">
                            <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mb-2">
                                <h1 class="text-4xl font-black text-white uppercase italic tracking-tighter">
                                    <?= htmlspecialchars($barman['prenom'] . ' ' . $barman['nom']) ?>
                                </h1>
                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest <?= $isGest ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' ?>">
                                <?= $barman['role'] ?>
                            </span>
                            </div>
                            <p class="text-slate-400 font-bold text-sm mb-6 opacity-60 italic"><?= htmlspecialchars($barman['email']) ?></p>

                            <div class="flex flex-wrap justify-center md:justify-start gap-4">
                                <a href="index.php?action=ecrireMessage&id_dest=<?= $barman['id'] ?>"
                                   class="bg-white text-black font-black uppercase text-[10px] px-8 py-4 rounded-2xl hover:bg-blue-600 hover:text-white transition-all shadow-xl">
                                    <i class="fa-solid fa-paper-plane mr-2"></i> Envoyer un message
                                </a>
                                <?php if ($_SESSION['role'] === 'gestionnaire' && $barman['id'] != $_SESSION['id']): ?>
                                    <button class="bg-red-500/10 text-red-500 border border-red-500/20 font-black uppercase text-[10px] px-8 py-4 rounded-2xl hover:bg-red-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-user-gear mr-2"></i> Gérer les droits
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="stat-card rounded-[2.5rem] p-8">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4">
                            Crédit_Buvette</p>
                        <div class="flex items-end gap-2">
                            <span class="text-4xl font-black text-white"><?= number_format($barman['solde'], 2) ?></span>
                            <span class="text-xl font-black text-emerald-500 mb-1">€</span>
                        </div>
                    </div>

                    <div class="stat-card rounded-[2.5rem] p-8">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4">
                            Affectation_Actuelle</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                <i class="fa-solid fa-hotel text-blue-500 text-sm"></i>
                            </div>
                            <span class="text-xl font-black text-white uppercase italic tracking-tight">
                            <?= isset($barman['nom_association']) ? htmlspecialchars($barman['nom_association']) : 'Multi-Asso' ?>
                        </span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 glass-panel rounded-[2.5rem] p-8 opacity-50">
                    <h3 class="text-white font-black uppercase italic text-xs mb-6 tracking-widest">
                        <i class="fa-solid fa-clock-rotate-left mr-2 text-blue-500"></i> Activité récente
                    </h3>
                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest text-center py-10 border-2 border-dashed border-white/5 rounded-3xl">
                        Aucun log de connexion récent disponible
                    </div>
                </div>

            </div>
        </div>
        <?php
    }


}