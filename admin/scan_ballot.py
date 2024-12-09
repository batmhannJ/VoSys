import pytesseract
import cv2
import sys

# Set the path to tesseract.exe
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def scan_ballot(image_path):
    # Load the image
    img = cv2.imread(image_path)
    
    if img is None:
        print("Error: Could not read the image.")
        return ""
    
    # Convert image to grayscale
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # Use pytesseract to extract text
    text = pytesseract.image_to_string(gray)
    
    return text

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python scan_ballot.py <image_path>")
    else:
        image_path = sys.argv[1]
        result = scan_ballot(image_path)
        print(result)
