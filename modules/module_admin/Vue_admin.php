<?php
include_once 'vue_generique.php';
include_once 'modules/module_commun/vue_commun.php';

class Vue_admin {
    private $nbDemandesAttente = 0;

    public function setNbDemandes($nb) {
        $this->nbDemandesAttente = $nb;
    }

    public function afficherNav() {
        $prenom = $_SESSION['prenom'] ?? 'Admin';
        $photo = $_SESSION['photo'] ?? null;
        $actionActuelle = $_GET['action'] ?? 'accueil';
        $nb = $this->nbDemandesAttente;
        $activeClass = "bg-blue-600/15 text-blue-400 border-r-4 border-blue-600 shadow-[0_0_20px_rgba(37,99,235,0.1)]";
        $inactiveClass = "text-slate-500 hover:bg-white/[0.03] hover:text-white border-r-4 border-transparent";
        $cheminPhoto = (!empty($photo) && file_exists("uploads/profiles/" . basename($photo)))
                ? "uploads/profiles/" . basename($photo)
                : null;

        echo '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Panel</title>
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                .font-montserrat { font-family: "Montserrat", sans-serif; }
                .glass-sidebar {
                    background: rgba(2, 6, 23, 0.9) !important;
                    backdrop-filter: blur(25px);
                    -webkit-backdrop-filter: blur(25px);
                }
                .custom-scrollbar::-webkit-scrollbar { width: 3px; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                .animate-slideIn { animation: slideIn 0.3s ease-out; }
            </style>
        </head>
        <body>
        <div class="flex min-h-screen bg-[#020617] font-montserrat">
            <aside class="w-72 glass-sidebar border-r border-white/5 hidden md:flex flex-col sticky top-0 h-screen z-[1001]">
                
                <div class="h-24 flex items-center px-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-900/40 transform -rotate-6">
                            <i class="fa-solid fa-shield-halved text-white text-lg rotate-6"></i>
                        </div>
                        <span class="text-xl font-[900] text-white tracking-tighter uppercase italic">Admin<span class="text-red-600">Panel</span></span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
                    
                    <a href="index.php?module=admin&action=accueil" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'accueil' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-chart-line text-lg"></i>
                        <span class="font-bold text-sm tracking-tight">Vue d\'ensemble</span>
                    </a>

                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mb-3 mt-8">Approbations</p>
                    
                    <a href="index.php?module=admin&action=validerAssos" class="flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'validerAssos' ? $activeClass : $inactiveClass) . '">
                        <div class="flex items-center gap-4">
                            <i class="fa-solid fa-file-circle-check text-lg"></i>
                            <span class="font-bold text-sm tracking-tight">Demandes Assos</span>
                        </div>' .
                ($nb > 0 ? '<span class="bg-red-600 text-[10px] font-black text-white px-2 py-0.5 rounded-lg shadow-lg shadow-red-600/20">' . $nb . '</span>' : '') . '
                    </a>

                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] px-4 mt-8 mb-3">Contrôle Système</p>

                    <a href="index.php?module=admin&action=gestionAssos" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'gestionAssos' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-building-columns text-lg"></i>
                        <span class="font-bold text-sm tracking-tight">Liste des Associations</span>
                    </a>

                    <a href="index.php?module=admin&action=gestionUtilisateurs" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'gestionUtilisateurs' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-users-gear text-lg"></i>
                        <span class="font-bold text-sm tracking-tight">Utilisateurs </span>
                    </a>

                    <a href="index.php?module=admin&action=catalogueProduits" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'catalogueProduits' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-boxes-stacked text-lg"></i>
                        <span class="font-bold text-sm tracking-tight">Catalogue Global</span>
                    </a>

                    <a href="index.php?module=admin&action=gestionFournisseurs" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-300 ' . ($actionActuelle == 'gestionFournisseurs' ? $activeClass : $inactiveClass) . '">
                        <i class="fa-solid fa-handshake text-lg"></i>
                        <span class="font-bold text-sm tracking-tight">Fournisseurs</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-white/5 space-y-3 bg-white/[0.01]">
                    <div class="flex items-center gap-3 px-4 py-3 rounded-2xl">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-red-600 to-orange-400 p-0.5 shadow-lg shadow-red-600/20">
                            <div class="w-full h-full rounded-[10px] bg-[#020617] overflow-hidden flex items-center justify-center">
                                ' . ($cheminPhoto
                        ? '<img src="' . $cheminPhoto . '" class="w-full h-full object-cover" alt="Photo de profil">'
                        : '<span class="text-xs font-black text-white">' . strtoupper(substr($prenom, 0, 1)) . '</span>') . '
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white truncate">' . htmlspecialchars($prenom) . '</p>
                            <p class="text-[9px] font-black text-red-500 uppercase tracking-widest italic">Administrateur</p>
                        </div>
                    </div>

