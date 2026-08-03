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
            zapHome: '',
            allowedHosts: ['3.106.225.84'],
            sessionPath: '',
            externalZap: true,
            rootCaFile: '',
            additionalConfigurations: []
        )

        importZapUrls(
            path: 'zap/urls.txt'
        )

        runZapCrawler(
            host: 'http://3.106.225.84:4280',
            maxChildren: 10,
            contextName: '',
            contextId: 0,
            subtreeOnly: false,
            recurse: true,
            userId: 0
        )

        runZapAttack(
            scanPolicyName: 'Default Policy',
            userId: 0,
            contextId: 0,
            recurse: true,
            inScopeOnly: false,
            method: '',
            postData: ''
        )

        archive(
            includes: '**/*.html'
        )

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