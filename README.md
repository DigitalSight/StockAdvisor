# StockAdvisor

## Installation Instructions
### System Requirements
- Docker version 20.10.8 or higher
- should run PHP 7.0 or higher

### Start Container
1) cd into the base of the project directory you have just cloned (the directory this README.md is in)
2) run the command ```docker-compose up -d```
3) After the command has completed navigate to the url ```http://localhost:8000/```
4) Move forward with normal wordpress instillation steps (creating an admin user ect)

### Theme Setup
(all these steps are in wp-admin)
1) Make sure the Twenty Sixteen theme is installed (should be there from base wordpress install)
2) Activate the "Stock Advisor" theme 
3) Activate ACF (Advanced Custom Fields) Plugin
4) Import ACF Fields using this file StockAdvisor-ACF-Import.json
   1) You can do this by navigating to Custom Fields > Tools
   2) click the "Import Field Groups" > Choose File > Press the "Import File" button after selecting StockAdvisor-ACF-Import.json
5) Go to Settings > Permalinks and choose the "Post name" setting then press save
6) Navigate to Appearance > Widgets
   1) Select the "Company Profile Widget" and place it in the "Stock Recommendation Sidebar"
7) (not necessary but nice to have) navigate to the user you created and update "Nickname" save then switch "Display name Publicly as" to new value
### Theme Instructions
The only special instructions for posting articles for this theme is that you have to add the "Stocks" taxonomies before
posting articles.
This can be done by navigating to admin panel > (News or Stock recommendations) > click on "Stocks" sub-menu and fill out the form
to add a new stock.

### Pages of Interest for evaluators
- Stocks Recommendation Archive page {baseURL}/stock-recommendations/
- Company Page {baseURL}/stocks/{stock slug}
