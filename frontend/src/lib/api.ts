import type { ApiResponse, Product, ProductListData } from "./types";

/**
 * URL de l'API côté serveur (SSR) ou client.
 */
function getApiUrl(): string {
  if (typeof window === "undefined" && process.env.API_INTERNAL_URL) {
    return process.env.API_INTERNAL_URL;
  }

  return process.env.NEXT_PUBLIC_API_URL ?? "http://127.0.0.1:8001/api/v1";
}

/**
 * En-têtes communs pour les appels API Laravel.
 *
 * @param token Token Bearer optionnel
 * @returns En-têtes HTTP
 */
function buildHeaders(token?: string): HeadersInit {
  const headers: HeadersInit = {
    Accept: "application/json",
    "X-localization": "fr",
  };

  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  return headers;
}

/**
 * Exécute une requête API et retourne les données typées.
 *
 * @param path Chemin relatif (ex: /products)
 * @param options Options fetch
 * @returns Données décodées
 */
async function apiFetch<T>(path: string, options: RequestInit = {}): Promise<T> {
  const response = await fetch(`${getApiUrl()}${path}`, {
    ...options,
    headers: {
      ...buildHeaders(),
      ...(options.headers ?? {}),
    },
    cache: "no-store",
  });

  const payload = (await response.json()) as ApiResponse<T>;

  if (!response.ok || !payload.success) {
    throw new Error(payload.message ?? "Erreur API");
  }

  return payload.data;
}

/**
 * Récupère la liste paginée des annonces.
 *
 * @param params Filtres de recherche
 * @returns Produits et pagination
 */
export async function getProducts(params: Record<string, string> = {}): Promise<ProductListData> {
  const query = new URLSearchParams(params).toString();
  const suffix = query ? `?${query}` : "";

  return apiFetch<ProductListData>(`/products${suffix}`);
}

/**
 * Récupère le détail d'une annonce.
 *
 * @param id Identifiant produit
 * @returns Annonce complète
 */
export async function getProduct(id: string): Promise<Product> {
  return apiFetch<Product>(`/products/${id}`);
}

/**
 * Vérifie que l'API Laravel est joignable.
 *
 * @returns true si l'API répond
 */
export async function checkApiHealth(): Promise<boolean> {
  try {
    await apiFetch<{ status: string }>("/health");
    return true;
  } catch {
    return false;
  }
}
