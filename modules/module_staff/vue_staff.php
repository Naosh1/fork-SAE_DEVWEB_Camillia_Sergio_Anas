<?php
include_once 'vue_generique.php';

class VueStaff extends VueCommun
{
    public function afficherClients($clients)
    {
        $this->afficherNav();
        ?>
        <style>
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(-15px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
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
            .row-delay-<?= $i ?> {
                animation-delay: <?= $i * 0.05 ?>s;
            }

            <?php endfor; ?>
        </style>

        <div class="p-6 md:p-12 bg-[#020617] min-h-screen font-montserrat text-white relative">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 cascade-row row-delay-1">
                <div>
                    <span class="text-amber-500 font-black uppercase text-[10px] tracking-[0.4em] mb-2 block italic">Database Management</span>
                    <h1 class="text-4xl font-black tracking-tighter uppercase flex items-center gap-4">
                        <i class="fa-solid fa-address-book text-amber-500"></i> Base Clients
                    </h1>
                </div>
                <a href="index.php?action=ajouterClient"
                   class="amber-btn flex items-center gap-3 px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest active:scale-95">
                    <i class="fa-solid fa-user-plus"></i> Nouveau Client
                </a>
            </div>

            <div class="glass-table-container rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-0 text-left">
                        <thead>
                        <tr class="bg-white/[0.03]">
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">ID
                            </th>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">
                                Identité
                            </th>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">
                                Solde
                            </th>
                            <th class="px-8 py-6 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">
                                Statut
                            </th>
                            <th class="px-8 py-6 text-right text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                        <?php $idx = 2;
                        foreach ($clients as $client): ?>
                            <tr class="hover:bg-amber-500/[0.03] transition-colors group cascade-row row-delay-<?= $idx ?>">
                                <td class="px-8 py-6 text-amber-500/40 font-mono text-[10px] font-bold tracking-tighter">
                                    // <?= $client['id'] ?></td>
                                <td class="px-8 py-6">
                                <span class="text-white font-black uppercase text-sm group-hover:text-amber-400 transition-colors">
                                    <?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom']) ?>
                                </span>
                                    <span class="block text-slate-500 text-[10px] italic"><?= htmlspecialchars($client['email']) ?></span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap font-black">
                                <span class="<?= $client['solde'] >= 0 ? 'text-amber-400' : 'text-rose-500' ?> text-sm">
                                    <?= number_format($client['solde'] ?? 0, 2, ',', ' ') ?> €
                                </span>
                                </td>
                                <td class="px-8 py-6">
                                    <?= !empty($client['est_barman']) ? '<span class="bg-amber-500 text-[#020617] text-[8px] font-black px-2 py-1 rounded">STAFF</span>' : '<span class="text-slate-500 text-[8px] font-black border border-white/10 px-2 py-1 rounded">CLIENT</span>' ?>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="index.php?action=gererSolde&id=<?= $client['id'] ?>"
                                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-[#020617] transition-all"
                                           title="Gérer le solde">
                                            <i class="fa-solid fa-wallet text-xs"></i>
                                        </a>
                                        <a href="index.php?action=modifierClient&id=<?= $client['id'] ?>"
                                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white transition-all">
                                            <i class="fa-solid fa-sliders text-xs"></i>
                                        </a>
                                        <a href="index.php?action=supprimerClient&id=<?= $client['id'] ?>"
                                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-slate-400 hover:bg-rose-600 hover:text-white transition-all"
                                           onclick="return confirm('Supprimer ce client ?')">
                                            <i class="fa-solid fa-power-off text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php $idx++; endforeach; ?>
                        </tbody>
                    </table>
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
            /* On permet au document de scroller naturellement */
            html, body {
                height: auto; /* Important : on enlève le 100vh */
                overflow-y: visible; /* On autorise le scroll vertical */
                background-color: #020617;
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            /* Le header devient collant (Sticky) : il reste en haut au scroll sans figer la page */
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
                padding: 40px 0 100px 0; /* On gère l'espacement ici */
            }

            /* --- DA CONSERVÉE --- */
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

        <div class="content-limit products-grid">
            <div id="productsWrapper" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">
                <?php foreach ($produits as $p):
                    $stock = (int)($p['quantiteActuelle'] ?? 0);
                    $isLow = $stock < 15;
                    $colorHex = $isLow ? '#f43f5e' : '#8b5cf6';
                    ?>
                    <div class="product-card group p-8 <?= $isLow ? 'card-urgent' : '' ?>"
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
                                <div class="h-full"
                                     style="width: <?= min(100, ($stock / 100) * 100) ?>%; background-color: <?= $colorHex ?>; box-shadow: 0 0 10px <?= $colorHex ?>;"></div>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3 opacity-0 group-hover:opacity-100 transition-all">
                            <a href="index.php?action=modifierProduit&id=<?= $p['id'] ?>"
                               class="flex-1 bg-white/5 py-4 rounded-xl text-center text-[9px] font-black uppercase text-white hover:bg-white/10 border border-white/5">Éditer</a>
                            <a href="index.php?action=ajouterStock&id=<?= $p['id'] ?>"
                               class="flex-[2] py-4 rounded-xl text-center text-[9px] font-black uppercase text-white shadow-md"
                               style="background-color: <?= $colorHex ?>;">+ Stock</a>
                        </div>
                    </div>
                <?php endforeach; ?>
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

}