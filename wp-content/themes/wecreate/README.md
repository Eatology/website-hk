#### 3. Enjoy coding your wecreate theme based on Genese.

```shell
wecreate/               # → Root folder for the project
├── lib/
        ├── inc/      # → WordPress Hooks and miscellanous helper functions.
        └── walkers/
├── resources/
        ├── assets/   # → Frontend assets source and Configs of compiling process.
        ├── lang/
        └── scripts/  # → Do touch, scripts to compile assets.
└── template-parts/
```


## 3.1 — Customize the frontend.
First you should get Nodejs installed on your machine.
Then install npm dependencies
```bash
npm install
```
Configure the assets bundling
```./resources/assets/config.js```
Start the dev script
```bash
npm run start
```
```bash
npm run build
```
  - 3.1.1 — Customize webpack
  - 3.1.2 — Include scripts into WordPress
 
## 3.2 — Setup the theme.
  - 3.2.1 — Declare navigations
  - 3.2.2 — Declare widget areas (sidebars)
 
## 3.3 — Customize templates
  - 3.3.1 — Customize the base template
  - 3.3.2 — Customize the template parts
    - 3.3.2.1 — Index views (index.php, archive.php, search.php)
    - 3.3.2.2 — Single views (single.php, page.php)

## Inspired by

* Genese
