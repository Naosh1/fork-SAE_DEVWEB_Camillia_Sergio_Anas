<?php
include_once 'vue_generique.php';
include "modules/module_commun/vue_commun.php";
include "modules/module_staff/vue_staff.php";

class VueGestionnaire extends VueStaff
{
    private $nbMessages = 0;

    public function afficherFormulaireDemande() {
        ?>
        <div class="max-w-4xl mx-auto p-8 bg-white/5 border border-white/10 rounded-[3rem] shadow-2xl mt-10">
            <h1 class="text-4xl font-black text-white italic mb-8 uppercase tracking-tighter">Créer une Association</h1>

            <form id="formDemandeAsso" action="index.php?module=gestionnaire&action=envoyerDemande" method="POST" enctype="multipart/form-data" class="space-y-8">

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest ml-4">Nom de l'association</label>
                    <input type="text" name="nom_association" required minlength="3" placeholder="Ex: BDE Informatique"
                           class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php
                    $files = [
                            'pdf_identite' => 'Carte d\'identité',
                            'pdf_pv' => 'Procès-Verbal',
                            'pdf_ago' => 'Statuts / AGO'
                    ];
                    foreach ($files as $name => $label): ?>
                        <div class="relative group">
                            <label class="block p-6 bg-black/40 border-2 border-dashed border-white/10 rounded-3xl hover:border-emerald-500/50 transition-all cursor-pointer text-center">
                                <input type="file" name="<?= $name ?>" id="<?= $name ?>" accept=".pdf" required class="hidden" onchange="updateFileName('<?= $name ?>')">
                                <i class="fa-solid fa-file-pdf text-3xl text-white/20 group-hover:text-emerald-500 mb-3 block"></i>
                                <span class="text-[10px] font-black text-white/40 uppercase block mb-1"><?= $label ?></span>
                                <span id="label_<?= $name ?>" class="text-xs text-emerald-500 font-bold truncate block">Choisir un PDF</span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" id="btnEnvoyer"
                        class="w-full bg-emerald-500 hover:bg-emerald-400 text-black font-black py-6 rounded-2xl uppercase tracking-widest shadow-xl shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-95">
                    <span id="btnText">Envoyer le dossier</span>
                </button>
            </form>
        </div>

        <script>
            function updateFileName(id) {
                const input = document.getElementById(id);
                const label = document.getElementById('label_' + id);
                if (input.files.length > 0) {
                    label.innerText = input.files[0].name;
                    label.classList.replace('text-emerald-500', 'text-white');
                }
            }

            document.getElementById('formDemandeAsso').onsubmit = function() {
                const btn = document.getElementById('btnEnvoyer');
                const txt = document.getElementById('btnText');

                btn.disabled = true;
                btn.style.opacity = "0.7";
                btn.style.cursor = "not-allowed";
                txt.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Envoi en cours...';
            };
        </script>
        <?php
    }

