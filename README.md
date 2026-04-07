# ============================================
# STEP 1: Create Azure VM
# ============================================
# Use Azure Portal or Azure CLI to create a VM
# - Choose Ubuntu image
# - Assign a public IP
# - Open port 5000 for Flask app
# Example CLI:
# az vm create --resource-group MyResourceGroup --name MyVM --image UbuntuLTS --admin-username azureuser --generate-ssh-keys
# az vm open-port --resource-group MyResourceGroup --name MyVM --port 5000

# ============================================
# STEP 2: Connect to VM
# ============================================
# SSH into the VM:
# ssh azureuser@<VM_PUBLIC_IP>

# ============================================
# STEP 3: Install Python, venv, pip
# ============================================
# sudo apt update
# sudo apt install python3 python3-venv python3-pip -y

# ============================================
# STEP 4: Create Python virtual environment
# ============================================
# python3 -m venv otel-env
# source otel-env/bin/activate

# ============================================
# STEP 5: Install required Python packages
# ============================================
# pip install flask azure-monitor-opentelemetry opentelemetry-sdk opentelemetry-instrumentation-flask

# ============================================
# STEP 6: Create Azure Application Insights
# ============================================
# 1. Go to Azure Portal → Create Resource → Application Insights
# 2. Choose Resource Group and Region
# 3. Give a Name → select "Python" as application type → Create
# 4. After creation, go to your Application Insights resource
# 5. Copy the "Connection String" from Overview → Instrumentation Key or Connection String

# ============================================
# STEP 7: Create Flask app with OpenTelemetry
# ============================================

# app.py
from flask import Flask, request
from opentelemetry.instrumentation.flask import FlaskInstrumentor
from azure.monitor.opentelemetry import configure_azure_monitor
from opentelemetry import trace
from opentelemetry.trace import SpanKind

# -------------------------------
# Step 1: Configure Azure Monitor
# -------------------------------
configure_azure_monitor(
    connection_string="InstrumentationKey=fad75225-e11f-49da-b5ef-66482eec4123;IngestionEndpoint=https://westus2-2.in.applicationinsights.azure.com/"
)

# -------------------------------
# Step 2: Create Flask app
# -------------------------------
app = Flask(__name__)
FlaskInstrumentor().instrument_app(app)  # Automatic request telemetry

# -------------------------------
# Step 3: Route with custom span + VM print
# -------------------------------
@app.route("/")
def home():
    tracer = trace.get_tracer(__name__)
    with tracer.start_as_current_span("home-page", kind=SpanKind.SERVER) as span:
        # Custom attributes sent to Application Insights
        span.set_attribute("log.message", "Home page accessed!")
        span.set_attribute("request.url", request.url)
        span.set_attribute("request.method", request.method)

        # Print in VM terminal
        print(f"[VM LOG] {request.method} request to {request.url} - Home page accessed!")

        return "Hello OpenTelemetry with Flask!"

# -------------------------------
# Step 4: Run the app
# -------------------------------
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
    # Access via: http://<YOUR_VM_PUBLIC_IP>:5000/


# ============================================
# STEP 8: Run Flask app
# ============================================
   source otel-env/bin/activate
   python app.py

# ============================================
# STEP 9: Verify telemetry in Azure Application Insights
# ============================================
# 1. Go to Application Insights → Logs
# 2. Query Requests:
# requests
# | order by timestamp desc
# | take 20
#
# 3. Query Traces:
# traces
# | order by timestamp desc
# | take 20
#
# 4. Optionally use Live Metrics Stream for real-time telemetry

# ============================================
# STEP 10: Create Dashboard & Alerts
# ============================================
# 1. In Azure Portal → Application Insights → Dashboards
# 2. Add tiles for: Request count, Response time, Failed requests
# 3. Alerts:
#    - Create alert for high response time or failure count
#    - Notifications via email/Teams/Logic Apps

# ============================================
# STEP 11: Test app
# ============================================
# curl http://<VM_PUBLIC_IP>:5000/
# After 30–60 seconds, verify the request shows up in Application Insights → Logs → requests
# Custom spans appear in Logs → traces



# Step 1: Create project folder
mkdir Open-Telemetery1
cd Open-Telemetery1

# Step 2: Clone your repository
git clone https://github.com/Tahira-Nawaz/Open-Telemetery.git
ls

# Step 3: Remove temporary folder if needed
cd ..
rm -r Open-Telemetery1

# Step 4: Enter cloned repo
cd Open-Telemetery
ls
composer init --name="tahira/otel-test" --require="php:>=8.0" --no-interaction

# Step 5: Install required OpenTelemetry packages
composer require open-telemetry/sdk
composer require open-telemetry/exporter-otlp
composer require php-http/guzzle7-adapter

# Step 6: Verify installed packages
composer show | grep open-telemetry

# Step 7: Run PHP built-in server
php -S 0.0.0.0:8000