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
        $photo = $_SESSION['photo'] ?? null;
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
                <a href="index.php?action=profil" class="flex items-center gap-3 mb-4 px-2 p-2 rounded-2xl transition-all group hover:bg-white/5 ' . ($actionActuelle == 'profil' ? 'ring-1 ring-blue-500/50 bg-blue-600/5' : '') . '">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 p-0.5 shadow-lg shadow-blue-900/40 group-hover:scale-110 transition-transform overflow-hidden">
                            <div class="w-full h-full rounded-full bg-[#020617] flex items-center justify-center overflow-hidden">';

        if (!empty($photo)) {
            echo '<img src="uploads/profiles/' . $photo . '" class="w-full h-full object-cover" onerror="this.src=\'https://ui-avatars.com/api/?name=' . $prenom . '&background=random\'">';
        } else {
            echo '<span class="text-white font-black text-sm">' . strtoupper(substr($prenom, 0, 1)) . '</span>';
        }

        echo '                  </div>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-[#020617] rounded-full"></div>
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-xs font-black text-white truncate uppercase tracking-tighter">' . htmlspecialchars($prenom) . '</p>
                        <p class="text-[10px] font-bold text-blue-500/80 uppercase tracking-widest leading-none mt-1 group-hover:text-blue-400">Voir profil</p>
                    </div>
                </a>
                
                <a href="index.php?action=deconnexion" class="flex items-center justify-center gap-2 w-full bg-white/5 hover:bg-rose-600/10 text-slate-400 hover:text-rose-500 py-3 rounded-2xl transition-all duration-300 text-[11px] font-black uppercase tracking-widest border border-white/5 hover:border-rose-500/20">
                    <i class="fa-solid fa-power-off text-xs"></i> Déconnexion
                </a>
            </div>
        </div>
    </aside>
    <main class="flex-1 overflow-x-hidden">';
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

            body { background: #020617; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; color: white; margin: 0; }
            .bg-glow { position: fixed; inset: 0; background: radial-gradient(circle at 50% -20%, #2e1065 0%, #020617 80%); z-index: -1; }

            .viewport-height { height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }

            .glass-container {
                background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(30px);
                border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 2.5rem;
                width: 100%; max-width: 1100px; height: 85vh; display: flex; overflow: hidden;
                box-shadow: 0 0 50px rgba(0,0,0,0.5);
            }

            .sidebar { width: 35%; background: rgba(255, 255, 255, 0.02); border-right: 1px solid rgba(255, 255, 255, 0.05); display: flex; flex-direction: column; padding: 2.5rem; }

            .main-content { width: 65%; padding: 3rem; display: flex; flex-direction: column; justify-content: space-between; overflow-y: auto; }

            .input-group { margin-bottom: 1.2rem; }
            .input-wrapper { position: relative; width: 100%; }

            .input-wrapper i {
                position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
                color: #a855f7; font-size: 0.9rem; opacity: 0.7; pointer-events: none;
            }

            .profile-input {
                background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(168, 85, 247, 0.1);
                color: white; padding: 0.8rem 1rem 0.8rem 2.8rem; border-radius: 1rem;
                width: 100%; font-size: 0.85rem; transition: 0.4s; box-sizing: border-box;
            }
            .profile-input:focus { border-color: #a855f7; background: rgba(0, 0, 0, 0.5); outline: none; }

            label { color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 0.6rem; letter-spacing: 0.1em; margin-bottom: 0.5rem; display: block; margin-left: 0.5rem; }

            #security-section {
                display: none;
                opacity: 0;
                transform: translateY(10px);
                transition: opacity 0.4s ease, transform 0.4s ease;
            }

            .btn-update {
                background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
                font-weight: 800; text-transform: uppercase; font-size: 0.75rem; padding: 1rem 2.5rem; border-radius: 1.2rem;
                box-shadow: 0 10px 20px -10px rgba(124, 58, 237, 0.5); transition: 0.3s; border: none; cursor: pointer; color: white;
            }

            .btn-cancel-secu {
                background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; cursor: pointer;
                width: 40px; height: 40px; border-radius: 0.8rem; transition: 0.3s;
                display: flex; align-items: center; justify-content: center; margin-bottom: 1.2rem;
            }
            .btn-cancel-secu:hover { background: #ef4444; color: white; }

            .order-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 1rem; padding: 0.75rem; margin-bottom: 0.5rem; font-size: 0.75rem; }
            .custom-scroll::-webkit-scrollbar { width: 3px; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(168, 85, 247, 0.2); border-radius: 10px; }
        </style>

        <div class="bg-glow"></div>

        <div class="viewport-height">
            <form id="formProfil" action="index.php?action=updateProfil" method="POST" enctype="multipart/form-data" onsubmit="return validerFormulaire()" style="width: 100%; display: flex; justify-content: center;">
                <div class="glass-container">

                    <div class="sidebar">
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div style="position: relative; display: inline-block; cursor: pointer;" onclick="document.getElementById('pp_upload').click()">
                                <div style="width: 128px; height: 128px; border-radius: 2.5rem; background: linear-gradient(to top right, #9333ea, #3b82f6); padding: 3px; overflow: hidden;">
                                    <div style="width: 100%; height: 100%; border-radius: 2.3rem; background: #020617; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <?php if ($pp && file_exists($pp)): ?>
                                            <img src="<?= $pp ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span style="font-size: 3rem; font-weight: 900; color: #a855f7;"><?= strtoupper(substr($prenom, 0, 1)) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <input type="file" id="pp_upload" name="profile_picture" style="display: none;" accept="image/*" onchange="submitPP()">
                                <div style="position: absolute; bottom: -4px; right: -4px; background: #9333ea; width: 32px; height: 32px; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 2px solid #020617;"><i class="fa-solid fa-camera" style="font-size: 10px; color: white;"></i></div>
                            </div>
                            <h2 style="margin-top: 1rem; font-size: 1.25rem; font-weight: 800; text-transform: uppercase; font-style: italic;">
                                <?= htmlspecialchars($prenom) ?> <span style="color: #a855f7;"><?= htmlspecialchars($nom) ?></span>
                            </h2>
                            <span style="font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.3em; display: block;"><?= $role ?></span>
                        </div>

                        <div style="display: flex; gap: 0.75rem; margin-bottom: 2rem;">
                            <div style="flex: 1; background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 1rem; text-align: center;">
                                <p style="font-size: 0.55rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Solde</p>
                                <p style="font-size: 1.1rem; font-weight: 900;"><?= number_format($solde, 2) ?>€</p>
                            </div>
                            <div style="flex: 1; background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 1rem; text-align: center;">
                                <p style="font-size: 0.55rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Points</p>
                                <p style="font-size: 1.1rem; font-weight: 900; color: #f59e0b;">1.2k</p>
                            </div>
                        </div>

                        <div style="flex: 1; display: flex; flex-direction: column; min-height: 0;">
                            <h3 style="font-size: 0.6rem; font-weight: 900; color: #a855f7; text-transform: uppercase; margin-bottom: 1rem; font-style: italic;">Dernières Commandes</h3>
                            <div class="custom-scroll" style="overflow-y: auto; flex: 1;">
                                <div class="order-card" style="display: flex; justify-content: space-between;"><span>#CMD-850</span><span style="color: #34d399; font-weight: 700;">12.00€</span></div>
                                <div class="order-card" style="display: flex; justify-content: space-between;"><span>#CMD-842</span><span style="color: #34d399; font-weight: 700;">45.50€</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="main-content">
                        <div>
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                                <h3 style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; font-style: italic;">Édition Profil</h3>
                                <div style="height: 1px; background: rgba(255,255,255,0.05); flex: 1;"></div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 1.5rem;">
                                <div class="input-group">
                                    <label>Prénom</label>
                                    <div class="input-wrapper"><i class="fa-solid fa-user"></i><input type="text" name="prenom" value="<?= htmlspecialchars($prenom) ?>" class="profile-input" required></div>
                                </div>
                                <div class="input-group">
                                    <label>Nom</label>
                                    <div class="input-wrapper"><i class="fa-solid fa-signature"></i><input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" class="profile-input" required></div>
                                </div>
                                <div class="input-group">
                                    <label>Email professionnel</label>
                                    <div class="input-wrapper"><i class="fa-solid fa-envelope"></i><input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="profile-input" required></div>
                                </div>
                                <div class="input-group">
                                    <label>Téléphone (10 chiffres)</label>
                                    <div class="input-wrapper">
                                        <i class="fa-solid fa-phone"></i>
                                        <input type="text" id="tel_input" name="tel" value="<?= htmlspecialchars($tel) ?>" class="profile-input" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="input-group" style="grid-column: span 2;">
                                    <label>Date de Naissance</label>
                                    <div class="input-wrapper"><i class="fa-solid fa-calendar-day"></i><input type="text" value="<?= $date_formatee ?>" class="profile-input" style="border-style: dashed; opacity: 0.4;" readonly></div>
                                </div>
                            </div>

                            <div style="margin-top: 1rem;">
                                <button type="button" onclick="toggleSecurity(true)" id="btn-secu-toggle" style="background: none; border: none; color: #a855f7; cursor: pointer; text-decoration: underline; font-size: 0.65rem; font-weight: 900; text-transform: uppercase;">
                                    <i class="fa-solid fa-lock"></i> Modifier le mot de passe
                                </button>

                                <div id="security-section" style="grid-template-columns: repeat(10, 1fr); gap: 1rem; margin-top: 1rem;">
                                    <div style="grid-column: span 3;" class="input-group">
                                        <label style="color: #f59e0b;">Actuel</label>
                                        <div class="input-wrapper"><i class="fa-solid fa-lock-open"></i><input type="password" name="old_password" id="old_password" class="profile-input" style="border-color: rgba(245, 158, 11, 0.2);"></div>
                                    </div>
                                    <div style="grid-column: span 3;" class="input-group">
                                        <label>Nouveau</label>
                                        <div class="input-wrapper"><i class="fa-solid fa-key"></i><input type="password" id="mdp1" name="new_password" class="profile-input" oninput="toggleConfirmField()"></div>
                                    </div>
                                    <div style="grid-column: span 3;" class="input-group">
                                        <label>Confirmation</label>
                                        <div class="input-wrapper"><i class="fa-solid fa-check-double"></i><input type="password" id="mdp2" class="profile-input" disabled style="opacity: 0.4;"></div>
                                    </div>
                                    <div style="grid-column: span 1; display: flex; align-items: flex-end;">
                                        <button type="button" onclick="toggleSecurity(false)" class="btn-cancel-secu"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2rem;">
                            <span style="font-size: 0.6rem; color: #64748b; font-weight: 800; text-transform: uppercase; font-style: italic;">Système Sécurisé</span>
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
                if(show) {
                    sec.style.display = 'grid';
                    setTimeout(() => { sec.style.opacity = '1'; sec.style.transform = 'translateY(0)'; }, 10);
                    btn.style.display = 'none';
                    document.getElementById('old_password').required = true;
                } else {
                    sec.style.opacity = '0';
                    sec.style.transform = 'translateY(10px)';
                    setTimeout(() => { sec.style.display = 'none'; btn.style.display = 'inline-block'; }, 400);
                    sec.querySelectorAll('input').forEach(i => { i.value = ""; i.required = false; });
                    document.getElementById('mdp2').disabled = true;
                }
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
                if (m1 !== "" && m1 !== m2) { alert("Les mots de passe ne correspondent pas"); return false; }

                const tel = document.getElementById('tel_input').value;
                if (tel !== "" && tel.length !== 10) { alert("Le numéro de téléphone doit comporter 10 chiffres"); return false; }
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
    public function afficherTableauDeBordAccueil($prenom, $data)
    {
        $this->afficherNav();

        $assos = $data['associations'] ?? [];
        $alertes = $data['alertes'] ?? [];
        $topProduits = $data['topProduits'] ?? [];
        $topBarmans = $data['topBarmans'] ?? [];
        $totalSolde = array_sum(array_column($assos, 'solde'));
        $nbBarmans = $data['nbBarmans'] ?? 0;
        $totalPertes = $data['totalPertes'] ?? 0;

        $raccourcis = [
                ['action' => 'ajouterProduit', 'label' => 'Nouveau Produit', 'icon' => 'fa-plus-circle'],
                ['action' => 'ajouterBarman',  'label' => 'Recruter Staff',  'icon' => 'fa-user-plus'],
                ['action' => 'voirProduits',   'label' => 'Gestion Stocks',  'icon' => 'fa-boxes-stacked'],
                ['action' => 'profil',         'label' => 'Mon Profil',      'icon' => 'fa-user-gear'],
        ];
        ?>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background: #020617 radial-gradient(circle at 50% -10%, #451a03 0%, #020617 70%) no-repeat fixed;
                margin: 0; height: 100vh; display: flex; flex-direction: column; overflow: hidden;
                font-family: 'Plus Jakarta Sans', sans-serif; color: #f8fafc;
            }
            .scrollable-content { flex-grow: 1; overflow-y: auto; scroll-behavior: smooth; }
            .content-limit { max-width: 1600px; margin: 0 auto; padding: 3rem 4rem; }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
            .reveal { opacity: 0; animation: fadeInUp 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }

            .glass-card {
                background: rgba(255, 255, 255, 0.02);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(245, 158, 11, 0.15);
                border-radius: 2rem;
                transition: all 0.4s ease;
            }
            .glass-card:hover {
                border-color: rgba(245, 158, 11, 0.5);
                transform: translateY(-6px);
                box-shadow: 0 20px 40px -15px rgba(0,0,0,0.7);
            }

            .text-stat { font-size: 2.8rem; font-weight: 950; letter-spacing: -2px; line-height: 1; }
            .bar-container { background: rgba(255,255,255,0.05); border-radius: 20px; height: 12px; margin-top: 10px; overflow: hidden;}
            .bar-fill { height: 100%; background: linear-gradient(90deg, #f59e0b, #fbbf24); transition: width 1.5s ease-out; box-shadow: 0 0 15px rgba(245,158,11,0.3); }

            .staff-badge {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.08);
                padding: 1.25rem; border-radius: 1.5rem;
                display: flex; align-items: center; gap: 18px;
            }

            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(245, 158, 11, 0.3); border-radius: 10px; }

            .btn-glow:hover { box-shadow: 0 0 25px rgba(245, 158, 11, 0.4); }
        </style>

        <div class="scrollable-content custom-scrollbar">
            <div class="content-limit">

                <div class="flex justify-between items-center mb-12 reveal">
                    <div>
                        <h1 class="text-5xl font-black text-white uppercase italic tracking-tighter">
                            Dashboard
                        </h1>
                        <p class="text-lg text-slate-400 font-medium mt-2">
                            Ravi de vous revoir, <span class="text-white font-bold border-b-2 border-amber-500"><?= htmlspecialchars($prenom) ?></span>
                        </p>
                    </div>
                    <?php if (!empty($alertes)): ?>
                        <div class="bg-rose-500/15 border border-rose-500/30 text-rose-500 px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest animate-pulse shadow-lg shadow-rose-900/20">
                            <i class="fa-solid fa-triangle-exclamation mr-2 text-sm"></i> <?= count($alertes) ?> Alertes critiques
                        </div>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 reveal" style="animation-delay: 0.1s">
                    <div class="glass-card p-8 border-l-8 border-amber-500">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Trésorerie Actuelle</span>
                        <div class="text-stat text-white mt-3"><?= number_format($totalSolde, 2, ',', ' ') ?>€</div>
                    </div>
                    <div class="glass-card p-8 border-l-8 border-rose-600">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Pertes Totales</span>
                        <div class="text-stat text-rose-500 mt-3"><?= number_format($totalPertes, 2, ',', ' ') ?>€</div>
                    </div>
                    <div class="glass-card p-8">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Effectif Bar</span>
                        <div class="text-stat text-white mt-3"><?= $nbBarmans ?> <span class="text-sm text-slate-600 uppercase tracking-normal">Staff</span></div>
                    </div>
                    <div class="glass-card p-8 bg-amber-500/5">
                        <span class="text-xs font-black text-amber-500 uppercase tracking-[0.2em]">Associations</span>
                        <div class="text-stat text-amber-500 mt-3"><?= count($assos) ?></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-12 reveal" style="animation-delay: 0.2s">
                    <div class="lg:col-span-2 glass-card p-10">
                        <h3 class="text-xl font-black text-white uppercase mb-10 italic flex items-center">
                            <i class="fa-solid fa-fire text-amber-500 mr-4"></i> Analyse des Performances Produits
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                            <?php if (!empty($topProduits)):
                                $sample = reset($topProduits);
                                $key = isset($sample['nb_ventes']) ? 'nb_ventes' : (isset($sample['total_vendu']) ? 'total_vendu' : (isset($sample['quantite']) ? 'quantite' : 'nb_ventes'));
                                $maxV = max(array_column($topProduits, $key) ?: [1]);
                                foreach (array_slice($topProduits, 0, 4) as $top):
                                    $valeur = $top[$key] ?? 0;
                                    $pct = ($valeur / $maxV) * 100; ?>
                                    <div class="group">
                                        <div class="flex justify-between items-end mb-2">
                                            <span class="text-sm font-black text-slate-200 uppercase tracking-wide group-hover:text-amber-500 transition-colors"><?= htmlspecialchars($top['nom']) ?></span>
                                            <span class="text-lg font-black text-amber-500"><?= $valeur ?> <span class="text-[10px] text-slate-600">UNITÉS</span></span>
                                        </div>
                                        <div class="bar-container"><div class="bar-fill" style="width: <?= $pct ?>%"></div></div>
                                    </div>
                                <?php endforeach;
                            else: ?>
                                <p class="text-slate-600 font-bold italic">Données de flux indisponibles pour le moment.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="glass-card p-10">
                        <h3 class="text-xl font-black text-white uppercase mb-10 italic">
                            <i class="fa-solid fa-medal text-amber-500 mr-4"></i> Top Staff
                        </h3>
                        <div class="space-y-4">
                            <?php if(!empty($topBarmans)):
                                $keyStaff = isset($topBarmans[0]['total_ventes']) ? 'total_ventes' : 'nb_ventes';
                                foreach(array_slice($topBarmans, 0, 3) as $idx => $b): ?>
                                    <div class="staff-badge group hover:bg-white/5 transition-all">
                                        <div class="w-12 h-12 rounded-xl bg-amber-500 text-black flex items-center justify-center font-black text-lg shadow-lg shadow-amber-900/20">
                                            #<?= $idx + 1 ?>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-black text-white uppercase tracking-wider"><?= htmlspecialchars($b['prenom'] ?? 'Anonyme') ?></p>
                                            <p class="text-xs text-amber-500/70 font-bold uppercase"><?= $b[$keyStaff] ?? 0 ?> Ventes conclues</p>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?>
                                <div class="h-48 flex flex-col items-center justify-center border-2 border-dashed border-white/5 rounded-3xl">
                                    <i class="fa-solid fa-user-clock text-slate-700 text-3xl mb-3"></i>
                                    <p class="text-xs text-slate-600 font-black uppercase">En attente de données</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 reveal" style="animation-delay: 0.3s">
                    <div class="lg:col-span-3 grid grid-cols-2 md:grid-cols-4 gap-6">
                        <?php foreach ($raccourcis as $r): ?>
                            <a href="index.php?action=<?= $r['action'] ?>" class="glass-card p-8 flex flex-col items-center gap-4 group hover:bg-amber-500 btn-glow transition-all">
                                <i class="fa-solid <?= $r['icon'] ?> text-3xl text-amber-500 group-hover:text-black transition-colors"></i>
                                <span class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 group-hover:text-black transition-colors"><?= $r['label'] ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <div class="glass-card p-6 flex flex-col items-center justify-center text-center">
                        <div class="relative flex h-5 w-5 mb-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-5 w-5 bg-amber-500 shadow-lg shadow-amber-500/50"></span>
                        </div>
                        <p class="text-xs font-black text-white uppercase tracking-widest leading-none">Amber Live Engine</p>
                        <p class="text-[10px] text-slate-500 font-bold italic mt-2 uppercase tracking-tight">Système de gestion synchronisé</p>
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
                -webkit-appearance: none;
                margin: 0;
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
                <p class="text-slate-500 font-medium mt-2">Ajustez les stocks physiques pour corriger les écarts de
                    vente.</p>
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
        <style>
            @keyframes slideInRight {
                from { opacity: 0; transform: translateX(-15px); }
                to { opacity: 1; transform: translateX(0); }
            }

            .cascade-row {
                opacity: 0;
                animation: slideInRight 0.4s ease forwards;
            }

            .glass-table-container {
                background: rgba(255, 255, 255, 0.02);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(245, 158, 11, 0.1);
            }

            .amber-btn {
                background: rgba(245, 158, 11, 0.1);
                border: 1px solid rgba(245, 158, 11, 0.2);
                color: #fbbf24;
                transition: all 0.3s ease;
            }

            .amber-btn:hover {
                background: #f59e0b;
                color: #020617;
                box-shadow: 0 0 20px rgba(245, 158, 11, 0.4);
            }

            <?php for($i = 0; $i < 50; $i++): ?>
            .row-delay-<?= $i ?> { animation-delay: <?= $i * 0.05 ?>s; }
            <?php endfor; ?>
        </style>

        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white relative overflow-hidden">
            <div class="absolute top-[-10%] right-[-5%] w-[30%] h-[30%] bg-amber-900/10 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 cascade-row row-delay-1">
                <div>
                    <span class="text-amber-500 font-black uppercase text-[10px] tracking-[0.4em] mb-2 block italic">Database Management</span>
                    <h1 class="text-4xl font-black tracking-tighter text-white uppercase flex items-center gap-4">
                        <i class="fa-solid fa-address-book text-amber-500"></i> Base Clients
                    </h1>
                </div>
                <a href="index.php?action=ajouterClient"
                   class="amber-btn flex items-center gap-3 px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest active:scale-95">
                    <i class="fa-solid fa-user-plus"></i> Nouveau Client
                </a>
            </div>

            <?php foreach (['success' => 'amber', 'error' => 'rose'] as $type => $color): ?>
                <?php if (isset($_SESSION[$type])): ?>
                    <div class="mb-8 p-5 rounded-2xl border border-<?= $color == 'amber' ? 'amber-500/30' : 'rose-500/30' ?> bg-<?= $color == 'amber' ? 'amber-500/10' : 'rose-500/10' ?> text-<?= $color == 'amber' ? 'amber-200' : 'rose-200' ?> flex items-center gap-4 shadow-xl cascade-row">
                        <i class="fa-solid <?= $type == 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?> text-xl"></i>
                        <span class="text-sm font-bold tracking-tight"><?= htmlspecialchars($_SESSION[$type]) ?></span>
                    </div>
                    <?php unset($_SESSION[$type]); ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="glass-table-container rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-0">
                        <thead>
                        <tr class="bg-white/[0.03]">
                            <th class="px-8 py-6 text-left text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">ID Reference</th>
                            <th class="px-8 py-6 text-left text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Identité</th>
                            <th class="px-8 py-6 text-left text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Coordonnées</th>
                            <th class="px-8 py-6 text-left text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Solde Actuel</th>
                            <th class="px-8 py-6 text-left text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Privilèges</th>
                            <th class="px-8 py-6 text-right text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php
                        $idx = 2;
                        foreach ($clients as $client):
                            $isPositive = ($client['solde'] ?? 0) >= 0;
                            $roleBarman = !empty($client['est_barman']);
                            ?>
                            <tr class="hover:bg-amber-500/[0.03] transition-colors group cascade-row row-delay-<?= $idx ?>">
                                <td class="px-8 py-6 text-amber-500/40 font-mono text-[10px] font-bold tracking-tighter">
                                    // <?= str_pad($client['id'], 4, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-white font-black uppercase text-sm tracking-tight block group-hover:text-amber-400 transition-colors">
                                    <?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom']) ?>
                                </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span class="text-slate-500 font-bold text-[11px] italic group-hover:text-slate-300 transition-colors"><?= htmlspecialchars($client['email']) ?></span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap font-black">
                                <span class="<?= $isPositive ? 'text-amber-400' : 'text-rose-500' ?> text-sm tracking-tighter">
                                    <?= number_format($client['solde'] ?? 0, 2, ',', ' ') ?> €
                                </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <?php if ($roleBarman): ?>
                                        <span class="px-3 py-1 text-[8px] font-black uppercase tracking-[0.2em] bg-amber-500 text-[#020617] rounded-md shadow-[0_0_10px_rgba(245,158,11,0.2)]">
                                        <i class="fa-solid fa-shield-halved mr-1"></i> Staff
                                    </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 text-[8px] font-black uppercase tracking-[0.2em] border border-white/10 text-slate-500 rounded-md">
                                        Client
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="index.php?action=modifierClient&id=<?= $client['id'] ?>"
                                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-slate-400 hover:bg-amber-500 hover:text-[#020617] transition-all shadow-inner border border-transparent hover:border-amber-400/20"
                                           title="Edit Node">
                                            <i class="fa-solid fa-sliders text-xs"></i>
                                        </a>
                                        <a href="index.php?action=supprimerClient&id=<?= $client['id'] ?>"
                                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-slate-400 hover:bg-rose-600 hover:text-white transition-all shadow-inner border border-transparent hover:border-rose-400/20"
                                           onclick="return confirm('Désactiver ce client ?')"
                                           title="Terminate Node">
                                            <i class="fa-solid fa-power-off text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $idx++;
                        endforeach; ?>

                        <?php if (empty($clients)): ?>
                            <tr>
                                <td colspan="6" class="px-8 py-24 text-center">
                                    <div class="relative inline-block">
                                        <i class="fa-solid fa-users-slash text-5xl text-slate-900 mb-4 block"></i>
                                        <div class="absolute inset-0 bg-amber-500/5 blur-2xl rounded-full"></div>
                                    </div>
                                    <p class="text-slate-600 font-black italic tracking-[0.3em] uppercase text-xs">System Database Empty</p>
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
                            <input type="text" id="clientSearch" class="search-input" placeholder="Rechercher nom ou ID...">
                        </div>

                        <div id="clientContainer" class="grid grid-cols-2 md:grid-cols-4 gap-3 max-h-64 overflow-y-auto p-2 custom-scrollbar">
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
                        <span class="step-badge">AFFECTATION ENTITÉ</span>
                        <div class="relative">
                            <select name="association_id" class="custom-select w-full p-4 rounded-xl font-bold appearance-none cursor-pointer" required>
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
            document.getElementById('clientSearch').addEventListener('input', function(e) {
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
                btn.innerHTML = "Prêt au recrutement (ID #"+id+")";
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

            .scrollable-content {
                flex-grow: 1;
                overflow-y: auto;
                padding-bottom: 5rem;
                scroll-behavior: smooth;
            }

            .content-limit {
                max-width: 1300px;
                margin: 0 auto;
                padding: 3rem 2rem;
            }

            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                margin-bottom: 3rem;
                animation: fadeInDown 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            }

            .glass-search-bar {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(16, 185, 129, 0.15);
                border-radius: 2rem;
                padding: 1.25rem 2.5rem;
                display: flex;
                gap: 1.5rem;
                align-items: center;
                margin-bottom: 3rem;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            }

            .member-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 2rem;
                padding: 1.5rem 2.5rem;
                margin-bottom: 1rem;
                display: grid;
                grid-template-columns: 120px 1.5fr 1fr 1fr 150px;
                align-items: center;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                text-decoration: none;
                position: relative;
                overflow: hidden;
            }

            .member-card:hover {
                background: rgba(16, 185, 129, 0.05);
                border-color: #10b981;
                transform: scale(1.01) translateX(10px);
                box-shadow: -10px 10px 40px rgba(0, 0, 0, 0.4);
            }

            .member-card::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 4px;
                background: #10b981;
                transform: scaleY(0);
                transition: 0.3s;
            }

            .member-card:hover::before {
                transform: scaleY(1);
            }

            .id-pill {
                font-family: 'JetBrains Mono', monospace;
                font-size: 11px;
                color: #10b981;
                background: rgba(16, 185, 129, 0.1);
                padding: 5px 12px;
                border-radius: 8px;
                width: fit-content;
                font-weight: 800;
            }

            .avatar-box {
                width: 45px;
                height: 45px;
                border-radius: 14px;
                background: linear-gradient(135deg, #064e3b 0%, #020617 100%);
                border: 1px solid rgba(16, 185, 129, 0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 900;
                color: #10b981;
                transition: 0.3s;
            }

            .member-card:hover .avatar-box {
                background: #10b981;
                color: #020617;
                transform: rotate(-5deg);
            }

            .status-tag {
                font-size: 10px;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 1px;
                display: flex;
                align-items: center;
                gap: 8px;
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
                transform: translateX(20px);
                transition: 0.4s;
            }

            .member-card:hover .btn-view {
                opacity: 1;
                transform: translateX(0);
            }

            #filterAsso option {
                background-color: #020617;
                color: #ffffff;
                font-family: 'Plus Jakarta Sans', sans-serif;
                padding: 10px;
            }

            #filterAsso {
                color: #10b981;
            }

            #filterAsso:hover {
                color: #ffffff;
            }
            @keyframes fadeInDown {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .scrollable-content::-webkit-scrollbar { width: 6px; }
            .scrollable-content::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.3); border-radius: 10px; }
        </style>

        <div class="scrollable-content">
            <div class="content-limit">

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
                       class="group flex items-center gap-4 bg-emerald-500 text-slate-950 px-8 py-4 rounded-2xl font-black text-xs uppercase transition-all hover:bg-white hover:scale-105 shadow-lg shadow-emerald-900/20">
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
                        <option value="all" class="text-white">Toutes les associations</option>
                        <?php foreach ($associations as $asso): ?>
                            <option value="<?= htmlspecialchars($asso['nom']) ?>" class="text-white">
                                <?= htmlspecialchars($asso['nom']) ?>
                            </option>
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
                                    <p class="text-[10px] text-emerald-500/40 font-bold tracking-tight lowercase">
                                        <?= htmlspecialchars($barman['email']) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="status-tag <?= $isActif ? 'text-emerald-400' : 'text-slate-500' ?>">
                                <span class="dot <?= $isActif ? 'dot-online' : 'dot-offline' ?>"></span>
                                <?= $isActif ? 'En service' : 'Inactif' ?>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-emerald-900 uppercase mb-1">Rattachement</span>
                                <span class="text-[11px] font-extrabold text-white uppercase tracking-tighter">
                                <?= htmlspecialchars($barman['nom_association'] ?? 'Non assigné') ?>
                            </span>
                            </div>

                            <div class="text-right">
                                <span class="btn-view">Consulter</span>
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


    public function afficherProduits($produits, $associations, $titre = "Stock")
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background-color: #020617;
                margin: 0; height: 100vh; display: flex; flex-direction: column; overflow: hidden;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .fixed-top-section {
                flex-shrink: 0;
                background: rgba(2, 6, 23, 0.98);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(139, 92, 246, 0.2);
                z-index: 100;
                position: sticky; top: 0;
            }

            .scrollable-content {
                flex-grow: 1; overflow-y: auto;
                background: radial-gradient(circle at top right, #2e106520, #020617);
            }

            .content-limit { max-width: 1600px; margin: 0 auto; padding: 0 40px; }
            .products-grid { margin-top: 60px; padding-bottom: 100px; }

            .product-card {
                position: relative;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.3s ease;
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(139, 92, 246, 0.2);
                box-shadow: 0 0 15px rgba(139, 92, 246, 0.08);
            }

            .product-card:hover {
                transform: scale(1.05);
                z-index: 50;
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(139, 92, 246, 0.6);
                box-shadow: 0 0 30px rgba(139, 92, 246, 0.25);
            }

            .card-urgent {
                border-color: rgba(244, 63, 94, 0.3);
                box-shadow: 0 0 15px rgba(244, 63, 94, 0.15);
            }

            .card-urgent:hover {
                border-color: rgba(244, 63, 94, 0.8);
                box-shadow: 0 0 35px rgba(244, 63, 94, 0.4);
            }

            .filter-group {
                display: flex;
                align-items: center;
                background: rgba(255,255,255,0.03);
                border: 1px solid rgba(255,255,255,0.08);
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
        </style>

        <div class="fixed-top-section">
            <div class="py-8">
                <div class="content-limit flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-violet-600 to-fuchsia-700 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-boxes-stacked text-2xl text-white"></i>
                        </div>
                        <h1 class="text-5xl font-black uppercase italic tracking-tighter text-white leading-none">
                            <?= htmlspecialchars($titre) ?>
                        </h1>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <div class="filter-group">
                            <i class="fa-solid fa-house-user text-violet-500"></i>
                            <select id="filterAsso" class="filter-select">
                                <option value="all">Toutes les assos</option>
                                <?php foreach ($associations as $asso): ?>
                                    <option value="<?= htmlspecialchars($asso['nom']) ?>"><?= htmlspecialchars($asso['nom']) ?></option>
                                <?php endforeach; ?>
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
        </div>

        <div class="scrollable-content">
            <div class="content-limit products-grid">
                <div id="productsWrapper" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">
                    <?php foreach ($produits as $p):
                        $stock = (int)($p['quantiteActuelle'] ?? 0);
                        $isLow = $stock < 15;
                        $colorHex = $isLow ? '#f43f5e' : '#8b5cf6';
                        ?>
                        <div class="product-card group rounded-[2.5rem] p-8 <?= $isLow ? 'card-urgent' : '' ?>"
                             data-nom="<?= htmlspecialchars(strtolower($p['nom'])) ?>"
                             data-asso="<?= htmlspecialchars($p['nom_association'] ?? 'Général') ?>"
                             data-stock="<?= $stock ?>"
                             data-prix="<?= $p['prix'] ?? 0 ?>">

                            <div class="absolute -top-3 left-8 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-tighter shadow-md"
                                 style="background: <?= $colorHex ?>; color: white;">
                                <?= htmlspecialchars($p['nom_association'] ?? 'Général') ?>
                            </div>

                            <div class="flex justify-between items-start mb-6">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center border border-white/10 bg-white/5"
                                     style="color: <?= $colorHex ?>; border-color: <?= $colorHex ?>44">
                                    <i class="fa-solid <?= ($p['type'] ?? '') == 'boisson' ? 'fa-wine-glass' : 'fa-utensils' ?> text-2xl"></i>
                                </div>
                                <div class="text-right">
                                    <span class="font-black italic text-2xl tracking-tighter text-white"><?= number_format($p['prix'] ?? 0, 2) ?>€</span>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h3 class="text-xl font-black uppercase mb-1 truncate text-white"><?= htmlspecialchars($p['nom']) ?></h3>
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
                                    <div class="h-full" style="width: <?= min(100, ($stock/100)*100) ?>%; background-color: <?= $colorHex ?>; box-shadow: 0 0 10px <?= $colorHex ?>;"></div>
                                </div>
                            </div>

                            <div class="mt-8 flex gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                <a href="index.php?action=modifierProduit&id=<?= $p['id'] ?>" class="flex-1 bg-white/5 py-4 rounded-xl text-center text-[9px] font-black uppercase text-white hover:bg-white/10 border border-white/5">Éditer</a>
                                <a href="index.php?action=ajouterStock&id=<?= $p['id'] ?>"
                                   class="flex-[2] py-4 rounded-xl text-center text-[9px] font-black uppercase text-white shadow-md"
                                   style="background-color: <?= $colorHex ?>;">+ Stock</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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

                let visibleCards = cards.filter(c => c.style.display !== 'none');
                visibleCards.sort((a, b) => {
                    if (sortBy === 'nom') return a.getAttribute('data-nom').localeCompare(b.getAttribute('data-nom'));
                    if (sortBy === 'stock') return parseInt(a.getAttribute('data-stock')) - parseInt(b.getAttribute('data-stock'));
                    if (sortBy === 'prix-asc') return parseFloat(a.getAttribute('data-prix')) - parseFloat(b.getAttribute('data-prix'));
                    if (sortBy === 'prix-desc') return parseFloat(a.getAttribute('data-prix')) - parseFloat(a.getAttribute('data-prix'));
                });

                visibleCards.forEach(card => wrapper.appendChild(card));
            }

            assoFilter.addEventListener('change', updateDisplay);
            sortFilter.addEventListener('change', updateDisplay);
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
                margin: 0; padding: 0;
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
                display: flex; align-items: center;
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

                <a href="index.php?action=voirProduits" class="flex items-center gap-3 text-violet-400 hover:text-white transition-all mb-10 group">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Retour Monitoring</span>
                </a>

                <div class="glass-card relative overflow-hidden">
                    <div class="relative z-10">
                        <header class="mb-12 header-title-container">
                            <p class="text-violet-500 font-black text-[10px] uppercase tracking-[0.5em] mb-2">Inventory System</p>
                            <h1 class="header-title">
                                Update <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">Produit</span>
                            </h1>
                        </header>

                        <form action="index.php?action=modifierProduit" method="post" class="space-y-8">
                            <input type="hidden" name="id" value="<?= $produit['id'] ?>">

                            <div>
                                <label class="label-tech">Désignation</label>
                                <div class="filter-group">
                                    <i class="fa-solid fa-signature text-violet-500"></i>
                                    <input type="text" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>" required class="input-text">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="label-tech">Catégorie</label>
                                    <div class="filter-group">
                                        <i class="fa-solid fa-list text-violet-500"></i>
                                        <select name="type" required class="filter-select">
                                            <option value="boisson" <?= $produit['type'] == 'boisson' ? 'selected' : '' ?>>Boisson</option>
                                            <option value="nourriture" <?= $produit['type'] == 'nourriture' ? 'selected' : '' ?>>Nourriture</option>
                                            <option value="autre" <?= $produit['type'] == 'autre' ? 'selected' : '' ?>>Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="label-tech">Prix (€)</label>
                                    <div class="filter-group">
                                        <i class="fa-solid fa-wallet text-fuchsia-500"></i>
                                        <input type="number" name="prix" step="0.01" value="<?= $produit['prix'] ?>" required class="input-text">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="label-tech">Stock Actuel</label>
                                <div class="filter-group">
                                    <i class="fa-solid fa-warehouse text-violet-500"></i>
                                    <input type="number" name="stock" value="<?= $produit['quantiteActuelle'] ?>" required class="input-text">
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="btn-submit w-full flex items-center justify-center gap-4 group">
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
        ?>
        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white relative overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-900/10 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 animate-fade-in">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-8 h-[2px] bg-blue-600"></span>
                        <span class="text-blue-500 font-black uppercase text-[10px] tracking-[0.3em] italic block">Gestion des entités</span>
                    </div>
                    <h1 class="text-4xl font-black tracking-tighter text-white uppercase flex items-center gap-4 italic">
                        <i class="fa-solid fa-sitemap text-blue-600"></i> Mes <span class="text-blue-600">Associations</span>
                    </h1>
                    <p class="text-slate-400 font-medium mt-2 italic text-sm">Liste des organisations validées sous votre supervision.</p>
                </div>

                <div class="bg-blue-600/10 border border-blue-500/20 px-6 py-3 rounded-2xl flex items-center gap-3 shadow-lg backdrop-blur-md">
                    <i class="fa-solid fa-check-double text-blue-400"></i>
                    <span class="text-xs font-black text-blue-100 uppercase tracking-widest"><?= count($associations) ?> Entités Actives</span>
                </div>
            </div>

            <?php if (empty($associations)): ?>
                <div class="bg-white/5 rounded-[3rem] p-20 text-center border border-white/5 border-dashed">
                    <i class="fa-solid fa-folder-open text-6xl text-slate-700 mb-6 block"></i>
                    <p class="text-slate-400 font-bold italic tracking-widest uppercase">Aucune association validée pour le moment.</p>
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
                                    Gérer l'entité
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
    public function afficherFournisseurs($fournisseurs)
    {
        $this->afficherNav();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            :root {
                --chrome-bg: #0a0a0c;
                --chrome-card: #141417;
                --electric-orange: #ff5722;
                --silver: #e2e8f0;
            }

            body {
                background-color: var(--chrome-bg);
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: var(--silver);
                margin: 0;
            }

            .fixed-top-section {
                position: sticky; top: 0; width: 100%;
                background: rgba(10, 10, 12, 0.95);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(255, 87, 34, 0.3);
                z-index: 1000; padding: 1.5rem 0;
            }

            .header-title {
                font-size: 3.2rem; font-weight: 900; text-transform: uppercase; font-style: italic;
                padding-right: 35px;
                background: linear-gradient(to bottom, #ffffff 40%, #555);
                -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                letter-spacing: -0.05em;
            }

            .supplier-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: 2rem; padding: 4rem 40px;
                max-width: 1600px; margin: 0 auto;
            }

            .supplier-card {
                background: var(--chrome-card);
                border-radius: 2rem;
                padding: 2.5rem;
                border: 1px solid rgba(255, 255, 255, 0.03);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                position: relative;
            }

            .supplier-card:hover {
                transform: translateY(-10px);
                border-color: var(--electric-orange);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            }

            .icon-container {
                width: 55px; height: 60px;
                background: linear-gradient(135deg, var(--electric-orange), #bf360c);
                color: white;
                display: flex; align-items: center; justify-content: center;
                clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
                font-size: 1.4rem;
                margin-bottom: 2rem;
            }

            .label-tech {
                font-size: 9px; font-weight: 900; color: var(--electric-orange);
                text-transform: uppercase; letter-spacing: 0.2em; display: block; margin-bottom: 5px;
            }

            .sup-name {
                font-size: 1.8rem; font-weight: 900; text-transform: uppercase;
                margin-bottom: 1.5rem; color: #fff;
            }

            .contact-info-box {
                background: rgba(255,255,255,0.02);
                padding: 1.2rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.05);
            }

            .sup-contact {
                font-size: 0.85rem; font-weight: 600; color: #a1a1aa;
                display: flex; align-items: center; gap: 12px; margin-bottom: 10px;
            }

            .btn-action {
                background: #ffffff;
                color: #000;
                font-size: 10px; font-weight: 900; text-transform: uppercase;
                padding: 1rem 1.5rem; border-radius: 0.8rem;
                text-decoration: none; transition: all 0.3s;
                display: inline-flex; align-items: center; gap: 8px;
            }

            .btn-action:hover {
                background: var(--electric-orange);
                color: #fff;
                transform: scale(1.05);
            }
        </style>

        <div class="fixed-top-section">
            <div class="max-w-[1600px] mx-auto px-10 flex justify-between items-center">
                <div>
                    <h1 class="header-title">MES <span style="color: var(--electric-orange); -webkit-text-fill-color: var(--electric-orange);">FOURNISSEURS</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.4em] mt-1">Ravitaillement & Stocks Buvette</p>
                </div>
                <a href="index.php?action=ajouterFournisseur" class="btn-action">
                    <i class="fa-solid fa-plus"></i> Nouveau Partenaire
                </a>
            </div>
        </div>

        <div class="supplier-grid">
            <?php foreach ($fournisseurs as $f): ?>
                <div class="supplier-card">
                    <div class="icon-container">
                        <i class="fa-solid fa-beer-mug-empty"></i>
                    </div>

                    <span class="label-tech">Distributeur Officiel</span>
                    <h3 class="sup-name"><?= htmlspecialchars($f['nom']) ?></h3>

                    <div class="contact-info-box">
                        <div class="sup-contact">
                            <i class="fa-solid fa-truck-fast text-orange-500 text-[11px]"></i>
                            <?= htmlspecialchars($f['email']) ?>
                        </div>
                        <div class="sup-contact">
                            <i class="fa-solid fa-phone text-orange-500 text-[11px]"></i>
                            <?= htmlspecialchars($f['telephone']) ?>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-white/5 flex justify-between items-center">
                        <a href="mailto:<?= $f['email'] ?>" class="btn-action">
                            Passer Commande <i class="fa-solid fa-cart-shopping"></i>
                        </a>
                        <a href="index.php?action=supprimerFournisseur&id=<?= $f['id'] ?>"
                           onclick="return confirm('Supprimer ce fournisseur ?')"
                           class="text-[9px] font-black text-slate-600 hover:text-red-500 uppercase transition-colors">
                            Retirer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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

            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(245, 158, 11, 0.2); border-radius: 10px; }
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
                                <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">Entité Certifiée</span>
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
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] mb-2">Trésorerie Actuelle</p>
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
                        <a href="index.php?action=voirProduits&id=<?= $assos['id'] ?>" class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Inventaire Complet</a>
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
                                    <td class="py-4 text-slate-400 text-xs font-bold"><?= number_format($p['prix'], 2) ?> €</td>
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
                        <a href="index.php?action=voirBarmans&id=<?= $assos['id'] ?>" class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Gérer</a>
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
                        <a href="index.php?action=voirListeClients&id=<?= $assos['id'] ?>" class="amber-tag px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-500 hover:text-black transition-all">Répertoire</a>
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
                                        <p class="text-lg font-black text-amber-400 tracking-tighter">+<?= number_format($v['montant_total'], 2) ?> €</p>
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


}