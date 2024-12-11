import pytesseract
import cv2
import re
import sys
import mysql.connector  # MySQL connector for Python

# Set the path to tesseract.exe (if needed)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

# Database connection setup
def connect_to_db():
    return mysql.connector.connect(
        host="vosys.org",  # e.g., 'localhost' or your Hostinger DB IP
        user="u247141684_vosys",        # your Hostinger MySQL username
        password="vosysOlshco5",    # your Hostinger MySQL password
        database="u247141684_votesystem",         # your database name
    )

def scan_ballot(image_path):
    # Load the image
    img = cv2.imread(image_path)
    
    if img is None:
        print("Error: Could not read the image.")
        return {}
    
    # Convert image to grayscale
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # Apply thresholding or other preprocessing techniques
    _, thresholded_img = cv2.threshold(gray, 150, 255, cv2.THRESH_BINARY)

    # Use pytesseract to extract text from the image
    text = pytesseract.image_to_string(thresholded_img)
    
    print("Raw OCR Output:")
    print(text) 

    return text

def process_votes(ocr_text):
    # Split the OCR output by lines
    lines = ocr_text.splitlines()

    # Initialize a dictionary to store votes
    votes = {
        "President": [],
        "VP Internal Affairs": [],
        "VP External Affairs": [],
        "Secretary": [],
        "Treasurer": [],
        "Auditor": [],
        "P.R.O": [],
        "Dir. for Membership": []
    }

    # Iterate through each line and check for @ or © symbols
    for line in lines:
        position = None
        candidates = []

        # Check for position and associated votes based on symbols
        if "President" in line:
            position = "President"
        elif "VP for Internal Affairs" in line:
            position = "VP for Internal Affairs"
        elif "VP for External Affairs" in line:
            position = "VP for External Affairs"
        elif "Secretary" in line:
            position = "Secretary"
        elif "Treasurer" in line:
            position = "Treasurer"
        elif "Auditor" in line:
            position = "Auditor"
        elif "P.R.O" in line:
            position = "P.R.O"
        elif "Dir. for Membership" in line:
            position = "Dir. for Membership"

        if position:
            # If the position is found, check for candidates with the @ or © symbol
            if '@' in line:  # Shaded (vote)
                candidate = line.split('@')[-1].strip()  # Get candidate name after @ symbol
                votes[position].append(candidate)

    print("Extracted Votes:")
    print(votes)
    return votes


def extract_votes(text):
    # Regex patterns for extracting voter ID and votes
    voter_id_pattern = r"voter ID: (\d+)"
    positions = ['President', 'VP for Internal Affairs', 'VP for External Affairs', 'Secretary', 'Treasurer', 'Auditor', 'P.R.O', 'D for Membership']
    
    votes = {}
    
    # Extract voter ID
    voter_id_match = re.search(voter_id_pattern, text)
    if voter_id_match:
        votes["voter_id"] = voter_id_match.group(1)
    
    # Extract votes for each position
    for position in positions:
        pattern = r'(?i){}.*?([@©]+.*?)\n'.format(position)  # Check for candidates marked with @ or ©
        match = re.search(pattern, text)
        if match:
            votes[position] = match.group(1).strip()
    
    return votes

def save_to_database(votes):
    # Connect to MySQL database
    db = connect_to_db()
    cursor = db.cursor()

    # Prepare the SQL query to insert vote data
    query = """
        INSERT INTO votes (election_id, category_id, voters_id, org_id, candidate_id, position_id, organization)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
    """
    
    # For each position, save the vote data (election_id = 1, org_id = 1, organization = 'JPCS')
    election_id = 1  # Fixed election ID for now
    org_id = 1       # Fixed org ID for JPCS
    organization = 'JPCS'

    for position, candidate in votes.items():
        if position == 'voter_id':  # Skip voter ID field for insertion
            continue
        
        position_id = get_position_id(position)  # Convert position to position_id
        if candidate:  # Candidate is the name of the candidate (e.g., "John Doe")
            # Split the candidate name into first and last name
            name_parts = candidate.split()  # Assuming the candidate name is split into first and last names
            if len(name_parts) >= 2:
                firstname = name_parts[0]
                lastname = name_parts[1]
            else:
                continue  # If there is no valid first and last name, skip this vote entry

            # Fetch the candidate_id from the database using the name
            candidate_id = get_candidate_id(firstname, lastname)

            if not candidate_id:
                print(f"Candidate {candidate} not found in database!")
                continue  # Skip if no matching candidate_id is found

            values = (election_id, position_id, votes['voter_id'], org_id, candidate_id, position_id, organization)

            # Execute the SQL insert query
            cursor.execute(query, values)
    
    # Commit the transaction and close the connection
    db.commit()
    cursor.close()
    db.close()
    
    print("Votes saved successfully!")

def get_position_id(position):
    # Map position name to position_id
    positions = {
        'President': 1,
        'VP for Internal Affairs': 2,
        'VP for External Affairs': 3,
        'Secretary': 4,
        'Treasurer': 5,
        'Auditor': 6,
        'P.R.O': 7,
        'D for Membership': 8
    }
    return positions.get(position, None)

def get_candidate_id(firstname, lastname):
    # Connect to the MySQL database
    db = connect_to_db()
    cursor = db.cursor()

    # Query to search for the candidate by first and last name
    query = """
        SELECT id FROM candidates
        WHERE firstname = %s AND lastname = %s
        LIMIT 1
    """

    # Execute the query with the candidate's first and last name
    cursor.execute(query, (firstname, lastname))
    result = cursor.fetchone()

    # Close the database connection
    cursor.close()
    db.close()

    # If a match is found, return the candidate_id, else return None
    if result:
        return result[0]  # The candidate_id is in the first column
    else:
        return None


if __name__ == "__main__":
    # Get the image path from the arguments
    if len(sys.argv) < 2:
        print("Usage: python scan_ballot.py <image_path>")
    else:
        image_path = sys.argv[1]
        result = scan_ballot(image_path)
        print("OCR Output:")
        print(result)
        
        # Extract the votes (Voter ID and selected candidates)
        votes = extract_votes(result)
        print("Extracted Votes:")
        print(votes)
        
        # Save the extracted votes to the database
        save_to_database(votes)
