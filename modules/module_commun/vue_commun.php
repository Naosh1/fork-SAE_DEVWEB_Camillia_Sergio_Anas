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

}