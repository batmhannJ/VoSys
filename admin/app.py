from flask import Flask, request, jsonify
import subprocess

app = Flask(__name__)

@app.route('/run-scan', methods=['POST'])
def run_scan():
    try:
        # Command to execute
        process = subprocess.Popen(
            ['python', 'scan_ballot.py'],
            cwd='C:\\Users\\Almira\\Desktop\\VoSys\\admin',
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            text=True  # Ensures the output is a string, not bytes
        )
        stdout, stderr = process.communicate()

        # Check if there are errors
        if process.returncode != 0:
            return jsonify({'status': 'error', 'message': stderr}), 500
        
        # Return the successful output
        return jsonify({'status': 'success', 'output': stdout})
    except Exception as e:
        return jsonify({'status': 'error', 'message': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
