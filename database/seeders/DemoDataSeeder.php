<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Auction;
use App\Models\Category;
use App\Models\File;
use App\Models\LotteryDraw;
use App\Models\Product;
use App\Models\User;
use App\Models\VerifiedSale;
use App\Models\YaOfeleDraw;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Alimente la base avec les données de démo Phase 1 + Phase 2 (images Unsplash).
 */
class DemoDataSeeder extends Seeder
{
  /**
   * Images du template Phase 2 (Unsplash).
   *
   * @var array<int, string>
   */
  private array $demoImages = [
    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1600607687644-c7171b42498b?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1200&q=80',
  ];

  /**
   * Exécute le seeding de démonstration.
   */
  public function run(): void
  {
    $this->call(AddressImmoSeeder::class);

    $owner = User::query()->orderBy('id')->first();

    if ($owner === null) {
      $this->command?->warn('Aucun utilisateur trouvé — seeding produits ignoré.');

      return;
    }

    $this->repairExistingCatalog($owner);
    $this->seedPhase2Properties($owner);
    $this->repairPhotoUrls();
    $this->seedAgencies();
    $this->seedAuctions();
    $this->seedLottery($owner);
    $this->seedYaOfele();
    $this->seedVerifiedSales($owner);

    $this->command?->info('Données de démo AddressImmo chargées.');
  }

  /**
   * Remet en vente les annonces Phase 1 existantes.
   *
   * @param User $owner Propriétaire par défaut
   * @return void
   */
  private function repairExistingCatalog(User $owner): void
  {
    $maisonCategory = Category::where('category_name', 'Maison')->first();
    $appartCategory = Category::where('category_name', 'Appartement')->first();

    Product::query()->where('is_service', 0)->where('quantity', '<=', 0)->update(['quantity' => 1]);

    Product::query()->where('id', 1)->update([
      'product_name' => 'Maison équipée à vendre à MaCampagne',
      'product_description' => 'Belle maison familiale avec jardin, quartier résidentiel sécurisé à Kinshasa.',
      'price' => 185000,
      'currency' => 'USD',
      'action' => 'sell',
      'type' => 'equipped_house',
      'country' => 'RDC',
      'city' => 'Kinshasa',
      'municipality' => 'Ngaliema',
      'neighborhood' => 'MaCampagne',
      'quantity' => 1,
      'category_id' => $maisonCategory?->id,
      'user_id' => $owner->id,
    ]);

    Product::query()->where('id', 2)->update([
      'product_name' => 'Maison à louer — Gombe',
      'product_description' => 'Maison spacieuse à louer, proche des ambassades et commerces.',
      'price' => 4500000,
      'currency' => 'CDF',
      'action' => 'rent',
      'type' => 'equipped_house',
      'country' => 'RDC',
      'city' => 'Kinshasa',
      'municipality' => 'Gombe',
      'quantity' => 1,
      'category_id' => $maisonCategory?->id,
      'user_id' => $owner->id,
    ]);

    Product::query()->whereIn('id', [3, 5, 6, 7, 8])->update([
      'country' => 'RDC',
      'city' => 'Kinshasa',
      'quantity' => 1,
      'user_id' => $owner->id,
    ]);

    Product::query()->where('id', 4)->update([
      'product_name' => 'Service de construction sur mesure',
      'product_description' => 'Accompagnement complet pour la construction de votre propriété.',
      'price' => 25000,
      'currency' => 'USD',
      'action' => 'build',
      'is_service' => 1,
      'quantity' => 99,
      'category_id' => Category::where('category_name', 'Construction')->value('id'),
      'user_id' => $owner->id,
    ]);

    if ($appartCategory !== null) {
      Product::query()->whereIn('id', [3, 7])->update(['category_id' => $appartCategory->id]);
    }

    if ($maisonCategory !== null) {
      Product::query()->whereIn('id', [5, 6, 8])->update(['category_id' => $maisonCategory->id]);
    }
  }

