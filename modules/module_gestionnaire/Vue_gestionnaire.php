<?php
include_once 'vue_generique.php';
include "modules/module_commun/vue_commun.php";
include "modules/module_staff/vue_staff.php";

class VueGestionnaire extends VueStaff
{
    private $nbMessages = 0;

    public function afficherProfilFournisseur($f)
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="css/profil-fournisseur.css">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap"
              rel="stylesheet">

        <div class="profile-wrapper">
            <div class="contact-card">
                <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(245, 158, 11, 0.03); border-radius: 50%; filter: blur(40px);"></div>

                <div class="profile-icon">
                    <?= strtoupper(substr($f['nom'] ?? 'F', 0, 1)) ?>
                </div>

                <h1 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-8">
                    <?= htmlspecialchars($f['nom']) ?>
                </h1>

                <div class="info-list">
                    <a href="mailto:<?= $f['email'] ?>" class="info-group">
                        <span class="info-label">Canal Email</span>
                        <span class="info-value"><?= htmlspecialchars($f['email']) ?></span>
                    </a>

                    <a href="tel:<?= $f['telephone'] ?>" class="info-group">
                        <span class="info-label">Ligne Directe</span>
                        <span class="info-value"><?= htmlspecialchars($f['telephone'] ?: 'Non répertorié') ?></span>
                    </a>
                </div>

