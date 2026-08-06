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
        docker compose -f compose.yml pull
        docker compose -f compose.yml up -d --force-recreate
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
        
        stage('SonarQube Analysis') {
    steps {
        script {
            def scannerHome = tool 'SonarScanner'

            withSonarQubeEnv('sonarqube') {
                sh """
                    ${scannerHome}/bin/sonar-scanner
                """
            }
        }
    }
}

//         stage('ZAP Scan') {
//     steps {

//         sh '''
//         echo "Check ZAP"

//         curl http://172.31.1.86:8090/JSON/core/view/version/

//         echo "Start Spider"

//         curl "http://172.31.1.86:8090/JSON/spider/action/scan/?url=http://16.176.15.93:4280"

//         sleep 60

//         echo "Generate Report"

//         curl "http://172.31.1.86:8090/OTHER/core/other/htmlreport/" \
//         -o zap-report/report.html
//         '''
//     }
// }

    }

    post {
    always {
        archiveArtifacts artifacts: 'zap-report/report.html', allowEmptyArchive: true
        }
    }
}