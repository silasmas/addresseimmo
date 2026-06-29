<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Initialise les rôles et catégories de base AddressImmo.
 */
class AddressImmoSeeder extends Seeder
{
  /**
   * Exécute le seeding.
   */
  public function run(): void
  {
    $roles = [
      ['role_name' => 'Administrateur', 'role_description' => 'Responsable de la gestion du fonctionnement de la plateforme.'],
      ['role_name' => 'Vendeur', 'role_description' => 'Personne qui vend ou fait louer des produits ou offre des services sur la plateforme.'],
      ['role_name' => 'Membre', 'role_description' => 'Personne qui commande des produits ou des services publiés sur la plateforme.'],
      ['role_name' => 'Agent', 'role_description' => 'Personne engagée comme immobilier sur la plateforme.'],
    ];

    foreach ($roles as $role) {
      Role::firstOrCreate(['role_name' => $role['role_name']], $role);
    }

    $categories = [
      ['category_name' => 'Maison', 'category_description' => 'Vente, achat ou location des maisons construites (équipées ou non).', 'for_service' => 0, 'icon' => 'flaticon-house'],
      ['category_name' => 'Appartement', 'category_description' => 'Vente, achat ou location des appartements bas ou dans immeuble.', 'for_service' => 0, 'icon' => 'flaticon-building'],
      ['category_name' => 'Parcelle', 'category_description' => 'Vente et achat des terrains vides prêts pour la construction.', 'for_service' => 0, 'icon' => 'flaticon-house-3'],
      ['category_name' => 'Equipement', 'category_description' => 'Vente d\'outils de construction ou de décoration et d\'équipement d\'aménagement.', 'for_service' => 0, 'icon' => 'flaticon-house-1'],
      ['category_name' => 'Déménagement', 'category_description' => 'Service pour aider les membres de la plateforme à se déménager.', 'for_service' => 1, 'icon' => 'bi bi-luggage'],
      ['category_name' => 'Construction', 'category_description' => 'Service pour aider les membres de la plateforme qui ont besoin de construire leurs propriétés.', 'for_service' => 1, 'icon' => 'bi bi-tools'],
      ['category_name' => 'Aménagement et décoration intérieure', 'category_description' => 'Service pour aider les membres de la plateforme à réaménager leur maison ou appartement.', 'for_service' => 1, 'icon' => 'bi bi-droplet-half'],
    ];

    foreach ($categories as $category) {
      Category::firstOrCreate(['category_name' => $category['category_name']], $category);
    }
  }
}