    public function afficherDemandeEnCours($demandes = []) {
        $this->afficherNav();
        ?>
        <div class="max-w-5xl mx-auto p-8 mt-10">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h1 class="text-4xl font-black text-white italic uppercase tracking-tighter">Suivi de mes dossiers</h1>
                    <p class="text-slate-500 text-sm">Vous pouvez soumettre plusieurs demandes d'association.</p>
                </div>

                <a href="index.php?module=gestionnaire&action=demanderCreationAsso&nouveau=1"
                   class="bg-emerald-500 hover:bg-emerald-400 text-black px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg shadow-emerald-500/20">
                    + Nouvelle demande
                </a>
            </div>

            <?php if (empty($demandes)): ?>
                <div class="bg-white/5 border border-dashed border-white/10 rounded-[3rem] p-20 text-center">
                    <i class="fa-solid fa-folder-open text-white/10 text-6xl mb-6"></i>
                    <p class="text-slate-400 font-bold">Aucun dossier envoyé.</p>
                </div>
            <?php else: ?>
                <div class="grid gap-6">
                    <?php foreach ($demandes as $d):
                        $statut = $d['statut'] ?? 'en_attente';
                        $config = [
                                'en_attente' => ['label' => 'En cours', 'class' => 'bg-amber-500/10 text-amber-500 border-amber-500/20', 'icon' => 'fa-hourglass-half'],
                                'validee'    => ['label' => 'Acceptée', 'class' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20', 'icon' => 'fa-check-circle'],
                                'refusee'    => ['label' => 'Refusée', 'class' => 'bg-rose-500/10 text-rose-500 border-rose-500/20', 'icon' => 'fa-times-circle']
                        ];
                        $current = $config[$statut] ?? $config['en_attente'];
                        ?>
                        <div class="bg-white/5 border border-white/10 rounded-[2rem] p-6 flex items-center justify-between hover:bg-white/[0.08] transition-all">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 bg-black/40 rounded-2xl flex items-center justify-center text-emerald-500">
                                    <i class="fa-solid fa-building text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-white uppercase italic"><?= htmlspecialchars((string)$d['nom_association']) ?></h3>
                                    <p class="text-xs text-slate-500">Envoyé le <?= date('d/m/Y à H:i', strtotime($d['date_soumission'])) ?></p>
                                </div>
                            </div>
                            <span class="px-5 py-2 rounded-full border text-[10px] font-black uppercase tracking-widest <?= $current['class'] ?>">
                        <i class="fa-solid <?= $current['icon'] ?> mr-2"></i> <?= $current['label'] ?>
                    </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
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

        $nomAsso = (is_array($assos) && isset($assos['nom'])) ? $assos['nom'] : "Association";
        $idAsso = (is_array($assos) && isset($assos['id'])) ? $assos['id'] : 0;

        $nomFournisseur = ($fournisseur && isset($fournisseur['nom'])) ? $fournisseur['nom'] : "Fournisseur inconnu";
        $idFournisseur = ($fournisseur && isset($fournisseur['id'])) ? $fournisseur['id'] : 0;
        ?>
        <link rel="stylesheet" href="css/reappro-detail.css?v=<?= time() ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <div class="reappro-scope">
            <div class="reappro-wrapper">
                <header class="hero-header">
                    <div class="header-content">
                        <a href="index.php?module=gestionnaire&action=fournisseurs" class="back-link"
                           style="color:#94a3b8; text-decoration:none; font-weight:bold; font-size:12px; text-transform:uppercase;">
                            <i class="fa-solid fa-arrow-left"></i> Retour
                        </a>
                        <span class="pre-title"
                              style="display:block; color:#f59e0b; font-weight:800; letter-spacing:2px; font-size:11px; text-transform:uppercase; margin-top:10px;">Espace Logistique</span>
                        <h1 class="main-asso-display"><?= htmlspecialchars($nomAsso) ?></h1>
                        <div class="supplier-pill"
                             style="background:rgba(245,158,11,0.1); padding:8px 16px; border-radius:50px; display:inline-flex; align-items:center; gap:10px; color:#f59e0b; border:1px solid rgba(245,158,11,0.2);">
                            <i class="fa-solid fa-truck-fast"></i>
                            <span>Fournisseur : <strong><?= htmlspecialchars($nomFournisseur) ?></strong></span>
                        </div>
                    </div>
                </header>

                <div class="reappro-grid">
                    <div class="catalog-side">
                        <div style="position:relative; margin-bottom:30px;">
                            <i class="fa-solid fa-magnifying-glass"
                               style="position:absolute; left:20px; top:50%; transform:translateY(-50%); color:#f59e0b;"></i>
                            <input type="text" id="searchBar" onkeyup="filterCatalogue()"
                                   placeholder="Rechercher un produit..."
                                   style="width:100%; padding:15px 15px 15px 50px; background:#0f172a; border:1px solid rgba(255,255,255,0.1); border-radius:15px; color:white; font-size:1rem; outline:none;">
                        </div>

                        <form id="mainOrderForm" action="index.php?module=gestionnaire&action=validerReappro"
                              method="POST">
                            <input type="hidden" name="id_fournisseur" value="<?= $idFournisseur ?>">
                            <input type="hidden" name="id_association" value="<?= $idAsso ?>">

                            <div id="catalogList">
                                <?php if (!empty($produits)): ?>
                                    <?php foreach ($produits as $p): ?>
                                        <div class="product-card" data-price="<?= $p['prix_achat'] ?>">
                                            <div class="p-details">
                                                <h3 style="margin:0; font-size:1.1rem; font-weight:800; text-transform:uppercase;"><?= htmlspecialchars($p['nom']) ?></h3>
                                                <span style="color:#f59e0b; font-weight:700;"><?= number_format($p['prix_achat'], 2) ?>€</span>
                                            </div>
                                            <div class="qty-selector">
                                                <button type="button" class="q-btn" onclick="updateQty(this, -1)"
                                                        style="width:35px; height:35px; background:#1e293b; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold;">
                                                    -
                                                </button>
                                                <input type="number" name="produits[<?= $p['id'] ?>][quantite]"
                                                       class="qty-input" value="0" readonly>
                                                <button type="button" class="q-btn" onclick="updateQty(this, 1)"
                                                        style="width:35px; height:35px; background:#1e293b; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold;">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color:#94a3b8; text-align:center;">Aucun produit disponible.</p>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <aside class="cart-side">
                        <div class="glass-cart">
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:15px;">
                                <i class="fa-solid fa-basket-shopping" style="color:#f59e0b;"></i>
                                <h2 style="margin:0; font-size:1rem; font-weight:800; letter-spacing:1px;">VOTRE
                                    PANIER</h2>
                            </div>

                            <div id="error-message"
                                 style="display:none; color:#ef4444; background:rgba(239,68,68,0.1); padding:10px; border-radius:10px; text-align:center; font-weight:bold; margin-bottom:15px;">
                                Panier vide !
                            </div>

                            <div id="cartSummaryList" class="cart-items-list">
                                <p class="empty-msg">Aucun article sélectionné</p>
                            </div>

                            <div style="margin-top:20px;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:5px; color:#94a3b8; font-size:0.8rem; font-weight:bold; text-transform:uppercase;">
                                    <span>Articles</span>
                                    <span id="totalQtyCount">0</span>
                                </div>
                                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid rgba(255,255,255,0.1); padding-top:15px; margin-top:10px;">
                                    <span style="color:white; font-weight:bold;">TOTAL HT</span>
                                    <span style="font-size:2rem; font-weight:900; color:#f59e0b;"><span
                                                id="totalPriceSum">0.00</span>€</span>
                                </div>
                                <button type="button" onclick="validerPanier()"
                                        style="width:100%; padding:18px; background:#f59e0b; border:none; border-radius:15px; font-weight:900; text-transform:uppercase; margin-top:20px; cursor:pointer; transition:0.3s;">
                                    CONFIRMER
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>

        <script src="js/reappro.js?v=<?= time() ?>"></script>
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
                    <i class="fa-solid fa-chevron-left transition-transform group-hover:-translate-x-1"></i> Retour à la
                    liste
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
                        <h2 class="text-2xl font-black text-white uppercase italic"><?= $prenom ?> <span
                                    class="text-emerald-500"><?= $nom ?></span></h2>
                        <p class="text-slate-500 font-bold text-[9px] tracking-widest uppercase mt-1">Matricule
                            #<?= str_pad($id_barman, 3, '0', STR_PAD_LEFT) ?></p>
                    </div>

                    <div class="bg-white/5 border border-white/5 rounded-[2.5rem] p-6">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-address-book text-emerald-500"></i> Coordonnées
                        </p>
                        <div class="space-y-3 text-xs text-slate-300 italic">
                            <p class="flex items-center gap-3"><i
                                        class="fa-solid fa-envelope text-emerald-500 w-4"></i> <?= $email_dest ?></p>
                            <p class="flex items-center gap-3"><i
                                        class="fa-solid fa-phone text-emerald-500 w-4"></i> <?= htmlspecialchars($barmans['tel'] ?? 'N/A') ?>
                            </p>
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

        <div id="customAlertModal"
             class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all">
            <div class="bg-[#020617] border border-white/10 w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl transform scale-95 transition-transform duration-300">
                <div class="w-16 h-16 bg-red-500/10 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-red-500/20">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>

                <h3 class="text-xl font-black text-white text-center uppercase italic mb-2">Attention</h3>
                <p class="text-slate-400 text-center text-sm font-medium leading-relaxed mb-8">
                    Voulez-vous vraiment révoquer les droits de barman ? L'utilisateur redeviendra simple client et
                    perdra ses accès au staff.
                </p>

                <div class="flex gap-3">
                    <button onclick="closeModal()"
                            class="flex-1 py-4 bg-white/5 hover:bg-white/10 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-2xl transition-all border border-white/5">
                        Annuler
                    </button>
                    <a id="confirmActionBtn" href="#"
                       class="flex-1 py-4 bg-red-500 hover:bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl text-center shadow-lg shadow-red-500/20 transition-all">
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
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="css/gestion-solde.css">

        <?php
        $idAsso = $personne['association_id'] ?? ($_GET['id_asso'] ?? '');
        $solde = (float)($personne['solde'] ?? 0);
        $isAsso = isset($personne['nom_association']) || (isset($personne['type']) && $personne['type'] === 'association');
        $nom = $isAsso ? ($personne['nom'] ?? $personne['nom_association']) : ($personne['prenom'] . ' ' . $personne['nom']);
        ?>

        <div class="bg-[#020617] font-montserrat min-h-screen">
            <div class="wrapper-centrage">
                <div class="w-full px-4 py-10">
                    <div class="max-w-[480px] mx-auto">
                        <div class="mb-6 px-4">
                            <a href="index.php?action=<?= $isAsso ? 'gererAssociation&id=' : 'voirListeClients&id=' ?><?= $idAsso ?>"
                               class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-500 transition-all text-[11px] font-black uppercase tracking-[0.2em] group no-underline">
                                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                                <span>Retour terminal</span>
                            </a>
                        </div>

                        <div class="glass-premium p-10">
                            <div class="text-center mb-10">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-600/10 border border-blue-500/20 mb-6">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                                    <span class="text-blue-500 text-[9px] font-black uppercase tracking-[0.2em]">Compte Client</span>
                                </div>
                                <h2 class="text-white text-2xl font-black uppercase italic tracking-tighter mb-4">
                                    <?= htmlspecialchars($nom) ?>
                                </h2>
                                <div class="text-6xl font-[900] text-white italic tracking-tighter glow-text">
                                    <?= number_format($solde, 2, ',', ' ') ?><span class="text-2xl not-italic ml-1 opacity-30">€</span>
                                </div>
                            </div>

                            <form method="POST" action="index.php?action=gererSolde" id="soldeForm" class="space-y-8">
                                <input type="hidden" name="id_association" value="<?= $idAsso ?>">
                                <input type="hidden" name="id_personne" value="<?= $personne['id'] ?? '' ?>">

                                <div class="flex gap-4">
                                    <div class="op-card flex-1 p-6 rounded-[2rem] text-center selected" data-op="ajouter">
                                        <i class="fas fa-plus text-blue-500 mb-3 text-lg"></i>
                                        <div class="text-[10px] font-black uppercase text-white tracking-widest">Recharger</div>
                                        <input type="radio" name="operation" value="ajouter" class="hidden" checked>
                                    </div>
                                    <div class="op-card flex-1 p-6 rounded-[2rem] text-center" data-op="retirer">
                                        <i class="fas fa-minus text-red-500 mb-3 text-lg"></i>
                                        <div class="text-[10px] font-black uppercase text-white tracking-widest">Encaisser</div>
                                        <input type="radio" name="operation" value="retirer" class="hidden">
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <input type="number" name="montant" id="mInput" step="0.01" min="0" required placeholder="0.00"
                                           class="m-input w-full rounded-[2rem] p-8 text-5xl font-[900] text-white text-center outline-none">
                                    <div class="grid grid-cols-5 gap-2 px-2">
                                        <?php foreach ([1, 2, 5, 10, 20] as $v): ?>
                                            <button type="button" onclick="addVal(<?= $v ?>)"
                                                    class="bg-white/5 border border-white/5 py-3 rounded-xl text-[11px] font-black text-blue-500 hover:bg-blue-600 hover:text-white transition-all">
                                                +<?= $v ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <button type="submit" class="btn-cyber w-full font-[900] uppercase italic py-6 rounded-[2rem] flex items-center justify-center gap-3">
                                    <span class="tracking-[0.2em] text-sm">Valider Transaction</span>
                                    <i class="fas fa-bolt-lightning"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="customModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#020617]/90 backdrop-blur-md">
                <div class="glass-premium max-w-sm w-full p-8 border-blue-500/30 shadow-[0_0_50px_rgba(37,99,235,0.2)]">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-shield-halved text-blue-500 text-2xl animate-pulse"></i>
                        </div>
                        <h3 class="text-white font-black uppercase italic tracking-tighter text-xl mb-2">Confirmation</h3>
                        <p id="modalMessage" class="text-slate-400 text-sm font-bold mb-8 uppercase tracking-widest leading-relaxed">
                            Voulez-vous valider cette transaction ?
                        </p>
                        <div class="flex gap-4">
                            <button type="button" onclick="closeModal()" class="flex-1 py-4 rounded-2xl bg-white/5 border border-white/10 text-slate-500 font-black text-[10px] uppercase tracking-widest hover:bg-white/10 transition-all">
                                Annuler
                            </button>
                            <button type="button" id="confirmBtn" class="flex-1 py-4 rounded-2xl bg-blue-600 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-600/20 hover:bg-blue-500 transition-all">
                                Confirmer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="loaderModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#020617] backdrop-blur-2xl">
                <div class="text-center">
                    <div class="relative w-24 h-24 mx-auto mb-8">
                        <div class="absolute inset-0 border-4 border-blue-600/20 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-t-blue-500 rounded-full animate-spin shadow-[0_0_15px_rgba(59,130,246,0.5)]"></div>
                        <i class="fas fa-bolt-lightning text-blue-500 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl animate-pulse"></i>
                    </div>
                    <h3 class="text-white font-[950] uppercase italic tracking-[0.3em] text-xl mb-2">Mise à jour</h3>
                    <p class="text-blue-500/50 text-[10px] font-black uppercase tracking-[0.5em] animate-pulse">Veuillez patienter...</p>
                    <div class="w-48 h-1 bg-white/5 mx-auto mt-8 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 animate-[progress_2s_ease-in-out_infinite]"></div>
                    </div>
                </div>
            </div>

            <div id="errorModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
                <div class="glass-premium max-w-sm w-full p-8 border-red-500/50">
                    <div class="text-center">
                        <i class="fas fa-triangle-exclamation text-red-500 text-4xl mb-4"></i>
                        <h3 class="text-white font-black uppercase italic mb-2">Erreur</h3>
                        <p id="errorMessage" class="text-slate-400 text-xs font-bold mb-8 uppercase tracking-widest"></p>
                        <button type="button" onclick="closeModal()" class="w-full py-4 rounded-2xl bg-red-600 text-white font-black text-[10px] uppercase">
                            Retour
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>window.currentSolde = <?= $solde ?>;</script>
        <script src="js/gestion-solde.js"></script>
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
        background: rgba(2, 6, 23, 0.9) !important;
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
    }
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
</style>

<div class="flex min-h-screen bg-[#020617] font-montserrat">
    <aside class="w-72 glass-sidebar border-r border-white/5 hidden md:flex flex-col sticky top-0 h-screen z-[1001]">
        
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

            <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mt-8 mb-3">Gestion Globale</p>

            <a href="index.php?module=gestionnaire&action=statistiques" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'statistiques' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-chart-pie text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Analytique & Stats</span>
            </a>

            <a href="index.php?action=barmans" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'barmans' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-id-card-clip text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Équipe Barmans</span>
            </a>

            <a href="index.php?action=voirListeClients" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'voirListeClients' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-address-book text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Clients</span>
            </a>

            <a href="index.php?action=fournisseurs" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'fournisseurs' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-truck-fast text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Réapprovisionnement</span>
            </a>

            <a href="index.php?action=distribuer" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'distribuer' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-boxes-stacked text-lg"></i>
                <span class="font-bold text-sm tracking-tight">Stocks & Logistique</span>
            </a>

            <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mt-8 mb-3">Configuration</p>
            
            <a href="index.php?module=gestionnaire&action=demanderCreationAsso" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'demanderCreationAsso' ? $activeClass : $inactiveClass) . '">
                <i class="fa-solid fa-file-shield text-lg text-emerald-500"></i>
                <span class="font-bold text-sm tracking-tight text-emerald-500">Nouvelle Association</span>
            </a>

