FROM jenkins/jenkins:lts

USER root

# Install Docker official repository
RUN apt-get update && \
    apt-get install -y ca-certificates curl gnupg lsb-release wget unzip openjdk-21-jre && \
    install -m 0755 -d /etc/apt/keyrings && \
    curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg && \
    chmod a+r /etc/apt/keyrings/docker.gpg && \
    echo \
      "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
      https://download.docker.com/linux/debian \
      $(. /etc/os-release && echo "$VERSION_CODENAME") stable" \
      | tee /etc/apt/sources.list.d/docker.list > /dev/null && \
    apt-get update && \
    apt-get install -y docker-ce-cli docker-compose-plugin && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install OWASP ZAP
RUN wget -O /tmp/ZAP.tar.gz \
    https://github.com/zaproxy/zaproxy/releases/download/v2.17.0/ZAP_2_17_0_unix.tar.gz && \
    mkdir -p /opt && \
    tar -xzf /tmp/ZAP.tar.gz -C /opt && \
    mv /opt/ZAP_2.17.0 /opt/zap && \
    ln -sf /opt/zaproxy/zap.sh /usr/local/bin/zap.sh && \
    rm /tmp/ZAP.tar.gz

USER jenkins