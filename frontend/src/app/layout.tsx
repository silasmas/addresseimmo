import type { Metadata } from "next";
import { Inter } from "next/font/google";
import { AppShell } from "@/components/AppShell";
import "./globals.css";

const inter = Inter({
  subsets: ["latin"],
  variable: "--font-inter",
});

export const metadata: Metadata = {
  title: "AddressImmo — Immobilier fiable et local",
  description: "Plateforme immobilière avec annonces, enchères, loto et ventes vérifiées.",
};

/**
 * Layout racine Next.js avec header et footer communs.
 */
export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="fr" className={inter.variable}>
      <body className="flex min-h-screen flex-col antialiased">
        <AppShell>{children}</AppShell>
      </body>
    </html>
  );
}
