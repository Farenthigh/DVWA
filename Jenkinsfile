pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        // stage('Build Docker Image') {
        //     steps {
        //         sh '''
        //         docker build -t farenthigh/dvwa:dev .
        //         '''
        //     }
        // }

        // stage('Push Image') {
        //     steps {
        //         withCredentials([usernamePassword(
        //             credentialsId: 'dockerhub',
        //             usernameVariable: 'DOCKER_USER',
        //             passwordVariable: 'DOCKER_PASS'
        //         )]) {
        //             sh '''
        //             echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
        //             docker push farenthigh/dvwa:dev
        //             '''
        //         }
        //     }
        // }

        stage('Test SSH') {
    steps {
        sshagent(credentials: ['aws-ec2-ssh']) {
            sh '''
            ssh -o StrictHostKeyChecking=no ubuntu@13.238.128.122 "
                whoami &&
                hostname
            "
            '''
        }
    }
}

        // stage('Test Application') {
        //     steps {
        //         sh '''
        //         sleep 15
        //         curl -f http://13.238.128.122:4280
        //         '''
        //     }
        // }
    }
}