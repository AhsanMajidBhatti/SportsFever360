# 📺 Cricket Score XML Feed Generator
## 📌 Overview
This project is a PHP-based data processing system designed to fetch cricket score data in JSON format, transform it into a structured XML feed, and provide a simplified interface for downstream users (e.g., designers or display systems).
The generated XML is consumed by frontend display systems—such as TV graphics or scoreboards—to present live cricket scores in a controlled and consistent format.

## ⚙️ How It Works
### Data Fetching
- The system retrieves cricket match data from an external API in JSON format.
- Data is automatically refreshed every 3 seconds to ensure near real-time updates.
### Data Processing
- Parses and filters only the required fields (e.g., team names, scores, overs, match status).
### XML Generation
- Converts the filtered data into a structured XML format.
- Ensures consistency and lightweight structure for easy consumption.
### Frontend Integration
- Other users upload or connect to the XML feed.
- Designers use this XML to render score data on TV screens or UI displays.

## 🔄 Real-Time Update Mechanism
### The system runs an automated process (e.g., cron job, loop, or scheduler) that:
- Fetches updated JSON data every 3 seconds
- Regenerates the XML file with the latest match information
- Ensures the frontend always displays live and up-to-date scores

## 🧩 Features
- ✅ Fetches live cricket data from API (JSON format)
- ✅ Extracts only relevant and minimal dataset
- ✅ Converts JSON → XML efficiently
- ✅ Lightweight and easy-to-use XML output
- ✅ Designed for real-time display systems
- ✅ Reduces frontend complexity for designers

## 🚀 Usage
### Configure API details in:
- config.php
### Run the PHP script:
- php fetch.php
### XML output will be generated:
- output.xml
- Upload or integrate output.xml into the frontend system.
### 📄 Sample XML Output
```xml
<match>
    <team1>India</team1>
    <team2>Australia</team2>
    <score>250/3</score>
    <overs>45.2</overs>
    <status>Live</status>
</match>
```

## 🎯 Use Case
- TV broadcast score overlays
- Stadium display systems
- Digital signage for live cricket updates
- Lightweight data feed for designers

## 🛠️ Technologies Used
- PHP
- JSON Parsing
- XML Generation (SimpleXML / DOMDocument)

## 📌 Key Contribution
### Developed a backend utility that bridges raw API data and frontend display requirements by:
- Simplifying complex JSON into usable XML
- Enabling non-technical users (designers) to easily integrate live data
- Improving performance and reducing frontend dependency on APIs

## 🔒 Notes
- API credentials (if any) should be kept secure and not committed to version control.
- XML structure can be extended based on frontend requirements.
