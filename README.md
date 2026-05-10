# Plan Céramique Studio

Projet WordPress local pour un site premium de plans de travail en céramique. Le dépôt contient un thème sur mesure, une configuration Docker, des scripts WP-CLI et une configuration SMTP locale pour tester les formulaires sans envoyer de vrais emails.

## Objectif

- Fournir une base WordPress propre et reproductible en local.
- Garder le rendu front-end stable pendant les évolutions.
- Centraliser progressivement les contenus modifiables dans l'administration WordPress.
- Préparer une mise en production avec SMTP, cron serveur, SEO et sauvegardes.

## Démarrage Local

```powershell
Copy-Item .env.example .env -Force
docker compose up -d db wordpress
docker compose --profile tools run --rm wpcli
```

## Accès Local

- Site : http://localhost:8081
- Administration : http://localhost:8081/wp-admin
- Identifiants de démonstration locale : `admin` / `admin123456`

Ces identifiants sont réservés à l'environnement local. En production, utiliser un compte administrateur nominatif avec un mot de passe fort.

## Structure

- `theme/plan-ceramique-premium/` : thème WordPress sur mesure.
- `theme/plan-ceramique-premium/inc/` : configuration du thème, réglages, contenus personnalisés et formulaires.
- `theme/plan-ceramique-premium/template-parts/` : sections et composants réutilisables.
- `theme/plan-ceramique-premium/assets/` : CSS, JavaScript et images.
- `scripts/` : automatisation d'installation WordPress, contenus, plugins, SEO et SMTP.
- `.env.example` : modèle de configuration locale.
- `.env` : configuration locale privée, à ne pas versionner.

## Plugins Utilisés

Les scripts installent et configurent les plugins nécessaires au projet local :

- Contact Form 7
- CFDB7
- Easy WP SMTP
- Yoast SEO

## Emails En Local

En local, les formulaires utilisent Mailpit comme faux serveur SMTP.

- Interface emails : http://localhost:8026
- SMTP interne Docker : `mailpit:1025`
- Les emails sont capturés localement et ne partent pas sur Internet.

## Emails De Test Et Production

- Adresse entreprise / production : `hello@mpc.contact`
- Adresse de test contrôlé : `chardinpoutcheu@gmail.com`
- Ne pas envoyer de tests réels vers l'adresse entreprise sans validation.
- Avant un test hors Mailpit, vérifier le destinataire dans WordPress et dans `.env`.
- Après un test, remettre le destinataire de production si le site doit être livré.

## Procédure De Test Email

1. Vérifier le destinataire actuel dans WordPress.
2. En local, ouvrir Mailpit : http://localhost:8026.
3. Envoyer une demande depuis le formulaire Contact.
4. Envoyer une demande depuis le formulaire Devis.
5. Vérifier le sujet, le contenu, le Reply-To et les pièces jointes éventuelles.
6. Pour un test réel hors Mailpit, utiliser uniquement `chardinpoutcheu@gmail.com`.
7. Ne pas tester sur `hello@mpc.contact` sans validation.

## Emails En Production

Pour livrer le site à une société, remplacer les variables SMTP dans l'environnement de production par les identifiants fournis par l'hébergeur ou par un service mail transactionnel.

- `SMTP_ENABLED=1`
- `SMTP_FROM_EMAIL=contact@domaine-client.fr`
- `SMTP_HOST=serveur-smtp-client`
- `SMTP_PORT=587`
- `SMTP_ENCRYPTION=tls`
- `SMTP_AUTH=1`
- `SMTP_USERNAME=utilisateur-smtp`
- `SMTP_PASSWORD=mot-de-passe-smtp`

Ne jamais envoyer les vrais mots de passe SMTP sur GitHub.

## Cron WordPress

Le thème utilise une file d'attente pour les emails de formulaire. En production, prévoir un cron serveur toutes les minutes pour exécuter les tâches WordPress :

```bash
* * * * * curl -s https://domaine-client.fr/wp-cron.php?doing_wp_cron >/dev/null 2>&1
```

## Règles De Maintenance

- Ne pas modifier le design front-end sans validation.
- Ne pas modifier le comportement des formulaires sans test complet.
- Garder les contenus administrables dans WordPress quand c'est possible.
- Tester les formulaires Contact et Devis après chaque changement lié aux emails.
- Vérifier `php -l` sur les fichiers PHP modifiés avant livraison.

## Vérification PHP

Avant une livraison ou une démonstration, lancer :

```powershell
.\scripts\check-php.ps1
```

Cette commande vérifie la syntaxe des fichiers PHP du projet sans modifier le site.

## Mise En Production

Avant une mise en ligne réelle :

- remplacer les identifiants locaux ;
- configurer un SMTP de production ;
- désactiver l'affichage des erreurs ;
- mettre en place un cron serveur ;
- vérifier Yoast SEO, sitemap, Open Graph et Search Console ;
- prévoir sauvegardes, sécurité et cache.