                <a href="index.php?module=gestionnaire&action=fournisseurs" class="btn-back">
                    <i class="fa-solid fa-arrow-left-long"></i> Retour
                </a>
            </div>
        </div>
        <?php
    }

    public function afficherDetailsFournisseur($fournisseur, $produits, $assos)
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="css/reappro-detail.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <div class="reappro-wrapper" style="padding: 20px 0;">

            <div style="max-width: 1400px; margin: 0 auto 20px auto; padding: 0 20px;">
                <a href="index.php?module=gestionnaire&action=fournisseurs"
                   style="text-decoration: none; color: var(--text-dim); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; gap: 8px; transition: 0.3s;"
                   onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='var(--text-dim)'">
                    <i class="fa-solid fa-arrow-left"></i> Retour aux fournisseurs
                </a>
            </div>

            <div class="reappro-grid">
                <div class="catalog-side">
                    <div style="margin-bottom: 30px;">
                        <p style="color: var(--text-dim); font-weight: 700; letter-spacing: 2px; font-size: 0.8rem; margin: 0;">
                            RÉAPPROVISIONNEMENT POUR
                        </p>
                        <h1 style="font-family: 'Montserrat', sans-serif; font-weight: 900; font-style: italic; text-transform: uppercase; font-size: 3.2rem; color: #f59e0b; line-height: 1; margin: 5px 0;">
                            <?= htmlspecialchars($assos['nom']) ?>
                        </h1>
                        <p style="color: var(--text-dim); font-size: 0.9rem;">FOURNISSEUR : <strong><?= htmlspecialchars($fournisseur['nom']) ?></strong></p>
                    </div>

                    <input type="text" id="searchBar" onkeyup="filterCatalogue()"
                           placeholder="Chercher un produit (ex: Coca, Chips...)" class="search-input">

                    <form id="mainOrderForm" action="index.php?module=gestionnaire&action=validerReappro" method="POST">
                        <input type="hidden" name="id_fournisseur" value="<?= $fournisseur['id'] ?>">
                        <input type="hidden" name="id_association" value="<?= $assos['id'] ?>">

                        <div id="catalogList">
                            <?php foreach ($produits as $p): ?>
                                <div class="product-card" data-price="<?= $p['prix_achat'] ?>">
                                    <div>
                                        <h3 style="font-weight: 700; text-transform: uppercase; margin: 0;"><?= htmlspecialchars($p['nom']) ?></h3>
                                        <span style="font-weight: 400; color: var(--text-dim); font-size: 0.9rem;">
                                    Prix unitaire : <?= number_format($p['prix_achat'], 2) ?>€
                                </span>
                                    </div>

                                    <div class="qty-controls">
                                        <button type="button" class="qty-btn" onclick="updateQty(this, -1)">-</button>
                                        <input type="number"
                                               name="produits[<?= $p['id'] ?>][quantite]"
                                               class="qty-input" value="0" readonly>
                                        <button type="button" class="qty-btn" onclick="updateQty(this, 1)">+</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </form>
                </div>

                <div class="cart-side">
                    <div class="cart-title">
                        <i class="fa-solid fa-cart-shopping" style="color: #f59e0b; margin-right: 10px;"></i>
                        RÉSUMÉ COMMANDE
                    </div>

                    <div id="error-message" style="display: none; background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; margin-bottom: 15px; text-align: center;">
                        <i class="fa-solid fa-circle-exclamation"></i> Votre panier est vide !
                    </div>

                    <div id="cartSummaryList" style="min-height: 100px; margin-bottom: 25px; border-bottom: 1px solid #1e293b; padding-bottom: 15px;">
                        <p style="text-align:center; color:#475569; font-size:0.8rem;">Panier vide</p>
                    </div>

                    <div style="background: rgba(0,0,0,0.2); padding: 20px; border-radius: 12px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Articles</span>
                            <span id="totalQtyCount" style="font-weight: 700;">0</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items: center;">
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Total HT</span>
                            <span style="font-size: 1.6rem; font-weight: 900; color: #f59e0b;">
                        <span id="totalPriceSum">0.00</span>€
                    </span>
                        </div>
                    </div>

                    <button type="button" class="btn-validate" onclick="validerPanier()">
                        CONFIRMER L'ACHAT
                    </button>
                </div>
            </div>
        </div>

        <script>
            function validerPanier() {
                const totalArticles = parseInt(document.getElementById('totalQtyCount').innerText);
                const errorDiv = document.getElementById('error-message');

                if (totalArticles <= 0) {
                    errorDiv.style.display = 'block';
                    errorDiv.style.animation = 'shake 0.4s ease-in-out';
                    setTimeout(() => {
                        errorDiv.style.display = 'none';
                    }, 3000);
                } else {
                    document.getElementById('mainOrderForm').submit();
                }
            }
        </script>

        <style>
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        </style>

        <script src="js/reappro.js"></script>
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
                Réapprovisionnement bien passée !
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

    public function afficherProfilBarman($barmans)
{
    $this->afficherNav();
    if (!$barmans) {
        echo '<div class="text-white p-10 text-center font-black uppercase italic opacity-50">Aucune donnée trouvée pour ce profil.</div>';
        return;
    }

    $prenom = htmlspecialchars($barmans['prenom']);
    $nom = htmlspecialchars($barmans['nom']);
    $email_dest = htmlspecialchars($barmans['email']);
    $id_barman = $barmans['id'];

    $estActif = isset($barmans['actif']) && ($barmans['actif'] == 1 || $barmans['actif'] === true);

    $photoBarman = !empty($barmans['photo']) ? $barmans['photo'] : null;
    $cheminPhoto = !empty($photoBarman) ? "uploads/profiles/" . $photoBarman : null;
    ?>

    <div class="w-full h-full px-6 md:px-12 py-10">

        <div class="mb-8 flex justify-between items-center">
            <a href="index.php?action=barmans"
               class="group text-slate-500 hover:text-emerald-500 transition-all text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fa-solid fa-chevron-left transition-transform group-hover:-translate-x-1"></i> Retour à la liste
            </a>

            <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/5">
                <span class="relative flex h-2 w-2">
                    <?php if ($estActif): ?>
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    <?php else: ?>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-slate-500"></span>
                    <?php endif; ?>
                </span>
                <span class="text-[9px] font-black uppercase tracking-widest <?= $estActif ? 'text-emerald-500' : 'text-slate-500' ?>">
                    <?= $estActif ? 'Opérationnel' : 'Accès Suspendu' ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-4 space-y-6">

                <div class="bg-white/5 border border-white/5 rounded-[3rem] p-8 text-center shadow-2xl relative overflow-hidden group">
                    <div class="w-32 h-32 mx-auto rounded-[2rem] bg-gradient-to-tr from-emerald-600 to-emerald-400 p-1 mb-6 shadow-xl shadow-emerald-900/20">
                        <div class="w-full h-full rounded-[1.8rem] bg-[#020617] flex items-center justify-center overflow-hidden">
                            <?php if ($cheminPhoto && file_exists($cheminPhoto)): ?>
                                <img src="<?= $cheminPhoto ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-4xl font-black text-white"><?= strtoupper(substr($prenom, 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <h2 class="text-2xl font-black text-white uppercase italic"><?= $prenom ?> <span class="text-emerald-500"><?= $nom ?></span></h2>
                    <p class="text-slate-500 font-bold text-[9px] tracking-widest uppercase mt-1">Matricule #<?= str_pad($id_barman, 3, '0', STR_PAD_LEFT) ?></p>
                </div>

                <div class="bg-white/5 border border-white/5 rounded-[2.5rem] p-6">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-address-book text-emerald-500"></i> Coordonnées
                    </p>
                    <div class="space-y-3 text-xs text-slate-300 italic">
                        <p class="flex items-center gap-3"><i class="fa-solid fa-envelope text-emerald-500 w-4"></i> <?= $email_dest ?></p>
                        <p class="flex items-center gap-3"><i class="fa-solid fa-phone text-emerald-500 w-4"></i> <?= htmlspecialchars($barmans['tel'] ?? 'N/A') ?></p>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/5 rounded-[2.5rem] p-6 space-y-3">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-emerald-500"></i> Administration
                    </p>

                    <?php if ($estActif): ?>
                        <a href="index.php?action=toggleBarman&id=<?= $id_barman ?>"
                           class="flex items-center justify-center w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border border-amber-500/20 bg-amber-500/5 text-amber-500 hover:bg-amber-500 hover:text-white">
                            <i class="fa-solid fa-user-slash mr-2"></i> Désactiver l'accès
                        </a>
                    <?php else: ?>
                        <a href="index.php?action=toggleBarman&id=<?= $id_barman ?>"
                           class="flex items-center justify-center w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border border-emerald-500/20 bg-emerald-500/5 text-emerald-500 hover:bg-emerald-500 hover:text-white">
                            <i class="fa-solid fa-user-check mr-2"></i> Réactiver l'accès
                        </a>
                    <?php endif; ?>

                    <button onclick="openModal('index.php?action=retrograderBarman&id=<?= $id_barman ?>')"
                       class="w-full py-4 bg-red-500/5 border border-red-500/20 hover:bg-red-500 text-red-500 hover:text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                        <i class="fa-solid fa-user-minus mr-2"></i> Révoquer du Staff
                    </button>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white/5 border border-white/5 rounded-[3rem] p-10 shadow-2xl">
                    <h3 class="text-lg font-black text-white uppercase italic tracking-tighter mb-8 border-b border-white/5 pb-4">
                        Envoyer un <span class="text-emerald-500">Message Direct</span>
                    </h3>

                    <form action="index.php?action=envoyerEmailBarman" method="POST" class="space-y-6">
                        <input type="hidden" name="email_destinataire" value="<?= $email_dest ?>">
                        <input type="hidden" name="id_barman" value="<?= $id_barman ?>">

                        <div>
                            <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-4 mb-2 block">Objet</label>
                            <input type="text" name="objet" required placeholder="Ex: Briefing de service"
                                   class="w-full bg-[#020617] border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-emerald-600/50 outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-4 mb-2 block">Message</label>
                            <textarea name="message" required rows="6" placeholder="Écrivez vos instructions..."
                                      class="w-full bg-[#020617] border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-emerald-600/50 outline-none transition-all resize-none"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full bg-emerald-500 hover:bg-emerald-400 text-[#020617] font-black uppercase text-xs tracking-[0.2em] py-5 rounded-2xl transition-all shadow-xl shadow-emerald-900/40">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Transmettre le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="customAlertModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all">
        <div class="bg-[#020617] border border-white/10 w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-16 h-16 bg-red-500/10 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-red-500/20">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>

            <h3 class="text-xl font-black text-white text-center uppercase italic mb-2">Attention</h3>
            <p class="text-slate-400 text-center text-sm font-medium leading-relaxed mb-8">
                Voulez-vous vraiment révoquer les droits de barman ? L'utilisateur redeviendra simple client et perdra ses accès au staff.
            </p>

            <div class="flex gap-3">
                <button onclick="closeModal()" class="flex-1 py-4 bg-white/5 hover:bg-white/10 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-2xl transition-all border border-white/5">
                    Annuler
                </button>
                <a id="confirmActionBtn" href="#" class="flex-1 py-4 bg-red-500 hover:bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl text-center shadow-lg shadow-red-500/20 transition-all">
                    Confirmer
                </a>
            </div>
        </div>
    </div>

    <script>
        function openModal(url) {
            const modal = document.getElementById('customAlertModal');
            const confirmBtn = document.getElementById('confirmActionBtn');
            confirmBtn.href = url;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('customAlertModal');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }
    </script>
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

           
           <a href="index.php?reset=1" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . $inactiveClass . '">
    <i class="fa-solid fa-right-left text-lg"></i>
    <span class="font-bold text-sm tracking-tight">Changer Association</span>
</a>
            <a href="index.php?action=barmans" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'barmans' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-user-group text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Équipe Barmans</span>
            </a>

            <a href="index.php?action=fournisseurs" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'fournisseurs' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-truck-fast text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Réapprovisionnement</span>
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

            /* État désactivé amélioré */
            .member-card.is-inactive {
                opacity: 0.6;
                filter: grayscale(0.5);
                border-color: rgba(239, 68, 68, 0.3) !important;
                background: rgba(239, 68, 68, 0.02);
            }

            .member-card.is-inactive:hover {
                opacity: 0.9;
                filter: grayscale(0);
                border-color: #ef4444 !important;
            }

            .dot-offline {
                background: #ef4444 !important;
                box-shadow: 0 0 10px rgba(239, 68, 68, 0.5) !important;
            }

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

            .tabs-container {
                display: flex;
                gap: 2rem;
                margin: 1.5rem 0 0.5rem 1rem;
            }

            .tab-btn {
                background: transparent;
                border: none;
                color: #475569;
                font-size: 10px;
                font-weight: 900;
                text-transform: uppercase;
                cursor: pointer;
                padding-bottom: 0.5rem;
                border-bottom: 2px solid transparent;
                transition: all 0.3s ease;
            }

            .tab-btn.active {
                color: #10b981;
                border-bottom: 2px solid #10b981;
            }

            #barmanList {
                flex-grow: 1;
                overflow-y: auto;
                padding-right: 15px;
                padding-bottom: 3rem;
                margin-top: 1rem;
            }

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
                color: #475569;
                background: rgba(255,255,255,0.05);
                border-radius: 8px;
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
                height: 8px;
                border-radius: 50%;
            }

            .dot-online { background: #10b981; box-shadow: 0 0 12px #10b981; }
            .dot-offline { background: #475569; }

            .btn-view {
                background: #10b981;
                color: #020617;
                padding: 10px 20px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 900;
                text-transform: uppercase;
                transition: 0.4s;
            }

            #barmanList::-webkit-scrollbar { width: 6px; }
            #barmanList::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.2); border-radius: 10px; }
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
                    <a href="index.php?module=gestionnaire&action=ajouterBarman"
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
                        <?php if(is_array($associations)): foreach ($associations as $asso): ?>
                            <option value="<?= htmlspecialchars($asso['nom']) ?>">
                                <?= htmlspecialchars($asso['nom']) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="tabs-container">
                    <button class="tab-btn active" data-status="all">Tous les membres</button>
                    <button class="tab-btn" data-status="active">En service</button>
                    <button class="tab-btn" data-status="inactive">Inactifs</button>
                </div>
            </div>

            <div id="barmanList">
                <?php foreach ($barmans as $barman):
                    // Correction ici : On s'assure que le statut est bien lu
                    $isActif = (isset($barman['actif']) && (int)$barman['actif'] === 1);
                    $nomAsso = !empty($barman['nom_association']) ? $barman['nom_association'] : 'Non assigné';
                    ?>

                    <a href="index.php?module=gestionnaire&action=voirProfilBarman&id=<?= $barman['id'] ?>"
                       class="member-card barman-row <?= !$isActif ? 'is-inactive' : '' ?>"
                       data-asso="<?= htmlspecialchars($nomAsso) ?>"
                       data-active="<?= $isActif ? 'true' : 'false' ?>">

                        <div style="display: flex; align-items: center;">
                        <span class="id-pill">
                            ID-<?= str_pad($barman['id'], 3, '0', STR_PAD_LEFT) ?>
                        </span>
                        </div>

                        <div class="flex items-center gap-5">
                            <div class="avatar-box <?= !$isActif ? 'bg-slate-800' : '' ?>">
                                <?= strtoupper(substr($barman['prenom'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-white uppercase leading-none mb-1">
                                    <?= htmlspecialchars(($barman['prenom'] ?? '') . ' ' . ($barman['nom'] ?? '')) ?>
                                </h3>
                                <p class="text-[10px] text-emerald-500/40 font-bold lowercase">
                                    <?= htmlspecialchars($barman['email'] ?? 'pas d\'email') ?>
                                </p>
                            </div>
                        </div>

                        <div class="status-tag <?= $isActif ? 'text-emerald-400' : 'text-red-500' ?>">
                            <span class="dot <?= $isActif ? 'dot-online' : 'dot-offline' ?>"></span>
                            <?php if ($isActif): ?>
                                <i class="fa-solid fa-check-circle mr-1"></i> En service
                            <?php else: ?>
                                <i class="fa-solid fa-circle-xmark mr-1"></i> Accès Révoqué
                            <?php endif; ?>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-[9px] font-black <?= $isActif ? 'text-emerald-500/50' : 'text-red-500/50' ?> uppercase mb-1">Rattachement</span>
                            <span class="text-[11px] font-extrabold text-white uppercase tracking-tighter">
                            <?= htmlspecialchars($nomAsso) ?>
                        </span>
                        </div>

                        <div class="text-right">
                        <span class="btn-view <?= !$isActif ? 'bg-slate-700 text-slate-400' : '' ?>">
                            <?= $isActif ? 'Consulter' : 'Gérer' ?>
                        </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,800;1,800&display=swap"
              rel="stylesheet">
        <link rel="stylesheet" href="css/reappro.css">

        <div class="fixed-top-section" style="background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(245, 158, 11, 0.1); padding: 15px 0; position: sticky; top: 0; z-index: 1000;">
            <div class="max-w-[1600px] mx-auto px-10 flex justify-between items-center gap-6">

                <div class="flex items-center gap-4">
                    <div style="background: linear-gradient(135deg, #f59e0b, #fbbf24); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #000; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
                        <i class="fa-solid fa-truck-fast text-xl"></i>
                    </div>
                    <div>
                        <h1 style="font-family: 'Montserrat', sans-serif; font-weight: 900; font-style: italic;" class="text-2xl text-white uppercase m-0 leading-none tracking-tighter">
                            RÉAPPRO<span style="color: #f59e0b;">.</span>
                        </h1>
                        <span class="text-[10px] text-amber-500/80 font-black tracking-[0.2em] uppercase">Logistique & Stocks</span>
                    </div>
                </div>

                <div class="global-selector-bar" id="selectorContainer" style="flex: 0 1 500px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05); border-radius: 14px; padding: 6px 15px; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease;">
                    <i class="fa-solid fa-store text-amber-500"></i>
                    <div class="search-box" style="flex:1; position:relative;">
                        <input type="text" id="mainAssoInput"
                               style="background: transparent; border: none; color: white; width: 100%; outline: none; font-weight: 600; font-size: 0.95rem;"
                               placeholder="Saisir le nom d'une association..."
                               autocomplete="off">

                        <div id="mainAssoResults" class="search-results shadow-2xl" style="position: absolute; top: 120%; left: -15px; right: -15px; background: #1e293b; border-radius: 12px; border: 1px solid rgba(245, 158, 11, 0.2); overflow: hidden; display: none; z-index: 1001;">
                            <?php foreach ($associations as $asso): ?>
                                <div class="result-item" data-id="<?= $asso['id'] ?>" style="padding: 12px 18px; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; transition: background 0.2s;">
                                    <div class="flex flex-col">
                                        <strong class="text-white text-sm uppercase"><?= htmlspecialchars($asso['nom']) ?></strong>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">ID: #<?= $asso['id'] ?></span>
                                    </div>
                                    <span style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 800;">
                                <?= number_format($asso['solde'], 2) ?>€
                            </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-500"></i>
                </div>

                <div class="flex items-center gap-3">
                    <a href="index.php?module=gestionnaire&action=rechercherPrix" class="flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-xs uppercase transition-all"
                       style="background: #f59e0b; color: black; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);">
                        <i class="fa-solid fa-magnifying-glass-chart"></i> Comparateur
                    </a>
                    <a href="index.php?module=gestionnaire&action=mesCommandes" class="flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-xs uppercase transition-all"
                       style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white;">
                        <i class="fa-solid fa-clock-rotate-left text-amber-500"></i> Historique
                    </a>
                </div>
            </div>
        </div>

        <style>
            .result-item:hover {
                background: rgba(245, 158, 11, 0.1) !important;
            }
            #selectorContainer:focus-within {
                border-color: #f59e0b !important;
                background: rgba(0,0,0,0.5) !important;
                box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
            }
        </style>

        <div class="supplier-grid">
            <?php foreach ($fournisseurs as $f): ?>
                <div class="supplier-card">
                <span class="status-badge" id="badge-<?= $f['id'] ?>">
                    <i class="fa-solid fa-lock text-slate-500"></i> Sélectionner asso
                </span>
                    <h2 class="text-2xl font-black text-white uppercase mb-4"><?= htmlspecialchars($f['nom']) ?></h2>

                    <a href="javascript:void(0)"
                       id="btn-cat-<?= $f['id'] ?>"
                       data-base-url="index.php?module=gestionnaire&action=voirFournisseur&id=<?= $f['id'] ?>"
                       class="catalog-btn">
                        <i class="fa-solid fa-cart-flatbed"></i> Ouvrir Catalogue
                    </a>

                    <div class="action-grid"
                         style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                        <a href="index.php?module=gestionnaire&action=contacterFournisseur&id=<?= $f['id'] ?>"
                           class="btn-sub btn-contact-full">
                            <i class="fa-solid fa-address-card"></i> Contacter
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <script src="js/recherche_assos.js"></script>
        <?php
    }

    public function afficherRechercheGlobale($resultats, $recherche)
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="css/comparateur.css">

        <div id="searchProgress" class="search-progress-container">
            <div id="searchProgressBar" class="search-progress-bar"></div>
        </div>

        <div class="page-container">

            <div class="w-full max-w-5xl mx-auto px-6 pt-6">
                <div class="flex justify-between items-start mb-6">
                    <a href="index.php?module=gestionnaire&action=fournisseurs"
                       class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all"
                       style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;"
                       onmouseover="this.style.background='rgba(245,158,11,0.1)'; this.style.borderColor='#f59e0b';"
                       onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)';">
                        <i class="fa-solid fa-arrow-left text-amber-500"></i> Fournisseurs
                    </a>

                    <div class="hidden md:flex gap-4">
                        <div class="instruction-step">
                            <i class="fa-solid fa-1"></i>
                            <span>Saisissez un produit</span>
                        </div>
                        <div class="instruction-step">
                            <i class="fa-solid fa-2"></i>
                            <span>Comparez les offres</span>
                        </div>
                    </div>
                </div>

                <header class="mb-8 text-center">
                    <h1 class="text-4xl md:text-6xl font-black italic uppercase tracking-tighter">
                        COMPARATEUR
                    </h1>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-[0.3em] mt-2">Analysez les tarifs en temps réel</p>
                </header>

                <form id="searchForm" action="index.php" method="GET" class="mb-4">
                    <input type="hidden" name="module" value="gestionnaire">
                    <input type="hidden" name="action" value="rechercherPrix">
                    <div class="relative flex flex-col md:flex-row gap-4">
                        <div class="relative flex-grow">
                            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                            <input type="text" name="q" value="<?= htmlspecialchars($recherche ?? '') ?>" required
                                   placeholder="Ex: Coca, Chips, Bière..."
                                   class="w-full bg-slate-900/80 border border-white/10 rounded-2xl py-5 pl-14 pr-4 focus:border-amber-500 outline-none transition-all text-lg shadow-2xl text-white">
                        </div>
                        <button type="submit"
                                class="bg-amber-500 text-black font-black px-10 py-5 rounded-2xl hover:bg-amber-400 hover:scale-[1.02] transition-all shadow-lg shadow-amber-500/20 uppercase tracking-widest">
                            Comparer
                        </button>
                    </div>
                </form>

                <?php if (!empty($recherche) && !empty($resultats)): ?>
                    <div class="flex items-center justify-between py-4 border-b border-white/5 bg-slate-900/20 px-4 rounded-t-2xl">
                        <div class="flex items-center gap-4">
                            <select id="sortResults" class="sort-select">
                                <option value="default">TRIER PAR DÉFAUT</option>
                                <option value="price-asc">PRIX : MOINS CHER</option>
                                <option value="price-desc">PRIX : PLUS CHER</option>
                                <option value="delivery-asc">LIVRAISON : RAPIDE</option>
                            </select>
                        </div>
                        <span class="text-[10px] font-black uppercase text-amber-500 bg-amber-500/10 px-3 py-1 rounded-lg">
                        <?= count($resultats) ?> offres trouvées
                    </span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="scroll-section px-6">
                <div id="resultsContainer" class="max-w-5xl mx-auto">

                    <?php if (empty($recherche)): ?>
                        <div class="text-center p-20 mt-10" style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(255,255,255,0.05); border-radius: 40px;">
                            <i class="fa-solid fa-keyboard text-5xl text-slate-800 mb-6"></i>
                            <h3 class="text-xl font-bold text-slate-400 uppercase tracking-widest">En attente de recherche</h3>
                            <p class="text-slate-600 mt-2">Utilisez la barre ci-dessus pour comparer les prix de vos fournisseurs.</p>
                        </div>

                    <?php elseif (empty($resultats)): ?>
                        <div class="text-center p-20 bg-slate-900/30 rounded-3xl border border-dashed border-white/10 mt-10">
                            <i class="fa-solid fa-triangle-exclamation text-5xl text-amber-500/20 mb-6"></i>
                            <p class="text-slate-400 font-bold uppercase tracking-widest">Aucune offre trouvée pour "<?= htmlspecialchars($recherche) ?>"</p>
                        </div>

                    <?php else: ?>
                        <div class="grid gap-3 mt-4" id="resultsGrid">
                            <?php foreach ($resultats as $index => $res): ?>
                                <div class="result-card flex items-center justify-between p-6 rounded-2xl"
                                     style="animation-delay: <?= $index * 0.05 ?>s"
                                     data-price="<?= $res['prix_achat'] ?>"
                                     data-delivery="<?= $res['delai_livraison'] ?>">

                                    <div class="flex flex-col gap-1">
                                    <span class="w-fit px-2 py-0.5 bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase rounded-md">
                                        <?= htmlspecialchars($res['type'] ?? 'Produit') ?>
                                    </span>
                                        <h4 class="text-xl font-extrabold tracking-tight text-white">
                                            <?= htmlspecialchars($res['produit_nom']) ?>
                                        </h4>
                                        <p class="text-slate-400 text-sm">
                                            Vendu par : <span class="text-slate-200 font-semibold italic"><?= htmlspecialchars($res['fournisseur_nom']) ?></span>
                                        </p>
                                    </div>

                                    <div class="text-right flex flex-col items-end gap-1">
                                        <div class="text-3xl font-black price-tag tracking-tighter">
                                            <?= number_format($res['prix_achat'], 2) ?> €
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500 text-[11px] font-bold uppercase">
                                            <i class="fa-solid fa-truck-fast text-amber-500/50"></i>
                                            <span><?= $res['delai_livraison'] ?> jours</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <script src="js/comparateur.js"></script>
        <?php
    }
    public function afficherListeCommandes($commandes)
    {
        $this->afficherNav();
        ?>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap');

            .history-container {
                background: #020617;
                background-image: radial-gradient(circle at 0% 0%, #0f172a 0%, #020617 50%);
                min-height: 100vh;
                font-family: 'Montserrat', sans-serif;
                color: white;
                padding: 2rem 2rem 5rem;
            }

            .back-nav {
                display: flex;
                align-items: center;
                margin-bottom: 2rem;
            }

            .back-btn {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 10px 20px;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                color: #94a3b8;
                text-decoration: none;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: all 0.3s ease;
            }

            .back-btn:hover {
                background: rgba(245, 158, 11, 0.1);
                border-color: #f59e0b;
                color: #f59e0b;
                transform: translateX(-5px);
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

                <div class="back-nav">
                    <a href="index.php?module=gestionnaire&action=fournisseurs" class="back-btn">
                        <i class="fa-solid fa-arrow-left"></i> Retour aux Fournisseurs
                    </a>
                </div>

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
                                    <a href="index.php?module=gestionnaire&action=detailsCommande&id=<?= $c['id'] ?>" class="view-btn" title="Voir les détails">
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

 public function afficherAffectationStock($associations, $reserve, $commandesFournisseurs, $historiqueAchats, $historiqueInventaires = [])
{
    $this->afficherNav();
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

        body {
            background: #020617 radial-gradient(circle at 50% -20%, #1e1b4b 0%, #020617 80%) no-repeat fixed;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #f8fafc;
        }

        .main-container { max-width: 1100px; margin: 0 auto; padding: 40px 20px; }

        /* Navigation par onglets */
        .tabs-nav {
            display: flex; justify-content: center; gap: 12px; margin-bottom: 40px;
            background: rgba(255, 255, 255, 0.03); padding: 8px; border-radius: 20px;
            width: fit-content; margin-left: auto; margin-right: auto;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .tab-btn {
            padding: 12px 28px; border-radius: 14px; font-size: 11px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: #64748b;
        }

        .tab-btn.active { background: #f8fafc; color: #020617; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3); }

        .tab-content { display: none; animation: slideUp 0.4s ease-out; }
        .tab-content.active { display: block; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px); border-radius: 32px; padding: 40px;
        }

        .item-card {
            background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 18px; padding: 20px; transition: all 0.2s;
        }

        .filter-input {
            background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1);
            color: white; padding: 12px 20px; border-radius: 14px; width: 100%; max-width: 400px; outline: none;
        }

        .status-badge { font-size: 9px; font-weight: 900; padding: 5px 12px; border-radius: 10px; text-transform: uppercase; }
        .status-livré { background: rgba(16, 185, 129, 0.15); color: #10b981; }
        .status-en_attente { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    </style>

    <div class="main-container">
        <div class="text-center mb-12">
            <h1 class="text-6xl font-black italic uppercase tracking-tighter leading-none mb-4">
                Flux <span class="text-blue-500">Logistique</span>
            </h1>
            <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-[10px] mb-8">Contrôle des stocks & flux entrants</p>

            <?php if (!empty($associations)): ?>
                <a href="index.php?action=faireInventaire&id=<?= htmlspecialchars($associations['id']) ?>"
                   class="inline-flex items-center gap-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-10 py-5 rounded-2xl font-black text-xs uppercase transition-all shadow-xl shadow-emerald-500/20">
                    <i class="fa-solid fa-clipboard-check text-lg"></i>
                    Lancer un inventaire
                </a>
            <?php else: ?>
                <div class="inline-block bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-3 rounded-xl text-xs font-bold uppercase">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                    Aucune association liée à votre compte
                </div>
            <?php endif; ?>
        </div>

        <div class="tabs-nav">
            <div class="tab-btn active" onclick="switchTab(event, 'tab-commandes')">Commandes</div>
            <div class="tab-btn" onclick="switchTab(event, 'tab-entrees')">Entrées</div>
            <div class="tab-btn" onclick="switchTab(event, 'tab-inventaires')">Inventaires</div>
        </div>

        <div class="glass-card">
            <div id="tab-commandes" class="tab-content active">
                <div class="flex flex-col items-center mb-10">
                    <h2 class="text-sm font-black uppercase tracking-widest text-amber-500 mb-6 italic">Suivi des achats fournisseurs</h2>
                    <input type="text" id="searchCmd" onkeyup="filterLocal('searchCmd', 'card-cmd', 'data-info')"
                           placeholder="Rechercher un fournisseur..." class="filter-input text-center">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($commandesFournisseurs as $cmd): ?>
                        <div class="card-cmd item-card" data-info="<?= strtolower($cmd['nom_fournisseur']) ?>">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-[9px] font-black text-slate-500 tracking-widest uppercase">Réf #<?= $cmd['id'] ?></span>
                                <span class="status-badge status-<?= str_replace(' ', '_', strtolower($cmd['statut'])) ?>"><?= $cmd['statut'] ?></span>
                            </div>
                            <p class="text-lg font-black uppercase text-white"><?= htmlspecialchars($cmd['nom_fournisseur']) ?></p>
                            <div class="flex justify-between items-end mt-6 border-t border-white/5 pt-4">
                                <span class="text-[11px] text-slate-500 font-bold"><?= date('d/m/Y', strtotime($cmd['date_commande'])) ?></span>
                                <span class="text-xl font-black text-white"><?= number_format($cmd['montant_total'], 2) ?> €</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="tab-entrees" class="tab-content">
                <div class="flex flex-col items-center mb-10">
                    <h2 class="text-sm font-black uppercase tracking-widest text-blue-400 mb-6 italic">Historique des arrivages</h2>
                    <input type="text" id="searchHist" onkeyup="filterLocal('searchHist', 'card-hist', 'data-info')"
                           placeholder="Filtrer par produit..." class="filter-input text-center">
                </div>
                <div class="max-w-2xl mx-auto space-y-3">
                    <?php foreach ($historiqueAchats as $h): ?>
                        <div class="card-hist item-card flex justify-between items-center" data-info="<?= strtolower($h['produit']) ?>">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                    <i class="fa-solid fa-plus-circle"></i>
                                </div>
                                <div>
                                    <p class="font-black uppercase text-sm text-white"><?= htmlspecialchars($h['produit']) ?></p>
                                    <p class="text-[9px] text-slate-500 font-bold uppercase"><?= htmlspecialchars($h['fournisseur']) ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-black text-emerald-500">+<?= $h['quantite'] ?></p>
                                <p class="text-[9px] text-slate-600 font-bold uppercase italic"><?= date('d/m H:i', strtotime($h['date'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="tab-inventaires" class="tab-content">
                <div class="text-center mb-10">
                    <h2 class="text-sm font-black uppercase tracking-widest text-emerald-400 italic">Rapports d'inventaire</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php if(empty($historiqueInventaires)): ?>
                        <div class="col-span-full text-center py-20 opacity-30">
                            <i class="fa-solid fa-folder-open text-4xl mb-4"></i>
                            <p class="uppercase font-black text-xs tracking-widest">Aucune donnée enregistrée</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($historiqueInventaires as $inv):
                            $diff = $inv['quantite_trouvee'] - $inv['quantite_theorique'];
                        ?>
                            <div class="item-card border-l-4 <?= $diff < 0 ? 'border-red-500' : 'border-emerald-500' ?>">
                                <div class="flex justify-between items-start mb-4">
                                    <p class="font-black uppercase text-sm"><?= htmlspecialchars($inv['produit']) ?></p>
                                    <span class="text-[9px] font-bold text-slate-600"><?= date('d/m/Y', strtotime($inv['date'])) ?></span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-center bg-black/30 rounded-xl p-4">
                                    <div><p class="text-[7px] font-black text-slate-500 mb-1">LOGICIEL</p><p class="text-sm font-bold"><?= $inv['quantite_theorique'] ?></p></div>
                                    <div><p class="text-[7px] font-black text-slate-500 mb-1">RÉEL</p><p class="text-sm font-bold text-white"><?= $inv['quantite_trouvee'] ?></p></div>
                                    <div><p class="text-[7px] font-black text-slate-500 mb-1">ÉCART</p><p class="text-sm font-black <?= $diff < 0 ? 'text-red-500' : 'text-emerald-400' ?>"><?= ($diff > 0 ? '+' : '') . $diff ?></p></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(evt, tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }

        function filterLocal(inputId, className, attr) {
            const val = document.getElementById(inputId).value.toLowerCase();
            document.querySelectorAll('.' + className).forEach(el => {
                el.style.display = el.getAttribute(attr).includes(val) ? '' : 'none';
            });
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
                from { opacity: 0; transform: scale(0.95); filter: blur(10px); }
                to { opacity: 1; transform: scale(1); filter: blur(0); }
            }

            .anim-focus { animation: fadeInScale 0.7s cubic-bezier(0.19, 1, 0.22, 1) forwards; }

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
        </style>

        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white relative">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-amber-900/10 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="glass-panel rounded-[2.5rem] p-8 mb-10 anim-focus shadow-2xl">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-amber-500 rounded-3xl flex items-center justify-center text-3xl font-black text-[#020617] shadow-[0_0_30px_rgba(245,158,11,0.3)]">
                            <?= strtoupper(substr($assos['nom'] ?? 'A', 0, 1)) ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">Certifiée</span>
                            </div>
                            <h1 class="text-4xl font-black text-white uppercase tracking-tighter leading-none mb-4">
                                <?= htmlspecialchars($assos['nom'] ?? 'Sans Nom') ?>
                            </h1>
                            <div class="flex flex-wrap gap-6 text-slate-400 text-xs font-bold italic">
                                <span><i class="fa-solid fa-location-dot mr-2 text-amber-500"></i><?= htmlspecialchars($assos['adresse'] ?? 'N/C') ?></span>
                                <span><i class="fa-solid fa-envelope mr-2 text-amber-500"></i><?= htmlspecialchars($assos['email'] ?? 'N/C') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black/40 border border-white/5 p-7 rounded-[2.2rem] text-right min-w-[240px] shadow-inner">
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] mb-2">Trésorerie Actuelle</p>
                        <p class="text-3xl font-black <?= ($assos['solde'] ?? 0) >= 0 ? 'text-amber-400' : 'text-rose-500' ?> tracking-tighter">
                            <?= number_format($assos['solde'] ?? 0, 2, ',', ' ') ?> <span class="text-sm">€</span>
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
                        <a href="index.php?action=voirProduits&id=<?= $assos['id'] ?>" class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Inventaire Complet</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-white/5">
                            <?php if(!empty($assos['produits'])): ?>
                                <?php foreach (array_slice($assos['produits'], 0, 5) as $p): ?>
                                    <tr class="group hover:bg-white/[0.02] transition-colors">
                                        <td class="py-4 font-bold text-white text-sm"><?= htmlspecialchars($p['nom']) ?></td>
                                        <td class="py-4 text-slate-400 text-xs font-bold"><?= number_format($p['prix'] ?? 0, 2, ',', ' ') ?> €</td>
                                        <td class="py-4 text-right">
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black <?= ($p['quantiteActuelle'] ?? 0) < 5 ? 'bg-rose-500/10 text-rose-500' : 'bg-amber-500/5 text-amber-500' ?>">
                                            <?= $p['quantiteActuelle'] ?? 0 ?> UNITÉS
                                        </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="py-10 text-center text-slate-500 text-[10px] font-black uppercase">Aucun produit</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="glass-panel rounded-[2.5rem] p-8 anim-focus" style="animation-delay: 0.2s;">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-bolt text-amber-500"></i> Équipe Staff
                        </h2>
                        <a href="index.php?action=voirBarmans&id=<?= $assos['id'] ?>" class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Gérer</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php if(!empty($assos['barmans'])): ?>
                            <?php foreach (array_slice($assos['barmans'], 0, 4) as $b):
                                // CORRECTION : On utilise 'actif' comme dans l'autre vue et on force le type Int
                                $actifVal = isset($b['actif']) ? (int)$b['actif'] : 0;
                                $isActif = ($actifVal === 1);
                                ?>
                                <div class="flex items-center justify-between p-4 bg-white/[0.03] rounded-2xl border border-white/5 group hover:border-amber-500/40 transition-all">
                                <span class="font-bold text-white text-xs truncate mr-2">
                                    <?= htmlspecialchars(($b['prenom'] ?? '') . ' ' . ($b['nom'] ?? '')) ?>
                                </span>
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $isActif ? 'bg-amber-500 shadow-[0_0_8px_#f59e0b]' : 'bg-slate-700' ?>"></span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter <?= $isActif ? 'text-amber-500' : 'text-slate-500' ?>">
                                        <?= $isActif ? 'Actif' : 'Off' ?>
                                    </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="col-span-2 text-center py-10 text-slate-500 text-[10px] font-black uppercase">Aucun staff membre</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="glass-panel rounded-[2.5rem] p-8 anim-focus" style="animation-delay: 0.3s;">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-address-book text-amber-500"></i> Base Clients
                        </h2>
                        <a href="index.php?action=voirListeClients&id=<?= $assos['id'] ?>" class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Répertoire</a>
                    </div>
                    <div class="space-y-2">
                        <?php if(!empty($assos['clients'])): ?>
                            <?php foreach (array_slice($assos['clients'], 0, 5) as $c): ?>
                                <div class="flex items-center justify-between p-4 bg-black/20 hover:bg-amber-500/5 rounded-2xl transition-all border border-transparent hover:border-amber-500/20 group">
                                    <span class="font-bold text-white text-sm group-hover:text-amber-400"><?= htmlspecialchars(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? '')) ?></span>
                                    <span class="font-black text-xs <?= ($c['solde'] ?? 0) >= 0 ? 'text-amber-400' : 'text-rose-500' ?>">
                                    <?= number_format($c['solde'] ?? 0, 2, ',', ' ') ?> €
                                </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}