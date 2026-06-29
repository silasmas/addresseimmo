import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import { getProduct } from "@/lib/api";

interface ProductDetailPageProps {
  params: Promise<{ id: string }>;
}

/**
 * Fiche détaillée d'une annonce.
 */
export default async function ProductDetailPage({ params }: ProductDetailPageProps) {
  const { id } = await params;

  let product;

  try {
    product = await getProduct(id);
  } catch {
    notFound();
  }

  const imageUrl = product.photos?.[0]?.file_url;
  const location = [
    product.street,
    product.neighborhood,
    product.municipality,
    product.city,
    product.country,
  ]
    .filter(Boolean)
    .join(", ");

  return (
    <div className="mx-auto max-w-6xl px-4 py-10">
      <Link href="/annonces" className="text-sm font-medium text-[var(--green)]">
        ← Retour aux annonces
      </Link>

      <div className="mt-6 grid gap-8 lg:grid-cols-2">
        <div className="relative aspect-[4/3] overflow-hidden rounded-2xl bg-[var(--soft)]">
          {imageUrl ? (
            <Image
              src={imageUrl}
              alt={product.product_name}
              fill
              className="object-cover"
              priority
              unoptimized
            />
          ) : (
            <div className="flex h-full items-center justify-center text-[var(--muted)]">
              Aucune photo
            </div>
          )}
        </div>

        <div>
          <p className="text-sm font-semibold uppercase tracking-wide text-[var(--green)]">
            {product.readable_action}
          </p>
          <h1 className="mt-2 text-3xl font-bold">{product.product_name}</h1>
          <p className="mt-2 text-[var(--muted)]">{location}</p>
          <p className="mt-6 text-2xl font-bold text-[var(--green)]">
            {product.converted_price} {product.readable_currency}
          </p>

          <dl className="mt-6 grid grid-cols-2 gap-4 text-sm">
            <div>
              <dt className="text-[var(--muted)]">Type</dt>
              <dd className="font-medium">{product.readable_type ?? product.type}</dd>
            </div>
            <div>
              <dt className="text-[var(--muted)]">Stock</dt>
              <dd className="font-medium">{product.quantity}</dd>
            </div>
            <div>
              <dt className="text-[var(--muted)]">Note</dt>
              <dd className="font-medium">{product.average_rating || "—"}</dd>
            </div>
            <div>
              <dt className="text-[var(--muted)]">Publié</dt>
              <dd className="font-medium">{product.created_at_explicit}</dd>
            </div>
          </dl>

          <div className="mt-8 rounded-xl border border-[var(--line)] p-4">
            <h2 className="font-semibold">Description</h2>
            <p className="mt-2 whitespace-pre-line text-sm leading-7 text-[var(--muted)]">
              {product.product_description || "Aucune description fournie."}
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
