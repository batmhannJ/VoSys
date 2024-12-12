import sys
print(sys.executable)
import cv2
import mysql.connector

# Check if the voter has already voted
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
        print(f"Database Error: {err}")
        return True  # Assume voter has voted if there's a database error

# Save votes to the database
def save_votes(voters_id):
    if check_voter_has_voted(voters_id):
        print(f"Voter {voters_id} has already voted. Scanning is not allowed.")
        return

    try:
        db = mysql.connector.connect(
            host="153.92.15.1",
            user="u247141684_vosys",
            password="vosysOlshco5",
            database="u247141684_votesystem"
        )
        cursor = db.cursor()
        query = "INSERT INTO votes (election_id, voters_id, category_id, candidate_id, organization) VALUES (1, %s, %s, %s, 'JPCS')"

        # Hardcoded category and candidate IDs
        votes = {
            1: 28,  # Category ID 1, Candidate ID 28
            3: 26,  # Category ID 3, Candidate ID 26
            4: 27   # Category ID 4, Candidate ID 27
        }

        for category_id, candidate_id in votes.items():
            print(f"Executing query: {query} with values ({voters_id}, {category_id}, {candidate_id})")
            cursor.execute(query, (voters_id, category_id, candidate_id))

        db.commit()
        print("Votes successfully saved to the database.")
        cursor.close()
        db.close()
    except mysql.connector.Error as err:
        print(f"Database Error: {err}")

# Main function
def main():
    voters_id = "650"  # Hardcoded Voter ID
    ip_camera_url = "http://192.168.254.101:8080/video"  # Update this with the actual URL
    cap = cv2.VideoCapture(ip_camera_url)

    if not cap.isOpened():
        print("Could not open IP camera stream.")
        return

    print("Press 's' to scan the ballot or 'q' to quit.")
    while True:
        ret, frame = cap.read()
        if not ret:
            print("Failed to grab frame from IP camera.")
            break

        cv2.imshow("Ballot Scanner", frame)
        
        key = cv2.waitKey(1) & 0xFF
        if key == ord('s'):
            ballot_image = "scanned_ballot.jpg"
            cv2.imwrite(ballot_image, frame)
            print("Ballot scanned and saved as scanned_ballot.jpg.")

            save_votes(voters_id)
        elif key == ord('q'):
            break

    cap.release()
    cv2.destroyAllWindows()

if __name__ == "__main__":
    main()
