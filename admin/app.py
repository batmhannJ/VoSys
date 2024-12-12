from flask import Flask, request, jsonify
import mysql.connector
import subprocess

app = Flask(__name__)

# Function to check if the voter has already voted
def check_voter_has_voted(voters_id):
    try:
        db = mysql.connector.connect(
            host="153.92.15.1",
            user="u247141684_vosys",
            password="vosysOlshco5",
            database="u247141684_votesystem"
        )
        cursor = db.cursor()
        query = "SELECT COUNT(*) FROM votes WHERE voters_id = %s"
        cursor.execute(query, (voters_id,))
        result = cursor.fetchone()
        cursor.close()
        db.close()
        return result[0] > 0
    except mysql.connector.Error as err:
        return {"status": "error", "message": f"Database Error: {err}"}

# API to scan ballot and handle voter status
@app.route('/run-scan', methods=['POST'])
def run_scan():
    voters_id = "650"  # Example Voter ID; can be dynamic
    voter_status = check_voter_has_voted(voters_id)

    if isinstance(voter_status, dict) and voter_status["status"] == "error":
        return jsonify(voter_status), 500

    if voter_status:
        return jsonify({
            "status": "error",
            "message": f"Voter {voters_id} has already voted."
        }), 400

    # Simulate ballot scanning and saving votes
    try:
        process = subprocess.Popen(
            ['python', 'scan_ballot.py'],
            cwd='C:\\Users\\Almira\\Desktop\\VoSys\\admin',
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE
        )
        stdout, stderr = process.communicate()

        if process.returncode != 0:
            return jsonify({
                "status": "error",
                "message": stderr.decode()
            }), 500

        return jsonify({
            "status": "success",
            "message": "Ballot scanned successfully.",
            "output": stdout.decode()
        })
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
