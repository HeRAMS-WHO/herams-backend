# Report definition for HeRAMS projects

This file describes the concept of Reports for HeRAMS projects.
These interactive reports are shown to the user via the browser.
Currently this is implemented via the frontend application.

## Navigation
1. The report consists of a navigation tree:
root
|- Overview
|- Infrastructure
   |- Descriptive
   |- Basic amenities
|- Damage
|- Management

2. Each leaf in the tree MUST have a page definition.
3. Each node in the tree MAY have a page definition. If no page is present the item is just a group that helps with navigation.

## Color scales
The system should support named color scales.
Each color scale has a name and an order set of colors.
We use Chroma to generate the correct number of colors from a given color scale.

https://vis4.net/chromajs/


## Page
Each page consists of several properties:
- string title
- Element[] element

### Elements
Currently we support 3 types of elements.

- Map
- PieChart
- Table

Each element type has several configuration options.
Global options, valid for all elements include:
- title
- ColorScale scale



#### Map

A map allows the user to configure the question codes for latitude, longitude and category and a list of colors.
Category is used to create separate series.

When clicking on an element on the map, a non-interactive dialog is shown:

----------------------------------
-            TITLE               -
----------------------------------
-            SUBTITLE            -
----------------------------------
- QUESTION:      ANSWER          -
----------------------------------
- QUESTION:      ANSWER          -
----------------------------------
- QUESTION:      ANSWER          -
----------------------------------
- QUESTION:      ANSWER          -
----------------------------------

The dialog is fully configurable TBD


### PieChart
The pie chart allows us to define pie charts.
The configuration is a question code and a set of colors.
The question MUST BE a single choice question.

### Table
A table element shows a in the report.

Definition TBD