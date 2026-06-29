import { PropertyCard } from "@/components/PropertyCard";
import { getProducts } from "@/lib/api";
import type { PaginationMeta, Product } from "@/lib/types";

interface AnnoncesPageProps {
  searchParams: Promise<{
    search?: string;
    action?: string;
    city?: string;
    page?: string;
  }>;
}

/**
 * Catalogue des annonces immobilières.
 */
export default async function AnnoncesPage({ searchParams }: AnnoncesPageProps) {
  const params = await searchParams;
  const query: Record<string, string> = { per_page: "12" };

  if (params.search) {
    query.search = params.search;
  }

  if (params.action) {
    query.action = params.action;
  }

  if (params.city) {
    query.city = params.city;
  }

  if (params.page) {
    query.page = params.page;
  }

  let items: Product[] = [];
  let pagination: PaginationMeta | null = null;
  let errorMessage: string | null = null;

  try {
    const data = await getProducts(query);
    items = data.items;
    pagination = data.pagination;
  } catch (error) {
    errorMessage = error instanceof Error ? error.message : "Erreur de chargement";
  }

  return (
    <div className="mx-auto max-w-6xl px-4 py-10">
      <div className="mb-8">
        <h1 className="text-3xl font-bold">Annonces</h1>
        <p className="mt-2 text-[var(--muted)]">Parcourez les biens et services disponibles</p>
      </div>

      <form method="get" className="mb-8 grid gap-3 rounded-xl border border-[var(--line)] bg-[var(--soft)] p-4 md:grid-cols-4">
        <input
          name="search"
          defaultValue={params.search ?? ""}
          placeholder="Rechercher..."
          className="rounded-lg border border-[var(--line)] bg-white px-3 py-2 md:col-span-2"
        />
        <select
          name="action"
          defaultValue={params.action ?? ""}
          className="rounded-lg border border-[var(--line)] bg-white px-3 py-2"
        >
          <option value="">Toutes les actions</option>
          <option value="sell">Vente</option>
          <option value="rent">Location</option>
          <option value="build">Construction</option>
        </select>
        <button
          type="submit"
          className="rounded-lg bg-[var(--green)] px-4 py-2 font-semibold text-white"
        >
          Filtrer
        </button>
      </form>

      {errorMessage && (
        <p className="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700">{errorMessage}</p>
      )}

      {items.length === 0 ? (
        <p className="text-[var(--muted)]">Aucune annonce trouvée.</p>
      ) : (
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {items.map((product) => (
            <PropertyCard key={product.id} product={product} />
          ))}
        </div>
      )}

      {pagination && pagination.last_page > 1 && (
        <p className="mt-8 text-sm text-[var(--muted)]">
          Page {pagination.current_page} sur {pagination.last_page} — {pagination.total} annonces
        </p>
      )}
    </div>
  );
}
