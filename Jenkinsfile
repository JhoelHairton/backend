pipeline {
    agent any

    environment {
        SONARQUBE_ENV = 'sonarqube'
        PROJECT_DIR = 'turismo-backend-v2'
        DB_HOST = 'mysql'  // Servicio de DB (necesitarás docker-compose)
    }

    stages {
        stage('Clonar repositorio') {
            steps {
                git branch: 'main',  // Usando tu rama
                    credentialsId: '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',  // Verifica que este ID existe
                    url: 'https://github.com/JhoelHairton/backend.git'
            }
        }

        stage('Instalar dependencias') {
            steps {
                dir("${PROJECT_DIR}") {
                    // Instalar Composer y extensiones PHP
                    sh '''
                        php -v
                        composer -V
                        composer install --no-interaction --prefer-dist
                        cp .env.example .env
                        php artisan key:generate
                    '''
                }
            }
        }

        stage('Base de datos') {
            steps {
                dir("${PROJECT_DIR}") {
                    sh 'php artisan migrate --seed --force'
                }
            }
        }

        stage('Pruebas') {
            steps {
                dir("${PROJECT_DIR}") {
                    sh 'php artisan test --testsuite=Feature --log-junit storage/test-results.xml --coverage-clover storage/coverage/clover.xml'
                }
            }
        }

        stage('Análisis SonarQube') {
            steps {
                dir("${PROJECT_DIR}") {
                    script {
                        // Descargar sonar-scanner 5.0+
                        sh '''
                            curl -o /tmp/sonar-scanner.zip -L https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip
                            unzip /tmp/sonar-scanner.zip -d /opt
                            ln -s /opt/sonar-scanner-*/bin/sonar-scanner /usr/local/bin/sonar-scanner
                        '''
                        withSonarQubeEnv("${SONARQUBE_ENV}") {
                        sh 'sonar-scanner -Dsonar.projectKey=turismo-capachica -Dsonar.php.coverage.reportPaths=storage/coverage/clover.xml'
                        }
                    }
                }
            }
        }
    }

    post {
    failure {
        echo "❌ Pipeline Fallido: ${env.JOB_NAME} (${env.BUILD_URL})"
    }
    success {
        echo "✅ Pipeline Exitoso: ${env.JOB_NAME} (${env.BUILD_URL})"
    }
}

}