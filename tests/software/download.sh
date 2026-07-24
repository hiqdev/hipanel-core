#!/bin/bash

GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m'

wget -c https://selenium-release.storage.googleapis.com/3.141/selenium-server-standalone-3.141.59.jar
wget -c https://chromedriver.storage.googleapis.com/76.0.3809.126/chromedriver_linux64.zip
unzip -u chromedriver_linux64.zip

cat <<EOF > run.sh
#!/bin/bash

java -jar -Dwebdriver.chrome.driver=chromedriver selenium-server-standalone-3.141.59.jar
EOF

chmod u+x run.sh

echo ''
echo ''
echo -e "${GREEN}Now you can run Selenium with ${BLUE}./run.sh${NC}"