                    <a href="index.php?module=admin&action=deconnexion" class="flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl text-red-500 bg-red-500/5 hover:bg-red-500 hover:text-white transition-all duration-300 group shadow-lg shadow-red-900/5 border border-red-500/10">
                        <i class="fa-solid fa-power-off text-sm group-hover:rotate-90 transition-transform duration-500"></i>
                        <span class="font-black text-[11px] uppercase tracking-widest">Déconnexion</span>
                    </a>
                </div>
            </aside>
            <main class="flex-1 relative overflow-y-auto p-8">
        ';
    }

    public function afficherFormulaireProduit($produit = null) {
        $isEdit = $produit !== null;
        $produit = $produit ?: ['id' => '', 'nom' => '', 'type' => '', 'prix' => '', 'description' => ''];
        $produit['nom'] = $produit['nom'] ?? '';
        $produit['type'] = $produit['type'] ?? '';
        $produit['prix'] = $produit['prix'] ?? '';
        $produit['description'] = $produit['description'] ?? '';
        $produit['id'] = $produit['id'] ?? '';

        $formAction = $isEdit ? 'modifierProduit' : 'ajouterProduit';

        return '
    <div id="modal-produit" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-[9999]" onclick="if(event.target===this) closeModalProduit()">
        <div class="bg-[#020617] border border-white/10 rounded-3xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black text-white">' . ($isEdit ? 'Modifier le Produit' : 'Nouveau Produit Référent') . '</h3>
                <button onclick="closeModalProduit()" class="text-slate-500 hover:text-white p-2">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="formProduit" action="index.php?module=admin&action=' . $formAction . '" method="POST" class="space-y-6">'
                . ($isEdit && !empty($produit['id']) ? '<input type="hidden" name="id" value="' . htmlspecialchars($produit['id']) . '">' : '')
                . '<div>
                    <label class="block text-sm font-bold text-slate-400 mb-2">Nom du produit *</label>
                    <input type="text" name="nom" value="' . htmlspecialchars($produit['nom']) . '" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-all">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">Type/Catégorie *</label>
                        <select name="type" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white focus:border-blue-500 focus:outline-none">
                            <option value="" disabled ' . (empty($produit['type']) ? 'selected' : '') . '>Sélectionner...</option>
                            <option value="alimentaire" ' . ($produit['type'] == 'alimentaire' ? 'selected' : '') . '>Alimentaire</option>
                            <option value="textile" ' . ($produit['type'] == 'textile' ? 'selected' : '') . '>Textile</option>
                            <option value="electronique" ' . ($produit['type'] == 'electronique' ? 'selected' : '') . '>Électronique</option>
                            <option value="papeterie" ' . ($produit['type'] == 'papeterie' ? 'selected' : '') . '>Papeterie</option>
                            <option value="autre" ' . ($produit['type'] == 'autre' ? 'selected' : '') . '>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">Prix unitaire (€) *</label>
                        <input type="number" name="prix" step="0.01" min="0" value="' . htmlspecialchars($produit['prix']) . '" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-400 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-slate-500 focus:border-blue-500 focus:outline-none">' . htmlspecialchars($produit['description']) . '</textarea>
                </div>
                
                <div class="flex gap-3 pt-4 border-t border-white/10">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl font-black text-sm uppercase transition-all">
                        ' . ($isEdit ? 'Mettre à jour' : 'Créer le produit') . '
                    </button>
                    <button type="button" onclick="closeModalProduit()" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white rounded-2xl font-bold transition-all">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    function closeModalProduit() {
        const modal = document.getElementById("modal-produit");
        if (modal) modal.remove();
    }
    
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            const modal = document.getElementById("modal-produit");
            if (modal) modal.remove();
        }
    });
    </script>';
    }

    public function afficherFormulaireFournisseur($fournisseur = null) {
        $isEdit = $fournisseur !== null;
        $fournisseur = $fournisseur ?: ['id' => '', 'nom' => '', 'telephone' => '', 'email' => ''];

        return '
    <div id="modal-fournisseur" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-[9999]" onclick="if(event.target===this) closeModalFournisseur()">
        <div class="bg-[#020617] border border-white/10 rounded-3xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black text-white">' . ($isEdit ? 'Modifier le Fournisseur' : 'Nouveau Fournisseur') . '</h3>
                <button onclick="closeModalFournisseur()" class="text-slate-500 hover:text-white p-2">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="formFournisseur" action="index.php?module=admin&action=' . ($isEdit ? 'modifierFournisseur' : 'ajouterFournisseur') . '" method="POST" class="space-y-6">'
                . ($isEdit ? '<input type="hidden" name="id" value="' . $fournisseur['id'] . '">' : '')
                . '<div>
                    <label class="block text-sm font-bold text-slate-400 mb-2">Nom du fournisseur *</label>
                    <input type="text" name="nom" value="' . htmlspecialchars($fournisseur['nom']) . '" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-all">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">Téléphone *</label>
                        <input type="tel" name="telephone" value="' . htmlspecialchars($fournisseur['telephone']) . '" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-400 mb-2">Email *</label>
                        <input type="email" name="email" value="' . htmlspecialchars($fournisseur['email']) . '" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4 border-t border-white/10">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3 rounded-2xl font-black text-sm uppercase transition-all">
                        ' . ($isEdit ? 'Mettre à jour' : 'Ajouter le fournisseur') . '
                    </button>
                    <button type="button" onclick="closeModalFournisseur()" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white rounded-2xl font-bold transition-all">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    function closeModalFournisseur() {
        const modal = document.getElementById("modal-fournisseur");
        if (modal) modal.remove();
    }
    
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            const modal = document.getElementById("modal-fournisseur");
            if (modal) modal.remove();
        }
    });
    </script>';
    }

    public function afficherCatalogue($produits, $showModal = false, $produitEdit = null) {
        echo '
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">Catalogue Global Produits</h2>
            <button onclick="ouvrirModalProduit()" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase transition-all shadow-lg shadow-blue-900/20">
                <i class="fa-solid fa-plus mr-2"></i> Nouveau Produit Référent
            </button>
        </div>';

        if ($showModal && $produitEdit) {
            echo $this->afficherFormulaireProduit($produitEdit);
        }

        echo '<div class="bg-white/5 border border-white/10 rounded-[2rem] overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10 bg-white/5">
                    <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Désignation</th>
                    <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Catégorie</th>
                    <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Prix Moyen</th>
                    <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Stock Total</th>
                    <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">';

        foreach ($produits as $p) {
            $description = isset($p['description']) ? htmlspecialchars($p['description']) : '';
            $type = isset($p['type']) ? htmlspecialchars($p['type']) : 'Non défini';
            $prix = isset($p['prix']) ? number_format($p['prix'], 2) : '0.00';
            $quantite = isset($p['quantiteActuelle']) ? $p['quantiteActuelle'] : 0;
            $nom = isset($p['nom']) ? htmlspecialchars($p['nom']) : 'Nom inconnu';
            $id = isset($p['id']) ? $p['id'] : 0;

            echo '
            <tr class="hover:bg-white/[0.02] transition-all group">
                <td class="p-6">
                    <div class="font-bold text-white group-hover:text-blue-400 transition-colors">' . $nom . '</div>
                    ' . (!empty($description) ? '<div class="text-xs text-slate-500 mt-1">' . substr($description, 0, 50) . '...</div>' : '') . '
                </td>
                <td class="p-6">
                    <span class="px-3 py-1 bg-white/5 text-slate-400 rounded-lg text-[10px] font-black uppercase">' . $type . '</span>
                </td>
                <td class="p-6 text-center font-black text-white">' . $prix . ' €</td>
                <td class="p-6 text-center">
                    <span class="text-sm font-bold ' . ($quantite < 10 ? 'text-rose-500' : 'text-slate-300') . '">
                        ' . $quantite . '
                    </span>
                </td>
                <td class="p-6 text-right space-x-2">
                    <a href="index.php?module=admin&action=modifierProduitForm&id=' . $id . '" class="inline-block p-3 bg-white/5 hover:bg-blue-600/20 text-slate-400 hover:text-blue-400 rounded-xl transition-all">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="index.php?module=admin&action=supprimerProduit&id=' . $id . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce produit ?\')" class="inline-block p-3 bg-white/5 hover:bg-rose-600/20 text-slate-400 hover:text-rose-500 rounded-xl transition-all">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>';
        }

        echo '</tbody>
        </table>
    </div>
    
    <script>
    function ouvrirModalProduit() {
        fetch("index.php?module=admin&action=getFormulaireProduit")
            .then(response => response.text())
            .then(html => {
                document.body.insertAdjacentHTML("beforeend", html);
            })
            .catch(error => console.error("Erreur:", error));
    }
    </script>
</div>';
    }

    public function afficherFournisseurs($fournisseurs, $showModal = false, $fournisseurEdit = null) {
        echo '
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">Partenaires & Fournisseurs</h2>
        <button onclick="ouvrirModalFournisseur()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase transition-all shadow-lg shadow-emerald-900/20">
            <i class="fa-solid fa-truck-fast mr-2"></i> Ajouter un Fournisseur
        </button>
    </div>';

        if ($showModal && $fournisseurEdit) {
            echo $this->afficherFormulaireFournisseur($fournisseurEdit);
        }

        echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';

        foreach ($fournisseurs as $f) {
            echo '
    <div class="bg-white/5 border border-white/10 p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-emerald-500/30 transition-all">
        <div class="absolute -right-4 -top-4 text-white/[0.02] text-8xl transform -rotate-12 group-hover:text-emerald-500/10 transition-all">
            <i class="fa-solid fa-building"></i>
        </div>

        <div class="relative z-10">
            <div class="text-2xl font-black text-white mb-6 uppercase tracking-tighter">' . htmlspecialchars($f['nom']) . '</div>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4 text-slate-400">
                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-phone text-sm"></i>
                    </div>
                    <span class="text-sm font-bold">' . htmlspecialchars($f['telephone'] ?? 'Non renseigné') . '</span>
                </div>
                
                <div class="flex items-center gap-4 text-slate-400">
                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </div>
                    <span class="text-sm font-bold truncate">' . htmlspecialchars($f['email'] ?? 'Non renseigné') . '</span>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/5 flex gap-2">
                <a href="index.php?module=admin&action=modifierFournisseurForm&id=' . $f['id'] . '" class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-[10px] font-black text-white uppercase rounded-xl transition-all text-center">Modifier</a>
                <a href="index.php?module=admin&action=supprimerFournisseur&id=' . $f['id'] . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce fournisseur ?\')" class="px-4 py-3 bg-rose-600/10 hover:bg-rose-600 text-rose-500 hover:text-white rounded-xl transition-all">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </a>
            </div>
        </div>
    </div>';
        }

        echo '</div>
    
    <script>
    function ouvrirModalFournisseur() {
        fetch("index.php?module=admin&action=getFormulaireFournisseur")
            .then(response => response.text())
            .then(html => {
                document.body.insertAdjacentHTML("beforeend", html);
            })
            .catch(error => console.error("Erreur:", error));
    }
    </script>
</div>';
    }

    public function afficherDashboard($stats, $topAssos = [], $activite = []) {
        echo '
    <div class="p-8">
        <h1 class="text-4xl font-black text-white italic mb-10 uppercase tracking-tighter">Tableau de Bord Global</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white/5 border border-white/10 p-6 rounded-[2rem]">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-blue-600/20 rounded-2xl text-blue-500">
                        <i class="fa-solid fa-fort-awesome text-2xl"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Actives</span>
                </div>
                <div class="mt-4">
                    <div class="text-4xl font-black text-white italic">' . $stats['total_assos'] . '</div>
                    <div class="text-xs text-slate-400 font-bold uppercase mt-1">Associations</div>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 p-6 rounded-[2rem]">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-emerald-600/20 rounded-2xl text-emerald-500">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-4xl font-black text-white italic">' . $stats['total_users'] . '</div>
                    <div class="text-xs text-slate-400 font-bold uppercase mt-1">Utilisateurs inscrits</div>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 p-6 rounded-[2rem]">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-amber-600/20 rounded-2xl text-amber-500">
                        <i class="fa-solid fa-coins text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-4xl font-black text-white italic">' . number_format($stats['ca_global'], 2, ',', ' ') . ' €</div>
                    <div class="text-xs text-slate-400 font-bold uppercase mt-1">Volume d\'affaires global</div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
                <h3 class="text-xl font-black text-white mb-6">Top 5 Associations par solde</h3>
                <div class="space-y-4">';

        if (!empty($topAssos)) {
            foreach ($topAssos as $index => $asso) {
                $colorClass = $index == 0 ? 'from-yellow-600/20 to-yellow-500/5 border-yellow-500/20' :
                        ($index == 1 ? 'from-slate-600/20 to-slate-500/5 border-slate-500/20' :
                                ($index == 2 ? 'from-amber-800/20 to-amber-700/5 border-amber-700/20' : 'bg-white/5 border-white/5'));

                echo '<div class="flex items-center justify-between p-4 rounded-2xl bg-gradient-to-r ' . $colorClass . ' border">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-white font-black text-sm">' . ($index + 1) . '</div>
                            <span class="font-bold text-white">' . htmlspecialchars($asso['nom']) . '</span>
                        </div>
                        <span class="font-black text-blue-400">' . number_format($asso['solde'], 2) . ' €</span>
                    </div>';
            }
        } else {
            echo '<p class="text-slate-500 italic">Aucune donnée disponible</p>';
        }

        echo '      </div>
            </div>
            
            <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
                <h3 class="text-xl font-black text-white mb-6">Activité récente (7 jours)</h3>
                <div class="space-y-4">';

        if (!empty($activite)) {
            foreach ($activite as $activ) {
                echo '<div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all">
                        <span class="font-bold text-white">' . $activ['date'] . '</span>
                        <span class="font-black text-emerald-500">' . $activ['nb_demandes'] . ' demande(s)</span>
                    </div>';
            }
        } else {
            echo '<p class="text-slate-500 italic">Aucune activité récente</p>';
        }

        echo '      </div>
            </div>
        </div>
    </div>';
    }

    public function afficherGestionUtilisateurs($users) {
        echo '
    <div class="p-8">
        <h2 class="text-3xl font-black text-white italic mb-8 uppercase">Modération des Comptes</h2>
        <div class="bg-white/5 border border-white/10 rounded-[2rem] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 bg-white/5">
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase">Utilisateur</th>
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase">Rôles</th>
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase">Solde</th>
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase">Statut</th>
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">';

        foreach ($users as $u) {
            $statusText = $u['actif'] ? 'Actif' : 'Inactif';
            $btnAction = $u['actif'] ? 'Bannir' : 'Réactiver';
            $btnColor = $u['actif'] ? 'bg-rose-600/20 text-rose-500 hover:bg-rose-600 hover:text-white' : 'bg-emerald-600/20 text-emerald-500 hover:bg-emerald-600 hover:text-white';
            $btnLink = 'index.php?module=admin&action=toggleUserStatus&id=' . $u['id'];

            echo '
                    <tr class="hover:bg-white/[0.02] transition-all">
                        <td class="p-6">
                            <div class="font-bold text-white">' . htmlspecialchars($u['prenom'] . ' ' . $u['nom']) . '</div>
                            <div class="text-xs text-slate-500">' . htmlspecialchars($u['email']) . '</div>
                        </td>
                        <td class="p-6">
                            <span class="px-3 py-1 bg-blue-600/10 text-blue-400 rounded-lg text-[10px] font-black uppercase">' . ($u['roles'] ?? 'Client') . '</span>
                        </td>
                        <td class="p-6 font-black text-white">' . number_format($u['solde'], 2) . ' €</td>
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase ' . ($u['actif'] ? 'bg-emerald-600/10 text-emerald-500' : 'bg-rose-600/10 text-rose-500') . '">' . $statusText . '</span>
                        </td>
                        <td class="p-6 text-right">
                            <a href="' . $btnLink . '" class="inline-block px-4 py-2 ' . $btnColor . ' rounded-xl font-black text-[10px] uppercase transition-all">
                                ' . $btnAction . '
                            </a>
                        </td>
                    </tr>';
        }

        echo '      </tbody>
            </table>
        </div>
    </div>';
    }

    public function afficherConfirmation($message, $type = 'success') {
        $colors = [
                'success' => 'bg-emerald-600/20 border-emerald-500/30 text-emerald-500',
                'error' => 'bg-rose-600/20 border-rose-500/30 text-rose-500',
                'warning' => 'bg-amber-600/20 border-amber-500/30 text-amber-500',
                'info' => 'bg-blue-600/20 border-blue-500/30 text-blue-500'
        ];

        $color = $colors[$type] ?? $colors['info'];
        $icon = $type == 'success' ? 'fa-check-circle' :
                ($type == 'error' ? 'fa-exclamation-circle' :
                        ($type == 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'));

        echo '
        <div class="fixed top-4 right-4 z-[9999] animate-slideIn">
            <div class="' . $color . ' border rounded-2xl px-6 py-4 shadow-2xl backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid ' . $icon . ' text-lg"></i>
                    <span class="font-bold text-sm">' . htmlspecialchars($message) . '</span>
                </div>
            </div>
        </div>
        
        <script>
        setTimeout(() => {
            const notification = document.querySelector(".fixed.top-4.right-4");
            if (notification) notification.remove();
        }, 5000);
        </script>';
    }

    public function afficherListeDemandes($demandes) {
        echo '<div class="space-y-6">';
        echo '<h2 class="text-2xl font-black text-white uppercase italic tracking-tighter">Demandes en attente</h2>';

        if (empty($demandes)) {
            echo '<p class="text-slate-500 italic">Aucune demande pour le moment.</p>';
        } else {
            foreach ($demandes as $d) {
                echo '
            <div class="bg-white/5 border border-white/10 p-6 rounded-3xl flex items-center justify-between group hover:border-blue-500/50 transition-all">
                <div>
                    <h3 class="text-xl font-bold text-white">' . htmlspecialchars($d['nom_association']) . '</h3>
                    <p class="text-slate-400 text-sm">Demandé par : <span class="text-blue-400">' . htmlspecialchars($d['prenom'] . ' ' . $d['nom']) . '</span></p>
                    <div class="flex gap-4 mt-2">
                        ' . (!empty($d['pdf_pv_creation']) ? '<a href="' . htmlspecialchars($d['pdf_pv_creation']) . '" target="_blank" class="text-[10px] font-black text-emerald-500 uppercase tracking-widest hover:underline italic">Voir PV Creation</a>' : '') . '
                        ' . (!empty($d['pdf_identite']) ? '<a href="' . htmlspecialchars($d['pdf_identite']) . '" target="_blank" class="text-[10px] font-black text-emerald-500 uppercase tracking-widest hover:underline italic">Pièce d\'identité</a>' : '') . '
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="index.php?module=admin&action=accepterAsso&id=' . $d['id'] . '" class="px-6 py-3 bg-emerald-600/20 text-emerald-500 rounded-xl font-black text-xs uppercase hover:bg-emerald-600 hover:text-white transition-all">Accepter</a>
                    <a href="index.php?module=admin&action=refuserAsso&id=' . $d['id'] . '" class="px-6 py-3 bg-red-600/20 text-red-500 rounded-xl font-black text-xs uppercase hover:bg-red-600 hover:text-white transition-all">Refuser</a>
                </div>
            </div>';
            }
        }
        echo '</div>';
    }

    public function afficherDemandesAssociation($demandes) {
        echo '
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">Validation des Associations</h2>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 bg-blue-600/20 text-blue-500 rounded-xl text-xs font-black uppercase">
                    ' . count($demandes) . ' demande(s) en attente
                </span>
            </div>
        </div>';

        if (empty($demandes)) {
            echo '
        <div class="bg-white/5 border border-white/10 rounded-3xl p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-emerald-600/20 flex items-center justify-center text-emerald-500">
                <i class="fa-solid fa-check-circle text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-white mb-3">Aucune demande en attente</h3>
            <p class="text-slate-400">Toutes les demandes d\'association ont été traitées.</p>
        </div>';
        } else {
            echo '<div class="space-y-6">';

            foreach ($demandes as $index => $demande) {
                $id = $demande['id'] ?? 0;
                $nomAsso = htmlspecialchars($demande['nom_association'] ?? 'Nom inconnu');
                $gestionnaire = htmlspecialchars(($demande['prenom'] ?? '') . ' ' . ($demande['nom'] ?? ''));
                $dateSoumission = date('d/m/Y H:i', strtotime($demande['date_soumission'] ?? ''));
                $hasIdentite = !empty($demande['pdf_identite']);
                $hasPvCreation = !empty($demande['pdf_pv_creation']);
                $hasStatut = !empty($demande['pdf_statut']);

                $docCount = ($hasIdentite ? 1 : 0) + ($hasPvCreation ? 1 : 0) + ($hasStatut ? 1 : 0);

                echo '
            <div class="bg-white/5 border border-white/10 rounded-3xl overflow-hidden group hover:border-blue-500/30 transition-all duration-300">
                <div class="p-6 border-b border-white/10 bg-white/[0.02]">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-blue-400 flex items-center justify-center text-white font-black">
                                    ' . ($index + 1) . '
                                </div>
                                <h3 class="text-2xl font-black text-white">' . $nomAsso . '</h3>
                            </div>
                            <div class="flex items-center gap-4 text-slate-400 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-user text-xs"></i>
                                    <span class="font-bold">' . $gestionnaire . '</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-calendar text-xs"></i>
                                    <span class="font-bold">' . $dateSoumission . '</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 bg-blue-600/10 text-blue-500 rounded-lg text-xs font-black uppercase">
                                ' . $docCount . '/3 documents
                            </span>
                            <span class="px-3 py-1.5 bg-amber-600/10 text-amber-500 rounded-lg text-xs font-black uppercase">
                                En attente
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="mb-8">
                        <h4 class="text-lg font-black text-white mb-4">Documents à vérifier</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            ' . $this->genererCarteDocument($demande, 'pdf_identite', 'Pièce d\'identité', 'fa-id-card', 'Identité') . '
                            ' . $this->genererCarteDocument($demande, 'pdf_pv_creation', 'PV de Création', 'fa-file-contract', 'PV Création') . '
                            ' . $this->genererCarteDocument($demande, 'pdf_statut', 'Statut/Règlement', 'fa-scale-balanced', 'Statut') . '
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-white/10">
                        <h4 class="text-lg font-black text-white mb-4">Décision</h4>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <form action="index.php?module=admin&action=accepterAsso" method="POST" class="flex-1">
                                <input type="hidden" name="id" value="' . $id . '">
                                <button type="submit" 
                                        onclick="return confirm(\'Êtes-vous sûr de vouloir accepter cette association ?\')"
                                        class="w-full flex items-center justify-center gap-3 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-4 rounded-2xl font-black text-sm uppercase transition-all shadow-lg shadow-emerald-900/20 hover:shadow-emerald-900/40">
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>Accepter l\'association</span>
                                </button>
                            </form>
                            
                            <form action="index.php?module=admin&action=refuserAsso" method="POST" class="flex-1">
                                <input type="hidden" name="id" value="' . $id . '">
                                <button type="submit" 
                                        onclick="return confirm(\'Êtes-vous sûr de vouloir refuser cette association ?\')"
                                        class="w-full flex items-center justify-center gap-3 bg-red-600 hover:bg-red-500 text-white px-6 py-4 rounded-2xl font-black text-sm uppercase transition-all shadow-lg shadow-red-900/20 hover:shadow-red-900/40">
                                    <i class="fa-solid fa-times-circle"></i>
                                    <span>Refuser l\'association</span>
                                </button>
                            </form>
                           
                            <button type="button" 
                                    onclick="ouvrirModalDetails(' . $id . ')"
                                    class="flex-1 flex items-center justify-center gap-3 bg-white/5 hover:bg-white/10 text-white px-6 py-4 rounded-2xl font-black text-sm uppercase transition-all">
                                <i class="fa-solid fa-eye"></i>
                                <span>Détails complets</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
            }
            echo '</div>';
        }

        echo '
        <div id="modalDetails" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4 z-[9999]">
            <div class="bg-[#020617] border border-white/10 rounded-3xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-white">Détails de la demande</h3>
                    <button onclick="fermerModalDetails()" class="text-slate-500 hover:text-white p-2">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div id="modalContent" class="space-y-6">
                </div>
            </div>
        </div>
        
        <script>
        function ouvrirModalDetails(id) {
            fetch("index.php?module=admin&action=getDetailsDemande&id=" + id)
                .then(response => response.text())
                .then(html => {
                    document.getElementById("modalContent").innerHTML = html;
                    document.getElementById("modalDetails").classList.remove("hidden");
                    document.getElementById("modalDetails").classList.add("flex");
                });
        }
        
        function fermerModalDetails() {
            document.getElementById("modalDetails").classList.add("hidden");
            document.getElementById("modalDetails").classList.remove("flex");
        }
        
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") fermerModalDetails();
        });
        </script>
    </div>';
    }

    private function genererCarteDocument($demande, $champ, $titre, $icone, $type) {
        $fichier = $demande[$champ] ?? '';
        $disponible = !empty($fichier);

        $couleur = $disponible ? 'border-emerald-500/30 bg-emerald-600/5' : 'border-slate-500/30 bg-white/5';
        $couleurIcone = $disponible ? 'text-emerald-500' : 'text-slate-500';
        $texteStatut = $disponible ? 'Disponible' : 'Manquant';

        $contenu = '
    <div class="' . $couleur . ' border rounded-2xl p-5 transition-all hover:scale-[1.02]">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center ' . $couleurIcone . '">
                <i class="fa-solid ' . $icone . ' text-lg"></i>
            </div>
            <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase '
                . ($disponible ? 'bg-emerald-600/10 text-emerald-500' : 'bg-slate-600/10 text-slate-500') . '">
                ' . $texteStatut . '
            </span>
        </div>
        
        <h5 class="text-sm font-black text-white mb-2">' . $titre . '</h5>
        <p class="text-xs text-slate-400 mb-4">Document ' . $type . ' requis pour validation</p>';

        if ($disponible) {
            $nomFichier = basename($fichier);
            $contenu .= '
        <div class="flex gap-2">
            <a href="' . htmlspecialchars($fichier) . '" 
               target="_blank" 
               class="flex-1 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 rounded-xl text-xs font-bold transition-all">
                <i class="fa-solid fa-eye text-xs"></i>
                Voir PDF
            </a>
            <a href="' . htmlspecialchars($fichier) . '" 
               download="' . htmlspecialchars($nomFichier) . '"
               class="px-3 py-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all">
                <i class="fa-solid fa-download text-xs"></i>
            </a>
        </div>';
        } else {
            $contenu .= '
        <div class="px-3 py-2 bg-white/5 text-slate-500 rounded-xl text-center text-xs font-bold">
            <i class="fa-solid fa-exclamation-circle mr-2"></i>
            Document non fourni
        </div>';
        }

        $contenu .= '</div>';

        return $contenu;
    }
    public function afficherDetailsDemande($demande) {
        $id = $demande['id'] ?? 0;

        echo '
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-600/20 flex items-center justify-center text-blue-500">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-400 uppercase">Association</h4>
                        <p class="text-lg font-black text-white">' . htmlspecialchars($demande['nom_association'] ?? '') . '</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600/20 flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-400 uppercase">Gestionnaire</h4>
                        <p class="text-lg font-black text-white">' . htmlspecialchars(($demande['prenom'] ?? '') . ' ' . ($demande['nom'] ?? '')) . '</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
            <h4 class="text-lg font-black text-white mb-4">Documents fournis</h4>
            <div class="space-y-4">';

        $documents = [
                ['champ' => 'pdf_identite', 'nom' => 'Pièce d\'identité (CNI/Passeport)', 'icone' => 'fa-id-card'],
                ['champ' => 'pdf_pv_creation', 'nom' => 'Procès-verbal de création', 'icone' => 'fa-file-contract'],
                ['champ' => 'pdf_statut', 'nom' => 'Statut/Règlement intérieur', 'icone' => 'fa-scale-balanced']
        ];

        foreach ($documents as $doc) {
            $fichier = $demande[$doc['champ']] ?? '';
            $disponible = !empty($fichier);

            echo '
                <div class="flex items-center justify-between p-3 rounded-xl ' . ($disponible ? 'bg-emerald-600/5' : 'bg-rose-600/5') . '">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg ' . ($disponible ? 'bg-emerald-600/20 text-emerald-500' : 'bg-rose-600/20 text-rose-500') . ' flex items-center justify-center">
                            <i class="fa-solid ' . $doc['icone'] . ' text-sm"></i>
                        </div>
                        <span class="font-bold text-white">' . $doc['nom'] . '</span>
                    </div>
                    
                    <div class="flex items-center gap-3">';

            if ($disponible) {
                echo '
                        <a href="' . htmlspecialchars($fichier) . '" target="_blank" class="text-blue-500 hover:text-blue-400 text-xs font-bold">
                            <i class="fa-solid fa-eye mr-1"></i> Voir
                        </a>
                        <a href="' . htmlspecialchars($fichier) . '" download class="text-slate-400 hover:text-white">
                            <i class="fa-solid fa-download text-sm"></i>
                        </a>';
            } else {
                echo '<span class="text-rose-500 text-xs font-bold"><i class="fa-solid fa-times mr-1"></i> Non fourni</span>';
            }

            echo '
                    </div>
                </div>';
        }

        echo '
            </div>
        </div>
      
        <div class="pt-6 border-t border-white/10">
            <div class="flex gap-4">
                <form action="index.php?module=admin&action=accepterAsso" method="POST" class="flex-1">
                    <input type="hidden" name="id" value="' . $id . '">
                    <button type="submit" 
                            onclick="return confirm(\'Confirmez-vous l\\\'acceptation de cette association ?\')"
                            class="w-full bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3 rounded-xl font-black text-sm uppercase transition-all">
                        <i class="fa-solid fa-check mr-2"></i> Accepter
                    </button>
                </form>
                
                                <button type="button" 
                        onclick="ouvrirModalRefus(' . $id . ')"
                        class="w-full bg-red-600 hover:bg-red-500 text-white px-6 py-3 rounded-xl font-black text-sm uppercase transition-all">
                    <i class="fa-solid fa-times mr-2"></i> Refuser
                </button>
            </div>
        </div>
    </div>
    
    <div id="modalRefus" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4 z-[9999]">
        <div class="bg-[#020617] border border-white/10 rounded-3xl p-8 max-w-md w-full">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black text-white">Motif du refus</h3>
                <button onclick="fermerModalRefus()" class="text-slate-500 hover:text-white p-2">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="formRefus" method="POST" class="space-y-6">
                <input type="hidden" name="id" id="refusId">
                
                <div>
                    <label class="block text-sm font-bold text-slate-400 mb-2">
                        Pourquoi refusez-vous cette demande ? *
                    </label>
                    <textarea name="raison" id="raisonRefus" rows="4" required
                        placeholder="Ex: Documents incomplets, statut non conforme, informations manquantes..."
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-slate-500 focus:border-red-500 focus:outline-none"></textarea>
                    <p class="text-xs text-slate-500 mt-2">Cette raison sera visible par le gestionnaire.</p>
                </div>
                
                <div class="flex gap-3 pt-4 border-t border-white/10">
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-500 text-white px-6 py-3 rounded-xl font-black text-sm uppercase transition-all">
                        Confirmer le refus
                    </button>
                    <button type="button" 
                            onclick="fermerModalRefus()"
                            class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold transition-all">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    function ouvrirModalRefus(id) {
        document.getElementById("refusId").value = id;
        document.getElementById("modalRefus").classList.remove("hidden");
        document.getElementById("modalRefus").classList.add("flex");
    }
    
    function fermerModalRefus() {
        document.getElementById("modalRefus").classList.add("hidden");
        document.getElementById("modalRefus").classList.remove("flex");
        document.getElementById("raisonRefus").value = "";
    }
    
    document.getElementById("formRefus").addEventListener("submit", function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        
        fetch("index.php?module=admin&action=refuserAsso", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.text();
            }
        })
        .then(data => {
            if (data && data.includes("Location:")) {
                const match = data.match(/Location:\s*(.+)/i);
                if (match) {
                    window.location.href = match[1].trim();
                }
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
            alert("Une erreur est survenue lors du refus.");
        });
    });
    
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") fermerModalRefus();
    });
    </script>';
    }

    public function afficherDetailsAsso($id, $association) {
        if (empty($association)) {
            echo '
    <div class="p-8">
        <div class="bg-white/5 border border-white/10 p-12 rounded-3xl text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-rose-600/20 flex items-center justify-center text-rose-500">
                <i class="fa-solid fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-white mb-3">Association non trouvée</h3>
            <p class="text-slate-400 max-w-md mx-auto mb-6">L\'association demandée n\'existe pas ou a été supprimée.</p>
            <a href="index.php?module=admin&action=gestionAssos" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all">
                <i class="fa-solid fa-arrow-left"></i> Retour aux associations
            </a>
        </div>
    </div>';
            return;
        }

        $nom = htmlspecialchars($association['nom'] ?? '');
        $email = htmlspecialchars($association['email'] ?? 'Non renseigné');
        $telephone = htmlspecialchars($association['telephone'] ?? 'Non renseigné');
        $adresse = htmlspecialchars($association['adresse'] ?? 'Non renseignée');
        $solde = number_format($association['solde'] ?? 0, 2);

        $dateCreation = '';
        if (!empty($association['date_creation'])) {
            $dateCreation = date('d/m/Y', strtotime($association['date_creation']));
        } else {
            $dateCreation = 'Date inconnue';
        }

        echo '
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <a href="index.php?module=admin&action=gestionAssos" 
                   class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:border-blue-500/30 transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-3xl font-black text-white italic uppercase">' . $nom . '</h2>
                    <p class="text-slate-400">ID: #' . $id . '</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="index.php?module=admin&action=modifierAsso&id=' . $id . '" 
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all flex items-center gap-2">
                    <i class="fa-solid fa-pen"></i> Modifier
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-600/20 flex items-center justify-center text-blue-400">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-400">Trésorerie</div>
                        <div class="text-2xl font-black ' . ($solde >= 0 ? 'text-blue-400' : 'text-rose-500') . '">' . $solde . ' €</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-600/20 flex items-center justify-center text-amber-400">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-400">Barmans</div>
                        <div class="text-2xl font-black text-amber-500">0</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600/20 flex items-center justify-center text-emerald-400">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-400">Clients</div>
                        <div class="text-2xl font-black text-emerald-500">0</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white/5 border border-white/10 p-6 rounded-3xl mb-8">
            <h3 class="text-lg font-black text-white mb-4">Informations de contact</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Email</p>
                            <p class="text-white font-medium">' . $email . '</p>
                        </div>
                    </div>
                  
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Téléphone</p>
                            <p class="text-white font-medium">' . $telephone . '</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Adresse</p>
                            <p class="text-white font-medium">' . $adresse . '</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Date de création</p>
                            <p class="text-white font-medium">' . $dateCreation . '</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-white/10 text-center">
            <form method="POST" action="index.php?module=admin&action=detailsAsso&id=' . $id . '" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cette association ?\')">
                <input type="hidden" name="action" value="supprimer">
                <button type="submit" class="px-8 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl transition-all flex items-center gap-2 inline-flex">
                    <i class="fa-solid fa-trash"></i> Supprimer l\'association
                </button>
            </form>
        </div>
    </div>';
    }



    public function afficherFormulaireModification($id, $association) {
        $nom = htmlspecialchars($association['nom'] ?? '');
        $email = htmlspecialchars($association['email'] ?? '');
        $telephone = htmlspecialchars($association['telephone'] ?? '');
        $adresse = htmlspecialchars($association['adresse'] ?? '');
        $solde = htmlspecialchars($association['solde'] ?? 0);

        if (isset($_SESSION['erreurs_modification'])) {
            echo '<div class="p-8">';
            foreach ($_SESSION['erreurs_modification'] as $erreur) {
                echo '
            <div class="mb-4 p-4 bg-rose-600/10 border border-rose-500/20 rounded-2xl text-rose-500">
                <i class="fa-solid fa-exclamation-circle mr-2"></i>' . htmlspecialchars($erreur) . '
            </div>';
            }
            echo '</div>';
            unset($_SESSION['erreurs_modification']);
        }

        echo '
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <a href="index.php?module=admin&action=detailsAsso&id=' . $id . '" 
                   class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:border-blue-500/30 transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-3xl font-black text-white italic uppercase">Modifier l\'association</h2>
                    <p class="text-slate-400">ID: #' . $id . '</p>
                </div>
            </div>
        </div>
        
        <div class="max-w-3xl mx-auto">
            <form method="POST" action="index.php?module=admin&action=modifierAsso&id=' . $id . '" class="space-y-6">
                <div class="bg-white/5 border border-white/10 p-8 rounded-3xl">
                    <h3 class="text-xl font-black text-white mb-6">Informations générales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-400 block">Nom de l\'association *</label>
                            <input type="text" 
                                   name="nom" 
                                   value="' . $nom . '"
                                   required
                                   class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-400 block">Trésorerie (€)</label>
                            <input type="number" 
                                   step="0.01"
                                   name="solde" 
                                   value="' . $solde . '"
                                   class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-400 block">Email</label>
                            <input type="email" 
                                   name="email" 
                                   value="' . $email . '"
                                   class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-400 block">Téléphone</label>
                            <input type="tel" 
                                   name="telephone" 
                                   value="' . $telephone . '"
                                   class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">
                        </div>
                    </div>
                 
                    <div class="mt-6 space-y-2">
                        <label class="text-sm font-bold text-slate-400 block">Adresse</label>
                        <textarea name="adresse" 
                                  rows="3"
                                  class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">' . $adresse . '</textarea>
                    </div>
                </div>
               
                <div class="flex justify-end gap-4">
                    <a href="index.php?module=admin&action=detailsAsso&id=' . $id . '" 
                       class="px-6 py-3 bg-white/5 border border-white/10 text-white font-bold rounded-xl transition-all hover:bg-white/10">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>';
    }
    public function afficherGestionAssos($assos) {
        $assosValidees = array_filter($assos, function($asso) {
            return $asso['status'] === 'validee';
        });

        $totalAssos = count($assosValidees);

        echo '
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="w-full md:w-auto">
                <h2 class="text-3xl font-black text-white italic uppercase">Associations Partenaires</h2>
            </div>
           
            <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-4 py-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 flex items-center justify-center">
                    <i class="fa-solid fa-building text-blue-400"></i>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase">Associations</div>
                    <div class="text-2xl font-black text-white">' . $totalAssos . '</div>
                </div>
            </div>
        </div>
        
        <div class="max-w-2xl mx-auto mb-8">
            <div class="relative">
                <input type="text" 
                       id="searchAssociation" 
                       placeholder="Rechercher une association..." 
                       class="w-full bg-white/5 border border-white/10 text-white placeholder-slate-500 rounded-2xl pl-12 pr-4 py-3 focus:outline-none focus:border-blue-500 transition-all text-center md:text-left">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <button id="clearSearch" 
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-500 hover:text-white hidden">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        </div>
        
        <div id="associationsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';

        if (empty($assosValidees)) {
            echo '
            <div class="col-span-full bg-white/5 border border-white/10 p-12 rounded-3xl text-center">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-slate-600/20 flex items-center justify-center text-slate-500">
                    <i class="fa-solid fa-building text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3">Aucune association validée</h3>
                <p class="text-slate-400 max-w-md mx-auto">Aucune association n\'a été validée pour le moment.</p>
            </div>';
        } else {
            foreach ($assosValidees as $a) {
                $status = htmlspecialchars($a['status']);
                $nom = htmlspecialchars($a['nom']);
                $solde = number_format($a['solde'], 2);
                $id = $a['id'];
                $adresse = !empty($a['adresse']) ? htmlspecialchars($a['adresse']) : '';
                $description = !empty($a['description']) ? htmlspecialchars(substr($a['description'], 0, 100)) . '...' : '';

                $statusColor = 'bg-emerald-600/10 text-emerald-500 border-emerald-500/20';
                $statusIcon = 'fa-check-circle';
                $statusLabel = 'Validée';

                echo '
            <div class="asso-card bg-white/5 border border-white/10 p-6 rounded-3xl flex flex-col justify-between group hover:border-blue-500/30 hover:bg-white/10 transition-all duration-300"
                 data-nom="' . strtolower($nom) . '"
                 data-status="' . $status . '">
                
                <div class="mb-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="text-xl font-black text-white uppercase group-hover:text-blue-400 transition-colors truncate">' . $nom . '</div>
                        </div>
                        <span class="px-3 py-1.5 rounded-xl text-xs font-black uppercase ' . $statusColor . ' ml-2 flex-shrink-0">
                            <i class="fa-solid ' . $statusIcon . ' mr-1.5"></i>' . $statusLabel . '
                        </span>
                    </div>
                    
                    ' . (!empty($adresse) ? '
                    <div class="flex items-center gap-2 text-slate-500 text-sm mb-3">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                        <span class="truncate">' . $adresse . '</span>
                    </div>' : '') . '
                    
                    ' . (!empty($description) ? '
                    <p class="text-slate-400 text-sm line-clamp-2 mb-4">' . $description . '</p>' : '') . '
                </div>
                
                <div class="pt-4 border-t border-white/10">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xs text-slate-500 font-bold uppercase mb-1">Trésorerie</div>
                            <div class="text-2xl font-black ' . ($solde >= 0 ? 'text-blue-400' : 'text-rose-500') . '">' . $solde . ' €</div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="index.php?module=admin&action=detailsAsso&id=' . $id . '" 
                               class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:border-blue-500/30 hover:bg-blue-500/10 transition-all"
                               title="Voir les détails">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="index.php?module=admin&action=modifierAsso&id=' . $id . '" 
                               class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-blue-400 hover:border-blue-500/30 hover:bg-blue-500/10 transition-all"
                               title="Modifier">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
            }
        }

        echo '
        </div>
        
        <div id="noResults" class="hidden bg-white/5 border border-white/10 p-12 rounded-3xl text-center mt-8">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-slate-600/20 flex items-center justify-center text-slate-500">
                <i class="fa-solid fa-search text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-white mb-3">Aucun résultat trouvé</h3>
            <p class="text-slate-400 max-w-md mx-auto">Aucune association ne correspond à votre recherche. Essayez avec d\'autres termes.</p>
            <button id="resetSearch" class="mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all">
                <i class="fa-solid fa-rotate-left mr-2"></i> Réinitialiser la recherche
            </button>
        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchAssociation");
            const clearBtn = document.getElementById("clearSearch");
            const resetBtn = document.getElementById("resetSearch");
            const assoCards = document.querySelectorAll(".asso-card");
            const noResults = document.getElementById("noResults");
            
            searchInput.addEventListener("input", function() {
                const searchTerm = this.value.toLowerCase().trim();
                filterAssociations(searchTerm);
                
                clearBtn.classList.toggle("hidden", searchTerm === "");
            });
            
            clearBtn.addEventListener("click", function() {
                searchInput.value = "";
                searchInput.focus();
                filterAssociations("");
                clearBtn.classList.add("hidden");
            });
            
            if (resetBtn) {
                resetBtn.addEventListener("click", function() {
                    searchInput.value = "";
                    filterAssociations("");
                    clearBtn.classList.add("hidden");
                    searchInput.focus();
                });
            }
            
            function filterAssociations(searchTerm) {
                let visibleCount = 0;
                
                assoCards.forEach(card => {
                    const nom = card.dataset.nom;
                    
                    const matchesSearch = searchTerm === "" || nom.includes(searchTerm);
                    
                    if (matchesSearch) {
                        card.classList.remove("hidden");
                        card.style.opacity = "1";
                        card.style.transform = "translateY(0)";
                        visibleCount++;
                    } else {
                        card.classList.add("hidden");
                    }
                });
                
                if (visibleCount > 0) {
                    const visibleCards = document.querySelectorAll(".asso-card:not(.hidden)");
                    visibleCards.forEach((card, index) => {
                        card.style.transitionDelay = (index * 0.05) + "s";
                        setTimeout(() => {
                            card.style.opacity = "1";
                            card.style.transform = "translateY(0)";
                        }, 10);
                    });
                }
                
                noResults.classList.toggle("hidden", visibleCount > 0);
            }
            
            searchInput.addEventListener("keydown", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                }
            });
                        searchInput.focus();
        });
        </script>
        
        <style>
        .asso-card {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        #searchAssociation::placeholder {
            text-align: center;
        }
        
        @media (min-width: 768px) {
            #searchAssociation::placeholder {
                text-align: left;
            }
        }
        </style>
    </div>';
    }



}