import Image from "next/image";
import Link from "next/link";
import {
  formatProductMeta,
  formatProductPrice,
  formatRating,
  isSecureProduct,
  isVerifiedProduct,
} from "@/lib/format-product";
import type { Product } from "@/lib/types";

interface PropertyCardProps {
  product: Product;
}

/**
 * Badge « Vérifié » du template Phase 2.
 *
 * @param label Texte du badge
 * @returns Span badge vérifié
 */
function VerifiedBadge({ label = "Vérifié" }: { label?: string }) {
  return <span className="badge badge-verified">{label}</span>;
}

/**
 * Carte annonce fidèle au template Phase 2.
 *
 * @param props Produit API à afficher
 * @returns Carte cliquable avec badges et note
 */
export function PropertyCard({ product }: PropertyCardProps) {
  const imageUrl = product.photos?.[0]?.file_url ?? "/placeholder-property.jpg";
  const location = [product.city, product.municipality].filter(Boolean).join(", ");
  const verified = isVerifiedProduct(product);
  const secure = isSecureProduct(product);
  const rating = formatRating(product.average_rating);

  return (
    <article className="property-card">
      <Link href={`/annonces/${product.id}`} className="property-card-link">
        <div className="media">
          <Image
            src={imageUrl}
            alt={`${product.product_name} à ${location}`}
            width={760}
            height={600}
            unoptimized
          />
          <div className="card-badges">
            {verified ? <VerifiedBadge /> : null}
            {secure ? <span className="badge badge-soft">Sécurisé</span> : null}
          </div>
          <button className="favorite" type="button" aria-label="Ajouter aux favoris" tabIndex={-1}>
            ♡
          </button>
        </div>
        <div className="property-body">
          <div className="property-line">
            <h3>{product.product_name}</h3>
            {rating ? <span className="rating">★ {rating}</span> : null}
          </div>
          <p>{location}</p>
          <p className="muted">{formatProductMeta(product)}</p>
          <strong>{formatProductPrice(product)}</strong>
        </div>
      </Link>
    </article>
  );
}
