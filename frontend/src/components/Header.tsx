"use client";

import Link from "next/link";
import { useAuth } from "@/contexts/AuthContext";

const navItems = [
  { label: "Annonces", href: "/annonces" },
  { label: "Enchères", href: "/encheres" },
  { label: "Loto", href: "/loto" },
  { label: "Ya Ofele", href: "/ya-ofele" },
  { label: "Ventes vérifiées", href: "/ventes-verifiees" },
];

/**
 * En-tête principal du site AddressImmo avec état de connexion.
 */
export function Header() {
  const { user, isLoading, isAuthenticated } = useAuth();

  return (
    <header className="border-b border-[var(--line)] bg-white">
      <div className="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
        <Link href="/" className="text-xl font-bold text-[var(--green)]">
          AddressImmo
        </Link>
        <nav className="hidden items-center gap-5 md:flex">
          {navItems.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className="text-sm font-medium text-[var(--muted)] transition hover:text-[var(--green)]"
            >
              {item.label}
            </Link>
          ))}
        </nav>
        <div className="flex items-center gap-3">
          <Link
            href="/annonces"
            className="rounded-lg border border-[var(--line)] px-3 py-2 text-sm font-medium"
          >
            Publier
          </Link>
          {!isLoading && isAuthenticated && user ? (
            <Link
              href="/compte"
              className="rounded-lg bg-[var(--green)] px-3 py-2 text-sm font-medium text-white"
            >
              {user.firstname}
            </Link>
          ) : (
            <Link
              href="/connexion"
              className="rounded-lg bg-[var(--green)] px-3 py-2 text-sm font-medium text-white"
            >
              Connexion
            </Link>
          )}
        </div>
      </div>
    </header>
  );
}