            <div class="pt-4 mt-4 border-t border-white/5">
                <a href="index.php?reset=1" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 text-amber-500/70 hover:bg-amber-500/10 hover:text-amber-500">
                    <i class="fa-solid fa-right-left text-lg"></i>
                    <span class="font-bold text-sm tracking-tight">Changer Association</span>
                </a>
            </div>

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
                    <p class="text-sm font-bold text-white truncate">' . htmlspecialchars($prenom) . '</p>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic">Gestionnaire</p>
                </div>
            </a>

            <a href="index.php?action=deconnexion" class="flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl text-red-500 bg-red-500/5 hover:bg-red-500 hover:text-white transition-all duration-300 group shadow-lg shadow-red-900/5 border border-red-500/10">
                <i class="fa-solid fa-power-off text-sm group-hover:rotate-90 transition-transform duration-500"></i>
                <span class="font-black text-[11px] uppercase tracking-widest">Déconnexion</span>
            </a>
        </div>
    </aside>
    <main class="flex-1 relative overflow-y-auto">';
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

            .member-card.is-inactive {
                opacity: 0.6;
                filter: grayscale(0.5);
                border-color: rgba(239, 68, 68, 0.3) !important;
                background: rgba(239, 68, 68, 0.05);
            }

            .member-card.is-inactive:hover {
                opacity: 0.9;
                filter: grayscale(0);
                border-color: #ef4444 !important;
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
                background: rgba(255, 255, 255, 0.05);
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

            .dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
            }

