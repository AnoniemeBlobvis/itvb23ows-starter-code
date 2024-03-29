pipeline {
    agent any

    stages {
        stage('SonarQube') {
            steps {
                script {
                    scannerHome = tool 'Hive-Sonar'
                    withSonarQubeEnv('Hive-Sonar') {
                        sh "${scannerHome}/bin/sonar-scanner \
                            -Dsonar.projectKey=Hive"
                    }
                }
            }
        }
        stage('Install dependencies') {
            steps {
                echo 'Testing..'
            }
        }
        stage('Unit Tests') {
            steps {
                echo 'Deploying....'
            }
        }
//         stage('Install dependencies') {
//             agent { docker { image 'composer:2.6' } }
//             steps {
//                 sh 'composer install --ignore-platform-reqs'
//                 stash name: 'vendor', includes: 'vendor/**'
//             }
//         }
//         stage('Unit Tests') {
//             agent { docker { image 'php:8.0-apache' } }
//             steps {
//                 unstash name: 'vendor'
//             }
//         }
    }
}