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
import { sendLoginOtp } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";

/**
 * Page de connexion par OTP (email ou telephone).
 */
export default function ConnexionPage() {
  const router = useRouter();
  const { completeOtpLogin, isAuthenticated, isLoading } = useAuth();
  const [loginValue, setLoginValue] = useState("");
  const [step, setStep] = useState<"request" | "verify">("request");
  const [maskedContact, setMaskedContact] = useState("");
  const [channel, setChannel] = useState("");
  const [debugOtp, setDebugOtp] = useState<string | null>(null);
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  if (!isLoading && isAuthenticated) {
    router.replace("/compte");
    return null;
  }

  /**
   * Demande l'envoi du code OTP.
   *
   * @param event Evenement submit
   */
  async function handleRequestOtp(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError("");
    setIsSubmitting(true);

    try {
      const delivery = await sendLoginOtp(loginValue.trim());
      setMaskedContact(delivery.masked_contact);
      setChannel(delivery.channel);
      setDebugOtp(delivery.debug_otp ?? null);
      setStep("verify");
    } catch (err) {
      const message = err instanceof ApiError ? err.message : "Envoi du code impossible.";
      setError(message);
    } finally {
      setIsSubmitting(false);
    }
  }

  /**
   * Verifie le code OTP et connecte l'utilisateur.
   *
   * @param otp Code saisi
   */
  async function handleVerifyOtp(otp: string) {
    setError("");
    setIsSubmitting(true);

    try {
      await completeOtpLogin(loginValue.trim(), otp);
      router.push("/compte");
    } catch (err) {
      const message = err instanceof ApiError ? err.message : "Code invalide.";
      setError(message);
    } finally {
      setIsSubmitting(false);
    }
  }

  /**
   * Renvoie un nouveau code OTP.
   */
  async function handleResendOtp() {
    setError("");
    setIsSubmitting(true);

    try {
      const delivery = await sendLoginOtp(loginValue.trim());
      setMaskedContact(delivery.masked_contact);
      setChannel(delivery.channel);
      setDebugOtp(delivery.debug_otp ?? null);
    } catch (err) {
      const message = err instanceof ApiError ? err.message : "Renvoi impossible.";
      setError(message);
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <AuthFormLayout
      title="Connexion"
      subtitle="Recevez un code de vérification par email ou SMS"
      alternateLabel="Pas encore de compte ?"
      alternateHref="/inscription"
    >
      {step === "request" ? (
        <form className="auth-form space-y-4" onSubmit={handleRequestOtp}>
          {error ? (
            <p className="auth-alert auth-alert-error" role="alert">
              {error}
            </p>
          ) : null}
          <label className="auth-field" htmlFor="login">
            <span className="auth-field-label">Email ou téléphone</span>
            <input
              id="login"
              type="text"
              required
              autoComplete="username"
              placeholder="vous@email.com ou +243..."
              className={authInputClassName}
              value={loginValue}
              onChange={(event) => setLoginValue(event.target.value)}
            />
          </label>
          <button type="submit" className={authButtonClassName} disabled={isSubmitting}>
            {isSubmitting ? "Envoi..." : "Recevoir le code"}
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
          onResend={handleResendOtp}
        />
      )}
    </AuthFormLayout>
  );
}
