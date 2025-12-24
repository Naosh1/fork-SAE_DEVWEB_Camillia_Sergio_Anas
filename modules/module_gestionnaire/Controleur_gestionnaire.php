<?php

class ControleurGestionnaire
{


    private $modele;
    private $vue;

    public function __construct($modele, $vue)
    {
        $this->modele = $modele;
        $this->vue = $vue;
    }

    public function gererAction($action)
    {
        switch ($action) {
            case 'produits':
                $produits = $this->modele->getProduits();
                $this->vue->afficherProduits($produits);
                break;

            case 'ventes':
                $ventes = $this->modele->getVentes();
                $this->vue->afficherVentes($ventes);
                break;

            case 'statistiques':
                $totalVentes = $this->modele->getTotalVentes();

                $stats = ['totalVentes' => $totalVentes];

                $this->vue->afficherStatistiques($stats);
                break;

            default:
                echo 'Page introuvable';
        }
    }
}