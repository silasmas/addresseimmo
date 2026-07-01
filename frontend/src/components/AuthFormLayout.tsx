import Link from "next/link";
import type { ReactNode } from "react";

/**
 * Props du layout partagé des pages d'authentification.
 */
interface AuthFormLayoutProps {
  title: string;
  subtitle: string;
  children: ReactNode;
  alternateLabel: string;
  alternateHref: string;
}

/**
 * Cadre visuel commun pour connexion et inscription.
 *
 * @param props Titre, formulaire et lien alternatif
 * @returns Section centrée avec carte formulaire
 */
export function AuthFormLayout({
  title,
  subtitle,
  children,
  alternateLabel,
  alternateHref,
}: AuthFormLayoutProps) {
  return (
    <section className="auth-page">
      <div className="auth-card">
        <div className="auth-card-header">
          <h1>{title}</h1>
          <p>{subtitle}</p>
        </div>
        {children}
        <p className="auth-card-footer">
          {alternateLabel}{" "}
          <Link href={alternateHref}>cliquez ici</Link>
        </p>
      </div>
    </section>
  );
}

/**
 * Classes communes des champs de formulaire auth.
 */
export const authInputClassName = "auth-field-input";

/**
 * Classes communes des boutons principaux auth.
 */
export const authButtonClassName = "auth-button-primary";
