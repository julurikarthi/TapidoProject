from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/api/test', methods=['POST'])
def receive_post_data():
    # Get the JSON data from the request
    data = request.json

    # Print the received data (optional)
    print("Received POST data:", data)

    # Process the received data (optional)
    # For example, you can access data['key1'], data['key2'], etc.

    # Return a JSON response
    return jsonify({'message': 'POST request received successfully', 'data': data}), 200

if __name__ == '__main__':
    app.run(debug=True)
