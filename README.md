# Plan Céramique Studio

Base technique locale WordPress avec Docker, VS Code, Gutenberg natif et thème custom.

## Démarrage

```powershell
Copy-Item .env.example .env -Force
docker compose up -d db wordpress
docker compose --profile tools run --rm wpcli
```

## Emails en local

En local, les formulaires utilisent Mailpit comme faux serveur SMTP.

- Interface emails: http://localhost:8026
- SMTP interne Docker: `mailpit:1025`
- Les emails sont captures localement et ne partent pas sur Internet.

## Emails en production

Pour livrer le site a une societe, remplacer les variables SMTP dans l'environnement de production par les identifiants fournis par l'hebergeur ou par un service mail transactionnel.

- `SMTP_ENABLED=1`
- `SMTP_FROM_EMAIL=contact@domaine-client.fr`
- `SMTP_HOST=serveur-smtp-client`
- `SMTP_PORT=587`
- `SMTP_ENCRYPTION=tls`
- `SMTP_AUTH=1`
- `SMTP_USERNAME=utilisateur-smtp`
- `SMTP_PASSWORD=mot-de-passe-smtp`

Ne jamais envoyer les vrais mots de passe SMTP sur GitHub.

Le theme utilise un endpoint rapide pour les formulaires Contact et Devis. Le visiteur recoit une reponse immediate, puis le site place l'email dans une file locale avant envoi SMTP par cron. En production, prevoir un cron serveur toutes les minutes pour executer les taches WordPress:

```bash
* * * * * curl -s https://domaine-client.fr/wp-cron.php?doing_wp_cron >/dev/null 2>&1
```

## Accès local

- Site: http://localhost:8081
- Admin: http://localhost:8081/wp-admin
- Identifiants par défaut: `admin` / `admin123456`

## Structure

- `theme/plan-ceramique-premium/`: thème custom modifiable dans VS Code
- `scripts/`: automatisation d'installation WordPress, pages, plugins et formulaires
- `.env`: configuration locale et variables SMTP
