"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";
import {
  AuthFormLayout,
  authButtonClassName,
  authInputClassName,
} from "@/components/AuthFormLayout";
import { ApiError } from "@/lib/api-error";
import { useAuth } from "@/contexts/AuthContext";

/**
 * Page de connexion utilisateur via l'API Sanctum.
 */
export default function ConnexionPage() {
  const router = useRouter();
  const { login, isAuthenticated, isLoading } = useAuth();
  const [loginValue, setLoginValue] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  if (!isLoading && isAuthenticated) {
    router.replace("/compte");
    return null;
  }

  /**
   * Soumet le formulaire de connexion.
   *
   * @param event Événement submit du formulaire
   */
  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError("");
    setIsSubmitting(true);

    try {
      await login({ login: loginValue.trim(), password });
      router.push("/compte");
    } catch (err) {
      const message = err instanceof ApiError ? err.message : "Connexion impossible.";
      setError(message);
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <AuthFormLayout
      title="Connexion"
      subtitle="Accédez à votre compte AddressImmo"
      alternateLabel="Pas encore de compte ?"
      alternateHref="/inscription"
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        {error && (
          <p className="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700" role="alert">
            {error}
          </p>
        )}
        <div>
          <label htmlFor="login" className="mb-1 block text-sm font-medium">
            Email, téléphone ou identifiant
          </label>
          <input
            id="login"
            type="text"
            required
            autoComplete="username"
            className={authInputClassName}
            value={loginValue}
            onChange={(event) => setLoginValue(event.target.value)}
          />
        </div>
        <div>
          <label htmlFor="password" className="mb-1 block text-sm font-medium">
            Mot de passe
          </label>
          <input
            id="password"
            type="password"
            required
            autoComplete="current-password"
            className={authInputClassName}
            value={password}
            onChange={(event) => setPassword(event.target.value)}
          />
        </div>
        <button type="submit" className={authButtonClassName} disabled={isSubmitting}>
          {isSubmitting ? "Connexion..." : "Se connecter"}
        </button>
      </form>
    </AuthFormLayout>
  );
}
