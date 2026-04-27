#!/bin/sh
set -eu

cd /var/www/html

until wp core version --allow-root >/dev/null 2>&1; do
  echo "Waiting for WordPress core..."
  sleep 5
done

if ! wp core is-installed --allow-root >/dev/null 2>&1; then
  wp core install \
    --url="${WORDPRESS_SITE_URL}" \
    --title="${WORDPRESS_SITE_TITLE}" \
    --admin_user="${WORDPRESS_ADMIN_USER}" \
    --admin_password="${WORDPRESS_ADMIN_PASSWORD}" \
    --admin_email="${WORDPRESS_ADMIN_EMAIL}" \
    --skip-email \
    --allow-root
fi

wp language core install "${WORDPRESS_LANGUAGE}" --activate --allow-root || true
wp option update timezone_string "Europe/Paris" --allow-root
wp option update date_format "j F Y" --allow-root
wp option update time_format "H:i" --allow-root
wp option update blogdescription "Plans de travail en céramique sur mesure, fabrication, livraison et pose partout en France." --allow-root
wp rewrite structure "/%postname%/" --hard --allow-root
wp rewrite flush --hard --allow-root

wp plugin delete akismet hello --allow-root || true
wp plugin install contact-form-7 contact-form-cfdb7 easy-wp-smtp wordpress-seo --activate --allow-root
wp theme activate plan-ceramique-premium --allow-root

wp eval-file /workspace/scripts/bootstrap-site.php --allow-root

if [ "${SMTP_ENABLED:-0}" = "1" ] && [ -n "${SMTP_HOST:-}" ]; then
  wp eval-file /workspace/scripts/configure-smtp.php --allow-root
fi
