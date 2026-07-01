"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useState } from "react";
import { BrandLogo } from "@/components/BrandLogo";
import { NAV_ITEMS } from "@/lib/home-content";
import { useAuth } from "@/contexts/AuthContext";

/**
 * Indique si un lien de navigation doit etre souligne comme actif.
 *
 * @param pathname Chemin courant
 * @param matchPrefix Prefixe de route a comparer
 * @returns true si le lien est actif
 */
function isNavActive(pathname: string, matchPrefix: string): boolean {
  if (matchPrefix === "annonces") {
    return pathname === "/annonces" || pathname.startsWith("/annonces/");
  }

  return pathname === `/${matchPrefix}` || pathname.startsWith(`/${matchPrefix}/`);
}

/**
 * En-tete sticky du template Phase 2 avec menu mobile.
 */
export function Header() {
  const pathname = usePathname();
  const { user, isLoading, isAuthenticated } = useAuth();
  const [menuOpen, setMenuOpen] = useState(false);

  /**
   * Bascule l'affichage du menu mobile.
   */
  function toggleMenu() {
    setMenuOpen((open) => !open);
  }

  return (
    <header className="site-header">
      <nav className="nav-shell" aria-label="Navigation principale">
        <BrandLogo />
        <button
          className="mobile-menu"
          type="button"
          aria-expanded={menuOpen}
          aria-controls="main-nav"
          onClick={toggleMenu}
        >
          Menu
        </button>
        <div className={`nav-links${menuOpen ? " open" : ""}`} id="main-nav">
          {NAV_ITEMS.map((item) => (
            <Link
              key={item.match}
              href={item.href}
              className={isNavActive(pathname, item.match) ? "active" : ""}
              onClick={() => setMenuOpen(false)}
            >
              {item.label}
            </Link>
          ))}
        </div>
        <div className="nav-actions">
          <Link className="btn btn-primary btn-small" href="/annonces">
            Publier annonce
          </Link>
          {!isLoading && isAuthenticated && user ? (
            <Link className="btn btn-ghost btn-small" href="/compte">
              {user.firstname}
            </Link>
          ) : (
            <Link className="btn btn-ghost btn-small" href="/connexion">
              Connexion
            </Link>
          )}
        </div>
      </nav>
    </header>
  );
}
