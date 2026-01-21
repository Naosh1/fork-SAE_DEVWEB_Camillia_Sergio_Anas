<?php
include_once 'vue_generique.php';

class VueStaff extends VueCommun
{public function afficherMesMessages($messages, $id_gestionnaire)
{
    echo '<script src="https://cdn.tailwindcss.com"></script>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">';
    $this->afficherNav();

    ?>


    <style>
        .font-cyber {
            font-family: 'Montserrat', sans-serif;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.2);
            border-radius: 10px;
        }

        .message-line {
            transition: all 0.15s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .message-line:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.1) !important;
            color: #3b82f6 !important;
            border-right: 3px solid #3b82f6;
        }

        .hidden-filter {
            display: none !important;
        }
    </style>

    <div class="flex h-screen bg-[#020617] font-cyber overflow-hidden">

        <aside class="w-72 border-r border-white/5 flex flex-col bg-[#020617]">
            <div class="p-8">
                <a href="index.php?action=ecrireMessage"
                   class="flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-500 text-white font-black uppercase text-[10px] tracking-widest py-4 rounded-2xl transition-all shadow-lg shadow-blue-900/20">
                    <i class="fa-solid fa-pen-fancy"></i> Nouveau Message
                </a>
            </div>

            <nav class="flex-1 space-y-1" id="sidebar-nav">
                <button onclick="filterMessages('all', this)"
                        class="sidebar-link active w-full flex items-center gap-4 px-8 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-white transition-all text-left">
                    <i class="fa-solid fa-inbox text-sm"></i> Boîte de réception
                </button>
                <button onclick="filterMessages('sent', this)"
                        class="sidebar-link w-full flex items-center gap-4 px-8 py-4 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all text-left">
                    <i class="fa-solid fa-paper-plane text-sm"></i> Envoyés
                </button>
                <button onclick="filterMessages('starred', this)"
                        class="sidebar-link w-full flex items-center gap-4 px-8 py-4 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all text-left">
                    <i class="fa-solid fa-star text-sm"></i> Important
                </button>
                <button onclick="filterMessages('trash', this)"
                        class="sidebar-link w-full flex items-center gap-4 px-8 py-4 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all text-left opacity-60">
                    <i class="fa-solid fa-trash text-sm"></i> Corbeille
                </button>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden bg-black/10">

            <header class="h-24 border-b border-white/5 flex items-center justify-between px-10 bg-[#020617]">
                <div class="flex items-center gap-4">
                    <h2 class="text-white font-black uppercase italic tracking-tighter text-2xl">
                        Console_Messages</h2>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-600 text-xs"></i>
                        <input type="text" id="searchInput" onkeyup="searchMessages()"
                               placeholder="RECHERCHER UN CONTACT OU OBJET..."
                               class="bg-white/5 border border-white/5 rounded-xl py-3 pl-10 pr-6 text-[10px] font-bold text-white focus:border-blue-500 outline-none transition-all w-80 uppercase tracking-widest">
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto custom-scroll">
                <table class="w-full border-collapse">
                    <tbody id="messageBody">
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="3" class="p-20 text-center opacity-20">
                                <i class="fa-solid fa-tray-can text-6xl mb-4"></i>
                                <p class="font-black uppercase tracking-[0.5em] text-xs">Aucune transmission
                                    détectée</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $msg):
                            $isMe = ($msg['id_expediteur'] == $id_gestionnaire);
                            $type = $isMe ? 'sent' : 'received';
                            $nomAffiché = $isMe ? 'Moi' : $this->e($msg['prenom'] . ' ' . $msg['nom']);
                            $destinataire = $this->e($msg['dest_prenom'] . ' ' . $msg['dest_nom']);
                            ?>
                            <tr class="message-line group cursor-pointer"
                                data-type="<?= $type ?>"
                                data-search="<?= strtolower($nomAffiché . ' ' . $destinataire . ' ' . $this->e($msg['objet'])) ?>">

                                <td class="px-10 py-6 w-1/4">
                                    <div class="flex items-center gap-4">
                                        <i class="fa-solid fa-star text-[10px] text-slate-800 hover:text-amber-500 transition-colors"
                                           onclick="toggleStar(event, this)"></i>
                                        <div class="w-8 h-8 rounded-lg <?= $isMe ? 'bg-emerald-500' : 'bg-blue-600' ?> flex items-center justify-center text-white text-[10px] font-black shadow-lg">
                                            <?= strtoupper(substr($this->e($msg['prenom']), 0, 1)) ?>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-tight <?= $isMe ? 'text-emerald-500' : 'text-white' ?>">
                                                <?= $nomAffiché ?>
                                            </span>
                                    </div>
                                </td>

                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                            <span class="msg-subject text-xs font-black text-white uppercase italic whitespace-nowrap">
                                                <?= $this->e($msg['objet']) ?>
                                            </span>
                                        <span class="text-xs text-slate-500 truncate font-normal opacity-50">
                                                — <?= mb_strimwidth($this->e($msg['contenu']), 0, 100, "...") ?>
                                            </span>
                                    </div>
                                </td>

                                <td class="px-10 py-6 text-right w-40">
                                    <div class="flex items-center justify-end gap-6">
                                            <span class="text-[10px] font-black text-slate-600 uppercase whitespace-nowrap">
                                                <?= date('d M', strtotime($msg['date_envoi'])) ?>
                                            </span>
                                        <i class="fa-solid fa-trash text-slate-800 hover:text-red-500 text-xs transition-colors"
                                           onclick="moveToTrash(event, this)"></i>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hidden detail-view bg-white/[0.02]">
                                <td colspan="3" class="px-10 py-10 border-b border-white/5">
                                    <div class="max-w-4xl">
                                        <div class="flex items-center justify-between mb-8">
                                            <div>
                                                <h3 class="text-blue-500 font-black uppercase italic text-2xl mb-1"><?= $this->e($msg['objet']) ?></h3>
                                                <p class="text-[9px] text-slate-500 font-bold tracking-widest uppercase">
                                                    De: <?= $this->e($msg['prenom'] . ' ' . $msg['nom']) ?> <i
                                                            class="fa-solid fa-arrow-right mx-2 text-[7px]"></i>
                                                    À: <?= $destinataire ?>
                                                </p>
                                            </div>
                                            <?php if (!$isMe): ?>
                                                <a href="index.php?action=ecrireMessage&id_dest=<?= $msg['id_expediteur'] ?>&objet=Re: <?= $this->e($msg['objet']) ?>"
                                                   class="px-6 py-3 bg-blue-600 text-white text-[9px] font-black uppercase rounded-xl hover:bg-blue-500 transition-all">
                                                    Répondre <i class="fa-solid fa-reply ml-2"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-slate-300 text-sm leading-relaxed italic bg-black/40 p-8 rounded-3xl border border-white/5 shadow-inner">
                                            <?= nl2br($this->e($msg['contenu'])) ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="js/messages.js"></script>
    <?php
}

    public function afficherFormulaireEnvoi($destinataires, $sujetPredefini = "", $idCible = null)
    {
        $this->afficherNav();
        echo '
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="js/recherche_contact.js"></script>
    <style>
        .custom-font, .custom-font input, .custom-font select, .custom-font textarea, .custom-font button {
            font-family: "Inter", sans-serif !important;
        }
        .fa-solid {
            display: inline-block !important;
        }
    </style>';
        ?>
        <div class="w-full h-full px-6 md:px-12 py-10 bg-[#020617] min-h-screen text-white custom-font">
            <div class="max-w-4xl mx-auto">
                <form action="index.php?action=envoyerMessage" method="POST"
                      class="bg-white/5 border border-white/5 rounded-[3rem] p-10 shadow-2xl backdrop-blur-md">

                    <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                        <h3 class="text-3xl font-[900] uppercase italic tracking-tighter">
                            Nouveau <span class="text-blue-500">Message</span>
                        </h3>
                        <div class="w-12 h-12 bg-blue-600/10 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-paper-plane text-blue-500 text-xl"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-8">
                        <div class="space-y-4">
                            <div class="flex justify-between items-end px-4">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Destinataire</label>
                                <span id="resultCounter" class="text-[9px] font-bold uppercase text-blue-500"></span>
                            </div>

                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                <input type="text" id="contactSearch"
                                       placeholder="Rechercher..."
                                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-12 py-4 text-white text-sm outline-none focus:border-blue-600 transition-all">
                            </div>

                            <select name="id_destinataire" id="destinataireSelect" required
                                    class="w-full bg-[#03081c] border border-white/10 rounded-2xl px-6 py-4 text-white font-bold outline-none cursor-pointer focus:ring-2 focus:ring-blue-600/50">
                                <option value="" disabled <?= empty($idCible) ? 'selected' : '' ?>>Sélectionner...
                                </option>
                                <?php foreach ($destinataires as $dest): ?>
                                    <?php
                                    $search = strtolower($dest['id'] . ' ' . $dest['nom'] . ' ' . $dest['prenom'] . ' ' . ($dest['email'] ?? ''));
                                    ?>
                                    <option value="<?= $dest['id'] ?>"
                                            data-search="<?= $this->e($search) ?>" <?= ($idCible == $dest['id']) ? 'selected' : '' ?>>
                                        [#<?= $dest['id'] ?>
                                        ] <?= strtoupper($this->e($dest['nom'])) ?> <?= $this->e($dest['prenom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex flex-col justify-end">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mb-3">Objet</label>
                            <input type="text" name="objet" required value="<?= $this->e($sujetPredefini) ?>"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold outline-none focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <div class="mb-10">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mb-3 block">Message</label>
                        <textarea name="contenu" required rows="6"
                                  class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-6 py-5 text-white outline-none focus:border-blue-600 transition-all resize-none"></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-[900] uppercase py-5 rounded-2xl transition-all flex items-center justify-center gap-3">
                            ENVOYER LE MESSAGE <i class="fa-solid fa-paper-plane"></i>
                        </button>
                        <a href="index.php?action=messagerie"
                           class="px-8 py-5 bg-white/5 hover:bg-white/10 text-slate-400 rounded-2xl transition-all font-black uppercase text-[10px] flex items-center">
                            ANNULER
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    public function afficherClients($clients, $nomAssos) {
    $this->afficherNav();
    ?>
    <link rel="stylesheet" href="css/liste-clients.css">

    <div class="p-8 bg-[#020617] min-h-screen font-montserrat">
        <div class="max-w-5xl mx-auto">

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-[950] text-white italic uppercase tracking-tighter">
                        <?= htmlspecialchars($nomAssos) ?> <span class="text-blue-600">/ Clients</span>
                    </h1>
                    <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.4em] mt-2 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></span>
                        Base de données des membres actifs
                    </p>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto">
                    <a href="index.php?action=ajouterClient"
                       class="flex-1 md:flex-none flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-blue-900/30 group">
                        <i class="fa-solid fa-plus-circle text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                        <span>Inscrire un client</span>
                    </a>

                    <div class="bg-white/[0.03] border border-white/10 px-5 py-2.5 rounded-2xl flex flex-col items-center min-w-[90px]">
                        <span class="text-white font-black text-2xl leading-none"><?= count($clients) ?></span>
                        <span class="text-blue-500 text-[8px] font-black uppercase tracking-tighter mt-1">Membres</span>
                    </div>
                </div>
            </header>

            <div class="grid gap-4">
                <?php if(empty($clients)): ?>
                    <div class="text-center py-24 bg-white/[0.01] border border-white/5 rounded-[2rem] border-dashed">
                        <div class="w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-users-slash text-slate-700 text-3xl"></i>
                        </div>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun client trouvé pour cette association</p>
                    </div>
                <?php else: ?>
                    <?php $i = 0; foreach($clients as $c): ?>
                        <div class="client-card cascade-row row-delay-<?= ($i < 6) ? $i : '5' ?> p-5 rounded-3xl flex items-center justify-between transition-all group">

                            <div class="flex items-center gap-6">
                                <div class="relative">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center text-white font-black text-xl shadow-xl group-hover:scale-110 transition-transform">
                                        <?= strtoupper(substr($c['nom'], 0, 1)) ?>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-[#020617] rounded-full"></div>
                                </div>

                                <div>
                                    <h3 class="text-white font-black text-xl tracking-tight group-hover:text-blue-400 transition-colors uppercase italic">
                                        <?= htmlspecialchars(mb_strtoupper($c['nom'] . ' ' . $c['prenom'])) ?>
                                    </h3>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="bg-blue-600/10 text-blue-500 text-[9px] font-black px-2 py-0.5 rounded-md uppercase tracking-tighter">ID #<?= $c['id'] ?></span>
                                        <span class="text-slate-600 text-[10px] font-bold italic">Membre régulier</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-right hidden sm:block">
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Solde Disponible</p>
                                    <p class="text-2xl font-black text-white italic tracking-tighter">
                                        <?= number_format($c['solde'], 2, ',', ' ') ?><span class="text-blue-600 ml-1">€</span>
                                    </p>
                                </div>

                                <div class="flex gap-3">
                                    <a href="index.php?action=gererSolde&id=<?= $c['id'] ?>"
                                       class="blue-btn" title="Gérer le solde">
                                        <i class="fa-solid fa-wallet"></i>
                                    </a>

                                    <a href="index.php?action=retirerClient&id=<?= $c['id'] ?>"
                                       class="red-btn"
                                       onclick="return confirm('Voulez-vous vraiment retirer ce client ?')"
                                       title="Retirer de l'asso">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php $i++; endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </main></div>
    <?php
}

    public function afficherListeCompleteClients($clients, $nomAsso) {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="css/clients-stats.css">

        <div class="stats-container">
            <header class="stats-header">
                <div class="stats-header-content">
                    <div>
                        <h1 class="stats-title">
                            TOP <span class="stats-highlight">CLIENTS</span>
                        </h1>
                        <p style="color: #64748b; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">
                            <i class="fa-solid fa-crown mr-2" style="color: var(--accent-blue);"></i>
                            <?= htmlspecialchars($nomAsso) ?>
                        </p>
                    </div>
                    <a href="index.php?module=gestionnaire&action=statistiques" class="btn-back">
                        Retour
                    </a>
                </div>
            </header>

            <div class="stats-grid">
                <?php foreach($clients as $index => $c): ?>
                    <div class="stats-card">
                        <div style="display: flex; align-items: center; gap: 20px;">
                        <span class="rank-badge <?= $index < 3 ? 'top-rank' : '' ?>">
                            #<?= $index + 1 ?>
                        </span>

                            <div class="item-info">
                                <h3><?= htmlspecialchars(mb_strtoupper($c['prenom'].' '.$c['nom'])) ?></h3>
                                <span style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">
                                <i class="fa-solid fa-receipt mr-1"></i>
                                <?= $c['nb_commandes'] ?? 0 ?> commandes
                            </span>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <span style="display:block; font-size: 0.6rem; font-weight: 900; color: var(--accent-blue); text-transform: uppercase; margin-bottom: 2px;">Dépense</span>
                            <span class="value-amount">
                            <?= number_format($c['depense_totale'] ?? 0, 2, ',', ' ') ?>€
                        </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    public function afficherListeCompleteProduits($produits, $nomAsso) {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="css/produits-stats.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <div class="stats-container">
            <header class="stats-header">
                <div class="stats-header-content">
                    <div>
                        <h1 class="stats-title">
                            PERFORMANCES <span style="color:var(--accent)">PRODUITS</span>
                        </h1>
                        <span class="asso-name"><?= htmlspecialchars($nomAsso) ?></span>
                    </div>
                    <a href="index.php?action=statistiques" class="btn-back">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </header>

            <div class="stats-grid">
                <?php foreach($produits as $p):
                    $type = strtolower($p['type'] ?? 'autre');
                    if (strpos($type, 'boisson') !== false) { $class="cat-boisson"; $icon="fa-wine-glass"; }
                    elseif (strpos($type, 'nourriture') !== false) { $class="cat-nourriture"; $icon="fa-burger"; }
                    else { $class="cat-autre"; $icon="fa-tag"; }
                    ?>
                    <div class="stats-card">
                        <div style="display: flex; align-items: center; gap: 25px;">
                            <div class="icon-container <?= $class ?>">
                                <i class="fa-solid <?= $icon ?>"></i>
                            </div>
                            <div>
                                <span class="item-name"><?= htmlspecialchars(mb_strtoupper($p['nom'])) ?></span><br>
                                <span class="item-type"><?= htmlspecialchars($p['type']) ?></span>
                            </div>
                        </div>

                        <div class="value-box">
                            <span class="value-unit">Unités vendues</span>
                            <span class="value-total"><?= $p['total'] ?? 0 ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function afficherDetailsCommande($commande, $produits)
    {
        $this->afficherNav();
        ?>
        <style>
            .details-container {
                background: #020617;
                min-height: 100vh;
                padding: 4rem 2rem;
                color: white;
                font-family: 'Montserrat', sans-serif;
            }
            .info-card {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 24px;
                padding: 2rem;
            }
            .product-row {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                padding: 1rem 0;
            }
            .status-badge {
                padding: 8px 16px;
                border-radius: 12px;
                font-weight: 900;
                text-transform: uppercase;
                font-size: 12px;
            }
            .status-payee { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid #22c55e; }
            .status-en_attente { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid #3b82f6; }
            .cancel-btn {
                background: #dc2626;
                color: white;
                padding: 0.5rem 1rem;
                font-weight: 900;
                border-radius: 12px;
                text-transform: uppercase;
                font-size: 12px;
                transition: background 0.3s;
            }
            .cancel-btn:hover { background: #b91c1c; }
        </style>

        <div class="details-container">
            <div class="max-w-4xl mx-auto">
                <a href="index.php?module=gestionnaire&action=mesCommandes" class="text-slate-400 hover:text-amber-500 transition-all text-xs font-bold uppercase mb-8 inline-block">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Retour à l'historique
                </a>

                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h1 class="text-4xl font-black italic uppercase">Commande #<?= str_pad($commande['id'], 5, '0', STR_PAD_LEFT) ?></h1>
                        <p class="text-slate-400 mt-2">Passée le <?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></p>
                    </div>
                    <div class="status-badge status-<?= str_replace(['é','è'], 'e', $commande['statut']) ?>">
                        <?= strtoupper($commande['statut']) ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="info-card">
                        <p class="text-amber-500 font-black text-[10px] uppercase mb-4">Fournisseur</p>
                        <p class="text-xl font-bold"><?= htmlspecialchars($commande['nom_fournisseur']) ?></p>
                    </div>
                    <div class="info-card">
                        <p class="text-amber-500 font-black text-[10px] uppercase mb-4">Association</p>
                        <p class="text-xl font-bold"><?= htmlspecialchars($commande['nom_association']) ?></p>
                    </div>
                </div>

                <div class="info-card">
                    <h3 class="text-amber-500 font-black text-[10px] uppercase mb-6">Détails des articles</h3>
                    <div class="space-y-2">
                        <?php foreach ($produits as $p): ?>
                            <div class="product-row flex justify-between items-center">
                                <div>
                                    <p class="font-bold"><?= htmlspecialchars($p['nom_produit']) ?></p>
                                    <p class="text-xs text-slate-500 italic"><?= $p['type_produit'] ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black"><?= $p['quantite'] ?> x <?= number_format($p['prix_unitaire'], 2) ?> €</p>
                                    <p class="text-amber-500 font-bold text-sm"><?= number_format($p['quantite'] * $p['prix_unitaire'], 2) ?> €</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10 flex justify-between items-center">
                        <span class="text-2xl font-black italic">TOTAL HT</span>
                        <span class="text-3xl font-black text-amber-500"><?= number_format($commande['montant_total'], 2, ',', ' ') ?> €</span>
                    </div>

                    <?php if ($commande['statut'] === 'payee'): ?>
                        <div class="mt-6 text-right">
                            <a href="index.php?action=annulerCommande&id=<?= $commande['id'] ?>"
                               class="cancel-btn"
                               onclick="return confirm('Voulez-vous vraiment annuler cette commande ?')">
                                Annuler la commande
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function afficherProduits($produits, $associations, $titre = "Stock")
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            html, body {
                height: auto;
                overflow-y: visible;
                background-color: #020617;
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .header-section {
                position: sticky;
                top: 0;
                background: rgba(2, 6, 23, 0.9);
                backdrop-filter: blur(15px);
                border-bottom: 1px solid rgba(139, 92, 246, 0.2);
                padding: 1.5rem 0;
                z-index: 1000;
            }

            .content-limit {
                max-width: 1600px;
                margin: 0 auto;
                padding: 0 40px;
            }

            .products-grid {
                padding: 40px 0 100px 0;
            }

            .product-card {
                position: relative;
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(139, 92, 246, 0.2);
                border-radius: 2.5rem;
                transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .product-card:hover {
                transform: translateY(-10px);
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(139, 92, 246, 0.6);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            }

            .card-urgent {
                border-color: rgba(244, 63, 94, 0.3);
                box-shadow: 0 0 15px rgba(244, 63, 94, 0.1);
            }

            .filter-group {
                display: flex;
                align-items: center;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 1.25rem;
                padding: 0.5rem 1.2rem;
                gap: 0.8rem;
            }

            .filter-select {
                background: transparent;
                color: white;
                font-size: 10px;
                font-weight: 900;
                text-transform: uppercase;
                border: none;
                outline: none;
                cursor: pointer;
            }

            .filter-select option {
                background: #020617;
                color: white;
            }
        </style>

        <div class="header-section">
            <div class="content-limit flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-600 to-fuchsia-700 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-boxes-stacked text-xl text-white"></i>
                    </div>
                    <h1 class="text-4xl font-black uppercase italic tracking-tighter text-white leading-none">
                        <?= htmlspecialchars($titre ?? "Stock") ?>
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <div class="filter-group">
                        <i class="fa-solid fa-house-user text-violet-500"></i>
                        <select id="filterAsso" class="filter-select">
                            <option value="all">Toutes les assos</option>
                            <?php if (is_iterable($associations)): ?>
                                <?php foreach ($associations as $asso): ?>
                                    <?php if (!empty($asso['nom'])): ?>
                                        <option value="<?= htmlspecialchars($asso['nom']) ?>"><?= htmlspecialchars($asso['nom']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <i class="fa-solid fa-arrow-down-wide-short text-violet-500"></i>
                        <select id="filterSort" class="filter-select">
                            <option value="nom">Nom (A-Z)</option>
                            <option value="stock">Urgence Stock</option>
                            <option value="prix-desc">Prix Décroissant</option>
                            <option value="prix-asc">Prix Croissant</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-limit products-grid">
            <div id="productsWrapper" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">
                <?php
                if (is_iterable($produits)):
                    foreach ($produits as $p):
                        if (!is_array($p)) continue;

                        $stock = (int)($p['stock_asso'] ?? 0);
                        $nom = $p['nom'] ?? 'Inconnu';
                        $assoNom = $p['nom_association'] ?? 'Général';
                        $type = $p['type'] ?? '';
                        $prix = (float)($p['prix'] ?? 0);
                        $id = $p['id'] ?? 0;

                        $isLow = $stock < 15;
                        $colorHex = $isLow ? '#f43f5e' : '#8b5cf6';
                        ?>
                        <div class="product-card group p-8 <?= $isLow ? 'card-urgent' : '' ?>"
                             data-nom="<?= htmlspecialchars(strtolower($nom)) ?>"
                             data-asso="<?= htmlspecialchars($assoNom) ?>"
                             data-stock="<?= $stock ?>"
                             data-prix="<?= $prix ?>">

                            <div class="absolute -top-3 left-8 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-tighter shadow-md"
                                 style="background: <?= $colorHex ?>; color: white;">
                                <?= htmlspecialchars($assoNom) ?>
                            </div>

                            <div class="flex justify-between items-start mb-6">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center border border-white/10 bg-white/5"
                                     style="color: <?= $colorHex ?>; border-color: <?= $colorHex ?>44">
                                    <i class="fa-solid <?= ($type == 'boisson') ? 'fa-wine-glass' : 'fa-utensils' ?> text-2xl"></i>
                                </div>
                                <div class="text-right">
                                    <span class="font-black italic text-2xl tracking-tighter text-white"><?= number_format($prix, 2) ?>€</span>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h3 class="text-xl font-black uppercase mb-1 truncate text-white"><?= htmlspecialchars($nom) ?></h3>
                                <span class="text-[10px] font-bold uppercase" style="color: <?= $colorHex ?>">
                                <?= $isLow ? '<i class="fa-solid fa-triangle-exclamation mr-1"></i>Urgence Stock' : 'Stock Optimal' ?>
                            </span>
                            </div>

                            <div class="bg-black/40 rounded-3xl p-5 border border-white/5">
                                <div class="flex justify-between text-[10px] font-black mb-3 uppercase tracking-widest">
                                    <span class="text-slate-500">Stock</span>
                                    <span style="color: <?= $colorHex ?>"><?= $stock ?> UNITÉS</span>
                                </div>
                                <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full"
                                         style="width: <?= min(100, ($stock / 100) * 100) ?>%; background-color: <?= $colorHex ?>; box-shadow: 0 0 10px <?= $colorHex ?>;"></div>
                                </div>
                            </div>

                            <div class="mt-8 flex gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                <a href="index.php?action=modifierProduit&id=<?= $id ?>"
                                   class="flex-1 bg-white/5 py-4 rounded-xl text-center text-[9px] font-black uppercase text-white hover:bg-white/10 border border-white/5">Éditer</a>
                                <a href="index.php?action=ajouterStock&id=<?= $id ?>"
                                   class="flex-[2] py-4 rounded-xl text-center text-[9px] font-black uppercase text-white shadow-md"
                                   style="background-color: <?= $colorHex ?>;">+ Stock</a>
                            </div>
                        </div>
                    <?php endforeach;
                endif; ?>
            </div>
        </div>

        <script>
            const assoFilter = document.getElementById('filterAsso');
            const sortFilter = document.getElementById('filterSort');
            const wrapper = document.getElementById('productsWrapper');

            function updateDisplay() {
                let cards = Array.from(document.querySelectorAll('.product-card'));
                const selectedAsso = assoFilter.value;
                const sortBy = sortFilter.value;

                cards.forEach(card => {
                    const cardAsso = card.getAttribute('data-asso');
                    card.style.display = (selectedAsso === 'all' || cardAsso === selectedAsso) ? 'block' : 'none';
                });

                cards.sort((a, b) => {
                    if (sortBy === 'nom') return a.getAttribute('data-nom').localeCompare(b.getAttribute('data-nom'));
                    if (sortBy === 'stock') return parseInt(a.getAttribute('data-stock')) - parseInt(b.getAttribute('data-stock'));
                    if (sortBy === 'prix-asc') return parseFloat(a.getAttribute('data-prix')) - parseFloat(b.getAttribute('data-prix'));
                    if (sortBy === 'prix-desc') return parseFloat(b.getAttribute('data-prix')) - parseFloat(a.getAttribute('data-prix'));
                    return 0;
                });

                cards.forEach(card => wrapper.appendChild(card));
            }

            assoFilter.addEventListener('change', updateDisplay);
            sortFilter.addEventListener('change', updateDisplay);
            window.addEventListener('DOMContentLoaded', updateDisplay);
        </script>
        <?php
    }

    public function formulaireStock($produit)
    {
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <div class="min-h-screen bg-[#020617] flex items-center justify-center p-6 font-sans">

            <div class="absolute w-96 h-96 bg-violet-600/10 blur-[120px] rounded-full -z-10 animate-pulse"></div>

            <div class="w-full max-w-md bg-white/[0.03] border border-white/10 backdrop-blur-2xl rounded-[3.5rem] p-10 shadow-2xl relative overflow-hidden group">

                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fa-solid fa-layer-group text-6xl text-white"></i>
                </div>

                <div class="text-center mb-10 relative">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-violet-500/20 to-fuchsia-500/20 text-violet-400 rounded-3xl mb-6 shadow-inner border border-white/5">
                        <i class="fa-solid fa-arrow-up-right-dots text-3xl"></i>
                    </div>

                    <h2 class="text-4xl font-black uppercase italic tracking-tighter text-white mb-2 pr-5">
                        Mise à jour <span class="text-violet-500 italic">Stock</span>
                    </h2>

                    <div class="inline-block px-4 py-1.5 bg-violet-500/10 border border-violet-500/20 rounded-full">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-violet-300">
                            <?= htmlspecialchars($produit['nom']) ?>
                        </p>
                    </div>
                </div>

                <form action="index.php?action=ajouterStock" method="POST" class="space-y-8 relative">
                    <input type="hidden" name="id_produit" value="<?= $produit['id'] ?>">

                    <div class="bg-black/40 rounded-[2.5rem] p-8 border border-white/5 shadow-inner transition-all hover:border-violet-500/30">

                        <div class="flex justify-between items-center mb-6">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-500">État actuel</span>
                                <span class="text-xl font-bold text-white"><?= $produit['quantiteActuelle'] ?> <small
                                            class="text-[10px] text-slate-500 uppercase">unités</small></span>
                            </div>
                            <div class="h-10 w-[1px] bg-white/10"></div>
                            <div class="flex flex-col text-right">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-500">Catégorie</span>
                                <span class="text-sm font-bold text-violet-400 uppercase tracking-tighter"><?= htmlspecialchars($produit['type'] ?? 'Produit') ?></span>
                            </div>
                        </div>

                        <div class="relative group/input">
                            <label class="block text-[10px] font-black text-violet-500 uppercase tracking-[0.3em] mb-3 px-1">Quantité
                                à réceptionner</label>
                            <input type="number" name="quantite" required min="1" autofocus placeholder="00"
                                   class="w-full bg-[#0f172a]/50 border-2 border-white/5 text-white text-center text-5xl font-black py-6 rounded-[2rem] focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 outline-none transition-all duration-300 placeholder:text-white/5">

                            <div class="absolute left-6 top-[60%] -translate-y-1/2 text-violet-600 font-black text-xl">
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
                                class="flex-[2] bg-violet-600 hover:bg-violet-500 text-white text-[10px] font-black uppercase py-5 rounded-2xl shadow-xl shadow-violet-900/40 transition-all hover:-translate-y-1 active:scale-95 order-1 sm:order-2">
                            Valider l'entrée stock <i class="fa-solid fa-check ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public function formulaireInventaire($produits, $id_assos)
    {
        $this->afficherNav();
        ?>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

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
                box-shadow: 0 0 20px rgba(249, 115, 22, 0.2);
                background: rgba(249, 115, 22, 0.05);
            }

            input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        </style>

        <div class="max-w-5xl mx-auto p-12">
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
                <form action="index.php?module=gestionnaire&action=enregistrerInventaire" method="POST" class="p-10">
                    <input type="hidden" name="association_id" value="<?= htmlspecialchars($id_assos) ?>">

                    <table class="w-full">
                        <thead>
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5">
                            <th class="pb-6 text-left">Produit</th>
                            <th class="pb-6 text-center">Théorique (Bar)</th>
                            <th class="pb-6 text-right">Réel (Physique)</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php foreach ($produits as $p):
                            $stockTheorique = isset($p['stock_asso']) ? $p['stock_asso'] : $p['quantiteActuelle'];
                            ?>
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_#f97316]"></div>
                                        <div>
                                            <p class="font-black text-slate-200 uppercase tracking-tight"><?= htmlspecialchars($p['nom']) ?></p>
                                            <p class="text-[10px] text-slate-600 font-bold uppercase"><?= htmlspecialchars($p['type'] ?? 'Produit') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-center">
                                    <span class="font-mono text-lg text-slate-400"><?= $stockTheorique ?></span>
                                </td>
                                <td class="py-6 text-right">
                                    <input type="number"
                                           name="stock_reel[<?= $p['id'] ?>]"
                                           value="<?= $stockTheorique ?>"
                                           class="w-32 py-3 px-4 rounded-xl input-orange font-black text-center text-lg"
                                           required min="0">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="mt-12 flex justify-between items-center">
                        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-widest italic">
                            <i class="fa-solid fa-circle-info mr-2"></i> La validation écrasera le stock théorique actuel.
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

}