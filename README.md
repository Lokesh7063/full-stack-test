# WPoets Full Stack Developer Test

## Setup

### Requirements
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- A local web server (Apache/nginx) or `php -S localhost:8000`

### 1. Database

```sql
-- Import the schema and seed data
mysql -u root -p < database.sql
```

Or run the SQL file contents through phpMyAdmin / TablePlus.

### 2. Configuration

Edit `config.php` and set your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'wpoets_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Run

```bash
# From the project root
php -S localhost:8000
```

Open **http://localhost:8000** for the portfolio view.  
Open **http://localhost:8000/admin.php** for the CRUD admin panel.

---

## Project Structure

```
wpoets/
├── index.php                         # Portfolio front page
├── admin.php                         # CRUD admin panel
├── config.php                        # DB connection config
├── database.sql                      # Schema + seed data
├── api/
│   └── index.php                     # REST API (CRUD endpoints)
├── css/
│   ├── style.css                     # Main styles
│   └── admin.css                     # Admin-specific styles
├── js/
│   ├── main.js                       # Tabs / sliders / image sync
│   └── admin.js                      # Admin CRUD interactions
└── Answers to technical questions.md
```

---

## API Reference

Base: `/api/index.php?resource=<resource>`

| Method | URL | Description |
|--------|-----|-------------|
| GET | `?resource=categories` | List all categories |
| GET | `?resource=categories&id=1` | Get one category |
| POST | `?resource=categories` | Create category `{name, sort_order}` |
| PUT | `?resource=categories&id=1` | Update category |
| DELETE | `?resource=categories&id=1` | Delete category (cascades slides) |
| GET | `?resource=slides` | List all slides |
| GET | `?resource=slides&category_id=1` | Slides for one category |
| GET | `?resource=slides&id=1` | Get one slide |
| POST | `?resource=slides` | Create slide `{category_id, title, description, image_url, sort_order}` |
| PUT | `?resource=slides&id=1` | Update slide |
| DELETE | `?resource=slides&id=1` | Delete slide |

---

## Design Decisions

- **3-column desktop layout** — tabs (col 1) drive an independent Swiper per category (col 2); slide changes sync a 1:1 feature image (col 3) via a crossfade transition.
- **Mobile** — Bootstrap accordion replaces tabs; each panel contains a full-bleed background-image Swiper with overlaid text.
- **PHP REST API** — thin PDO-based router, no framework dependency, easy to extend.
- **No build step** — assets are plain CSS/JS loaded from CDN, ready to run on any shared host.
