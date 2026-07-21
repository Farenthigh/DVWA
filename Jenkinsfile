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
                docker build -t dvwa:test .
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