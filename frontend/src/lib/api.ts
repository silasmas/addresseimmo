import { ApiError } from "./api-error";
import type {
  ApiResponse,
  AuthData,
  InstallStatus,
  LoginPayload,
  OtpDeliveryData,
  Product,
  ProductListData,
  RegisterPayload,
  User,
} from "./types";

/**
 * Délai maximum d'attente des requêtes API (millisecondes).
 */
const API_TIMEOUT_MS = 8000;

/**
 * Options étendues pour les requêtes API.
 */
interface ApiFetchOptions extends RequestInit {
  token?: string;
}

/**
 * URL de l'API côté serveur (SSR) ou client.
 *
 * @returns URL de base de l'API v1
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
 * @param withJson Indique si le corps est du JSON
 * @returns En-têtes HTTP
 */
function buildHeaders(token?: string, withJson = false): HeadersInit {
  const headers: HeadersInit = {
    Accept: "application/json",
    "X-localization": "fr",
  };

  if (withJson) {
    headers["Content-Type"] = "application/json";
  }

  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  return headers;
}

/**
 * Exécute une requête API et retourne les données typées.
 *
 * @param path Chemin relatif (ex: /products)
 * @param options Options fetch et token
 * @returns Données décodées
 */
async function apiFetch<T>(path: string, options: ApiFetchOptions = {}): Promise<T> {
  const { token, ...fetchOptions } = options;
  const hasBody = fetchOptions.body !== undefined;
  const controller = new AbortController();
  const timeoutId = setTimeout(() => controller.abort(), API_TIMEOUT_MS);

  try {
    const response = await fetch(`${getApiUrl()}${path}`, {
      ...fetchOptions,
      signal: controller.signal,
      headers: {
        ...buildHeaders(token, hasBody),
        ...(fetchOptions.headers ?? {}),
      },
      cache: "no-store",
    });

    const payload = (await response.json()) as ApiResponse<T> & {
      errors?: Record<string, string[]>;
    };

    const isSuccess = payload.success !== false && response.ok;

    if (!isSuccess) {
      const fieldErrors = payload.errors ?? {};
      const message =
        payload.message ??
        (typeof payload.data === "string" ? payload.data : `Erreur API (${response.status})`);

      throw new ApiError(message, response.status, fieldErrors);
    }

    return payload.data;
  } catch (error) {
    if (error instanceof DOMException && error.name === "AbortError") {
      throw new ApiError("L'API met trop de temps à répondre.", 408);
    }
    throw error;
  } finally {
    clearTimeout(timeoutId);
  }
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
 * Inscrit un nouvel utilisateur.
 *
 * @param data Données d'inscription
 * @returns Utilisateur et token Sanctum
 */
export async function registerUser(data: RegisterPayload): Promise<AuthData> {
  return apiFetch<AuthData>("/auth/register", {
    method: "POST",
    body: JSON.stringify(data),
  });
}

/**
 * Connecte un utilisateur existant.
 *
 * @param data Identifiants de connexion
 * @returns Utilisateur et token Sanctum
 */
export async function loginUser(data: LoginPayload): Promise<AuthData> {
  return apiFetch<AuthData>("/auth/login", {
    method: "POST",
    body: JSON.stringify(data),
  });
}

/**
 * Demande un code OTP pour la connexion.
 *
 * @param login Email ou telephone
 * @returns Infos d'envoi OTP
 */
export async function sendLoginOtp(login: string): Promise<OtpDeliveryData> {
  return apiFetch<OtpDeliveryData>("/auth/otp/send", {
    method: "POST",
    body: JSON.stringify({ login: login.trim() }),
  });
}

/**
 * Inscrit un utilisateur et declenche l'envoi OTP.
 *
 * @param data Donnees d'inscription
 * @returns Infos d'envoi OTP
 */
export async function registerWithOtp(data: RegisterPayload): Promise<OtpDeliveryData> {
  return apiFetch<OtpDeliveryData>("/auth/otp/register", {
    method: "POST",
    body: JSON.stringify(data),
  });
}

/**
 * Verifie un code OTP et ouvre la session.
 *
 * @param login Identifiant utilise a l'envoi
 * @param otp Code recu
 * @returns Utilisateur et token Sanctum
 */
export async function verifyOtp(login: string, otp: string): Promise<AuthData> {
  return apiFetch<AuthData>("/auth/otp/verify", {
    method: "POST",
    body: JSON.stringify({ login: login.trim(), otp: otp.trim() }),
  });
}

/**
 * Récupère le profil de l'utilisateur connecté.
 *
 * @param token Token Bearer Sanctum
 * @returns Profil utilisateur
 */
export async function getCurrentUser(token: string): Promise<User> {
  return apiFetch<User>("/auth/me", { token });
}

/**
 * Révoque le token courant côté API.
 *
 * @param token Token Bearer Sanctum
 */
export async function logoutUser(token: string): Promise<void> {
  await apiFetch<null>("/auth/logout", {
    method: "POST",
    token,
  });
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

/**
 * Récupère le statut d'installation du backend.
 *
 * @returns État du déploiement (migrations, admin, frontend prêt)
 */
export async function getInstallStatus(): Promise<InstallStatus> {
  return apiFetch<InstallStatus>("/install/status");
}
