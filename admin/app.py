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
            stderr=subprocess.PIPE
        )
        stdout, stderr = process.communicate()
        if process.returncode != 0:
            return jsonify({'status': 'error', 'message': stderr.decode()}), 500
        return jsonify({'status': 'success', 'output': stdout.decode()})
    except Exception as e:
        return jsonify({'status': 'error', 'message': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
