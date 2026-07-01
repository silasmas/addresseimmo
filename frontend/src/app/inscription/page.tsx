"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";
import {
  AuthFormLayout,
  authButtonClassName,
  authInputClassName,
} from "@/components/AuthFormLayout";
import { OtpCodeForm } from "@/components/OtpCodeForm";
import { ApiError } from "@/lib/api-error";
import { registerWithOtp } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";

/**
 * Page d'inscription avec validation OTP.
 */
export default function InscriptionPage() {
  const router = useRouter();
  const { completeOtpLogin, isAuthenticated, isLoading } = useAuth();
  const [firstname, setFirstname] = useState("");
  const [lastname, setLastname] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [loginValue, setLoginValue] = useState("");
  const [step, setStep] = useState<"register" | "verify">("register");
  const [maskedContact, setMaskedContact] = useState("");
  const [channel, setChannel] = useState("");
  const [debugOtp, setDebugOtp] = useState<string | null>(null);
  const [error, setError] = useState("");
  const [fieldErrors, setFieldErrors] = useState<Record<string, string[]>>({});
  const [isSubmitting, setIsSubmitting] = useState(false);

  if (!isLoading && isAuthenticated) {
    router.replace("/compte");
    return null;
  }

  /**
   * Retourne le premier message d'erreur pour un champ donne.
   *
   * @param field Nom du champ API
   * @returns Message d'erreur ou chaine vide
   */
  function getFieldError(field: string): string {
    return fieldErrors[field]?.[0] ?? "";
  }

  /**
   * Soumet l'inscription et declenche l'envoi OTP.
   *
   * @param event Evenement submit
   */
  async function handleRegister(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError("");
    setFieldErrors({});
    setIsSubmitting(true);

    if (!email.trim() && !phone.trim()) {
      setError("Indiquez au moins un email ou un numéro de téléphone.");
      setIsSubmitting(false);
      return;
    }

    try {
      const delivery = await registerWithOtp({
        firstname: firstname.trim(),
        lastname: lastname.trim() || undefined,
        email: email.trim() || undefined,
        phone: phone.trim() || undefined,
        password,
        password_confirmation: passwordConfirmation,
        currency: "USD",
      });
      setLoginValue(delivery.login);
      setMaskedContact(delivery.masked_contact);
      setChannel(delivery.channel);
      setDebugOtp(delivery.debug_otp ?? null);
      setStep("verify");
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

  /**
   * Verifie le code OTP apres inscription.
   *
   * @param otp Code saisi
   */
  async function handleVerifyOtp(otp: string) {
    setError("");
    setIsSubmitting(true);

    try {
      await completeOtpLogin(loginValue, otp);
      router.push("/compte");
    } catch (err) {
      const message = err instanceof ApiError ? err.message : "Code invalide.";
      setError(message);
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <AuthFormLayout
      title="Inscription"
      subtitle="Créez votre compte puis validez-le avec le code reçu"
      alternateLabel="Déjà inscrit ?"
      alternateHref="/connexion"
    >
      {step === "register" ? (
        <form className="auth-form space-y-4" onSubmit={handleRegister}>
          {error ? (
            <p className="auth-alert auth-alert-error" role="alert">
              {error}
            </p>
          ) : null}
          <div className="auth-grid-2">
            <label className="auth-field" htmlFor="firstname">
              <span className="auth-field-label">Prénom</span>
              <input
                id="firstname"
                type="text"
                required
                className={authInputClassName}
                value={firstname}
                onChange={(event) => setFirstname(event.target.value)}
              />
              {getFieldError("firstname") ? (
                <span className="auth-field-error">{getFieldError("firstname")}</span>
              ) : null}
            </label>
            <label className="auth-field" htmlFor="lastname">
              <span className="auth-field-label">Nom</span>
              <input
                id="lastname"
                type="text"
                className={authInputClassName}
                value={lastname}
                onChange={(event) => setLastname(event.target.value)}
              />
            </label>
          </div>
          <label className="auth-field" htmlFor="email">
            <span className="auth-field-label">Email</span>
            <input
              id="email"
              type="email"
              autoComplete="email"
              placeholder="vous@email.com"
              className={authInputClassName}
              value={email}
              onChange={(event) => setEmail(event.target.value)}
            />
            {getFieldError("email") ? (
              <span className="auth-field-error">{getFieldError("email")}</span>
            ) : null}
          </label>
          <label className="auth-field" htmlFor="phone">
            <span className="auth-field-label">Téléphone</span>
            <input
              id="phone"
              type="tel"
              autoComplete="tel"
              placeholder="+243..."
              className={authInputClassName}
              value={phone}
              onChange={(event) => setPhone(event.target.value)}
            />
            {getFieldError("phone") ? (
              <span className="auth-field-error">{getFieldError("phone")}</span>
            ) : null}
          </label>
          <label className="auth-field" htmlFor="password">
            <span className="auth-field-label">Mot de passe</span>
            <input
              id="password"
              type="password"
              required
              autoComplete="new-password"
              className={authInputClassName}
              value={password}
              onChange={(event) => setPassword(event.target.value)}
            />
            {getFieldError("password") ? (
              <span className="auth-field-error">{getFieldError("password")}</span>
            ) : null}
          </label>
          <label className="auth-field" htmlFor="passwordConfirmation">
            <span className="auth-field-label">Confirmer le mot de passe</span>
            <input
              id="passwordConfirmation"
              type="password"
              required
              autoComplete="new-password"
              className={authInputClassName}
              value={passwordConfirmation}
              onChange={(event) => setPasswordConfirmation(event.target.value)}
            />
          </label>
          <button type="submit" className={authButtonClassName} disabled={isSubmitting}>
            {isSubmitting ? "Envoi du code..." : "Créer mon compte"}
          </button>
        </form>
      ) : (
        <OtpCodeForm
          maskedContact={maskedContact}
          channel={channel}
          debugOtp={debugOtp}
          isSubmitting={isSubmitting}
          error={error}
          onSubmit={handleVerifyOtp}
        />
      )}
    </AuthFormLayout>
  );
}
