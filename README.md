# SWS-Pakemones

Complete pakémon‑inspired web platform with user management, pakédex, social interactions (photo sharing, comments), and administrative tools. Built with vanilla PHP, JSON flat‑file storage, and HTML/CSS templates. Developed as part of the *Web Services: Server* course at UPNA (Telecommunications Engineering, Spring 2026).
---

## Features

**User System** — Complete authentication and profile management:
- User registration with SHA‑1 password hashing
- Login/logout with session management
- Profile editing (username, email, name, password change)
- Role‑based access control (admin / regular user)

**Admin Dashboard** — Administrative tools:
- List all registered users with edit/delete capabilities
- Create new admin users
- Full pakédex management (add, edit, delete pakémon entries)
- Activity log viewer with pagination

**pakédex** — Complete creature catalog:
- List all pakémon with detailed information (types, region, generation, physical attributes)
- Filtering by capture status (captured vs. available)
- Image upload for each pakémon
- Region and type classification (JSON‑based reference data)

**Capture System** — Users can capture pakémon:
- Add pakémon to personal collection via one‑click capture
- View captured pakémon in `mis_pakemones.php`
- Prevent duplicate captures

**Social Features (PakeTwittermon)** — Photo sharing and commenting:
- Users can upload photos of their pakémon (per pakémon detail page)
- Public feed (`pake_twittermon.php`) shows all user uploads
- Comment system on photos
- Like/comment functionality (without requiring page reload — server‑side rendering)

**Activity Logging** — Automatic logging of all HTTP requests:
- Logs: timestamp, IP address, URL, HTTP method, GET/POST data, session data, cookies
- Admin‑only log viewer with pagination (10 entries per page)
- JSON storage (`logs.json`)

**Flat‑file JSON Storage** — No database required:
- `users.json` — User accounts (id, username, email, names, SHA‑1 password, role)
- `pakemones.json` — pakémon catalog (17+ fields including stats, types, region)
- `regiones.json` — Reference table for regions
- `tipos.json` — Reference table for pakémon types (18 types)
- `mypakemons.json` — User‑captured pakémon relationships
- `images.json` — User‑uploaded photos with pakémon associations
- `comentarios.json` — Comments on photos

---

## Repository Structure

```
├── practica8/
│  ├── admin.php # Admin dashboard
│  ├── anadir_pakemon.php # Add new pakémon (admin)
│  ├── anhadir_admin.php # Create admin user
│  ├── capturar_pakemon.php # Capture pakémon (user action)
│  ├── comentar.php # Add comment (social feed)
│  ├── comentarmio.php # Add comment (detail page)
│  ├── detalle_pakemon.php # pakémon detail with photo upload
│  ├── eliminar.php # Delete user (admin)
│  ├── eliminar_pakemon.php # Delete pakémon (admin)
│  ├── hashear_jsonusers.php # Utility: SHA‑1 hash existing passwords
│  ├── index.php # Login page + session handling
│  ├── listado.php # User list (admin)
│  ├── listado_pakemones.php # pakémon list (admin)
│  ├── logger.php # Logging function (included everywhere)
│  ├── mis_pakemones.php # User's captured pakémon
│  ├── modificar.php # Edit user (admin)
│  ├── modificar_pakemon.php # Edit pakémon (admin)
│  ├── pake_twittermon.php # Social feed (all users' photos)
│  ├── pakedex.php # User pakédex (uncaptured pakémon)
│  ├── perfil.php # Edit own profile
│  ├── registro.php # User registration
│  ├── subir_foto.php # Upload photo (detail page)
│  ├── user.php # User dashboard
│  └── visualizar_logs.php # Activity log viewer (admin)
│
├── private/
│ ├── json/ # Flat‑file data storage
│ └── templates/ # HTML templates (no PHP)
│
├── images/
│ ├── pakemon/ # logo
│ └── fotos/ # User‑uploaded photos
```

> **Note:** The `private/` directory must be placed outside the web root or protected via `.htaccess` (sensitive JSON files contain passwords).

---

## Dependencies

- **PHP 7.4 or higher** (tested on PHP 8.x)
- **Web server** (Apache recommended with `.htaccess` support)
- **No database required** — uses JSON flat files
- **No external PHP libraries** — pure PHP + HTML/CSS

---

## How to Use

**For Regular Users**

1. **Register** — Create a new account via `registro.php`

2. **Login** — Use `index.php`

3. **Browse pakédex** — View uncaptured pakémon at `pakedex.php`

4. **apture pakémon** — Click "Capturar" button on any uncaptured pakémon

5. **View Collection** — See captured pakémon at `mis_pakemones.php`

6. **Upload Photos** — Go to `detalle_pakemon.php?id=X` and upload photos of your pakémon

7. **Social Feed** — Browse all user uploads at `pake_twittermon.php` and comment

8. **Edit Profile** — Update personal info at `perfil.php`

9. **Logout** — Use the logout link (adds `?logout=true` to `index.php`)

**For Administrators**

1. **Login** with admin credentials

2. **Admin Dashboard** — Access at `admin.php`

3. **Manage Users** — List, edit, delete users at `listado.php`

4. **Create Admin** — Add new admin users at `anhadir_admin.php`

5. **Manage pakémon** — List, add, edit, delete at `listado_pakemones.php` / `anadir_pakemon.php`

6. **View Logs** — Monitor activity at `visualizar_logs.php` (paginated)

---

## Security Considerations

Current implementation (academic project — not production‑ready):

⚠️ **Passwords** — Stored with SHA‑1 (weak, use `password_hash()` in production)

⚠️ **JSON storage** — No transaction support, race conditions possible

⚠️ **File permissions** — JSON files must be writable by web server

⚠️ **SQL injection** — Not applicable (no database)

⚠️ **XSS** — Uses `htmlspecialchars()` on output (mitigated)

⚠️ **CSRF** — No tokens implemented

✅ **Session management** — Uses PHP sessions with regeneration on login

✅ **Path traversal** — File paths are hardcoded or validated

✅ **File uploads** — Validates image type with `exif_imagetype()`

For production deployment, consider:

1. Move `private`/ outside web root

2. Replace SHA‑1 with `password_hash()` / `password_verify()`

3. Add CSRF tokens to forms

4. Implement rate limiting on login

5. Use HTTPS only

6. Add proper session timeout handling

7. Validate and sanitize all file uploads more strictly

---

## Authors

- **Íñigo Aguirre**
- **Cristina García**

*Students of Telecommunications Engineering — Universidad Pública de Navarra (UPNA)*

---

## License

This project was developed for academic purposes as part of a Web Services course. Pokémon and related trademarks are property of Nintendo/Game Freak — this is an educational fan project.