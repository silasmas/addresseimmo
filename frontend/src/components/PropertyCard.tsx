import Image from "next/image";
import Link from "next/link";
import type { Product } from "@/lib/types";

interface PropertyCardProps {
  product: Product;
}

/**
 * Carte d'annonce pour les grilles catalogue.
 */
export function PropertyCard({ product }: PropertyCardProps) {
  const imageUrl = product.photos?.[0]?.file_url ?? "/placeholder-property.jpg";
  const location = [product.city, product.municipality].filter(Boolean).join(", ");

  return (
    <article className="overflow-hidden rounded-xl border border-[var(--line)] bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
      <Link href={`/annonces/${product.id}`}>
        <div className="relative aspect-[4/3] bg-[var(--soft)]">
          <Image
            src={imageUrl}
            alt={product.product_name}
            fill
            className="object-cover"
            sizes="(max-width: 768px) 100vw, 33vw"
            unoptimized
          />
        </div>
        <div className="space-y-2 p-4">
          <div className="flex items-center justify-between gap-2">
            <h3 className="line-clamp-1 font-semibold text-[var(--ink)]">{product.product_name}</h3>
            {product.average_rating > 0 && (
              <span className="text-xs text-[var(--muted)]">★ {product.average_rating}</span>
            )}
          </div>
          <p className="text-sm text-[var(--muted)]">{location}</p>
          <p className="text-sm font-semibold text-[var(--green)]">
            {product.converted_price} {product.readable_currency}
            {product.readable_action ? ` · ${product.readable_action}` : ""}
          </p>
        </div>
      </Link>
    </article>
  );
}
