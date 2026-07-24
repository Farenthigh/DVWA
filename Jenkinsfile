pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker Image') {
    steps {
        sh '''
        docker build \
        --platform linux/amd64 \
        -t farenthigh/dvwa:dev .
        '''
    }
}

        stage('Push Image') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'dockerhub',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {
                    sh '''
                    echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                    docker push farenthigh/dvwa:dev
                    '''
                }
            }
        }

        stage('Deploy to AWS') {
    steps {
        sshagent(credentials: ['aws-ec2-ssh']) {
            sh '''
            ssh -o StrictHostKeyChecking=no ubuntu@13.238.128.122 "
                cd ~/DVWA &&
                docker compose pull &&
                docker compose up -d --force-recreate
            "
            '''
        }
    }
}

stage('Test Application') {
    steps {
        sh '''
        sleep 15
        curl -f http://13.238.128.122:4280
        '''
    }
}

stage('OWASP ZAP Scan') {
    steps {
        sh '''
        mkdir -p zap-report

        docker run --rm \
            -v $(pwd)/zap-report:/zap/wrk \
            ghcr.io/zaproxy/zaproxy:stable \
            zap-baseline.py \
            -t http://13.238.128.122:4280 \
            -r report.html
        '''
    }
}
    post {
    always {
        archiveArtifacts artifacts: 'zap-report/*', fingerprint: true
    }
}
}