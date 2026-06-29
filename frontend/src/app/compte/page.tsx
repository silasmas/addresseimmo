"use client";

import Image from "next/image";
import Link from "next/link";
import { useEffect } from "react";
import { useRouter } from "next/navigation";
import { authButtonClassName } from "@/components/AuthFormLayout";
import { useAuth } from "@/contexts/AuthContext";

/**
 * Page compte utilisateur (profil et déconnexion).
 */
export default function ComptePage() {
  const router = useRouter();
  const { user, isLoading, isAuthenticated, logout } = useAuth();

  useEffect(() => {
    if (!isLoading && !isAuthenticated) {
      router.replace("/connexion");
    }
  }, [isLoading, isAuthenticated, router]);

  /**
   * Déconnecte l'utilisateur et redirige vers l'accueil.
   */
  async function handleLogout() {
    await logout();
    router.push("/");
  }

  if (isLoading || !user) {
    return (
      <section className="px-4 py-12">
        <div className="mx-auto max-w-2xl text-center text-[var(--muted)]">Chargement...</div>
      </section>
    );
  }

  return (
    <section className="bg-[var(--soft)] px-4 py-12">
      <div className="mx-auto w-full max-w-2xl rounded-2xl border border-[var(--line)] bg-white p-8 shadow-sm">
        <div className="flex flex-col items-center gap-4 border-b border-[var(--line)] pb-6 sm:flex-row sm:items-start">
          <Image
            src={user.avatar_url}
            alt={user.fullname}
            width={80}
            height={80}
            unoptimized
            className="h-20 w-20 rounded-full border border-[var(--line)] object-cover"
          />
          <div className="text-center sm:text-left">
            <h1 className="text-2xl font-bold">{user.fullname}</h1>
            <p className="text-sm text-[var(--muted)]">
              {user.selected_role?.role_name ?? "Membre"} · {user.readable_currency ?? user.currency}
            </p>
          </div>
        </div>

        <dl className="mt-6 grid gap-4 sm:grid-cols-2">
          <div>
            <dt className="text-xs uppercase tracking-wide text-[var(--muted)]">Email</dt>
            <dd className="font-medium">{user.email ?? "—"}</dd>
          </div>
          <div>
            <dt className="text-xs uppercase tracking-wide text-[var(--muted)]">Téléphone</dt>
            <dd className="font-medium">{user.phone ?? "—"}</dd>
          </div>
          <div>
            <dt className="text-xs uppercase tracking-wide text-[var(--muted)]">Ville</dt>
            <dd className="font-medium">{user.city ?? "—"}</dd>
          </div>
          <div>
            <dt className="text-xs uppercase tracking-wide text-[var(--muted)]">Pays</dt>
            <dd className="font-medium">{user.country ?? "—"}</dd>
          </div>
          <div>
            <dt className="text-xs uppercase tracking-wide text-[var(--muted)]">Note moyenne</dt>
            <dd className="font-medium">{user.average_rating}/5</dd>
          </div>
          <div>
            <dt className="text-xs uppercase tracking-wide text-[var(--muted)]">Membre depuis</dt>
            <dd className="font-medium">{user.created_at}</dd>
          </div>
        </dl>

        <div className="mt-8 flex flex-wrap gap-3">
          <Link
            href="/annonces"
            className="rounded-lg border border-[var(--line)] px-4 py-2 text-sm font-medium"
          >
            Voir les annonces
          </Link>
          <button type="button" onClick={handleLogout} className={authButtonClassName + " w-auto px-6"}>
            Se déconnecter
          </button>
        </div>
      </div>
    </section>
  );
}
