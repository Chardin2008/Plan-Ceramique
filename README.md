# Plan Céramique Premium

Base technique locale WordPress avec Docker, VS Code, Gutenberg natif et thème custom.

## Démarrage

```powershell
Copy-Item .env.example .env -Force
docker compose up -d db wordpress
docker compose --profile tools run --rm wpcli
```

## Accès local

- Site: http://localhost:8081
- Admin: http://localhost:8081/wp-admin
- Identifiants par défaut: `admin` / `admin123456`

## Structure

- `theme/plan-ceramique-premium/`: thème custom modifiable dans VS Code
- `scripts/`: automatisation d'installation WordPress, pages, plugins et formulaires
- `.env`: configuration locale et variables SMTP
