const TOKEN_KEY = "addressimmo_token";

/**
 * Lit le token Sanctum stocké côté navigateur.
 *
 * @returns Token Bearer ou null
 */
export function getStoredToken(): string | null {
  if (typeof window === "undefined") {
    return null;
  }

  return localStorage.getItem(TOKEN_KEY);
}

/**
 * Persiste le token Sanctum côté navigateur.
 *
 * @param token Token Bearer
 */
export function setStoredToken(token: string): void {
  localStorage.setItem(TOKEN_KEY, token);
}

/**
 * Supprime le token Sanctum du navigateur.
 */
export function clearStoredToken(): void {
  localStorage.removeItem(TOKEN_KEY);
}
