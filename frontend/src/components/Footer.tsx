/**
 * Pied de page AddressImmo.
 */
export function Footer() {
  return (
    <footer className="mt-auto border-t border-[var(--line)] bg-[var(--soft)]">
      <div className="mx-auto max-w-6xl px-4 py-8 text-sm text-[var(--muted)]">
        <p className="font-semibold text-[var(--ink)]">AddressImmo</p>
        <p className="mt-2">Immobilier fiable et local — RDC et Afrique centrale.</p>
        <p className="mt-4">© {new Date().getFullYear()} AddressImmo. Tous droits réservés.</p>
      </div>
    </footer>
  );
}
