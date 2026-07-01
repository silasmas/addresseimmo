<x-filament-panels::page>
  <div class="space-y-6">
    <x-filament::section heading="État du système">
      <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <div class="text-sm text-gray-500">Installé</div>
          <div class="text-lg font-bold">{{ ($status['installed'] ?? false) ? 'Oui' : 'Non' }}</div>
        </div>
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <div class="text-sm text-gray-500">Base de données</div>
          <div class="text-lg font-bold">{{ ($status['database_connected'] ?? false) ? 'OK' : 'Erreur' }}</div>
        </div>
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <div class="text-sm text-gray-500">Migrations en attente</div>
          <div class="text-lg font-bold">{{ count($status['pending_migrations'] ?? []) }}</div>
        </div>
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <div class="text-sm text-gray-500">Frontend prêt</div>
          <div class="text-lg font-bold">{{ ($status['frontend_ready'] ?? false) ? 'Oui' : 'Non' }}</div>
        </div>
      </div>
    </x-filament::section>

    @if (!empty($status['pending_migrations']))
      <x-filament::section heading="Migrations en attente">
        <ul class="list-disc space-y-1 pl-5 text-sm">
          @foreach ($status['pending_migrations'] as $migration)
            <li>{{ $migration }}</li>
          @endforeach
        </ul>
      </x-filament::section>
    @endif

    <x-filament::section heading="Actions de déploiement">
      <div class="flex flex-wrap gap-3">
        <x-filament::button wire:click="runMigrations" color="primary">
          Exécuter migrations
        </x-filament::button>
        <x-filament::button wire:click="runMigrationSync" color="gray">
          Synchroniser (import SQL)
        </x-filament::button>
        <x-filament::button wire:click="runBaseSeeders" color="gray">
          Seeders de base
        </x-filament::button>
        <x-filament::button wire:click="runDemoSeeders" color="gray">
          Seeders démo
        </x-filament::button>
        <x-filament::button wire:click="clearCaches" color="warning">
          Vider les caches
        </x-filament::button>
      </div>
    </x-filament::section>

    <x-filament::section heading="Informations">
      <dl class="grid gap-2 text-sm md:grid-cols-2">
        <div><dt class="font-semibold">Version</dt><dd>{{ $status['version'] ?? '—' }}</dd></div>
        <div><dt class="font-semibold">Environnement</dt><dd>{{ $status['app_env'] ?? '—' }}</dd></div>
        <div><dt class="font-semibold">PHP</dt><dd>{{ $status['php_version'] ?? '—' }}</dd></div>
        <div><dt class="font-semibold">Installé le</dt><dd>{{ $status['installed_at'] ?? '—' }}</dd></div>
        <div><dt class="font-semibold">Admin initial</dt><dd>{{ $status['admin_email'] ?? '—' }}</dd></div>
        <div><dt class="font-semibold">URL installateur</dt><dd>{{ $status['install_url'] ?? url('/install') }}</dd></div>
      </dl>
    </x-filament::section>
  </div>
</x-filament-panels::page>