            .dot-online {
                background: #10b981;
                box-shadow: 0 0 12px #10b981;
            }

            .dot-offline {
                background: #ef4444;
                box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
            }

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

            #barmanList::-webkit-scrollbar {
                width: 6px;
            }

            #barmanList::-webkit-scrollbar-thumb {
                background: rgba(16, 185, 129, 0.2);
                border-radius: 10px;
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
                    <a href="index.php?module=gestionnaire&action=ajouterBarman"
                       class="group flex items-center gap-4 bg-emerald-500 text-slate-950 px-8 py-4 rounded-2xl font-black text-xs uppercase transition-all hover:bg-white shadow-lg shadow-emerald-900/20">
                        Recruter un membre
                        <i class="fa-solid fa-plus transition-transform group-hover:rotate-90"></i>
                    </a>
                </header>

                <div class="glass-search-bar">
                    <i class="fa-solid fa-magnifying-glass text-emerald-500"></i>
                    <input type="text" id="filterSearch" placeholder="Rechercher par nom, mail ou ID..."
                           class="flex-1 bg-transparent border-none text-white outline-none font-bold text-sm placeholder:text-slate-600">

                    <div class="h-6 w-[1px] bg-white/10"></div>

                    <select id="filterAsso"
                            class="bg-transparent text-white border-none outline-none font-black text-[10px] uppercase tracking-widest cursor-pointer hover:text-emerald-500 transition-colors">
                        <option value="all">Toutes les associations</option>
                        <?php if (is_array($associations)): foreach ($associations as $asso): ?>
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
                    $isActif = (isset($barman['actif']) && (int)$barman['actif'] === 1);
                    $nomAsso = !empty($barman['nom_association']) ? $barman['nom_association'] : 'Non assigné';
                    ?>

                    <a href="index.php?module=gestionnaire&action=voirProfilBarman&id=<?= $barman['id'] ?>"
                       class="member-card barman-row <?= !$isActif ? 'is-inactive' : '' ?>"
                       data-asso="<?= htmlspecialchars($nomAsso) ?>"
                       data-active="<?= $isActif ? 'true' : 'false' ?>">

                        <div style="display: flex; align-items: center;">
                            <span class="id-pill">ID-<?= str_pad($barman['id'], 3, '0', STR_PAD_LEFT) ?></span>
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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('filterSearch');
                const assoSelect = document.getElementById('filterAsso');
                const tabBtns = document.querySelectorAll('.tab-btn');
                const rows = document.querySelectorAll('.barman-row');

                function filterTable() {
                    const searchValue = searchInput.value.toLowerCase().trim();
                    const assoValue = assoSelect.value;
                    const activeTab = document.querySelector('.tab-btn.active');
                    const statusFilter = activeTab ? activeTab.getAttribute('data-status') : 'all';

                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        const rowAsso = row.getAttribute('data-asso');
                        const isActive = row.getAttribute('data-active') === 'true';

                        const matchesSearch = text.includes(searchValue);

                        const matchesAsso = (assoValue === 'all' || rowAsso === assoValue);

                        let matchesStatus = true;
                        if (statusFilter === 'active') {
                            matchesStatus = (isActive === true);
                        } else if (statusFilter === 'inactive') {
                            matchesStatus = (isActive === false);
                        } else {
                            matchesStatus = true;
                        }

                        if (matchesSearch && matchesAsso && matchesStatus) {
                            row.style.display = 'grid';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                tabBtns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        tabBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        filterTable();
                    });
                });

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


    public function afficherFormulaireAjoutClient($assoActuelle, $clients, $erreur = null)
    {
        $this->afficherNav();

        $idAsso = is_array($assoActuelle) ? ($assoActuelle['id'] ?? '') : $assoActuelle;
        $nomAsso = is_array($assoActuelle) ? ($assoActuelle['nom'] ?? 'Association') : 'Association';
        $success = $_GET['success'] ?? null;
        ?>

        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <link rel="stylesheet" href="css/ajout-client.css">

        <div id="cyber-overlay">
            <div class="cyber-spinner"></div>
            <p class="loading-text">Ajout du client...</p>
        </div>

        <?php if ($success): ?>
        <div id="success-toast" class="success-toast">
            <div class="toast-content">
                <i class="fa-solid fa-check-double"></i>
                <div>
                    <p class="toast-title">Succès</p>
                    <p class="toast-msg">Le client a été bien ajouté !</p>
                </div>
            </div>
            <div class="toast-progress"></div>
        </div>
    <?php endif; ?>

        <div class="dashboard-container">
            <div class="text-center mb-10 animate-up">
                <h2 class="text-6xl font-[1000] text-white italic tracking-tighter uppercase glow-title">
                    Link<span class="text-blue-600">Member</span>
                </h2>
            </div>

            <div class="form-card animate-up">
                <form action="index.php?action=validerAjoutClient" method="POST" id="clientForm">
                    <input type="hidden" name="id_assos" value="<?= htmlspecialchars($idAsso) ?>">

                    <div class="mb-6">
                        <label class="label-style">Rechercher le membre</label>
                        <select name="id_client" id="select-client" placeholder="Saisir un nom ou email..." required>
                            <option value=""></option>
                            <?php foreach ($clients as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars(($c['nom'] ?? '') . " " . ($c['prenom'] ?? '')) ?> — <?= htmlspecialchars($c['email'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="destination-badge">
                        <span class="dest-label">Unité de destination</span>
                        <span class="dest-name"><?= htmlspecialchars($nomAsso) ?></span>
                    </div>

                    <button type="submit" class="btn-cyber-submit">
                        Confirmer l'ajout <i class="fa-solid fa-bolt ml-2"></i>
                    </button>

                    <a href="index.php?action=voirListeClients&id=<?= $idAsso ?>" class="back-link">Annuler l'opération</a>
                </form>
            </div>
        </div>

        <script src="js/ajout-client.js"></script>
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


    public function afficherStatsDetails($evolution, $topProduits, $pertesTotales, $nomAsso, $topClients, $statsHoraires) {
        $this->afficherNav();
        echo '<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">';

        $heuresCompletes = [];
        for ($h = 8; $h <= 23; $h++) {
            $heuresCompletes[$h] = $statsHoraires[$h] ?? 0;
        }

        $maxVentesHeure = max($heuresCompletes) ?: 1;
        $maxRecette = !empty($evolution['recettes']) ? max($evolution['recettes']) : 1;
        ?>

        <main class="h-screen overflow-hidden bg-[#020617] text-slate-300 p-6 flex flex-col stats-global-wrapper">

            <div class="max-w-7xl w-full mx-auto mb-6 flex justify-between items-center bg-white/5 p-10 rounded-[3rem] border border-white/5 shadow-2xl relative">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                        <p class="text-slate-500 font-black text-[10px] uppercase tracking-[0.4em]">Dashboard Logistique</p>
                    </div>
                    <h1 class="text-6xl font-black text-white italic uppercase tracking-tighter">
                        <?= htmlspecialchars($nomAsso) ?><span class="text-blue-600">.</span>STATS
                    </h1>
                </div>

                <div class="relative z-10 text-right bg-blue-500/10 border border-blue-500/20 px-10 py-6 rounded-[2.5rem]">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Pertes Inventaire</p>
                    <div class="text-4xl font-black text-white italic tracking-tighter">
                        -<?= number_format($pertesTotales, 2, ',', ' ') ?> €
                    </div>
                </div>
            </div>

            <div class="flex-1 grid grid-cols-12 gap-6 max-h-[calc(100vh-280px)] max-w-7xl w-full mx-auto">

                <div class="col-span-12 lg:col-span-8 bg-white/5 border border-white/5 rounded-[2.5rem] p-8 flex flex-col">
                    <h2 class="text-xs font-black text-white uppercase italic tracking-widest mb-8 flex items-center gap-3">
                        <i class="fa-solid fa-chart-line text-blue-500"></i> Performance Hebdomadaire
                    </h2>
                    <div class="flex-1 flex items-end justify-between gap-4 px-2 pb-2">
                        <?php foreach($evolution['dates'] as $i => $date):
                            $val = $evolution['recettes'][$i] ?? 0;
                            $h_bar = ($val / $maxRecette) * 100;
                            ?>
                            <div class="flex-1 flex flex-col items-center gap-4 h-full justify-end group/bar relative">
                                <div class="absolute -top-10 bg-blue-600 text-white text-[10px] font-black px-2 py-1 rounded-lg opacity-0 group-hover/bar:opacity-100 transition-all">
                                    <?= round($val) ?>€
                                </div>
                                <div class="chart-bar-flux w-full max-w-[45px] bg-blue-500/10 border-t-2 border-blue-500/40 rounded-t-xl transition-all duration-700 group-hover/bar:bg-blue-600 group-hover/bar:shadow-[0_0_20px_rgba(37,99,235,0.3)]"
                                     style="height: <?= max($h_bar, 5) ?>%;"></div>
                                <span class="text-[9px] font-black text-slate-600 uppercase italic"><?= $date ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-4 bg-white/5 border border-white/5 rounded-[2.5rem] p-8 flex flex-col shadow-2xl">
                    <h2 class="text-xs font-black text-white uppercase italic tracking-widest mb-8 flex items-center gap-3">
                        <i class="fa-solid fa-bolt text-blue-400"></i> Affluence Live
                    </h2>
                    <div class="flex-1 flex items-end justify-between gap-1 px-1">
                        <?php foreach($heuresCompletes as $h_idx => $valeur):
                            $hauteur = ($valeur / $maxVentesHeure) * 100;
                            ?>
                            <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group/h relative">
                                <div class="absolute -top-8 bg-white text-black text-[8px] font-black px-1.5 py-0.5 rounded opacity-0 group-hover/h:opacity-100 transition-all">
                                    <?= round($valeur) ?>€
                                </div>
                                <div class="w-2 bg-white/5 rounded-full h-full flex items-end overflow-hidden">
                                    <div class="w-full bg-blue-500 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.4)] transition-all duration-1000"
                                         style="height: <?= max($hauteur, 3) ?>%;"></div>
                                </div>
                                <span class="text-[8px] font-black text-slate-600"><?= $h_idx ?>H</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-span-6 bg-white/5 border border-white/5 rounded-[2.5rem] p-6 shadow-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-[11px] font-black text-white uppercase italic flex items-center gap-2">
                            <i class="fa-solid fa-fire text-blue-500"></i> Top Ventes
                        </h2>
                        <a href="index.php?module=gestionnaire&action=detailsProduitsStats" class="text-[9px] bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1 rounded-full hover:bg-blue-600 hover:text-white transition-all font-black uppercase">Détails</a>
                    </div>
                    <div class="space-y-2">
                        <?php foreach(array_slice($topProduits, 0, 3) as $p): ?>
                            <div class="flex items-center justify-between p-3 bg-black/20 border border-white/5 rounded-2xl hover:border-blue-500/30 transition-all">
                                <span class="text-xs font-bold text-slate-200"><?= htmlspecialchars($p['nom']) ?></span>
                                <span class="text-blue-500 font-black text-[10px]"><?= $p['total'] ?> UNITÉS</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-span-6 bg-white/5 border border-white/5 rounded-[2.5rem] p-6 shadow-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-[11px] font-black text-white uppercase italic flex items-center gap-2">
                            <i class="fa-solid fa-crown text-blue-500"></i> Élite Clients
                        </h2>
                        <a href="index.php?module=gestionnaire&action=detailsClientsStats" class="text-[9px] bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1 rounded-full hover:bg-blue-600 hover:text-white transition-all font-black uppercase">Voir Tout</a>
                    </div>
                    <div class="space-y-2">
                        <?php foreach(array_slice($topClients, 0, 3) as $c): ?>
                            <div class="flex items-center justify-between p-3 bg-black/20 border border-white/5 rounded-2xl hover:border-blue-500/30 transition-all">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-white"><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></span>
                                    <span class="text-[9px] text-slate-500 font-black uppercase"><?= $c['nb_commandes'] ?? 0 ?> Commandes</span>
                                </div>
                                <span class="text-blue-500 font-black italic text-sm"><?= number_format($c['depense_totale'], 2) ?>€</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
        <?php
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

        $currentAssoId = $_SESSION['asso_choisi'] ?? null;
        $currentAssoName = "Gestionnaire";

        if ($currentAssoId) {
            foreach ($associations as $asso) {
                if ($asso['id'] == $currentAssoId) {
                    $currentAssoName = $asso['nom'];
                    break;
                }
            }
        }
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="css/reappro_fournisseurs.css">

        <div class="reappro-header">
            <div class="header-content">
                <div class="top-navigation">
                    <div class="brand-mini">
                        <i class="fa-solid fa-truck-fast text-amber-500"></i>
                        <span>Logistique & Approvisionnement</span>
                    </div>
                    <div class="header-actions">
                        <a href="index.php?module=gestionnaire&action=rechercherPrix" class="nav-btn">
                            <i class="fa-solid fa-chart-line"></i> Comparateur
                        </a>
                        <a href="index.php?module=gestionnaire&action=mesCommandes" class="nav-btn">
                            <i class="fa-solid fa-clock-rotate-left"></i> Historique
                        </a>
                        <a href="index.php?reset=1" class="nav-btn reset">
                            <i class="fa-solid fa-arrow-rotate-left"></i> Changer d'asso
                        </a>
                    </div>
                </div>

                <h1 class="main-asso-title">
                    <?= htmlspecialchars($currentAssoName) ?><span class="dot">.</span>
                </h1>
            </div>
        </div>

        <div class="supplier-container">
            <div class="supplier-grid">
                <?php foreach ($fournisseurs as $f): ?>
                    <div class="supplier-card-modern">
                        <div class="card-top">
                            <div class="avatar-circle">
                                <?= strtoupper(substr($f['nom'], 0, 1)) ?>
                            </div>
                            <div class="badge-status <?= $currentAssoId ? 'on' : 'off' ?>">
                                <?= $currentAssoId ? 'Disponible' : 'Bloqué' ?>
                            </div>
                        </div>

                        <div class="card-mid">
                            <h2 class="name"><?= htmlspecialchars($f['nom']) ?></h2>
                            <div class="contact-row">
                                <i class="fa-solid fa-envelope"></i>
                                <span><?= htmlspecialchars($f['email'] ?? 'Non renseigné') ?></span>
                            </div>
                        </div>

                        <div class="card-bottom">
                            <?php if ($currentAssoId): ?>
                                <a href="index.php?module=gestionnaire&action=voirFournisseur&id=<?= $f['id'] ?>"
                                   class="btn-main">
                                    <i class="fa-solid fa-box-open"></i> Catalogue
                                </a>
                            <?php else: ?>
                                <a href="index.php" class="btn-main lock">Choisir une association</a>
                            <?php endif; ?>

                            <a href="index.php?module=gestionnaire&action=contacterFournisseur&id=<?= $f['id'] ?>"
                               class="btn-side" title="Contacter">
                                <i class="fa-solid fa-paper-plane"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
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
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-[0.3em] mt-2">Analysez les tarifs en
                        temps réel</p>
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
                        <div class="text-center p-20 mt-10"
                             style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(255,255,255,0.05); border-radius: 40px;">
                            <i class="fa-solid fa-keyboard text-5xl text-slate-800 mb-6"></i>
                            <h3 class="text-xl font-bold text-slate-400 uppercase tracking-widest">En attente de
                                recherche</h3>
                            <p class="text-slate-600 mt-2">Utilisez la barre ci-dessus pour comparer les prix de vos
                                fournisseurs.</p>
                        </div>

                    <?php elseif (empty($resultats)): ?>
                        <div class="text-center p-20 bg-slate-900/30 rounded-3xl border border-dashed border-white/10 mt-10">
                            <i class="fa-solid fa-triangle-exclamation text-5xl text-amber-500/20 mb-6"></i>
                            <p class="text-slate-400 font-bold uppercase tracking-widest">Aucune offre trouvée pour
                                "<?= htmlspecialchars($recherche) ?>"</p>
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
                                            Vendu par : <span
                                                    class="text-slate-200 font-semibold italic"><?= htmlspecialchars($res['fournisseur_nom']) ?></span>
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

            /* --- MODIFICATION ICI : PAYEE ET LIVREE EN VERT --- */
            .status-payee, .status-payée, .status-livree {
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
                            $statut = $c['statut'] ?? 'payée';
                            $icon = 'fa-clock';
                            if ($statut == 'livree' || $statut == 'payée' || $statut == 'payee') {
                                $icon = 'fa-check-circle';
                            } elseif ($statut == 'annulee') {
                                $icon = 'fa-xmark-circle';
                            }

                            $statusClass = str_replace(['é', 'è'], 'e', $statut);
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
                                    <span class="status-pill status-<?= $statusClass ?>">
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
                                    <a href="index.php?module=gestionnaire&action=detailsCommande&id=<?= $c['id'] ?>"
                                       class="view-btn" title="Voir les détails">
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
        $totalCmd = count($commandesFournisseurs);
        $totalEntrees = array_sum(array_column($historiqueAchats, 'quantite'));
        $alertes = count(array_filter($historiqueInventaires, function($i) { return $i['quantite_trouvee'] != $i['quantite_theorique']; }));
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

            body {
                background: #020617;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: #f8fafc;
                overflow: hidden;
            }

            .main-container {
                height: 100vh;
                display: flex;
                flex-direction: column;
                padding: 20px 40px;
                max-width: 1600px;
                margin: 0 auto;
            }

            /* --- DASHBOARD HEADER --- */
            .stat-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-bottom: 20px;
            }

            .stat-card {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.05);
                padding: 15px 25px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                gap: 20px;
            }

            .stat-icon {
                width: 45px;
                height: 45px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
            }

            /* --- CONTENT AREA --- */
            .content-box {
                flex: 1;
                background: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 30px;
                padding: 30px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }

            .scroll-area {
                flex: 1;
                overflow-y: auto;
                padding-right: 10px;
            }

            /* Scrollbar stylisée hyper fine */
            .scroll-area::-webkit-scrollbar { width: 4px; }
            .scroll-area::-webkit-scrollbar-track { background: transparent; }
            .scroll-area::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

            .tab-btn {
                padding: 10px 24px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                cursor: pointer;
                transition: 0.3s;
                color: #64748b;
            }

            .tab-btn.active {
                background: rgba(37, 99, 235, 0.1);
                color: #3b82f6;
                border: 1px solid rgba(37, 99, 235, 0.2);
            }

            /* Cartes ultra-plates */
            .row-item {
                background: rgba(255,255,255,0.02);
                border-radius: 16px;
                padding: 12px 20px;
                margin-bottom: 8px;
                display: grid;
                grid-template-columns: 2fr 1fr 1fr 1fr;
                align-items: center;
                transition: 0.2s;
                border: 1px solid transparent;
            }

            .row-item:hover {
                background: rgba(255,255,255,0.05);
                border-color: rgba(255,255,255,0.1);
            }

            .badge-lite {
                font-size: 9px;
                font-weight: 800;
                padding: 4px 10px;
                border-radius: 8px;
                text-transform: uppercase;
                width: fit-content;
            }
        </style>

        <div class="main-container">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-black italic tracking-tighter uppercase">
                    Stock<span class="text-blue-500">Terminal</span>
                </h1>

                <div class="flex gap-2 bg-black/40 p-1.5 rounded-xl border border-white/5">
                    <button class="tab-btn active" onclick="switchTab(event, 'tab-commandes')">Flux Achat</button>
                    <button class="tab-btn" onclick="switchTab(event, 'tab-entrees')">Arrivages</button>
                    <button class="tab-btn" onclick="switchTab(event, 'tab-inventaires')">Contrôle</button>
                </div>

                <?php if (!empty($associations)): ?>
                    <a href="index.php?action=faireInventaire&id=<?= htmlspecialchars($associations['id']) ?>"
                       class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-[10px] uppercase hover:bg-blue-500 transition-all shadow-lg shadow-blue-500/20">
                        Nouveau Scan <i class="fa-solid fa-qrcode ml-2"></i>
                    </a>
                <?php endif; ?>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-blue-500/10 text-blue-500"><i class="fa-solid fa-cart-shopping"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Commandes</p>
                        <p class="text-xl font-black"><?= $totalCmd ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-emerald-500/10 text-emerald-500"><i class="fa-solid fa-box"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Articles Entrants</p>
                        <p class="text-xl font-black"><?= $totalEntrees ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-amber-500/10 text-amber-500"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Écarts Inventaire</p>
                        <p class="text-xl font-black"><?= $alertes ?></p>
                    </div>
                </div>
            </div>

            <div class="content-box">
                <div id="tab-commandes" class="tab-content h-full flex flex-col">
                    <div class="flex justify-between mb-4 items-center">
                        <h2 class="text-xs font-black uppercase text-slate-400">Commandes en cours</h2>
                        <input type="text" id="searchCmd" onkeyup="filterLocal('searchCmd', 'row-cmd', 'data-info')" placeholder="Filtrer..." class="bg-white/5 border border-white/10 rounded-lg px-4 py-1.5 text-xs outline-none focus:border-blue-500 w-64">
                    </div>

                    <div class="scroll-area">
                        <?php foreach ($commandesFournisseurs as $cmd): ?>
                            <div class="row-cmd row-item" data-info="<?= strtolower($cmd['nom_fournisseur']) ?>">
                                <div class="flex items-center gap-4">
                                    <span class="text-[10px] font-bold text-slate-600">#<?= $cmd['id'] ?></span>
                                    <span class="font-bold text-sm"><?= htmlspecialchars($cmd['nom_fournisseur']) ?></span>
                                </div>
                                <div class="badge-lite bg-blue-500/10 text-blue-400 border border-blue-500/20"><?= $cmd['statut'] ?></div>
                                <div class="text-[11px] font-bold text-slate-500"><?= date('d M Y', strtotime($cmd['date_commande'])) ?></div>
                                <div class="text-right font-black text-white"><?= number_format($cmd['montant_total'], 2) ?> €</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div id="tab-entrees" class="tab-content h-full flex flex-col hidden">
                    <div class="flex justify-between mb-4 items-center">
                        <h2 class="text-xs font-black uppercase text-slate-400">Flux de réception</h2>
                    </div>
                    <div class="scroll-area">
                        <?php foreach ($historiqueAchats as $h): ?>
                            <div class="row-item">
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm text-white"><?= htmlspecialchars($h['produit']) ?></span>
                                    <span class="text-[9px] text-slate-600 uppercase font-black"><?= htmlspecialchars($h['fournisseur']) ?></span>
                                </div>
                                <div class="text-emerald-500 font-black">+ <?= $h['quantite'] ?> units</div>
                                <div class="text-[11px] text-slate-500"><?= date('d/m H:i', strtotime($h['date'])) ?></div>
                                <div class="text-right"><i class="fa-solid fa-circle-check text-emerald-500/20"></i></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div id="tab-inventaires" class="tab-content h-full flex flex-col hidden">
                    <div class="flex justify-between mb-4 items-center">
                        <h2 class="text-xs font-black uppercase text-slate-400">Rapports d'écarts</h2>
                    </div>
                    <div class="scroll-area">
                        <?php foreach ($historiqueInventaires as $inv):
                            $diff = $inv['quantite_trouvee'] - $inv['quantite_theorique'];
                            ?>
                            <div class="row-item">
                                <div class="font-bold text-sm"><?= htmlspecialchars($inv['produit']) ?></div>
                                <div class="text-[10px] font-bold text-slate-400">Log: <?= $inv['quantite_theorique'] ?> / Réel: <?= $inv['quantite_trouvee'] ?></div>
                                <div class="font-black <?= $diff < 0 ? 'text-rose-500' : 'text-emerald-500' ?>">
                                    <?= ($diff > 0 ? '+' : '') . $diff ?>
                                </div>
                                <div class="text-right text-[10px] text-slate-600 font-bold"><?= date('d/m/y', strtotime($inv['date'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function switchTab(evt, tabName) {
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.getElementById(tabName).classList.remove('hidden');
                evt.currentTarget.classList.add('active');
            }

            function filterLocal(inputId, className, attr) {
                const val = document.getElementById(inputId).value.toLowerCase();
                document.querySelectorAll('.' + className).forEach(el => {
                    el.style.display = el.getAttribute(attr).includes(val) ? 'grid' : 'none';
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
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] mb-2">Trésorerie
                            Actuelle</p>
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
                        <a href="index.php?action=voirProduits&id=<?= $assos['id'] ?>"
                           class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Inventaire
                            Complet</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-white/5">
                            <?php if (!empty($assos['produits'])): ?>
                                <?php foreach (array_slice($assos['produits'], 0, 5) as $p): ?>
                                    <tr class="group hover:bg-white/[0.02] transition-colors">
                                        <td class="py-4 font-bold text-white text-sm"><?= htmlspecialchars($p['nom']) ?></td>
                                        <td class="py-4 text-slate-400 text-xs font-bold"><?= number_format($p['prix'] ?? 0, 2, ',', ' ') ?>
                                            €
                                        </td>
                                        <td class="py-4 text-right">
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black <?= ($p['quantiteActuelle'] ?? 0) < 5 ? 'bg-rose-500/10 text-rose-500' : 'bg-amber-500/5 text-amber-500' ?>">
                                            <?= $p['quantiteActuelle'] ?? 0 ?> UNITÉS
                                        </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3"
                                        class="py-10 text-center text-slate-500 text-[10px] font-black uppercase">Aucun
                                        produit
                                    </td>
                                </tr>
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
                        <a href="index.php?action=voirBarmans&id=<?= $assos['id'] ?>"
                           class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Gérer</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php if (!empty($assos['barmans'])): ?>
                            <?php foreach (array_slice($assos['barmans'], 0, 4) as $b):
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
                            <p class="col-span-2 text-center py-10 text-slate-500 text-[10px] font-black uppercase">
                                Aucun staff membre</p>
                        <?php endif; ?>
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
                        <?php if (!empty($assos['clients'])): ?>
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