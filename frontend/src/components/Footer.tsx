import Link from "next/link";
import { BrandLogo } from "@/components/BrandLogo";

/**
 * Pied de page du template Phase 2 (4 colonnes).
 */
export function Footer() {
  return (
    <footer className="site-footer">
      <div className="footer-grid">
        <div>
          <BrandLogo className="brand footer-brand" />
          <p>
            Une plateforme locale pour trouver, vérifier, comparer et sécuriser vos projets
            immobiliers.
          </p>
          <p className="muted">+243 890 00 45 00</p>
        </div>
        <div>
          <h3>Explorer</h3>
          <Link href="/annonces">Annonces</Link>
          <Link href="/annonces">Agences</Link>
          <Link href="/encheres">Enchères</Link>
          <Link href="/ventes-verifiees">Ventes vérifiées</Link>
        </div>
        <div>
          <h3>Modules</h3>
          <Link href="/loto">Loto immobilier</Link>
          <Link href="/ya-ofele">Ya Ofele Gratos</Link>
          <Link href="http://127.0.0.1:8001/admin">Administration</Link>
        </div>
        <div>
          <h3>Légal</h3>
          <Link href="/ventes-verifiees">Contrôle juridique</Link>
          <Link href="/">CGU</Link>
          <Link href="/">FAQ</Link>
        </div>
      </div>
    </footer>
  );
}
