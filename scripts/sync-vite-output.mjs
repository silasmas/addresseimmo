import { cpSync, existsSync, mkdirSync, rmSync } from "node:fs";
import { join } from "node:path";

/**
 * Copie public/build vers build/ pour les hébergeurs qui attendent ce dossier.
 * Laravel continue d'utiliser public/build via @vite.
 */
const sourceDir = join(process.cwd(), "public", "build");
const targetDir = join(process.cwd(), "build");

if (!existsSync(sourceDir)) {
  console.error("Erreur : public/build introuvable. Lancez d'abord vite build.");
  process.exit(1);
}

if (existsSync(targetDir)) {
  rmSync(targetDir, { recursive: true, force: true });
}

mkdirSync(targetDir, { recursive: true });
cpSync(sourceDir, targetDir, { recursive: true });

console.log("OK — assets Vite copiés vers build/ (compatibilité déploiement).");
