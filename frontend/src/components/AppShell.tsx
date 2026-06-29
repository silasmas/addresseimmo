"use client";

import type { ReactNode } from "react";
import { Footer } from "@/components/Footer";
import { Header } from "@/components/Header";
import { AuthProvider } from "@/contexts/AuthContext";

/**
 * Enveloppe client pour header, footer et authentification.
 *
 * @param props Contenu des pages
 * @returns Layout applicatif
 */
export function AppShell({ children }: { children: ReactNode }) {
  return (
    <AuthProvider>
      <Header />
      <main className="flex-1">{children}</main>
      <Footer />
    </AuthProvider>
  );
}
