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
        curl -f http://3.106.225.84:4280
        '''
            }
        }

        stage('ZAP Scan') {
    steps {

        startZap(
            host: 'localhost',
            port: 8090,
            timeout: 60,
            zapHome: '/opt/zaproxy',
            allowedHosts: '3.106.225.84',
            sessionPath: '',
            externalZap: true,
            rootCaFile: '',
            additionalConfigurations: ''
        )

        importZapUrls(
            path: 'zap/urls.txt'
        )

        runZapCrawler(
            host: 'http://3.106.225.84:4280'
        )

        runZapAttack()

        archive()

        stopZap()
    }
}
    }

    post {
    always {
        archiveArtifacts artifacts: 'zap-report/report.html', allowEmptyArchive: true
        }
    }
}