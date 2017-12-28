pipeline {
  agent any
  stages {
    stage('Start') {
      parallel {
        stage('Start 1') {
          steps {
            echo 'Start 1'
          }
        }
        stage('Start 2') {
          steps {
            echo 'Start 2'
          }
        }
        stage('Start 3') {
          steps {
            echo 'Start 3'
          }
        }
        stage('Start 4') {
          steps {
            echo 'Start 4'
          }
        }
      }
    }
    stage('Print End') {
      steps {
        sh 'echo "End";'
      }
    }
  }
}