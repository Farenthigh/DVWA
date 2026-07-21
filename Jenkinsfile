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


        stage('Test Image') {
            steps {
                sh '''
                docker images | grep dvwa
                '''
            }
        }

        stage('Test Application') {
    steps {
        sh '''
        sleep 10
        curl -f http://localhost:4280 || exit 1
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

    }
}