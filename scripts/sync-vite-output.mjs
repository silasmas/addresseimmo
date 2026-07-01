import { cpSync, existsSync, mkdirSync, rmSync, writeFileSync } from "node:fs";
import { join } from "node:path";

/**
 * Copie public/build vers les dossiers attendus par Hostinger (build, dist).
 * Laravel utilise toujours public/build via @vite.
 */
const sourceDir = join(process.cwd(), "public", "build");
const targets = ["build", "dist"];

if (!existsSync(sourceDir)) {
  console.error("Erreur : public/build introuvable. Lancez d'abord vite build.");
  process.exit(1);
}

for (const folder of targets) {
  const targetDir = join(process.cwd(), folder);

  if (existsSync(targetDir)) {
    rmSync(targetDir, { recursive: true, force: true });
  }

  mkdirSync(targetDir, { recursive: true });
  cpSync(sourceDir, targetDir, { recursive: true });

  writeFileSync(
    join(targetDir, ".hostinger-build-ok"),
    `Copie depuis public/build le ${new Date().toISOString()}\n`,
    "utf8"
  );

  console.log(`OK — assets copiés vers ${folder}/`);
}

console.log("Build Hostinger prêt : build/, dist/ et public/build/");
