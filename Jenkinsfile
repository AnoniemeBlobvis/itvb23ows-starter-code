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
                script {
                    sh 'docker-compose up --build -d'
                }
            }
        }
        stage('Unit Tests') {
            steps {
                sh 'vendor/bin/phpunit'
            }
        }
    }
}