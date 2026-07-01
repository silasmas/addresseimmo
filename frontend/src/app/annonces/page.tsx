import { Suspense } from "react";
import { AnnoncesCatalog } from "@/components/AnnoncesCatalog";
import { PropertyGridSkeleton } from "@/components/skeleton/HomeSkeletons";

/**
 * Page catalogue des annonces immobilieres.
 */
export default function AnnoncesPage() {
  return (
    <>
      <section className="page-hero compact">
        <span className="eyebrow">Catalogue</span>
        <h1>Annonces immobilieres</h1>
        <p>Un catalogue clair pour acheter, louer, comparer et garder vos favoris.</p>
      </section>

      <section className="section">
        <Suspense fallback={<PropertyGridSkeleton count={6} layout="grid" />}>
          <AnnoncesCatalog />
        </Suspense>
      </section>
    </>
  );
}
