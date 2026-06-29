/**
 * Page placeholder pour les modules Phase 2 (API à venir).
 */
export function ModulePlaceholderPage({
  title,
  description,
}: {
  title: string;
  description: string;
}) {
  return (
    <div className="mx-auto max-w-3xl px-4 py-16 text-center">
      <h1 className="text-3xl font-bold">{title}</h1>
      <p className="mt-4 text-[var(--muted)]">{description}</p>
      <p className="mt-8 rounded-xl border border-dashed border-[var(--line)] bg-[var(--soft)] p-6 text-sm">
        Module en cours d&apos;intégration via l&apos;API REST Laravel v1.
      </p>
    </div>
  );
}
