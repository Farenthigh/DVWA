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
                docker build -t farenthigh/dvwa:dev .
                '''
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                docker compose down
                docker compose up -d
                '''
            }
        }

        stage('Test Application') {
    steps {
        sh '''
        sleep 10
        curl -f http://dvwa:80
        '''
    }
}

    }
}