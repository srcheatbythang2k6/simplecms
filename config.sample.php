<?php>
echo -e "${GREEN}[7/8] Creating configuration file...${NC}"
if [ ! -f "config.php" ]; then
  cp config.sample.php config.php

  # Replace placeholders
  sed -i "s/DB_HOST', '.*'/DB_HOST', 'localhost'/" config.php
  sed -i "s/DB_NAME', '.*'/DB_NAME', '$DB_NAME'/" config.php
  sed -i "s/DB_USER', '.*'/DB_USER', '$DB_USER'/" config.php
  sed -i "s/DB_PASS', '.*'/DB_PASS', '$DB_PASS'/" config.php
  sed -i "s|SITE_URL', '.*'|SITE_URL', 'http://$DOMAIN'|" config.php

  # Generate random keys
  AUTH_KEY=$(openssl rand -base64 32)
  SECURE_KEY=$(openssl rand -base64 32)
  sed -i "s/AUTH_KEY', '.*'/AUTH_KEY', '$AUTH_KEY'/" config.php
  sed -i "s/SECURE_AUTH_KEY', '.*'/SECURE_AUTH_KEY', '$SECURE_KEY'/" config.php
fi
