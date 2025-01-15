import time
from flask import Flask
import paho.mqtt.client as mqtt
import requests

app = Flask(__name__)

# Configuration for MQTT Broker
MQTT_BROKER_URL = "broker.hivemq.com"
MQTT_BROKER_PORT = 1883
MQTT_TOPICS = [("rc/baterailevel", 0), ("rc/waterlevel", 0), ("rc/speed", 0)]

# Configuration for the target API
API_URL = "http://localhost/api_project/test.php"

# MQTT Callback function on connect
def on_connect(client, userdata, flags, rc):
    print("Connected with result code " + str(rc))
    for topic in MQTT_TOPICS:
        client.subscribe(topic)

# MQTT Callback function on message
def on_message(client, userdata, msg):
    print(f"Received `{msg.payload.decode()}` from `{msg.topic}` topic")
    # Delay posting data by 10 seconds
    time.sleep(10)
    post_data_to_api(msg.topic, msg.payload.decode())

def post_data_to_api(topic, message):
    if topic == "rc/baterailevel":
        data_key = "battery"
    elif topic == "rc/waterlevel":
        data_key = "water_level"
    elif topic == "rc/speed":
        data_key = "speed"
    else:
        return
    
    # Post the received message as JSON to the PHP API
    response = requests.post(API_URL, json={data_key: message})
    print("Data posted to API:", response.text)

# Setup MQTT Client
mqtt_client = mqtt.Client()
mqtt_client.on_connect = on_connect
mqtt_client.on_message = on_message
mqtt_client.connect(MQTT_BROKER_URL, MQTT_BROKER_PORT, 60)

# Run the MQTT loop in a separate thread
mqtt_client.loop_start()

@app.route('/')
def index():
    return "MQTT to API Bridge Running"

if __name__ == '__main__':
    app.run(debug=True, port=5000)