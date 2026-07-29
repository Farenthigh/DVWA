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

        stage('Deploy DVWA') {
    steps {
        sh '''
        docker-compose -f compose.yml pull
        docker-compose -f compose.yml up -d --force-recreate
        '''
        }
    }

        stage('Test Application') {
    steps {
        sh '''
        sleep 15
        curl -f http://3.27.125.80:4280
        '''
        }
    }

        stage('OWASP ZAP Scan') {
    steps {
        sh '''
        # ลบ Report เก่าทั้งหมด
        rm -rf zap-report
        mkdir -p zap-report

        docker run --rm \
            --user root \
            -v "$PWD/zap-report:/zap/wrk:rw" \
            ghcr.io/zaproxy/zaproxy:stable \
            zap-baseline.py \
            -t http://3.27.125.80:4280 \
            -r report.html || true

        echo "===== Verify report ====="
        ls -lah zap-report
        grep "3.27.125.80" zap-report/report.html
        '''
        }
    }
}

    post {
        always {
            archiveArtifacts artifacts: 'zap-report/*', fingerprint: true
            }
    }
}