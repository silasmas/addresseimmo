import Link from "next/link";
import { PropertyCard } from "@/components/PropertyCard";
import { getProducts } from "@/lib/api";
import type { Product } from "@/lib/types";

/**
 * Page d'accueil — annonces récentes depuis l'API Laravel.
 */
export default async function HomePage() {
  let products: Product[] = [];

  try {
    const data = await getProducts({ per_page: "6" });
    products = data.items;
  } catch {
    products = [];
  }

  return (
    <div>
      <section className="bg-[var(--soft)]">
        <div className="mx-auto max-w-6xl px-4 py-16">
          <p className="text-sm font-semibold uppercase tracking-wide text-[var(--green)]">
            Immobilier fiable et local
          </p>
          <h1 className="mt-3 max-w-2xl text-4xl font-bold leading-tight text-[var(--ink)]">
            Trouvez, louez ou vendez votre bien en toute confiance
          </h1>
          <p className="mt-4 max-w-2xl text-[var(--muted)]">
            Annonces vérifiées, enchères, loto immobilier et ventes sécurisées — propulsé par
            AddressImmo.
          </p>
          <div className="mt-8 flex flex-wrap gap-3">
            <Link
              href="/annonces"
              className="rounded-lg bg-[var(--green)] px-5 py-3 text-sm font-semibold text-white"
            >
              Voir les annonces
            </Link>
            <Link
              href="/ventes-verifiees"
              className="rounded-lg border border-[var(--line)] bg-white px-5 py-3 text-sm font-semibold"
            >
              Ventes vérifiées
            </Link>
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-4 py-12">
        <div className="mb-6 flex items-end justify-between gap-4">
          <div>
            <h2 className="text-2xl font-bold">Annonces récentes</h2>
            <p className="mt-1 text-sm text-[var(--muted)]">Données en direct depuis l&apos;API Laravel</p>
          </div>
          <Link href="/annonces" className="text-sm font-semibold text-[var(--green)]">
            Tout voir →
          </Link>
        </div>

        {products.length === 0 ? (
          <p className="rounded-xl border border-dashed border-[var(--line)] p-8 text-center text-[var(--muted)]">
            Aucune annonce disponible. Démarrez l&apos;API AddressImmo :{" "}
            <code className="text-sm">php artisan serve --port=8001</code>
          </p>
        ) : (
          <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {products.map((product) => (
              <PropertyCard key={product.id} product={product} />
            ))}
          </div>
        )}
      </section>
    </div>
  );
}
