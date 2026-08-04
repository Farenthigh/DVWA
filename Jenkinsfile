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
        curl -f http://16.176.15.93:4280
        '''
            }
        }
        

        stage('ZAP Scan') {
    steps {

        startZap(
            host: '172.31.1.86',
            port: 8090,
            timeout: 60,
            zapHome: '',
            allowedHosts: ['16.176.15.93'],
            sessionPath: '',
            externalZap: true,
            rootCaFile: '',
            additionalConfigurations: []
        )

        importZapUrls(
            path: 'zap/urls.txt'
        )

        runZapCrawler(
            host: 'http://16.176.15.93:4280',
            maxChildren: 10,
            contextName: 'DVWA',
            contextId: -1,
            subtreeOnly: false,
            recurse: true,
            userId: -1
        )
    }
}
    }

    post {
    always {
        archiveArtifacts artifacts: 'zap-report/report.html', allowEmptyArchive: true
        }
    }
}