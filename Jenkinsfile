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

        stage('ZAP Scan') {
    steps {
        sh '''
        rm -rf zap-report
        mkdir -p zap-report

        docker run --rm \
    -v $(pwd)/zap-report:/zap/wrk \
    zaproxy/zap-stable \
    zap-baseline.py \
    -t http://3.27.125.80:4280 \
    -r /zap/wrk/report.html

        ls -lah zap-report
        '''
            }
        }
    }

    post {
    always {
        archiveArtifacts artifacts: 'zap-report/report.html', allowEmptyArchive: true
        }
    }
}