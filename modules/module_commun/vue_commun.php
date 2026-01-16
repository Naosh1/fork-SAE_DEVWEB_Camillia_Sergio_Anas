<?php
include_once 'vue_generique.php';

class VueCommun extends VueGenerique
{
    protected function e($str)
    {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }

    public function afficherNav()
    {
    }

    public function finirPage()
    {
        echo '
            </main>
        </div> 
        <footer class="p-8 text-center text-slate-600 text-[10px] font-black uppercase tracking-[0.3em]">
            &copy; 2026 AssoManager - Système de Gestion
        </footer>
        </body>
        </html>';
    }

    // === MESSAGERIE ===

    public function afficherMesMessages($messages, $id_gestionnaire)
    {
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

            /* État actif des onglets de la sidebar */
            .sidebar-link.active {
                background: rgba(59, 130, 246, 0.1) !important;
                color: #3b82f6 !important;
                border-right: 3px solid #3b82f6;
            }

            /* Classe utilitaire pour le filtrage JS */
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="js/recherche_contact.js"></script>
    <style>
        .custom-font, .custom-font input, .custom-font select, .custom-font textarea, .custom-font button {
            font-family: "Inter", sans-serif !important;
        }
    </style>';
        ?>
        <div class="w-full h-full px-6 md:px-12 py-10 bg-[#020617] min-h-screen text-white custom-font">
            <div class="max-w-4xl mx-auto">
                <form action="index.php?action=envoyerMessageGlobal" method="POST"
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
                            ENVOYER LE MESSAGE
                        </button>
                        <a href="index.php?action=mesMessages"
                           class="px-8 py-5 bg-white/5 hover:bg-white/10 text-slate-400 rounded-2xl transition-all font-black uppercase text-[10px] flex items-center">
                            ANNULER
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public function afficherProfil($user)
    {
        $this->afficherNav();
        $prenom = $user['prenom'] ?? 'Utilisateur';
        $nom = $user['nom'] ?? 'Inconnu';
        $email = $user['email'] ?? 'Non renseigné';
        $tel = ($user['tel'] === 'Non renseigné') ? '' : ($user['tel'] ?? '');
        $role = $user['role'] ?? 'Gestionnaire';
        $solde = $user['solde'] ?? 0;

        $date_db = $user['date_naissance'] ?? '';
        $date_formatee = (!empty($date_db) && $date_db !== '0000-00-00') ? date('Y-m-d', strtotime($date_db)) : "";
        $pp = !empty($user['photo']) ? 'uploads/profiles/' . $user['photo'] : null;
        ?>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

            body {
                background: #020617;
                font-family: 'Plus Jakarta Sans', sans-serif;
                overflow: hidden;
                color: white;
                margin: 0;
            }

            .bg-glow {
                position: fixed;
                inset: 0;
                background: radial-gradient(circle at 50% -20%, #2e1065 0%, #020617 80%);
                z-index: -1;
            }

            .viewport-height {
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            .glass-container {
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(30px);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 2.5rem;
                width: 100%;
                max-width: 1100px;
                height: 85vh;
                display: flex;
                overflow: hidden;
                box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
            }

            .sidebar {
                width: 35%;
                background: rgba(255, 255, 255, 0.02);
                border-right: 1px solid rgba(255, 255, 255, 0.05);
                display: flex;
                flex-direction: column;
                padding: 2.5rem;
            }

            .main-content {
                width: 65%;
                padding: 3rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                overflow-y: auto;
            }

            .input-group {
                margin-bottom: 1.2rem;
            }

            .input-wrapper {
                position: relative;
                width: 100%;
            }

            .input-wrapper i {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #a855f7;
                font-size: 0.9rem;
                opacity: 0.7;
                pointer-events: none;
            }

            .profile-input {
                background: rgba(0, 0, 0, 0.3);
                border: 1px solid rgba(168, 85, 247, 0.1);
                color: white;
                padding: 0.8rem 1rem 0.8rem 2.8rem;
                border-radius: 1rem;
                width: 100%;
                font-size: 0.85rem;
                transition: 0.4s;
                box-sizing: border-box;
            }

            .profile-input:focus {
                border-color: #a855f7;
                background: rgba(0, 0, 0, 0.5);
                outline: none;
            }

            label {
                color: #94a3b8;
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.6rem;
                letter-spacing: 0.1em;
                margin-bottom: 0.5rem;
                display: block;
                margin-left: 0.5rem;
            }

            #security-section {
                display: none;
                opacity: 0;
                transform: translateY(10px);
                transition: 0.4s ease;
            }

            .btn-update {
                background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
                font-weight: 800;
                text-transform: uppercase;
                font-size: 0.75rem;
                padding: 1rem 2.5rem;
                border-radius: 1.2rem;
                box-shadow: 0 10px 20px -10px rgba(124, 58, 237, 0.5);
                border: none;
                cursor: pointer;
                color: white;
                transition: 0.3s;
            }

            .btn-cancel-secu {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
                border: none;
                cursor: pointer;
                width: 40px;
                height: 40px;
                border-radius: 0.8rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .order-card {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 1rem;
                padding: 0.75rem;
                margin-bottom: 0.5rem;
                font-size: 0.75rem;
            }

            .custom-scroll::-webkit-scrollbar {
                width: 3px;
            }

            .custom-scroll::-webkit-scrollbar-thumb {
                background: rgba(168, 85, 247, 0.2);
                border-radius: 10px;
            }
        </style>

        <div class="bg-glow"></div>
        <div class="viewport-height">
            <form id="formProfil" action="index.php?action=updateProfil" method="POST" enctype="multipart/form-data"
                  onsubmit="return validerFormulaire()" style="width: 100%; display: flex; justify-content: center;">
                <div class="glass-container">
                    <div class="sidebar">
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div style="position: relative; display: inline-block; cursor: pointer;"
                                 onclick="document.getElementById('pp_upload').click()">
                                <div style="width: 128px; height: 128px; border-radius: 2.5rem; background: linear-gradient(to top right, #9333ea, #3b82f6); padding: 3px;">
                                    <div style="width: 100%; height: 100%; border-radius: 2.3rem; background: #020617; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <?php if ($pp && file_exists($pp)): ?>
                                            <img src="<?= $pp ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span style="font-size: 3rem; font-weight: 900; color: #a855f7;"><?= strtoupper(substr($prenom, 0, 1)) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <input type="file" id="pp_upload" name="profile_picture" style="display: none;"
                                       accept="image/*" onchange="submitPP()">
                            </div>
                            <h2 style="margin-top: 1rem; font-size: 1.25rem; font-weight: 800; text-transform: uppercase; italic;"><?= htmlspecialchars($prenom) ?>
                                <span style="color: #a855f7;"><?= htmlspecialchars($nom) ?></span></h2>
                            <span style="font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.3em;"><?= $role ?></span>
                        </div>

                        <div style="display: flex; gap: 0.75rem; margin-bottom: 2rem;">
                            <div style="flex: 1; background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 1rem; text-align: center;">
                                <p style="font-size: 0.55rem; color: #64748b; font-weight: 700; text-transform: uppercase;">
                                    Solde</p>
                                <p style="font-size: 1.1rem; font-weight: 900;"><?= number_format($solde, 2) ?>€</p>
                            </div>
                        </div>
                    </div>

                    <div class="main-content">
                        <div>
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                                <h3 style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; font-style: italic;">
                                    Édition Profil</h3>
                                <div style="height: 1px; background: rgba(255,255,255,0.05); flex: 1;"></div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 1.5rem;">
                                <div class="input-group">
                                    <label>Prénom</label>
                                    <div class="input-wrapper"><i class="fa-solid fa-user"></i><input type="text"
                                                                                                      name="prenom"
                                                                                                      value="<?= htmlspecialchars($prenom) ?>"
                                                                                                      class="profile-input"
                                                                                                      required></div>
                                </div>
                                <div class="input-group">
                                    <label>Nom</label>
                                    <div class="input-wrapper"><i class="fa-solid fa-signature"></i><input type="text"
                                                                                                           name="nom"
                                                                                                           value="<?= htmlspecialchars($nom) ?>"
                                                                                                           class="profile-input"
                                                                                                           required>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <label>Email</label>
                                    <div class="input-wrapper"><i class="fa-solid fa-envelope"></i><input type="email"
                                                                                                          name="email"
                                                                                                          value="<?= htmlspecialchars($email) ?>"
                                                                                                          class="profile-input"
                                                                                                          required>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <label>Téléphone</label>
                                    <div class="input-wrapper"><i class="fa-solid fa-phone"></i><input type="text"
                                                                                                       id="tel_input"
                                                                                                       name="tel"
                                                                                                       value="<?= htmlspecialchars($tel) ?>"
                                                                                                       class="profile-input"
                                                                                                       maxlength="10">
                                    </div>
                                </div>
                            </div>

                            <button type="button" onclick="toggleSecurity(true)" id="btn-secu-toggle"
                                    style="background: none; border: none; color: #a855f7; cursor: pointer; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; margin-top: 1rem;">
                                <i class="fa-solid fa-lock"></i> Modifier le mot de passe
                            </button>

                            <div id="security-section"
                                 style="grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1rem;">
                                <div class="input-group">
                                    <label>Ancien</label>
                                    <input type="password" name="old_password" id="old_password" class="profile-input">
                                </div>
                                <div class="input-group">
                                    <label>Nouveau</label>
                                    <input type="password" id="mdp1" name="new_password" class="profile-input"
                                           oninput="toggleConfirmField()">
                                </div>
                                <div class="input-group">
                                    <label>Confirmer</label>
                                    <input type="password" id="mdp2" class="profile-input" disabled
                                           style="opacity: 0.4;">
                                </div>
                                <button type="button" onclick="toggleSecurity(false)" class="btn-cancel-secu"><i
                                            class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2rem;">
                            <button type="submit" class="btn-update">Enregistrer les modifications</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script>
            function toggleSecurity(show) {
                const sec = document.getElementById('security-section');
                const btn = document.getElementById('btn-secu-toggle');
                sec.style.display = show ? 'grid' : 'none';
                btn.style.display = show ? 'none' : 'inline-block';
                if (show) setTimeout(() => sec.style.opacity = "1", 10);
                document.getElementById('old_password').required = show;
            }

            function toggleConfirmField() {
                const m1 = document.getElementById('mdp1');
                const m2 = document.getElementById('mdp2');
                m2.disabled = (m1.value.length === 0);
                m2.style.opacity = m2.disabled ? "0.4" : "1";
            }

            function validerFormulaire() {
                const m1 = document.getElementById('mdp1').value;
                const m2 = document.getElementById('mdp2').value;
                if (m1 !== "" && m1 !== m2) {
                    alert("Mots de passe différents");
                    return false;
                }
                const tel = document.getElementById('tel_input').value;
                if (tel !== "" && tel.length !== 10) {
                    alert("Le téléphone doit faire 10 chiffres");
                    return false;
                }
                return true;
            }

            function submitPP() {
                const form = document.getElementById('formProfil');
                document.getElementById('old_password').required = false;
                form.action = "index.php?action=modifierPP";
                form.submit();
            }
        </script>
        <?php
    }


    protected function afficherAlerte($type, $message)
    {
        $colors = [
                'success' => 'bg-green-500/10 border-green-500 text-green-500',
                'error' => 'bg-red-500/10 border-red-500 text-red-500'
        ];
        $class = $colors[$type] ?? 'bg-blue-500/10 border-blue-500 text-blue-500';
        echo '<div class="border-l-4 p-4 mb-4 rounded-xl ' . $class . ' font-bold uppercase text-[10px] tracking-widest">';
        echo $this->e($message);
        echo '</div>';
    }
}