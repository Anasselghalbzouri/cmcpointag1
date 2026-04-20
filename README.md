# CMC Pointage

Application Laravel de gestion de présence (scan CNE entrée/sortie), avec interface Bootstrap 5.

## Prérequis

- PHP 8.3+
- Composer
- Node.js + npm
- MySQL 8+ (ou MariaDB compatible)

## Configuration MySQL

1. Créer la base de données :

```sql
CREATE DATABASE cmc_p CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Vérifier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cmc_p
DB_USERNAME=root
DB_PASSWORD=
```

3. Installer et initialiser :

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --force
```

## Démarrage local

```bash
composer run dev
```

Cette commande lance :
- serveur Laravel (`php artisan serve`)
- worker queue (`php artisan queue:listen`)
- logs (`php artisan pail`)
- Vite (`npm run dev`)

## Initialiser des comptes de test

Ouvrir :

`http://127.0.0.1:8000/setup`

Comptes créés :
- `admin / password`
- `agent / password`
- `CNE12345 / password`

## Tests

```bash
php artisan test
```
