<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Installation — AddressImmo</title>
  <style>
    :root {
      --green: #228b57;
      --ink: #1a2332;
      --muted: #5f6b7a;
      --line: #dde3ea;
      --soft: #f4f7f9;
      --danger: #c0392b;
      --radius: 10px;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: "Segoe UI", system-ui, sans-serif;
      color: var(--ink);
      background: linear-gradient(180deg, #eef6f1 0%, #f8fafb 40%);
    }

    .wrap {
      max-width: 920px;
      margin: 0 auto;
      padding: 32px 20px 64px;
    }

    .hero {
      margin-bottom: 24px;
    }

    .hero h1 {
      margin: 0 0 8px;
      font-size: clamp(1.6rem, 4vw, 2.2rem);
    }

    .hero p {
      margin: 0;
      color: var(--muted);
      line-height: 1.55;
    }

    .card {
      background: white;
      border: 1px solid var(--line);
      border-radius: calc(var(--radius) + 2px);
      padding: 22px;
      margin-bottom: 18px;
      box-shadow: 0 8px 24px rgba(26, 35, 50, 0.06);
    }

    .card h2 {
      margin: 0 0 14px;
      font-size: 1.05rem;
    }

    .checks {
      display: grid;
      gap: 8px;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .checks li {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding: 10px 12px;
      border-radius: var(--radius);
      background: var(--soft);
      font-size: 0.92rem;
    }

    .ok { color: var(--green); font-weight: 700; }
    .ko { color: var(--danger); font-weight: 700; }

    .grid-2 {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
    }

    label {
      display: grid;
      gap: 6px;
      font-size: 0.85rem;
      font-weight: 700;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="tel"] {
      min-height: 44px;
      padding: 0 12px;
      border: 1.5px solid #c8d0d8;
      border-radius: var(--radius);
      background: #f8fafb;
      font-size: 0.95rem;
    }

    input:focus {
      outline: none;
      border-color: var(--green);
      box-shadow: 0 0 0 3px rgba(34, 139, 87, 0.15);
      background: white;
    }

    .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 14px;
    }

    button,
    .btn {
      min-height: 42px;
      padding: 0 16px;
      border: none;
      border-radius: var(--radius);
      background: var(--green);
      color: white;
      font-weight: 800;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
    }

    .btn-secondary {
      background: white;
      color: var(--ink);
      border: 1px solid var(--line);
    }

    .alert {
      padding: 12px 14px;
      border-radius: var(--radius);
      margin-bottom: 16px;
      font-weight: 700;
    }

    .alert-success {
      background: #edf9f1;
      border: 1px solid #bfe6cb;
      color: var(--green);
    }

    .alert-error {
      background: #fff5f4;
      border: 1px solid #f0c4c0;
      color: var(--danger);
    }

    .meta {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 10px;
    }

    .meta div {
      padding: 12px;
      border-radius: var(--radius);
      background: var(--soft);
      font-size: 0.88rem;
    }

    .meta strong {
      display: block;
      margin-bottom: 4px;
    }

    @media (max-width: 720px) {
      .grid-2 { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="hero">
      <h1>Installation AddressImmo</h1>
      <p>Déployez le backend (migrations, seeders, super administrateur) avant de mettre le frontend en ligne.</p>
    </div>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
      <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="card">
      <h2>1. Prérequis serveur</h2>
      <ul class="checks">
        @foreach ($status['requirements']['checks'] as $check)
          <li>
            <span>{{ $check['label'] }}</span>
            <span class="{{ $check['ok'] ? 'ok' : 'ko' }}">{{ $check['value'] }}</span>
          </li>
        @endforeach
      </ul>
    </div>

    <div class="card">
      <h2>2. État du déploiement</h2>
      <div class="meta">
        <div><strong>Base de données</strong>{{ $status['database_connected'] ? 'Connectée' : 'Non connectée' }}</div>
        <div><strong>Migrations en attente</strong>{{ count($status['pending_migrations']) }}</div>
        <div><strong>Super admin</strong>{{ $status['has_admin'] ? 'Créé' : 'À créer' }}</div>
        <div><strong>Version</strong>{{ $status['version'] }}</div>
      </div>
    </div>

    <div class="card">
      <h2>3. Migrations</h2>
      <p style="color: var(--muted); margin-top: 0;">Installez les tables ou synchronisez une base SQL déjà importée.</p>
      <div class="actions">
        <form method="POST" action="{{ route('install.migrate') }}">
          @csrf
          <input type="hidden" name="mode" value="migrate">
          <button type="submit">Exécuter migrations</button>
        </form>
        <form method="POST" action="{{ route('install.migrate') }}">
          @csrf
          <input type="hidden" name="mode" value="sync">
          <button type="submit" class="btn-secondary">Synchroniser (import SQL)</button>
        </form>
      </div>
    </div>

    <div class="card">
      <h2>4. Seeders</h2>
      <p style="color: var(--muted); margin-top: 0;">Rôles, catégories et optionnellement les données de démonstration.</p>
      <div class="actions">
        <form method="POST" action="{{ route('install.seed') }}">
          @csrf
          <button type="submit">Seeders de base</button>
        </form>
        <form method="POST" action="{{ route('install.seed') }}">
          @csrf
          <input type="hidden" name="include_demo" value="1">
          <button type="submit" class="btn-secondary">Seeders + démo</button>
        </form>
      </div>
    </div>

    <div class="card">
      <h2>5. Super administrateur</h2>
      <form method="POST" action="{{ route('install.admin') }}">
        @csrf
        <div class="grid-2">
          <label>
            Prénom
            <input type="text" name="firstname" value="{{ old('firstname') }}" required>
          </label>
          <label>
            Nom
            <input type="text" name="lastname" value="{{ old('lastname') }}">
          </label>
          <label>
            Email
            <input type="email" name="email" value="{{ old('email') }}" required>
          </label>
          <label>
            Téléphone
            <input type="tel" name="phone" value="{{ old('phone') }}">
          </label>
          <label>
            Mot de passe
            <input type="password" name="password" required minlength="8">
          </label>
          <label>
            Confirmer
            <input type="password" name="password_confirmation" required minlength="8">
          </label>
        </div>
        <div class="actions">
          <button type="submit">Créer le super admin</button>
        </div>
      </form>
    </div>

    <div class="card">
      <h2>6. Finaliser</h2>
      <p style="color: var(--muted); margin-top: 0;">Marque l'application comme installée et ouvre le back-office Filament.</p>
      <form method="POST" action="{{ route('install.finish') }}">
        @csrf
        <input type="hidden" name="admin_email" value="{{ old('email') }}">
        <div class="actions">
          <button type="submit">Terminer l'installation</button>
          <a class="btn btn-secondary" href="/{{ trim(config('install.admin_panel_path', 'back-office'), '/') }}">Aller au back-office</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
