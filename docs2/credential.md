site_key 0x4AAAAAACH50FVSWDr8HGam
secret_key REPLACE_WITH_YOUR_CLOUDFLARE_SECRET_KEY
cdn https://challenges.cloudflare.com/turnstile/v0/api.js

username REPLACE_WITH_YOUR_SMTP_USERNAME
password REPLACE_WITH_YOUR_SMTP_APP_PASSWORD
encryption ssl
port 465
host smtp.gmail.com

client_id: REPLACE_WITH_YOUR_GOOGLE_CLIENT_ID
project_id: lab-482107
auth_uri: https://accounts.google.com/o/oauth2/auth
token_uri: https://oauth2.googleapis.com/token
auth_provider_x509_cert_url: https://www.googleapis.com/oauth2/v1/certs
client_secret: REPLACE_WITH_YOUR_GOOGLE_CLIENT_SECRET
redirect_uris: [will be decided]
javascript_origins: [will be decided]

redirect_uris: [\"http:\\/\\/localhost\\/lab\\/users\\/register.php\",\"http:\\/\\/localhost:82\\/lab\\/users\\/register.php\",\"http:\\/\\/localhost\\/lab\\/users\\/login.php\",\"http:\\/\\/localhost:82\\/lab\\/users\\/login.php\"]
javascript_origins: [\"http:\\/\\/localhost\",\"http:\\/\\/localhost:82\"]
