pipeline {

    agent any

    stages {

        stage('Checkout') {
            steps {
                echo 'Checkout DVWA source'
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

    }
}