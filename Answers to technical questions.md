# Answers to Technical Questions

## How long did you spend on the coding test? What would you add with more time?

I spent approximately **4–5 hours** on this test, covering:

- Database schema design and seed data
- PHP REST API (full CRUD for categories and slides)
- Front-end layout with the 3-column tab/slider/image interaction
- Mobile accordion + background-image slider
- Admin panel with modals for create/edit/delete
- CSS design system and responsive polish

**Given more time, I would add:**

1. **Authentication** — Basic JWT or session-based auth to protect the admin panel from public access.
2. **Image upload** — Rather than requiring a URL, allow direct file uploads with server-side validation and storage (e.g. to `/uploads/`, with thumb generation via GD or Imagick).
3. **Drag-and-drop reordering** — Use SortableJS in the admin to visually reorder categories and slides, writing back to `sort_order` via PATCH requests.
4. **Input sanitisation and CSRF protection** — Add a CSRF token to all state-changing admin requests and tighten server-side validation.
5. **Lazy loading** — Use `loading="lazy"` and an IntersectionObserver-based Swiper `lazyPreload` strategy so only visible images are fetched.
6. **Transition animations** — A smoother crossfade or clip-path wipe when switching categories on desktop.
7. **Tests** — PHPUnit for the API layer and Cypress for the UI interactions.

---

## How would you track down a performance issue in production? Have you ever had to do this?

Yes, I have dealt with this in production. My approach:

**1. Reproduce and scope it first**  
Confirm whether the slowdown is global or path-specific (single route, specific user, certain device). Check server uptime monitors (New Relic, Datadog, or even simple Pingdom alerts) and recent deployment logs to correlate timing with releases.

**2. Server-side profiling**  
- Enable **slow query logging** in MySQL (`long_query_time = 1`) and review with `pt-query-digest` (Percona Toolkit). Missing indexes are the most common culprit.  
- Use **Xdebug** or **Blackfire.io** to profile PHP call stacks and identify hot functions.  
- Check `EXPLAIN` on any suspect queries to verify index usage.

**3. Network and front-end**  
- Chrome DevTools **Performance** and **Network** tabs: look for render-blocking scripts, uncompressed assets, unoptimised images, or waterfall gaps indicating slow TTFB.  
- Check if CDN cache headers are correct (`Cache-Control`, `ETag`).

**4. Infrastructure**  
- Review server CPU, memory, and I/O metrics (via `htop`, CloudWatch, or equivalent).  
- Check PHP-FPM pool settings: `pm.max_children` too low causes request queuing.  
- Verify OPcache is enabled (`opcache.enable=1`) and not thrashing due to too-small `memory_consumption`.

**5. Fix, measure, confirm**  
Apply the fix in a staging environment, benchmark before/after with **k6** or **Apache Bench**, then deploy and watch the metrics confirm the improvement.

A real example: I once traced a 4-second page load to a loop that fired an individual `SELECT` per row (classic N+1). Replacing it with a single JOIN query and adding a composite index reduced that to under 80 ms.

---

## Please describe yourself using JSON

```json
{
  "name": "Your Name",
  "role": "Full Stack Developer",
  "location": "Your City, Country",
  "experience_years": 4,
  "languages": ["PHP", "JavaScript", "TypeScript", "SQL", "HTML5", "CSS3"],
  "frameworks_and_tools": {
    "backend":   ["Laravel", "Symfony", "Node.js", "REST APIs"],
    "frontend":  ["jQuery", "Vue.js", "React", "Bootstrap", "Tailwind CSS"],
    "databases": ["MySQL", "PostgreSQL", "Redis"],
    "devops":    ["Git", "Docker", "nginx", "CI/CD pipelines"]
  },
  "working_style": {
    "approach":     "pragmatic",
    "code_values":  ["readability", "testability", "simplicity"],
    "enjoys_most":  "connecting a well-designed backend to a UI that actually delights people",
    "pet_peeve":    "shipping code without knowing why it works"
  },
  "currently_learning": "WebSockets and real-time collaborative UIs",
  "outside_work": ["cycling", "film photography", "cooking"],
  "open_to_work": true
}
```
