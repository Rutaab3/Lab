# docs2 Documentation Hub

This folder contains the setup notes, seed data, route references, and integration notes used by the Lab Automation System.

For the main project overview, start with the root [`README.md`](../README.md).

## Start Here

1. Import [`lab_automation.sql`](lab_automation.sql) into a database named `lab_automation`.
2. Update [`../config/db.php`](../config/db.php) with your local database connection settings.
3. Replace the seeded mail, Cloudflare, and Google values in `system_settings` with your own credentials.
4. Use [`auth_logic.png`](auth_logic.png) and the files in [`Oauth roadmap`](Oauth%20roadmap/Readme.md) while configuring OAuth.
5. Log in through `http://localhost/lab/users/login.php`.

## Credentials You Should Replace

Before using this project outside a throwaway local setup, edit the following values in [`lab_automation.sql`](lab_automation.sql):

- `mail.username`
- `mail.password`
- `cloudflare.site_key`
- `cloudflare.secret_key`
- `google.client_id`
- `google.project_id`
- `google.client_secret`
- `google.redirect_uris`
- `google.javascript_origins`

## Document Index

| Document | Purpose |
| --- | --- |
| [`readme.md`](readme.md) | This documentation index |
| [`lab_automation.sql`](lab_automation.sql) | Database schema, seed data, and `system_settings` values |
| [`credential.md`](credential.md) | Credential placeholders and integration reference values |
| [`auth_logic.png`](auth_logic.png) | Authentication and OAuth logic reference image |
| [`todo.md`](todo.md) | Original setup checklist |
| [`users.csv`](users.csv) | Example seeded user accounts for local testing |
| [`products.md`](products.md) | Product and equipment descriptions |
| [`libararies.md`](libararies.md) | Frontend library notes |
| [`changelog.md`](changelog.md) | File-level project change history snapshot |
| [`contributing.md`](contributing.md) | Contributor names |
| [`license.md`](license.md) | MIT license text |
| [`link.xml`](link.xml) | Local route list for important pages |
| [`Oauth roadmap/Readme.md`](Oauth%20roadmap/Readme.md) | Simple OAuth overview |
| [`Oauth roadmap/google.md`](Oauth%20roadmap/google.md) | Google OAuth concept notes |
| [`Oauth roadmap/google2.md`](Oauth%20roadmap/google2.md) | Google Cloud setup steps for OAuth credentials |
| [`Oauth roadmap/cloudfare.md`](Oauth%20roadmap/cloudfare.md) | Cloudflare Turnstile setup notes |
| [`Oauth roadmap/facebook.md`](Oauth%20roadmap/facebook.md) | Facebook Login setup notes |

## Recommended Reading Order

1. [`todo.md`](todo.md)
2. [`lab_automation.sql`](lab_automation.sql)
3. [`credential.md`](credential.md)
4. [`auth_logic.png`](auth_logic.png)
5. [`Oauth roadmap/google2.md`](Oauth%20roadmap/google2.md)
6. [`users.csv`](users.csv)
