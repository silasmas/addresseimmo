import Link from "next/link";
import { HeroGallery } from "@/components/HeroGallery";
import { ModuleCard } from "@/components/ModuleCard";
import { PropertyCard } from "@/components/PropertyCard";
import { SearchBar } from "@/components/SearchBar";
import { SectionHeader } from "@/components/SectionHeader";
import { isVerifiedProduct } from "@/lib/format-product";
import { getProducts } from "@/lib/api";
import { HOME_MODULES, QUICK_FILTERS } from "@/lib/home-content";
import type { Product } from "@/lib/types";

/**
 * Page d'accueil fidèle au template Phase 2 (hero, modules, grilles annonces).
 */
export default async function HomePage() {
  let products: Product[] = [];

  try {
    const data = await getProducts({ per_page: "12" });
    products = data.items;
  } catch {
    products = [];
  }

  const popularProducts = products.slice(0, 3);
  const verifiedProducts = products.filter(isVerifiedProduct);
  const verifiedDisplay = verifiedProducts.length > 0 ? verifiedProducts : products.slice(0, 3);

  return (
    <>
      <section className="hero">
        <div className="hero-copy">
          <span className="eyebrow">RDC, Sénégal, Côte d&apos;Ivoire, Cameroun</span>
          <h1>Trouver un bien fiable, clair et local.</h1>
          <p>
            Parcourez des annonces immobilières, participez aux modules spéciaux et privilégiez
            les ventes sécurisées avec contrôle juridique.
          </p>
          <SearchBar />
          <div className="quick-filters" aria-label="Filtres rapides">
            {QUICK_FILTERS.map((filter) => (
              <Link key={filter.label} href={filter.href}>
                {filter.label}
              </Link>
            ))}
          </div>
        </div>
        <HeroGallery />
      </section>

      <section className="section">
        <SectionHeader
          title="Modules spéciaux"
          text="Des expériences immobilières encadrées, visibles dès l'accueil."
          actionLabel="Découvrir"
          actionHref="/encheres"
        />
        <div className="module-grid">
          {HOME_MODULES.map((module) => (
            <ModuleCard
              key={module.title}
              title={module.title}
              text={module.text}
              href={module.href}
              cta={module.cta}
            />
          ))}
        </div>
      </section>

      <section className="section">
        <SectionHeader
          title="Biens populaires"
          text="Sélection photo, quartiers recherchés et annonces récentes."
          actionLabel="Voir les biens"
          actionHref="/annonces"
        />
        {popularProducts.length === 0 ? (
          <EmptyProductsMessage />
        ) : (
          <div className="property-grid">
            {popularProducts.map((product) => (
              <PropertyCard key={product.id} product={product} />
            ))}
          </div>
        )}
      </section>

      <section className="section">
        <SectionHeader
          title="Annonces vérifiées"
          text="Documents ou propriétaires contrôlés par l'équipe."
          actionLabel="Voir tout"
          actionHref="/ventes-verifiees"
        />
        {verifiedDisplay.length === 0 ? (
          <EmptyProductsMessage />
        ) : (
          <div className="property-grid">
            {verifiedDisplay.map((product) => (
              <PropertyCard key={product.id} product={product} />
            ))}
          </div>
        )}
      </section>

      <section className="section">
        <SectionHeader
          title="Nouveautés"
          text="Les dernières publications multi-pays."
          actionLabel="Voir tout"
          actionHref="/annonces"
        />
        {products.length === 0 ? (
          <EmptyProductsMessage />
        ) : (
          <div className="property-row">
            {products.map((product) => (
              <PropertyCard key={product.id} product={product} />
            ))}
          </div>
        )}
      </section>
    </>
  );
}

/**
 * Message affiché lorsque l'API ne renvoie aucune annonce.
 *
 * @returns Bloc d'information API
 */
function EmptyProductsMessage() {
  return (
    <p className="notice">
      Aucune annonce disponible. Démarrez l&apos;API AddressImmo :{" "}
      <code>php artisan serve --port=8001</code>
    </p>
  );
}