  /**
   * Crée les annonces inspirées du template Phase 2.
   *
   * @param User $owner Propriétaire des annonces
   * @return void
   */
  private function seedPhase2Properties(User $owner): void
  {
    $items = [
      [
        'product_name' => 'Villa familiale avec jardin',
        'product_description' => 'Villa lumineuse de 360 m², 4 pièces, jardin et parking. Idéale pour famille.',
        'price' => 4500000,
        'currency' => 'CDF',
        'action' => 'rent',
        'type' => 'equipped_house',
        'city' => 'Kinshasa',
        'municipality' => 'Gombe',
        'country' => 'RDC',
        'category' => 'Maison',
        'image' => $this->demoImages[0],
      ],
      [
        'product_name' => 'Appartement avec terrasse',
        'product_description' => 'Appartement haut standing de 142 m² avec terrasse panoramique.',
        'price' => 185000000,
        'currency' => 'CDF',
        'action' => 'sell',
        'type' => 'equipped_apartment',
        'city' => 'Dakar',
        'municipality' => 'Almadies',
        'country' => 'Sénégal',
        'category' => 'Appartement',
        'image' => $this->demoImages[1],
      ],
      [
        'product_name' => 'Terrain résidentiel titré',
        'product_description' => 'Parcelle de 600 m² titrée, prête pour construction résidentielle.',
        'price' => 42000000,
        'currency' => 'CDF',
        'action' => 'sell',
        'type' => 'empty_plot',
        'city' => 'Abidjan',
        'municipality' => 'Bingerville',
        'country' => "Côte d'Ivoire",
        'category' => 'Parcelle',
        'image' => $this->demoImages[2],
      ],
      [
        'product_name' => 'Duplex haut standing',
        'product_description' => 'Duplex 210 m², 4 pièces, sécurisé, quartier Bonapriso.',
        'price' => 1800000,
        'currency' => 'CDF',
        'action' => 'rent',
        'type' => 'equipped_house',
        'city' => 'Douala',
        'municipality' => 'Bonapriso',
        'country' => 'Cameroun',
        'category' => 'Maison',
        'image' => $this->demoImages[3],
      ],
      [
        'product_name' => 'Studio meublé proche université',
        'product_description' => 'Studio meublé 42 m², idéal étudiant ou jeune professionnel.',
        'price' => 950000,
        'currency' => 'CDF',
        'action' => 'rent',
        'type' => 'equipped_apartment',
        'city' => 'Kinshasa',
        'municipality' => 'Ngaliema',
        'country' => 'RDC',
        'category' => 'Appartement',
        'image' => $this->demoImages[4],
      ],
      [
        'product_name' => 'Maison de vacances sécurisée',
        'product_description' => 'Maison de vacances 180 m² avec piscine, zone sécurisée à Saly.',
        'price' => 72000000,
        'currency' => 'CDF',
        'action' => 'sell',
        'type' => 'equipped_house',
        'city' => 'Saly',
        'municipality' => 'Mbour',
        'country' => 'Sénégal',
        'category' => 'Maison',
        'image' => $this->demoImages[5],
      ],
    ];

    foreach ($items as $item) {
      $categoryId = Category::where('category_name', $item['category'])->value('id');

      $product = Product::updateOrCreate(
        ['product_name' => $item['product_name']],
        [
          'product_description' => $item['product_description'],
          'quantity' => 1,
          'price' => $item['price'],
          'currency' => $item['currency'],
          'is_service' => 0,
          'action' => $item['action'],
          'country' => $item['country'],
          'city' => $item['city'],
          'municipality' => $item['municipality'],
          'type' => $item['type'],
          'category_id' => $categoryId,
          'user_id' => $owner->id,
          'created_by' => $owner->id,
        ]
      );

      File::updateOrCreate(
        [
          'product_id' => $product->id,
          'file_name' => 'couverture.jpg',
        ],
        [
          'file_url' => $item['image'],
          'file_type' => 'photo',
        ]
      );
    }
  }

  /**
   * Remplace les URLs de photos cassées par des images Unsplash.
   *
   * @return void
   */
  private function repairPhotoUrls(): void
  {
    $photos = File::query()->where('file_type', 'photo')->orderBy('id')->get();

    foreach ($photos as $index => $photo) {
      $photo->update([
        'file_url' => $this->demoImages[$index % count($this->demoImages)],
      ]);
    }

    Product::query()->with('photos')->get()->each(function (Product $product, int $index) {
      if ($product->photos->isEmpty()) {
        File::create([
          'file_name' => 'couverture.jpg',
          'file_url' => $this->demoImages[$index % count($this->demoImages)],
          'file_type' => 'photo',
          'product_id' => $product->id,
        ]);
      }
    });
  }

