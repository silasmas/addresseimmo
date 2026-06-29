"use client";

import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useState,
  type ReactNode,
} from "react";
import { getCurrentUser, loginUser, logoutUser, registerUser } from "@/lib/api";
import { clearStoredToken, getStoredToken, setStoredToken } from "@/lib/auth-storage";
import type { LoginPayload, RegisterPayload, User } from "@/lib/types";

/**
 * Valeurs exposées par le contexte d'authentification.
 */
interface AuthContextValue {
  user: User | null;
  token: string | null;
  isLoading: boolean;
  isAuthenticated: boolean;
  login: (payload: LoginPayload) => Promise<void>;
  register: (payload: RegisterPayload) => Promise<void>;
  logout: () => Promise<void>;
  refreshUser: () => Promise<void>;
}

const AuthContext = createContext<AuthContextValue | null>(null);

/**
 * Fournit l'état d'authentification à l'application Next.js.
 *
 * @param props Enfants React
 * @returns Provider d'authentification
 */
export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  /**
   * Charge le profil depuis le token stocké localement.
   */
  const refreshUser = useCallback(async () => {
    const storedToken = getStoredToken();

    if (!storedToken) {
      setUser(null);
      setToken(null);
      return;
    }

    try {
      const profile = await getCurrentUser(storedToken);
      setUser(profile);
      setToken(storedToken);
    } catch {
      clearStoredToken();
      setUser(null);
      setToken(null);
    }
  }, []);

  useEffect(() => {
    let cancelled = false;

    /**
     * Restaure la session au chargement de l'application.
     */
    async function bootstrapSession() {
      const storedToken = getStoredToken();

      if (!storedToken) {
        if (!cancelled) {
          setIsLoading(false);
        }
        return;
      }

      try {
        const profile = await getCurrentUser(storedToken);
        if (!cancelled) {
          setUser(profile);
          setToken(storedToken);
        }
      } catch {
        if (!cancelled) {
          clearStoredToken();
          setUser(null);
          setToken(null);
        }
      } finally {
        if (!cancelled) {
          setIsLoading(false);
        }
      }
    }

    bootstrapSession();

    return () => {
      cancelled = true;
    };
  }, []);

  /**
   * Connecte l'utilisateur et persiste le token Sanctum.
   *
   * @param payload Identifiants de connexion
   */
  const login = useCallback(async (payload: LoginPayload) => {
    const auth = await loginUser(payload);
    setStoredToken(auth.token);
    setToken(auth.token);
    setUser(auth.user);
  }, []);

  /**
   * Inscrit l'utilisateur et ouvre une session.
   *
   * @param payload Données d'inscription
   */
  const register = useCallback(async (payload: RegisterPayload) => {
    const auth = await registerUser(payload);
    setStoredToken(auth.token);
    setToken(auth.token);
    setUser(auth.user);
  }, []);

  /**
   * Déconnecte l'utilisateur et révoque le token API.
   */
  const logout = useCallback(async () => {
    const activeToken = token ?? getStoredToken();

    if (activeToken) {
      try {
        await logoutUser(activeToken);
      } catch {
        // Token déjà invalide côté serveur
      }
    }

    clearStoredToken();
    setToken(null);
    setUser(null);
  }, [token]);

  const value = useMemo<AuthContextValue>(
    () => ({
      user,
      token,
      isLoading,
      isAuthenticated: user !== null && token !== null,
      login,
      register,
      logout,
      refreshUser,
    }),
    [user, token, isLoading, login, register, logout, refreshUser],
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

/**
 * Accède au contexte d'authentification depuis un composant client.
 *
 * @returns Valeurs du contexte auth
 */
export function useAuth(): AuthContextValue {
  const context = useContext(AuthContext);

  if (!context) {
    throw new Error("useAuth doit être utilisé dans AuthProvider.");
  }

  return context;
}
