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
    <section className="bg-[var(--soft)] px-4 py-12">
      <div className="mx-auto w-full max-w-md rounded-2xl border border-[var(--line)] bg-white p-8 shadow-sm">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-bold text-[var(--ink)]">{title}</h1>
          <p className="mt-2 text-sm text-[var(--muted)]">{subtitle}</p>
        </div>
        {children}
        <p className="mt-6 text-center text-sm text-[var(--muted)]">
          {alternateLabel}{" "}
          <Link href={alternateHref} className="font-medium text-[var(--green)] hover:underline">
            cliquez ici
          </Link>
        </p>
      </div>
    </section>
  );
}

/**
 * Styles communs des champs de formulaire auth.
 */
export const authInputClassName =
  "w-full rounded-lg border border-[var(--line)] px-3 py-2 text-sm outline-none transition focus:border-[var(--green)] focus:ring-2 focus:ring-[var(--green-soft)]";

/**
 * Styles communs des boutons principaux auth.
 */
export const authButtonClassName =
  "w-full rounded-lg bg-[var(--green)] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[var(--green-dark)] disabled:cursor-not-allowed disabled:opacity-60";