  /**
   * Crée les agences partenaires Phase 2.
   *
   * @return void
   */
  private function seedAgencies(): void
  {
    $agencies = [
      [
        'name' => 'Habitat Plus',
        'description' => 'Agence immobilière spécialisée à Kinshasa.',
        'city' => 'Kinshasa',
        'country' => 'RDC',
        'is_verified' => true,
        'logo_url' => $this->demoImages[0],
      ],
      [
        'name' => 'Terra Afrique',
        'description' => 'Expert en vente et location en Afrique de l\'Ouest.',
        'city' => 'Abidjan',
        'country' => "Côte d'Ivoire",
        'is_verified' => true,
        'logo_url' => $this->demoImages[2],
      ],
      [
        'name' => 'Immo Horizon',
        'description' => 'Solutions immobilières à Dakar et environs.',
        'city' => 'Dakar',
        'country' => 'Sénégal',
        'is_verified' => false,
        'logo_url' => $this->demoImages[1],
      ],
    ];

    foreach ($agencies as $agency) {
      Agency::updateOrCreate(['name' => $agency['name']], $agency);
    }
  }

  /**
   * Crée les enchères de démonstration.
   *
   * @return void
   */
  private function seedAuctions(): void
  {
    $items = [
      [
        'title' => 'Villa moderne à vendre aux enchères',
        'location' => 'Kinshasa, Gombe',
        'start_price' => 98000,
        'current_bid' => 121500,
        'currency' => 'USD',
        'status' => 'active',
        'ends_at' => Carbon::now()->addDays(2),
      ],
      [
        'title' => 'Terrain commercial titré',
        'location' => 'Abidjan, Cocody',
        'start_price' => 36000000,
        'current_bid' => null,
        'currency' => 'CDF',
        'status' => 'scheduled',
        'ends_at' => Carbon::now()->addDays(5),
      ],
      [
        'title' => 'Immeuble R+2 proche avenue',
        'location' => 'Douala, Akwa',
        'start_price' => 145000000,
        'current_bid' => 172000000,
        'currency' => 'CDF',
        'status' => 'closed',
        'ends_at' => Carbon::now()->subDay(),
      ],
    ];

    foreach ($items as $index => $item) {
      Auction::updateOrCreate(
        ['title' => $item['title']],
        array_merge($item, [
          'starts_at' => Carbon::now()->subDays(1),
          'product_id' => Product::query()->skip($index)->value('id'),
        ])
      );
    }
  }

  /**
   * Crée un tirage loto de démonstration.
   *
   * @param User $owner Propriétaire lié au bien
   * @return void
   */
  private function seedLottery(User $owner): void
  {
    $product = Product::updateOrCreate(
      ['product_name' => 'Appartement premium de la semaine — Loto'],
      [
        'product_description' => 'Bien mis en jeu pour le loto immobilier de la semaine à Limete.',
        'quantity' => 1,
        'price' => 68000,
        'currency' => 'USD',
        'action' => 'sell',
        'type' => 'equipped_apartment',
        'country' => 'RDC',
        'city' => 'Kinshasa',
        'municipality' => 'Limete',
        'user_id' => $owner->id,
        'category_id' => Category::where('category_name', 'Appartement')->value('id'),
      ]
    );

    File::updateOrCreate(
      ['product_id' => $product->id, 'file_name' => 'loto-cover.jpg'],
      ['file_url' => $this->demoImages[8], 'file_type' => 'photo']
    );

    LotteryDraw::updateOrCreate(
      ['product_id' => $product->id],
      [
        'ticket_price' => 5000,
        'currency' => 'CDF',
        'tickets_sold' => 3270,
        'tickets_available' => 5000,
        'status' => 'open',
        'draw_at' => Carbon::now()->addDays(7),
      ]
    );
  }

  /**
   * Crée un tirage Ya Ofele Gratos.
   *
   * @return void
   */
  private function seedYaOfele(): void
  {
    YaOfeleDraw::updateOrCreate(
      ['prize_description' => 'Kit maison + frais de dossier foncier'],
      [
        'status' => 'open',
        'draw_at' => Carbon::now()->addDays(3),
      ]
    );
  }

  /**
   * Crée des ventes vérifiées liées aux annonces.
   *
   * @param User $owner Demandeur
   * @return void
   */
  private function seedVerifiedSales(User $owner): void
  {
    $productNames = [
      'Villa familiale avec jardin',
      'Terrain résidentiel titré',
      'Maison de vacances sécurisée',
    ];

    foreach ($productNames as $name) {
      $product = Product::where('product_name', $name)->first();

      if ($product === null) {
        continue;
      }

      VerifiedSale::updateOrCreate(
        ['product_id' => $product->id],
        [
          'user_id' => $owner->id,
          'status' => 'verified',
          'verified_at' => Carbon::now()->subDays(2),
        ]
      );
    }
  }
}
