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
 * Page d'inscription utilisateur via l'API Sanctum.
 */
export default function InscriptionPage() {
  const router = useRouter();
  const { register, isAuthenticated, isLoading } = useAuth();
  const [firstname, setFirstname] = useState("");
  const [lastname, setLastname] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [error, setError] = useState("");
  const [fieldErrors, setFieldErrors] = useState<Record<string, string[]>>({});
  const [isSubmitting, setIsSubmitting] = useState(false);

  if (!isLoading && isAuthenticated) {
    router.replace("/compte");
    return null;
  }

  /**
   * Retourne le premier message d'erreur pour un champ donné.
   *
   * @param field Nom du champ API
   * @returns Message d'erreur ou chaîne vide
   */
  function getFieldError(field: string): string {
    return fieldErrors[field]?.[0] ?? "";
  }

  /**
   * Soumet le formulaire d'inscription.
   *
   * @param event Événement submit du formulaire
   */
  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError("");
    setFieldErrors({});
    setIsSubmitting(true);

    try {
      await register({
        firstname: firstname.trim(),
        lastname: lastname.trim() || undefined,
        email: email.trim() || undefined,
        phone: phone.trim() || undefined,
        password,
        password_confirmation: passwordConfirmation,
        currency: "USD",
      });
      router.push("/compte");
    } catch (err) {
      if (err instanceof ApiError) {
        setError(err.message);
        setFieldErrors(err.fieldErrors);
      } else {
        setError("Inscription impossible.");
      }
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <AuthFormLayout
      title="Inscription"
      subtitle="Créez votre compte AddressImmo"
      alternateLabel="Déjà inscrit ?"
      alternateHref="/connexion"
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        {error && (
          <p className="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700" role="alert">
            {error}
          </p>
        )}
        <div className="grid gap-4 sm:grid-cols-2">
          <div>
            <label htmlFor="firstname" className="mb-1 block text-sm font-medium">
              Prénom
            </label>
            <input
              id="firstname"
              type="text"
              required
              className={authInputClassName}
              value={firstname}
              onChange={(event) => setFirstname(event.target.value)}
            />
            {getFieldError("firstname") && (
              <p className="mt-1 text-xs text-red-600">{getFieldError("firstname")}</p>
            )}
          </div>
          <div>
            <label htmlFor="lastname" className="mb-1 block text-sm font-medium">
              Nom
            </label>
            <input
              id="lastname"
              type="text"
              className={authInputClassName}
              value={lastname}
              onChange={(event) => setLastname(event.target.value)}
            />
          </div>
        </div>
        <div>
          <label htmlFor="email" className="mb-1 block text-sm font-medium">
            Email
          </label>
          <input
            id="email"
            type="email"
            autoComplete="email"
            className={authInputClassName}
            value={email}
            onChange={(event) => setEmail(event.target.value)}
          />
          {getFieldError("email") && (
            <p className="mt-1 text-xs text-red-600">{getFieldError("email")}</p>
          )}
        </div>
        <div>
          <label htmlFor="phone" className="mb-1 block text-sm font-medium">
            Téléphone
          </label>
          <input
            id="phone"
            type="tel"
            autoComplete="tel"
            className={authInputClassName}
            value={phone}
            onChange={(event) => setPhone(event.target.value)}
          />
          {getFieldError("phone") && (
            <p className="mt-1 text-xs text-red-600">{getFieldError("phone")}</p>
          )}
        </div>
        <div>
          <label htmlFor="password" className="mb-1 block text-sm font-medium">
            Mot de passe
          </label>
          <input
            id="password"
            type="password"
            required
            autoComplete="new-password"
            className={authInputClassName}
            value={password}
            onChange={(event) => setPassword(event.target.value)}
          />
          {getFieldError("password") && (
            <p className="mt-1 text-xs text-red-600">{getFieldError("password")}</p>
          )}
        </div>
        <div>
          <label htmlFor="passwordConfirmation" className="mb-1 block text-sm font-medium">
            Confirmer le mot de passe
          </label>
          <input
            id="passwordConfirmation"
            type="password"
            required
            autoComplete="new-password"
            className={authInputClassName}
            value={passwordConfirmation}
            onChange={(event) => setPasswordConfirmation(event.target.value)}
          />
        </div>
        <button type="submit" className={authButtonClassName} disabled={isSubmitting}>
          {isSubmitting ? "Création..." : "Créer mon compte"}
        </button>
      </form>
    </AuthFormLayout>
  );
}
