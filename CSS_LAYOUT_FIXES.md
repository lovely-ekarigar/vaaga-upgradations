# CSS & layout fixes – single source

To avoid fixing the same issues in multiple places, layout and global CSS fixes live in **one place**:

## Where layout CSS lives

- **`resources/views/frontend/layout/sub-master.blade.php`**  
  First `<style>` block (comment: "Single source: layout & CSS fixes").  
  Contains: `.wrapper`, `main` padding-top, `.main-header .container` flex, logo size, footer baseline, `.btn-outline-white`.

- **`resources/views/frontend/include/menu.blade.php`**  
  Navbar order (Logo | Nav | Search | Dashboard) – only the small `<style>` at the top.

## What was fixed (plan)

1. **Duplicate logo** – `sub-header.blade.php`: single logo image + `onerror` fallback to `ltlogo.png` (no second image, no double alt text).
2. **Header order** – Menu: flex order so desktop shows Logo → Nav links → Search → Dashboard.
3. **Main content** – `main` has `padding-top: 76px` for fixed header; wrapper uses flex so footer stays at bottom.
4. **Empty hero** – `welcome.blade.php`: when `$slider` is empty, a fallback hero shows "Welcome to VaaGa Academy" and About / Our Courses.
5. **Footer** – Baseline styles in sub-master so footer is visible when `newassets/css/theme.css` is missing.
6. **style.css** – `welcome.blade.php` uses `{{ asset('style.css') }}` (not `/public/style.css`).

## Do not

- Add the same layout rules in `welcome.blade.php` or other views.
- Reintroduce two logo images in the header without hiding one by CSS.
