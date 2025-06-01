FROM jenkins/jenkins:lts

USER root

RUN apt-get update && apt-get install -y \
    php php-cli php-mbstring php-xml php-curl php-zip php-mysql unzip git curl default-jdk wget && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    wget -O /tmp/sonar-scanner.zip https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip && \
    unzip -o /tmp/sonar-scanner.zip -d /opt && \
    ln -s /opt/sonar-scanner-*/bin/sonar-scanner /usr/local/bin/sonar-scanner && \
    rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/jenkins_home/.composer && \
    chown -R jenkins:jenkins /var/jenkins_home/.composer

USER jenkins
