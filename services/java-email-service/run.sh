#!/bin/bash

# Sathyabama Email Service - Build and Run Script

echo "🚀 Building and Starting Sathyabama Email Service..."

# Check if Maven is installed
if ! command -v mvn &> /dev/null; then
    echo "❌ Maven is not installed. Please install Maven first."
    echo "   Visit: https://maven.apache.org/install.html"
    exit 1
fi

# Check if Java is installed
if ! command -v java &> /dev/null; then
    echo "❌ Java is not installed. Please install Java 11 or higher."
    echo "   Visit: https://adoptopenjdk.net/"
    exit 1
fi

# Check Java version
JAVA_VERSION=$(java -version 2>&1 | head -1 | cut -d'"' -f2 | sed '/^1\./s///' | cut -d'.' -f1)
if [ "$JAVA_VERSION" -lt 11 ]; then
    echo "❌ Java 11 or higher is required. Current version: $JAVA_VERSION"
    exit 1
fi

echo "✅ Java version: $JAVA_VERSION"

# Navigate to project directory
cd "$(dirname "$0")"

echo "📦 Cleaning and compiling the project..."
mvn clean compile

if [ $? -ne 0 ]; then
    echo "❌ Compilation failed. Please check the error messages above."
    exit 1
fi

echo "✅ Compilation successful!"

echo "🎯 Starting the Email Service..."
echo "📧 The service will be available at: http://localhost:8081/email-service"
echo "🔍 Health check: http://localhost:8081/email-service/api/email/health"
echo "🧪 Test endpoint: http://localhost:8081/email-service/api/email/test"
echo ""
echo "⚡ To stop the service, press Ctrl+C"
echo ""

# Run the application
mvn spring-boot:run