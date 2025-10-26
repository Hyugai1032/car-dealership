RideZone — Admin Car Management (CRUD) Setup

1) Copy the folder structure into your webroot (e.g., C:\xampp\htdocs\RideZone or /var/www/html/RideZone).

2) Import the SQL:
   - Using phpMyAdmin or mysql CLI: import ridezone_db.sql.

3) Edit config.php:
   - Set BASE_URL to the folder path (e.g., '/RideZone' or '' if root).
   - Set UPLOADS_DIR if you placed uploads elsewhere.

4) Edit includes/db.php:
   - Set DB host, db name, username, password.

5) Make uploads folder writable:
   - chmod 775 uploads/cars (or 777 for quick dev).

6) Open admin/dashboard.php in browser:
   - e.g., http://localhost/RideZone/admin/dashboard.php

7) If images don't show:
   - Ensure uploads files exist or remove placeholder; sample images in seed are just names; upload actual files via Add Car.

8) Integration to LavaLust:
   - Move logic from api/cars_api.php into a controller method.
   - Map routes: /api/cars_api.php?action=... -> /cars/save, /cars/delete, etc.
   - Views in admin/ map to LavaLust views folder.

9) Security & production:
   - Add authentication for admin pages (session-based).
   - Use CSRF tokens.
   - Validate user inputs server-side more strictly.

Enjoy! If you want: I can now
 - wire Chart.js analytics on the dashboard,
 - add appointments CRUD,
 - add dealer CRUD,
 - or convert the API into a LavaLust-style controller ready for integration.
